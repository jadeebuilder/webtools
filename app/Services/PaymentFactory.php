<?php

namespace App\Services;

use App\Models\PaymentMethod;
use App\Models\Currency;
use App\Services\Payments\PaymentProcessorInterface;
use App\Services\Payments\StripePaymentProcessor;
use App\Services\Payments\PaypalPaymentProcessor;
use Illuminate\Support\Facades\Log;

class PaymentFactory
{
    /**
     * Créer une instance de processeur de paiement
     * 
     * @param string $type Code du type de processeur (stripe, paypal, etc.)
     * @param array $config Configuration supplémentaire
     * @return PaymentProcessorInterface
     * @throws \Exception Si le processeur n'existe pas
     */
    public function create(string $type, array $config = []): PaymentProcessorInterface
    {
        $processor = match(strtolower($type)) {
            'stripe' => new StripePaymentProcessor($config),
            'paypal' => new PaypalPaymentProcessor($config),
            default => $this->createFromClassName($type, $config),
        };
        
        return $processor;
    }
    
    /**
     * Créer un processeur à partir du nom de classe
     * 
     * @param string $className Nom de la classe (App\\Services\\Payments\\CustomProcessor)
     * @param array $config Configuration
     * @return PaymentProcessorInterface
     * @throws \Exception Si la classe n'existe pas ou n'implémente pas l'interface
     */
    protected function createFromClassName(string $className, array $config = []): PaymentProcessorInterface
    {
        if (!class_exists($className)) {
            throw new \Exception("Classe de processeur de paiement non trouvée: {$className}");
        }
        
        $processor = new $className($config);
        
        if (!($processor instanceof PaymentProcessorInterface)) {
            throw new \Exception("La classe {$className} n'implémente pas PaymentProcessorInterface");
        }
        
        return $processor;
    }
    
    /**
     * Créer un processeur pour une méthode de paiement et une devise
     * 
     * @param PaymentMethod $method Méthode de paiement
     * @param Currency $currency Devise
     * @return PaymentProcessorInterface
     */
    public function createForMethodAndCurrency(PaymentMethod $method, Currency $currency): PaymentProcessorInterface
    {
        // Récupérer la configuration spécifique à la devise depuis la table pivot
        $pivotData = $method->currencies()->where('currency_id', $currency->id)->first()->pivot ?? null;
        
        $currencyConfig = $pivotData ? ($pivotData->settings ?? []) : [];
        
        // Fusionner les configurations
        $config = array_merge($method->settings ?? [], $currencyConfig);
        
        // Créer le processeur
        if (!empty($method->processor_class)) {
            return $this->createFromClassName($method->processor_class, $config);
        }
        
        return $this->create($method->code, $config);
    }
    
    /**
     * Obtenir les méthodes de paiement disponibles pour une devise
     * 
     * @param Currency $currency Devise
     * @return array Tableau de méthodes [code => nom]
     */
    public static function getAvailableMethodsForCurrency(Currency $currency): array
    {
        $methods = [];
        
        try {
            $availableMethods = $currency->paymentMethods()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get();
                
            foreach ($availableMethods as $method) {
                $methods[$method->code] = $method->name;
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des méthodes de paiement: ' . $e->getMessage());
        }
        
        return $methods;
    }
} 