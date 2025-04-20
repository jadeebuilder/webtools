<?php

namespace App\Services\Payments;

use App\Models\Transaction;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Package;
use App\Models\Currency;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

abstract class AbstractPaymentProcessor implements PaymentProcessorInterface
{
    /**
     * Configuration du processeur de paiement
     * 
     * @var array
     */
    protected array $config;
    
    /**
     * Constructeur
     * 
     * @param array $config Configuration du processeur
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->initialize();
    }
    
    /**
     * Initialise le processeur de paiement
     * 
     * @return void
     */
    protected function initialize(): void
    {
        // À implémenter par les classes concrètes si nécessaire
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsRecurring(): bool
    {
        return false; // Par défaut, les processeurs ne supportent pas les abonnements récurrents
    }
    
    /**
     * Crée une nouvelle transaction
     * 
     * @param User $user L'utilisateur concerné
     * @param Package $package Le package acheté
     * @param Currency $currency La devise utilisée
     * @param float $amount Le montant
     * @param string $cycle Le cycle de facturation (monthly, annual, lifetime)
     * @param array $metadata Métadonnées additionnelles
     * @return Transaction La transaction créée
     */
    protected function createTransaction(
        User $user, 
        Package $package, 
        Currency $currency, 
        float $amount, 
        string $cycle, 
        array $metadata = []
    ): Transaction {
        // Générer une référence unique
        $referenceId = Transaction::generateReferenceId();
        
        // Créer la transaction avec le statut initial "pending"
        $transaction = new Transaction([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'payment_method_id' => $this->getPaymentMethodId(),
            'reference_id' => $referenceId,
            'amount' => $amount,
            'type' => Transaction::TYPE_PAYMENT,
            'status' => Transaction::STATUS_PENDING,
            'metadata' => array_merge($metadata, [
                'package_id' => $package->id,
                'cycle' => $cycle,
            ]),
        ]);
        
        $transaction->save();
        
        return $transaction;
    }
    
    /**
     * Crée un nouvel abonnement après paiement réussi
     * 
     * @param Transaction $transaction La transaction associée
     * @param string $providerId L'identifiant chez le fournisseur de paiement
     * @param \DateTimeInterface|null $startDate La date de début (par défaut: maintenant)
     * @param \DateTimeInterface|null $endDate La date de fin (null pour les abonnements à vie)
     * @return Subscription L'abonnement créé
     */
    protected function createSubscription(
        Transaction $transaction, 
        string $providerId, 
        \DateTimeInterface $startDate = null, 
        \DateTimeInterface $endDate = null
    ): Subscription {
        $metadata = $transaction->metadata;
        $packageId = $metadata['package_id'] ?? null;
        $cycle = $metadata['cycle'] ?? 'monthly';
        
        if (!$packageId) {
            throw new \InvalidArgumentException("ID de package manquant dans les métadonnées de la transaction");
        }
        
        $package = Package::findOrFail($packageId);
        $user = $transaction->user;
        
        // Calculer la date de fin selon le cycle
        $startDate = $startDate ?? now();
        if (!$endDate && $cycle !== 'lifetime') {
            $endDate = $this->calculateEndDate($startDate, $package, $cycle);
        }
        
        // Déterminer le statut de l'abonnement
        $status = Subscription::STATUS_ACTIVE;
        
        // Créer l'abonnement
        $subscription = new Subscription([
            'user_id' => $user->id,
            'plan_id' => $packageId, // On utilise l'ID du package comme plan_id
            'payment_method_id' => $transaction->payment_method_id,
            'currency_id' => $transaction->currency_id,
            'amount_paid' => $transaction->amount,
            'provider' => $this->getProviderCode(),
            'provider_id' => $providerId,
            'provider_status' => 'active',
            'payment_reference' => $transaction->reference_id,
            'status' => $status,
            'frequency' => $cycle,
            'starts_at' => $startDate,
            'ends_at' => $endDate,
            'next_payment_at' => $cycle !== 'lifetime' ? $this->calculateNextPaymentDate($startDate, $cycle) : null,
            'auto_renew' => $cycle !== 'lifetime',
        ]);
        
        // Associer l'abonnement à la transaction
        DB::transaction(function() use ($subscription, $transaction) {
            $subscription->save();
            $transaction->subscription_id = $subscription->id;
            $transaction->save();
        });
        
        return $subscription;
    }
    
    /**
     * Calcule la date de fin d'un abonnement
     * 
     * @param \DateTimeInterface $startDate La date de début
     * @param Package $package Le package
     * @param string $cycle Le cycle de facturation
     * @return \DateTimeInterface La date de fin
     */
    protected function calculateEndDate(
        \DateTimeInterface $startDate, 
        Package $package, 
        string $cycle
    ): \DateTimeInterface {
        $cycleType = $package->cycle_type;
        $cycleCount = $package->cycle_count;
        
        $date = \DateTime::createFromInterface($startDate);
        
        switch ($cycle) {
            case 'monthly':
                $date->modify("+{$cycleCount} month");
                break;
            case 'annual':
                $date->modify("+{$cycleCount} year");
                break;
            default:
                $date->modify("+30 days"); // Par défaut, 30 jours
        }
        
        return $date;
    }
    
    /**
     * Calcule la date du prochain paiement
     * 
     * @param \DateTimeInterface $startDate La date de début
     * @param string $cycle Le cycle de facturation
     * @return \DateTimeInterface La date du prochain paiement
     */
    protected function calculateNextPaymentDate(
        \DateTimeInterface $startDate, 
        string $cycle
    ): \DateTimeInterface {
        $date = \DateTime::createFromInterface($startDate);
        
        switch ($cycle) {
            case 'monthly':
                $date->modify("+1 month");
                break;
            case 'annual':
                $date->modify("+1 year");
                break;
            default:
                $date->modify("+30 days"); // Par défaut, 30 jours
        }
        
        return $date;
    }
    
    /**
     * Obtient l'ID de la méthode de paiement associée
     * 
     * @return int
     */
    abstract protected function getPaymentMethodId(): int;
    
    /**
     * Obtient le code du fournisseur de paiement
     * 
     * @return string
     */
    abstract protected function getProviderCode(): string;
} 