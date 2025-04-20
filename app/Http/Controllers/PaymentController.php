<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Package;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\PackagePrice;
use App\Services\PaymentFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * @var PaymentFactory
     */
    protected $paymentFactory;
    
    /**
     * Constructeur
     */
    public function __construct(PaymentFactory $paymentFactory)
    {
        $this->middleware('auth');
        $this->paymentFactory = $paymentFactory;
    }
    
    /**
     * Affiche la page de sélection des packages
     * 
     * @param string $locale Code de langue
     * @return \Illuminate\View\View
     */
    public function packages(string $locale)
    {
        // Récupérer les packages actifs
        $packages = Package::active()->orderBy('order')->get();
        
        // Récupérer la devise par défaut
        $defaultCurrency = Currency::getDefault();
        
        return view('payments.packages', [
            'packages' => $packages,
            'defaultCurrency' => $defaultCurrency,
        ]);
    }
    
    /**
     * Affiche la page de sélection de la devise et du cycle
     * 
     * @param Request $request
     * @param string $locale Code de langue
     * @param string $slug Slug du package
     * @return \Illuminate\View\View
     */
    public function checkout(Request $request, string $locale, string $slug)
    {
        // Récupérer le package
        $package = Package::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        // Récupérer la devise par défaut
        $defaultCurrency = Currency::getDefault();
        
        if (!$defaultCurrency) {
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Aucune devise par défaut n\'est configurée dans le système'));
        }
        
        // Récupérer toutes les devises disponibles
        $currencies = Currency::whereHas('packagePrices', function($query) use ($package) {
            $query->where('package_id', $package->id);
        })->get();
        
        // Si aucune devise n'est disponible, utiliser la devise par défaut
        if ($currencies->isEmpty()) {
            // Vérifier si un prix existe déjà pour ce package et cette devise
            $packagePrice = PackagePrice::firstOrCreate(
                [
                    'package_id' => $package->id,
                    'currency_id' => $defaultCurrency->id
                ],
                [
                    'monthly_price' => $package->monthly_price ?? 0,
                    'annual_price' => $package->annual_price ?? 0,
                    'lifetime_price' => $package->lifetime_price ?? 0,
                    'payment_provider_ids' => []
                ]
            );
            
            $currencies = collect([$defaultCurrency]);
        }
        
        // Vérifier si la devise par défaut est disponible, sinon prendre la première
        $selectedCurrency = $currencies->contains('id', $defaultCurrency->id) 
            ? $defaultCurrency 
            : $currencies->first();
            
        // Déterminer les cycles disponibles
        $cycles = [];
        
        // Récupérer les prix pour cette devise
        $prices = PackagePrice::where('package_id', $package->id)
            ->where('currency_id', $selectedCurrency->id)
            ->first();
            
        if (!$prices) {
            // Créer des prix par défaut si nécessaire
            $prices = PackagePrice::create([
                'package_id' => $package->id,
                'currency_id' => $selectedCurrency->id,
                'monthly_price' => $package->monthly_price ?? 0,
                'annual_price' => $package->annual_price ?? 0,
                'lifetime_price' => $package->lifetime_price ?? 0,
                'payment_provider_ids' => []
            ]);
        }
        
        // Ajouter les cycles disponibles
        if ($prices->monthly_price > 0) {
            $cycles['monthly'] = __('Mensuel');
        }
        
        if ($prices->annual_price > 0) {
            $cycles['annual'] = __('Annuel');
        }
        
        if ($prices->lifetime_price > 0) {
            $cycles['lifetime'] = __('À vie');
        }
        
        // Si aucun cycle n'est disponible
        if (empty($cycles)) {
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Aucun plan de tarification n\'est configuré pour ce package'));
        }
        
        // Stocker les données nécessaires en session
        session([
            'checkout_package_id' => $package->id,
            'checkout_currency_id' => $selectedCurrency->id,
        ]);
        
        return view('payments.checkout', [
            'package' => $package,
            'currencies' => $currencies,
            'selectedCurrency' => $selectedCurrency,
            'cycles' => $cycles,
        ]);
    }
    
    /**
     * Traitement du formulaire de sélection de devise et de cycle
     * 
     * @param Request $request
     * @param string $locale Code de langue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function selectCurrencyAndCycle(Request $request, string $locale)
    {
        $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'cycle' => 'required|in:monthly,annual,lifetime',
        ]);
        
        // Récupérer les données de la session
        $packageId = session('checkout_package_id');
        
        if (!$packageId) {
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Veuillez sélectionner un package'));
        }
        
        // Récupérer le package
        $package = Package::findOrFail($packageId);
        
        // Mettre à jour les données en session
        session([
            'checkout_currency_id' => $request->currency_id,
            'checkout_cycle' => $request->cycle,
        ]);
        
        return redirect()->route('payment.methods', ['locale' => $locale]);
    }
    
    /**
     * Affiche les méthodes de paiement disponibles
     * 
     * @param string $locale Code de langue
     * @return \Illuminate\View\View
     */
    public function paymentMethods(string $locale)
    {
        // Récupérer les données de la session
        $packageId = session('checkout_package_id');
        $currencyId = session('checkout_currency_id');
        $cycle = session('checkout_cycle');
        
        if (!$packageId || !$currencyId || !$cycle) {
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Informations de paiement incomplètes'));
        }
        
        // Récupérer le package, la devise et le prix
        $package = Package::findOrFail($packageId);
        $currency = Currency::findOrFail($currencyId);
        
        // Récupérer le prix pour cette devise
        $packagePrice = PackagePrice::where('package_id', $package->id)
            ->where('currency_id', $currency->id)
            ->first();
            
        if (!$packagePrice) {
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Prix non disponible pour cette devise'));
        }
        
        // Récupérer les méthodes de paiement disponibles pour cette devise
        $paymentMethods = PaymentMethod::whereHas('currencies', function($query) use ($currency) {
            $query->where('currency_id', $currency->id)
                ->where('currency_payment_method.is_active', true);
        })->get();
        
        // Vérifier que le prix est configuré pour les méthodes de paiement
        $availableMethods = collect();
        
        foreach ($paymentMethods as $method) {
            $priceId = $packagePrice->getPaymentProviderId($method->code, $cycle);
            
            // Pour les méthodes sans ID de produit (comme les virements manuels), on vérifie juste le prix
            $priceAvailable = $priceId || !in_array($method->code, ['stripe', 'paypal']);
            
            if ($priceAvailable) {
                $availableMethods->push($method);
            }
        }
        
        if ($availableMethods->isEmpty()) {
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Aucune méthode de paiement disponible pour cette devise et ce cycle'));
        }
        
        return view('payments.methods', [
            'package' => $package,
            'currency' => $currency,
            'cycle' => $cycle,
            'packagePrice' => $packagePrice,
            'paymentMethods' => $availableMethods,
        ]);
    }
    
    /**
     * Initialise le paiement avec la méthode choisie
     * 
     * @param Request $request
     * @param string $locale Code de langue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, string $locale)
    {
        $request->validate([
            'payment_method' => 'required|exists:payment_methods,code',
        ]);
        
        // Récupérer les données de la session
        $packageId = session('checkout_package_id');
        $currencyId = session('checkout_currency_id');
        $cycle = session('checkout_cycle');
        
        if (!$packageId || !$currencyId || !$cycle) {
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Informations de paiement incomplètes'));
        }
        
        try {
            // Récupérer le package, la devise, l'utilisateur et la méthode de paiement
            $package = Package::findOrFail($packageId);
            $currency = Currency::findOrFail($currencyId);
            $user = Auth::user();
            $paymentMethod = PaymentMethod::where('code', $request->payment_method)
                ->where('is_active', true)
                ->firstOrFail();
                
            // Créer le processeur de paiement
            $processor = $this->paymentFactory->createForMethodAndCurrency($paymentMethod, $currency);
            
            // Initialiser le paiement
            $result = $processor->initiatePayment([
                'package' => $package,
                'user' => $user,
                'currency' => $currency,
                'cycle' => $cycle,
            ]);
            
            if (!isset($result['success']) || !$result['success']) {
                return redirect()->route('payment.methods', ['locale' => $locale])
                    ->with('error', $result['error'] ?? __('Erreur lors de l\'initialisation du paiement'));
            }
            
            // Rediriger vers l'URL de paiement
            if (isset($result['redirect_url'])) {
                return redirect()->away($result['redirect_url']);
            }
            
            // Si pas d'URL, c'est un succès immédiat
            return redirect()->route('payment.success', [
                'locale' => $locale,
                'provider' => $paymentMethod->code,
                'reference' => $result['reference_id'] ?? null,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du processus de paiement: ' . $e->getMessage());
            
            return redirect()->route('payment.methods', ['locale' => $locale])
                ->with('error', __('Erreur lors du traitement du paiement: ') . $e->getMessage());
        }
    }
    
    /**
     * Page de succès après paiement
     * 
     * @param Request $request
     * @param string $locale Code de langue
     * @param string $provider Code du fournisseur de paiement
     * @return \Illuminate\View\View
     */
    public function success(Request $request, string $locale, string $provider)
    {
        $reference = $request->reference;
        
        if (!$reference) {
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Référence de transaction manquante'));
        }
        
        try {
            // Récupérer la méthode de paiement
            $paymentMethod = PaymentMethod::where('code', $provider)
                ->where('is_active', true)
                ->first();
                
            if (!$paymentMethod) {
                return redirect()->route('packages', ['locale' => $locale])
                    ->with('error', __('Méthode de paiement non trouvée'));
            }
            
            // Récupérer la transaction
            $transaction = Transaction::where('reference_id', $reference)->first();
            
            if (!$transaction) {
                return redirect()->route('packages', ['locale' => $locale])
                    ->with('error', __('Transaction non trouvée'));
            }
            
            // Vérifier l'état du paiement auprès du fournisseur
            $processor = $this->paymentFactory->create($provider);
            
            $paymentVerified = $processor->handleReturn([
                'reference' => $reference,
                'provider' => $provider,
                'token' => $request->token ?? null,
                'paymentId' => $request->paymentId ?? null,
                'PayerID' => $request->PayerID ?? null,
            ]);
            
            // Si le paiement est vérifié, afficher la page de succès
            if ($paymentVerified) {
                return view('payments.success', [
                    'transaction' => $transaction,
                ]);
            }
            
            // Sinon, rediriger vers la page d'erreur
            return redirect()->route('payment.cancel', [
                'locale' => $locale,
                'reference' => $reference,
            ])->with('error', __('Le paiement n\'a pas été confirmé'));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification du paiement: ' . $e->getMessage());
            
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Erreur lors de la vérification du paiement: ') . $e->getMessage());
        }
    }
    
    /**
     * Page d'annulation de paiement
     * 
     * @param Request $request
     * @param string $locale Code de langue
     * @return \Illuminate\View\View
     */
    public function cancel(Request $request, string $locale)
    {
        $reference = $request->reference;
        
        if ($reference) {
            // Récupérer la transaction
            $transaction = Transaction::where('reference_id', $reference)->first();
            
            if ($transaction && $transaction->status === Transaction::STATUS_PENDING) {
                // Marquer la transaction comme annulée
                $transaction->status = Transaction::STATUS_CANCELLED;
                $transaction->save();
            }
        }
        
        return view('payments.cancel');
    }
    
    /**
     * Traite les webhooks des fournisseurs de paiement
     * 
     * @param Request $request
     * @param string $provider Code du fournisseur de paiement
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request, string $provider)
    {
        try {
            // Récupérer la méthode de paiement
            $paymentMethod = PaymentMethod::where('code', $provider)
                ->where('is_active', true)
                ->first();
                
            if (!$paymentMethod) {
                Log::error('Méthode de paiement non trouvée pour le webhook: ' . $provider);
                return response()->json(['error' => 'Méthode de paiement non trouvée'], 404);
            }
            
            // Créer le processeur de paiement
            $processor = $this->paymentFactory->create($provider, $paymentMethod->settings);
            
            // Préparer les données du webhook selon le fournisseur
            $webhookData = [];
            
            switch ($provider) {
                case 'stripe':
                    $webhookData = [
                        'payload' => $request->getContent(),
                        'signature' => $request->header('Stripe-Signature'),
                    ];
                    break;
                    
                case 'paypal':
                    $webhookData = $request->all();
                    break;
                    
                default:
                    $webhookData = $request->all();
            }
            
            // Traiter le webhook
            $result = $processor->handleNotification($webhookData);
            
            if ($result) {
                return response()->json(['success' => true]);
            }
            
            return response()->json(['error' => 'Erreur lors du traitement du webhook'], 500);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur interne'], 500);
        }
    }
} 