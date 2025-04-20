<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Currency;
use App\Models\PackagePrice;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackagePriceController extends Controller
{
    /**
     * Affiche la liste des prix des packages.
     *
     * @param string $locale
     * @return \Illuminate\View\View
     */
    public function index(string $locale)
    {
        try {
            // Récupérer tous les packages actifs
            $packages = Package::where('is_active', true)->orderBy('order')->get();
            
            // Récupérer toutes les devises
            $currencies = Currency::orderBy('is_default', 'desc')->orderBy('code')->get();
            
            // Récupérer les méthodes de paiement
            $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('display_order')->get();
            
            return view('admin.payments.package-prices.index', [
                'packages' => $packages,
                'currencies' => $currencies,
                'paymentMethods' => $paymentMethods
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement des prix des packages: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard', ['locale' => $locale])
                ->with('error', __('Impossible de charger les prix des packages. ') . $e->getMessage());
        }
    }
    
    /**
     * Affiche le formulaire de modification des prix pour un package et une devise spécifiques.
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, string $locale)
    {
        try {
            $packageId = $request->input('package_id');
            $currencyId = $request->input('currency_id');
            
            if (!$packageId || !$currencyId) {
                return redirect()->route('admin.payments.package-prices.index', ['locale' => $locale])
                    ->with('error', __('Veuillez sélectionner un package et une devise.'));
            }
            
            // Récupérer le package
            $package = Package::findOrFail($packageId);
            
            // Récupérer la devise
            $currency = Currency::findOrFail($currencyId);
            
            // Récupérer les prix existants ou en créer un nouveau
            $packagePrice = PackagePrice::firstOrNew([
                'package_id' => $packageId,
                'currency_id' => $currencyId
            ]);
            
            // Récupérer les méthodes de paiement
            $paymentMethods = PaymentMethod::where('is_active', true)
                ->whereHas('currencies', function($query) use ($currencyId) {
                    $query->where('currency_id', $currencyId);
                })
                ->orderBy('display_order')
                ->get();
            
            return view('admin.payments.package-prices.edit', [
                'package' => $package,
                'currency' => $currency,
                'packagePrice' => $packagePrice,
                'paymentMethods' => $paymentMethods
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement du formulaire d\'édition des prix: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.package-prices.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le formulaire. ') . $e->getMessage());
        }
    }
    
    /**
     * Met à jour les prix d'un package pour une devise spécifique.
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $locale)
    {
        try {
            $validatedData = $request->validate([
                'package_id' => 'required|exists:packages,id',
                'currency_id' => 'required|exists:currencies,id',
                'monthly_price' => 'required|numeric|min:0',
                'annual_price' => 'required|numeric|min:0',
                'lifetime_price' => 'required|numeric|min:0',
                'payment_provider_ids' => 'nullable|array',
                'payment_provider_ids.*' => 'nullable|array',
                'payment_provider_ids.*.*' => 'nullable|string',
            ]);
            
            // Nettoyer les ID de fournisseurs de paiement vides
            $paymentProviderIds = [];
            
            if (isset($validatedData['payment_provider_ids'])) {
                foreach ($validatedData['payment_provider_ids'] as $provider => $cycles) {
                    $paymentProviderIds[$provider] = [];
                    
                    foreach ($cycles as $cycle => $id) {
                        if (!empty($id)) {
                            $paymentProviderIds[$provider][$cycle] = $id;
                        }
                    }
                }
            }
            
            DB::beginTransaction();
            
            try {
                // Mettre à jour ou créer les prix
                $packagePrice = PackagePrice::updateOrCreate(
                    [
                        'package_id' => $validatedData['package_id'],
                        'currency_id' => $validatedData['currency_id']
                    ],
                    [
                        'monthly_price' => $validatedData['monthly_price'],
                        'annual_price' => $validatedData['annual_price'],
                        'lifetime_price' => $validatedData['lifetime_price'],
                        'payment_provider_ids' => $paymentProviderIds
                    ]
                );
                
                DB::commit();
                
                return redirect()->route('admin.payments.package-prices.index', ['locale' => $locale])
                    ->with('success', __('Les prix du package ont été mis à jour avec succès.'));
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Erreur lors de la mise à jour des prix: ' . $e->getMessage());
                
                return redirect()->back()->withInput()
                    ->with('error', __('Erreur lors de la mise à jour des prix: ') . $e->getMessage());
            }
        } catch (\Exception $e) {
            \Log::error('Erreur de validation: ' . $e->getMessage());
            
            return redirect()->back()->withInput()
                ->with('error', __('Erreur de validation: ') . $e->getMessage());
        }
    }
} 