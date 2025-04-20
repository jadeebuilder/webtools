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
use Illuminate\Support\Str;

class PaypalPaymentProcessor extends AbstractPaymentProcessor
{
    /**
     * Le code du fournisseur
     */
    const PROVIDER_CODE = 'paypal';
    
    /**
     * ID de la méthode de paiement en base de données
     */
    protected ?int $paymentMethodId = null;
    
    /**
     * Client PayPal
     */
    protected $client = null;
    
    /**
     * Base URL pour l'API PayPal
     */
    protected string $apiUrl;
    
    /**
     * {@inheritdoc}
     */
    protected function initialize(): void
    {
        // Déterminer si on est en mode sandbox ou production
        $sandbox = $this->config['sandbox'] ?? true;
        
        // Définir l'URL de l'API en fonction du mode
        $this->apiUrl = $sandbox 
            ? 'https://api-m.sandbox.paypal.com' 
            : 'https://api-m.paypal.com';
        
        // Récupérer l'ID de la méthode de paiement
        try {
            $method = PaymentMethod::where('code', self::PROVIDER_CODE)
                ->where('is_active', true)
                ->first();
                
            if ($method) {
                $this->paymentMethodId = $method->id;
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'initialisation du processeur PayPal: ' . $e->getMessage());
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
        
        // Vérifier que la devise est supportée par PayPal
        if (!$this->isCurrencySupported($currency->code)) {
            throw new \InvalidArgumentException('Devise non supportée par PayPal: ' . $currency->code);
        }
        
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
        
        // Créer la transaction
        $transaction = $this->createTransaction($user, $package, $currency, $amount, $cycle);
        
        try {
            // Obtenir un token d'accès
            $accessToken = $this->getAccessToken();
            
            // Créer une commande PayPal
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $transaction->reference_id,
                        'description' => $package->getName() . ' - ' . ucfirst($cycle),
                        'custom_id' => $transaction->reference_id,
                        'amount' => [
                            'currency_code' => $currency->code,
                            'value' => number_format($amount, 2, '.', ''),
                        ],
                    ]
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'landing_page' => 'BILLING',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('payment.success', [
                        'locale' => app()->getLocale(),
                        'provider' => self::PROVIDER_CODE,
                        'reference' => $transaction->reference_id
                    ]),
                    'cancel_url' => route('payment.cancel', [
                        'locale' => app()->getLocale(),
                        'reference' => $transaction->reference_id
                    ]),
                ],
            ];
            
            // Ajouter la période pour les abonnements récurrents
            if (in_array($cycle, ['monthly', 'annual']) && $this->config['subscription_enabled'] ?? false) {
                $frequencyMap = [
                    'monthly' => [
                        'interval_unit' => 'MONTH',
                        'interval_count' => 1,
                    ],
                    'annual' => [
                        'interval_unit' => 'YEAR',
                        'interval_count' => 1,
                    ],
                ];
                
                // Pour les abonnements, utiliser une structure différente
                // Note: PayPal Subscriptions API a une structure spécifique
                // Ceci est une simplification pour l'exemple
                $orderData['plan'] = [
                    'billing_cycles' => [
                        [
                            'frequency' => $frequencyMap[$cycle],
                            'tenure_type' => 'REGULAR',
                            'sequence' => 1,
                            'total_cycles' => 0, // 0 = unlimited
                            'pricing_scheme' => [
                                'fixed_price' => [
                                    'currency_code' => $currency->code,
                                    'value' => number_format($amount, 2, '.', ''),
                                ],
                            ],
                        ],
                    ],
                ];
            }
            
            // Envoyer la requête à PayPal
            $response = $this->sendRequest('POST', '/v2/checkout/orders', $orderData, $accessToken);
            
            if (!isset($response['id'])) {
                throw new \Exception('Réponse PayPal invalide: ID de commande manquant');
            }
            
