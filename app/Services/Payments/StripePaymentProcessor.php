<?php

namespace App\Services\Payments;

use App\Models\User;
use App\Models\Package;
use App\Models\PackagePrice;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class StripePaymentProcessor extends AbstractPaymentProcessor
{
    /**
     * Le code du fournisseur
     */
    const PROVIDER_CODE = 'stripe';
    
    /**
     * ID de la méthode de paiement en base de données
     */
    protected ?int $paymentMethodId = null;
    
    /**
     * {@inheritdoc}
     */
    protected function initialize(): void
    {
        // Définir la clé API Stripe
        $apiKey = $this->config['secret_key'] ?? config('services.stripe.secret');
        Stripe::setApiKey($apiKey);
        
        // Récupérer l'ID de la méthode de paiement
        try {
            $method = PaymentMethod::where('code', self::PROVIDER_CODE)
                ->where('is_active', true)
                ->first();
                
            if ($method) {
                $this->paymentMethodId = $method->id;
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'initialisation du processeur Stripe: ' . $e->getMessage());
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function initiatePayment(array $data): array
    {
        // Vérifier que les données nécessaires sont présentes
        if (!isset($data['user']) || !isset($data['package']) || !isset($data['currency']) || !isset($data['cycle'])) {
            throw new \InvalidArgumentException('Données de paiement incomplètes');
        }
        
        /** @var User $user */
        $user = $data['user'];
        /** @var Package $package */
        $package = $data['package'];
        /** @var Currency $currency */
        $currency = $data['currency'];
        $cycle = $data['cycle'];
        
        // Trouver le prix en fonction de la devise
        $packagePrice = PackagePrice::where('package_id', $package->id)
            ->where('currency_id', $currency->id)
            ->first();
            
        if (!$packagePrice) {
            throw new \InvalidArgumentException('Prix non configuré pour ce package et cette devise');
        }
        
        // Obtenir le montant selon le cycle
        $amount = $packagePrice->getPriceForCycle($cycle);
        
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Le prix doit être supérieur à 0');
        }
        
        // Obtenir l'ID de prix Stripe
        $priceId = $packagePrice->getPaymentProviderId(self::PROVIDER_CODE, $cycle);
        
        if (empty($priceId)) {
            throw new \InvalidArgumentException('ID de prix Stripe non configuré pour ce package, cycle et devise');
        }
        
        // Créer la transaction
        $transaction = $this->createTransaction($user, $package, $currency, $amount, $cycle);
        
        // Déterminer le mode de paiement (abonnement ou paiement unique)
        $mode = in_array($cycle, ['monthly', 'annual']) ? 'subscription' : 'payment';
        
        try {
            // Créer la session Stripe Checkout
            $session = Session::create([
                'customer_email' => $user->email,
                'line_items' => [
                    [
                        'price' => $priceId,
                        'quantity' => 1,
                    ],
                ],
                'mode' => $mode,
                'success_url' => route('payment.success', [
                    'locale' => app()->getLocale(),
                    'provider' => self::PROVIDER_CODE,
                    'reference' => $transaction->reference_id
                ]),
                'cancel_url' => route('payment.cancel', [
                    'locale' => app()->getLocale(), 
                    'reference' => $transaction->reference_id
                ]),
                'metadata' => [
                    'transaction_id' => $transaction->id,
                    'reference_id' => $transaction->reference_id,
                    'package_id' => $package->id,
                    'user_id' => $user->id,
                    'cycle' => $cycle,
                    'currency' => $currency->code,
                ],
            ]);
            
            // Mettre à jour la transaction avec l'ID de la session
            $transaction->provider_id = $session->id;
            $transaction->save();
            
            // Retourner l'URL de redirection
            return [
                'success' => true,
                'redirect_url' => $session->url,
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
            ];
            
        } catch (ApiErrorException $e) {
            // Marquer la transaction comme échouée
            $transaction->markAsFailed(['error' => $e->getMessage()]);
            
            // Journaliser l'erreur
            Log::error('Erreur Stripe lors de l\'initialisation du paiement: ' . $e->getMessage());
            
            // Retourner une réponse d'erreur
            return [
                'success' => false,
                'error' => 'Erreur lors de l\'initialisation du paiement: ' . $e->getMessage(),
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
            ];
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function handleNotification(array $data): bool
    {
        try {
            // Récupérer l'événement Stripe
            $payload = $data['payload'] ?? null;
            $sigHeader = $data['signature'] ?? null;
            
            if (!$payload || !$sigHeader) {
                throw new \InvalidArgumentException('Données de webhook incomplètes');
            }
            
            // Vérifier la signature Stripe
            $endpoint_secret = $this->config['webhook_secret'] ?? config('services.stripe.webhook_secret');
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpoint_secret);
            
            // Traiter l'événement selon son type
            switch ($event->type) {
                case 'checkout.session.completed':
                    return $this->handleCheckoutSessionCompleted($event->data->object);
                    
                case 'payment_intent.succeeded':
                    return $this->handlePaymentIntentSucceeded($event->data->object);
                    
                case 'charge.succeeded':
                    return $this->handleChargeSucceeded($event->data->object);
                    
                case 'invoice.paid':
                    return $this->handleInvoicePaid($event->data->object);
                    
                case 'customer.subscription.deleted':
                    return $this->handleSubscriptionDeleted($event->data->object);
                    
                default:
                    // Ignorer les autres types d'événements
                    return true;
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du webhook Stripe: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Traite une session de paiement complétée
     * 
     * @param \Stripe\Checkout\Session $session
     * @return bool
     */
    protected function handleCheckoutSessionCompleted($session): bool
    {
        // Récupérer la transaction à partir des métadonnées
        $referenceId = $session->metadata->reference_id ?? null;
        
        if (!$referenceId) {
            Log::error('Référence de transaction manquante dans la session Stripe');
            return false;
        }
        
        $transaction = Transaction::where('reference_id', $referenceId)->first();
        
        if (!$transaction) {
            Log::error('Transaction non trouvée pour la référence: ' . $referenceId);
            return false;
        }
        
        // Mise à jour de la transaction
        DB::beginTransaction();
        
        try {
            // Marquer la transaction comme complétée
            $transaction->provider_reference = $session->payment_intent ?? $session->subscription;
            $transaction->markAsCompleted();
            
            // Créer l'abonnement
            $providerId = $session->subscription ?? $session->payment_intent;
            $subscription = $this->createSubscription($transaction, $providerId);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement de la session de paiement: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Traite un intent de paiement réussi
     * 
     * @param \Stripe\PaymentIntent $paymentIntent
     * @return bool
     */
    protected function handlePaymentIntentSucceeded($paymentIntent): bool
    {
        // Récupérer la transaction
        $transaction = Transaction::where('provider_reference', $paymentIntent->id)->first();
        
        if (!$transaction) {
            Log::info('Aucune transaction trouvée pour l\'intent de paiement: ' . $paymentIntent->id);
            return true; // Peut être un paiement non lié à notre système
        }
        
        // Si la transaction est déjà complétée, ignorer
        if ($transaction->status === Transaction::STATUS_COMPLETED) {
            return true;
        }
        
        // Marquer la transaction comme complétée
        $transaction->markAsCompleted();
        
        return true;
    }
    
    /**
     * Traite une charge réussie
     * 
     * @param \Stripe\Charge $charge
     * @return bool
     */
    protected function handleChargeSucceeded($charge): bool
    {
        // Similaire à handlePaymentIntentSucceeded, mais en utilisant l'ID de la charge
        return true;
    }
    
    /**
     * Traite une facture payée (pour les abonnements récurrents)
     * 
     * @param \Stripe\Invoice $invoice
     * @return bool
     */
    protected function handleInvoicePaid($invoice): bool
    {
        // Récupérer l'abonnement associé
        $subscription = Subscription::where('provider', self::PROVIDER_CODE)
            ->where('provider_id', $invoice->subscription)
            ->first();
            
        if (!$subscription) {
            Log::info('Aucun abonnement trouvé pour la facture: ' . $invoice->id);
            return true;
        }
        
        // Créer une nouvelle transaction pour ce paiement
        $transaction = new Transaction([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'currency_id' => $subscription->currency_id,
            'payment_method_id' => $subscription->payment_method_id,
            'reference_id' => Transaction::generateReferenceId(),
            'provider_id' => $invoice->id,
            'provider_reference' => $invoice->payment_intent,
            'amount' => $invoice->amount_paid / 100, // Stripe utilise des centimes
            'type' => Transaction::TYPE_SUBSCRIPTION,
            'status' => Transaction::STATUS_COMPLETED,
            'paid_at' => now(),
        ]);
        
        $transaction->save();
        
        // Mettre à jour la date de prochain paiement
        $subscription->next_payment_at = $this->calculateNextPaymentDate(now(), $subscription->frequency);
        $subscription->save();
        
        return true;
    }
    
    /**
     * Traite un abonnement supprimé
     * 
     * @param \Stripe\Subscription $stripeSubscription
     * @return bool
     */
    protected function handleSubscriptionDeleted($stripeSubscription): bool
    {
        // Récupérer l'abonnement
        $subscription = Subscription::where('provider', self::PROVIDER_CODE)
            ->where('provider_id', $stripeSubscription->id)
            ->first();
            
        if (!$subscription) {
            Log::info('Aucun abonnement trouvé pour l\'ID Stripe: ' . $stripeSubscription->id);
            return true;
        }
        
        // Mettre à jour l'abonnement
        $subscription->status = Subscription::STATUS_CANCELLED;
        $subscription->provider_status = 'cancelled';
        $subscription->ends_at = now()->addDays(1); // Généralement, l'accès reste valide jusqu'à la fin de la période payée
        $subscription->auto_renew = false;
        $subscription->save();
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function handleReturn(array $data): bool
    {
        // Récupérer la référence de transaction
        $referenceId = $data['reference'] ?? null;
        
        if (!$referenceId) {
            return false;
        }
        
        // Vérifier si la transaction existe et est complétée
        $transaction = Transaction::where('reference_id', $referenceId)->first();
        
        if (!$transaction) {
            return false;
        }
        
        // Si la transaction n'est pas encore marquée comme complétée, vérifier son statut chez Stripe
        if ($transaction->status !== Transaction::STATUS_COMPLETED && $transaction->provider_id) {
            try {
                $session = Session::retrieve($transaction->provider_id);
                
                if ($session->payment_status === 'paid') {
                    // Marquer la transaction comme complétée
                    $transaction->markAsCompleted($session->payment_intent);
                    
                    // Créer l'abonnement si nécessaire
                    if (!$transaction->subscription_id) {
                        $providerId = $session->subscription ?? $session->payment_intent;
                        $this->createSubscription($transaction, $providerId);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de la vérification du statut Stripe: ' . $e->getMessage());
                return false;
            }
        }
        
        return $transaction->status === Transaction::STATUS_COMPLETED;
    }
    
    /**
     * {@inheritdoc}
     */
    public function cancelPayment(string $paymentId): bool
    {
        // Récupérer la transaction
        $transaction = Transaction::where('reference_id', $paymentId)
            ->where('status', Transaction::STATUS_PENDING)
            ->first();
            
        if (!$transaction) {
            return false;
        }
        
        // Marquer la transaction comme annulée
        $transaction->status = Transaction::STATUS_CANCELLED;
        $transaction->save();
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsRecurring(): bool
    {
        return true; // Stripe supporte les abonnements récurrents
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getPaymentMethodId(): int
    {
        if (!$this->paymentMethodId) {
            throw new \RuntimeException('ID de méthode de paiement Stripe non disponible');
        }
        
        return $this->paymentMethodId;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getProviderCode(): string
    {
        return self::PROVIDER_CODE;
    }
} 