            // Trouver l'URL d'approbation
            $approvalUrl = null;
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }
            
            if (!$approvalUrl) {
                throw new \Exception('URL d\'approbation PayPal manquante');
            }
            
            // Mettre à jour la transaction avec l'ID de la commande PayPal
            $transaction->provider_id = $response['id'];
            $transaction->save();
            
            // Retourner l'URL de redirection
            return [
                'success' => true,
                'redirect_url' => $approvalUrl,
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
            ];
            
        } catch (\Exception $e) {
            // Marquer la transaction comme échouée
            $transaction->markAsFailed(['error' => $e->getMessage()]);
            
            // Journaliser l'erreur
            Log::error('Erreur PayPal lors de l\'initialisation du paiement: ' . $e->getMessage());
            
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
            // Vérifier l'événement PayPal
            $eventType = $data['event_type'] ?? '';
            $resource = $data['resource'] ?? [];
            
            if (empty($eventType) || empty($resource)) {
                Log::error('Données webhook PayPal incomplètes');
                return false;
            }
            
            // Traiter selon le type d'événement
            switch ($eventType) {
                case 'PAYMENT.CAPTURE.COMPLETED':
                    return $this->handlePaymentCaptureCompleted($resource);
                    
                case 'CHECKOUT.ORDER.APPROVED':
                    return $this->handleOrderApproved($resource);
                    
                case 'BILLING.SUBSCRIPTION.CREATED':
                    return $this->handleSubscriptionCreated($resource);
                    
                case 'BILLING.SUBSCRIPTION.CANCELLED':
                    return $this->handleSubscriptionCancelled($resource);
                    
                default:
                    // Ignorer les autres types d'événements
                    return true;
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du webhook PayPal: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Traite un paiement capturé
     * 
     * @param array $resource Les données de la ressource PayPal
     * @return bool
     */
    protected function handlePaymentCaptureCompleted(array $resource): bool
    {
        // Récupérer l'ID de commande PayPal
        $orderId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;
        
        if (!$orderId) {
            Log::error('ID de commande PayPal manquant dans le webhook');
            return false;
        }
        
        // Trouver la transaction associée
        $transaction = Transaction::where('provider_id', $orderId)->first();
        
        if (!$transaction) {
            // Essayer de trouver par référence personnalisée
            $customId = $resource['custom_id'] ?? '';
            $transaction = Transaction::where('reference_id', $customId)->first();
            
            if (!$transaction) {
                Log::error('Transaction non trouvée pour l\'ID PayPal: ' . $orderId);
                return false;
            }
        }
        
        // Si la transaction est déjà complétée, ignorer
        if ($transaction->status === Transaction::STATUS_COMPLETED) {
            return true;
        }
        
        // Mise à jour de la transaction
        DB::beginTransaction();
        
        try {
            // Marquer la transaction comme complétée
            $transaction->provider_reference = $resource['id'] ?? null;
            $transaction->markAsCompleted();
            
            // Créer l'abonnement
            if (!$transaction->subscription_id) {
                $providerId = $orderId;
                $this->createSubscription($transaction, $providerId);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement du paiement PayPal: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Traite une commande approuvée
     * 
     * @param array $resource Les données de la ressource PayPal
     * @return bool
     */
    protected function handleOrderApproved(array $resource): bool
    {
        // Note: Cet événement est généralement suivi de PAYMENT.CAPTURE.COMPLETED
        // Il peut être utilisé pour capturer le paiement si le mode d'intention est "AUTHORIZE"
        return true;
    }
    
    /**
     * Traite un abonnement créé
     * 
     * @param array $resource Les données de la ressource PayPal
     * @return bool
     */
    protected function handleSubscriptionCreated(array $resource): bool
    {
        // Récupérer la référence personnalisée depuis les métadonnées de l'abonnement
        $customId = $resource['custom_id'] ?? '';
        
        if (empty($customId)) {
            Log::error('ID personnalisé manquant dans l\'abonnement PayPal');
            return false;
        }
        
        $transaction = Transaction::where('reference_id', $customId)->first();
        
        if (!$transaction) {
            Log::error('Transaction non trouvée pour la référence: ' . $customId);
            return false;
        }
        
        // Mise à jour de la transaction
        DB::beginTransaction();
        
        try {
            // Marquer la transaction comme complétée
            $transaction->provider_reference = $resource['id'] ?? null;
            $transaction->markAsCompleted();
            
            // Créer l'abonnement s'il n'existe pas déjà
            if (!$transaction->subscription_id) {
                $providerId = $resource['id'] ?? '';
                $this->createSubscription($transaction, $providerId);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement de l\'abonnement PayPal: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Traite un abonnement annulé
     * 
     * @param array $resource Les données de la ressource PayPal
     * @return bool
     */
    protected function handleSubscriptionCancelled(array $resource): bool
    {
        // Récupérer l'ID de l'abonnement
        $subscriptionId = $resource['id'] ?? '';
        
        if (empty($subscriptionId)) {
            Log::error('ID d\'abonnement manquant dans le webhook PayPal');
            return false;
        }
        
        // Trouver l'abonnement dans notre système
        $subscription = Subscription::where('provider', self::PROVIDER_CODE)
            ->where('provider_id', $subscriptionId)
            ->first();
            
        if (!$subscription) {
            Log::info('Aucun abonnement trouvé pour l\'ID PayPal: ' . $subscriptionId);
            return true;
        }
        
        // Mettre à jour l'abonnement
        $subscription->status = Subscription::STATUS_CANCELLED;
        $subscription->provider_status = 'cancelled';
        $subscription->ends_at = now()->addDays(1); // Accès valide jusqu'à la fin de la période payée
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
        $token = $data['token'] ?? null;
        
        if (!$referenceId) {
            return false;
        }
        
        // Vérifier si la transaction existe
        $transaction = Transaction::where('reference_id', $referenceId)->first();
        
        if (!$transaction) {
            return false;
        }
        
        // Si la transaction n'est pas encore complétée, vérifier son statut chez PayPal
        if ($transaction->status !== Transaction::STATUS_COMPLETED && $transaction->provider_id) {
            try {
                // Obtenir un token d'accès
                $accessToken = $this->getAccessToken();
                
                // Vérifier le statut de la commande
                $orderDetails = $this->sendRequest('GET', "/v2/checkout/orders/{$transaction->provider_id}", null, $accessToken);
                
                if (isset($orderDetails['status']) && $orderDetails['status'] === 'COMPLETED') {
                    // Marquer la transaction comme complétée
                    $transaction->markAsCompleted();
                    
                    // Créer l'abonnement si nécessaire
                    if (!$transaction->subscription_id) {
                        $providerId = $transaction->provider_id;
                        $this->createSubscription($transaction, $providerId);
                    }
                } elseif (isset($orderDetails['status']) && $orderDetails['status'] === 'APPROVED') {
                    // Capturer le paiement
                    $captureResult = $this->sendRequest('POST', "/v2/checkout/orders/{$transaction->provider_id}/capture", [], $accessToken);
                    
                    if (isset($captureResult['status']) && $captureResult['status'] === 'COMPLETED') {
                        // Marquer la transaction comme complétée
                        $transaction->markAsCompleted();
                        
                        // Créer l'abonnement si nécessaire
                        if (!$transaction->subscription_id) {
                            $providerId = $transaction->provider_id;
                            $this->createSubscription($transaction, $providerId);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de la vérification du statut PayPal: ' . $e->getMessage());
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
            
        if (!$transaction || !$transaction->provider_id) {
            return false;
        }
        
        try {
            // Annuler la commande chez PayPal si elle est en cours
            $accessToken = $this->getAccessToken();
            
            // Vérifier d'abord le statut de la commande
            $orderDetails = $this->sendRequest('GET', "/v2/checkout/orders/{$transaction->provider_id}", null, $accessToken);
            
            if (isset($orderDetails['status']) && in_array($orderDetails['status'], ['CREATED', 'SAVED', 'APPROVED'])) {
                // La commande peut être annulée
                $this->sendRequest('POST', "/v2/checkout/orders/{$transaction->provider_id}/cancel", [], $accessToken);
            }
            
            // Marquer la transaction comme annulée dans notre système
            $transaction->status = Transaction::STATUS_CANCELLED;
            $transaction->save();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation du paiement PayPal: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsRecurring(): bool
    {
        return isset($this->config['subscription_enabled']) && $this->config['subscription_enabled'];
    }
    
    /**
     * Obtenir un token d'accès PayPal
     * 
     * @return string Le token d'accès
     * @throws \Exception Si l'authentification échoue
     */
    protected function getAccessToken(): string
    {
        $clientId = $this->config['client_id'] ?? '';
        $clientSecret = $this->config['client_secret'] ?? '';
        
        if (empty($clientId) || empty($clientSecret)) {
            throw new \Exception('Identifiants PayPal manquants');
        }
        
        // Préparer la requête
        $ch = curl_init($this->apiUrl . '/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $clientSecret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Accept-Language: en_US',
        ]);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new \Exception('Erreur cURL: ' . curl_error($ch));
        }
        
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($statusCode !== 200) {
            throw new \Exception('Erreur d\'authentification PayPal: ' . $response);
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['access_token'])) {
            throw new \Exception('Token d\'accès PayPal manquant dans la réponse');
        }
        
        return $data['access_token'];
    }
    
    /**
     * Envoie une requête à l'API PayPal
     * 
     * @param string $method Méthode HTTP (GET, POST, etc.)
     * @param string $endpoint Endpoint de l'API
     * @param array|null $data Données à envoyer
     * @param string $accessToken Token d'accès
     * @return array La réponse décodée
     * @throws \Exception Si la requête échoue
     */
    protected function sendRequest(string $method, string $endpoint, ?array $data, string $accessToken): array
    {
        $url = $this->apiUrl . $endpoint;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken,
        ];
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new \Exception('Erreur cURL: ' . curl_error($ch));
        }
        
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $responseData = json_decode($response, true);
        
        if ($statusCode < 200 || $statusCode >= 300) {
            $errorMsg = isset($responseData['error_description']) 
                ? $responseData['error_description'] 
                : (isset($responseData['message']) ? $responseData['message'] : 'Erreur inconnue');
                
            throw new \Exception('Erreur API PayPal (' . $statusCode . '): ' . $errorMsg);
        }
        
        return $responseData;
    }
    
    /**
     * Vérifie si une devise est supportée par PayPal
     * 
     * @param string $currencyCode Code ISO de la devise
     * @return bool
     */
    protected function isCurrencySupported(string $currencyCode): bool
    {
        // Liste des devises supportées par PayPal
        $supportedCurrencies = [
            'AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'INR', 
            'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 
            'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD'
        ];
        
        return in_array(strtoupper($currencyCode), $supportedCurrencies);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getPaymentMethodId(): int
    {
        if (!$this->paymentMethodId) {
            throw new \RuntimeException('ID de méthode de paiement PayPal non disponible');
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