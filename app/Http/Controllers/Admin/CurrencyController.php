<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    /**
     * Affiche la liste des devises.
     *
     * @param string $locale
     * @return \Illuminate\View\View
     */
    public function index(string $locale)
    {
        try {
            $currencies = Currency::with('country')
                ->orderBy('is_default', 'desc')
                ->orderBy('code')
                ->get();
                
            return view('admin.payments.currencies.index', compact('currencies'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des devises: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard', ['locale' => $locale])
                ->with('error', __('Impossible de charger les devises. ') . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de création d'une devise.
     *
     * @param string $locale
     * @return \Illuminate\View\View
     */
    public function create(string $locale)
    {
        try {
            $countries = Country::orderBy('name')->get();
            return view('admin.payments.currencies.create', compact('countries'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire de création: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le formulaire. ') . $e->getMessage());
        }
    }

    /**
     * Enregistre une nouvelle devise.
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, string $locale)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|size:3|unique:currencies,code',
                'country_id' => 'nullable|exists:countries,id',
                'symbol' => 'required|string|max:10',
                'symbol_native' => 'nullable|string|max:10',
                'symbol_first' => 'sometimes|boolean',
                'precision' => 'required|integer|min:0|max:4',
                'decimal_mark' => 'required|string|max:1',
                'thousands_separator' => 'required|string|max:1',
            ]);

            DB::beginTransaction();

            try {
                $currency = Currency::create([
                    'name' => $validatedData['name'],
                    'code' => strtoupper($validatedData['code']),
                    'country_id' => $validatedData['country_id'] ?? null,
                    'symbol' => $validatedData['symbol'],
                    'symbol_native' => $validatedData['symbol_native'] ?? $validatedData['symbol'],
                    'symbol_first' => $validatedData['symbol_first'] ?? true,
                    'precision' => $validatedData['precision'],
                    'decimal_mark' => $validatedData['decimal_mark'],
                    'thousands_separator' => $validatedData['thousands_separator'],
                ]);

                // Si c'est la première devise, définir comme devise par défaut
                $countCurrencies = Currency::count();
                if ($countCurrencies === 1) {
                    Setting::set('default_currency', $currency->id, 'payments');
                    $currency->is_default = true;
                    $currency->save();
                }

                DB::commit();

                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('success', __('Devise créée avec succès!'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur transaction: ' . $e->getMessage());
                return back()->with('error', __('Erreur lors de la création: ') . $e->getMessage())->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Erreur de validation: ' . $e->getMessage());
            return back()->with('error', __('Erreur de validation: ') . $e->getMessage())->withInput();
        }
    }

    /**
     * Affiche les détails d'une devise.
     *
     * @param string $locale
     * @param Currency $currency
     * @return \Illuminate\View\View
     */
    public function show(string $locale, Currency $currency)
    {
        try {
            $currency->load('country');
            return view('admin.payments.currencies.show', compact('currency'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des détails: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger les détails. ') . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire d'édition d'une devise.
     *
     * @param string $locale
     * @param Currency $currency
     * @return \Illuminate\View\View
     */
    public function edit(string $locale, Currency $currency)
    {
        try {
            $countries = Country::orderBy('name')->get();
            return view('admin.payments.currencies.edit', compact('currency', 'countries'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire d\'édition: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le formulaire. ') . $e->getMessage());
        }
    }

    /**
     * Met à jour la devise.
     *
     * @param Request $request
     * @param string $locale
     * @param Currency $currency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $locale, Currency $currency)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'code' => ['required', 'string', 'size:3', Rule::unique('currencies', 'code')->ignore($currency->id)],
                'country_id' => 'nullable|exists:countries,id',
                'symbol' => 'required|string|max:10',
                'symbol_native' => 'nullable|string|max:10',
                'symbol_first' => 'sometimes|boolean',
                'precision' => 'required|integer|min:0|max:4',
                'decimal_mark' => 'required|string|max:1',
                'thousands_separator' => 'required|string|max:1',
            ]);

            DB::beginTransaction();

            try {
                $currency->update([
                    'name' => $validatedData['name'],
                    'code' => strtoupper($validatedData['code']),
                    'country_id' => $validatedData['country_id'] ?? null,
                    'symbol' => $validatedData['symbol'],
                    'symbol_native' => $validatedData['symbol_native'] ?? $validatedData['symbol'],
                    'symbol_first' => $validatedData['symbol_first'] ?? true,
                    'precision' => $validatedData['precision'],
                    'decimal_mark' => $validatedData['decimal_mark'],
                    'thousands_separator' => $validatedData['thousands_separator'],
                ]);

                DB::commit();

                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('success', __('Devise mise à jour avec succès!'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur transaction: ' . $e->getMessage());
                return back()->with('error', __('Erreur lors de la mise à jour: ') . $e->getMessage())->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Erreur de validation: ' . $e->getMessage());
            return back()->with('error', __('Erreur de validation: ') . $e->getMessage())->withInput();
        }
    }

    /**
     * Supprime la devise.
     *
     * @param string $locale
     * @param Currency $currency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $locale, Currency $currency)
    {
        try {
            // Vérifier si c'est la devise par défaut
            if ($currency->is_default) {
                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('error', __('La devise par défaut ne peut pas être supprimée.'));
            }
            
            // Vérifier si la devise est utilisée dans des prix ou transactions
            $isUsedInPrices = DB::table('package_prices')->where('currency_id', $currency->id)->exists();
            $isUsedInTransactions = DB::table('transactions')->where('currency_id', $currency->id)->exists();
            
            if ($isUsedInPrices || $isUsedInTransactions) {
                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('error', __('Cette devise est utilisée dans des prix ou des transactions et ne peut pas être supprimée.'));
            }
            
            DB::beginTransaction();
            
            try {
                // Détacher les relations avec les méthodes de paiement
                $currency->paymentMethods()->detach();
                
                // Supprimer la devise
                $currency->delete();
                
                DB::commit();
                
                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('success', __('Devise supprimée avec succès!'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur transaction: ' . $e->getMessage());
                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('error', __('Erreur lors de la suppression: ') . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression: ' . $e->getMessage());
            return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                ->with('error', __('Erreur lors de la suppression: ') . $e->getMessage());
        }
    }

    /**
     * Définit une devise comme étant la devise par défaut.
     *
     * @param string $locale
     * @param Currency $currency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDefault(string $locale, Currency $currency)
    {
        try {
            DB::beginTransaction();
            
            try {
                // Réinitialiser toutes les devises
                Currency::where('is_default', true)->update(['is_default' => false]);
                
                // Définir la nouvelle devise par défaut
                $currency->is_default = true;
                $currency->save();
                
                // Mettre à jour le paramètre global
                Setting::set('default_currency', $currency->id, 'payments');
                
                DB::commit();
                
                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('success', __('Devise définie par défaut avec succès!'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur transaction: ' . $e->getMessage());
                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('error', __('Erreur lors de la définition de la devise par défaut: ') . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la définition de la devise par défaut: ' . $e->getMessage());
            return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                ->with('error', __('Erreur lors de la définition de la devise par défaut: ') . $e->getMessage());
        }
    }

    /**
     * Active ou désactive une devise.
     *
     * @param string $locale
     * @param Currency $currency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(string $locale, Currency $currency)
    {
        try {
            // Si c'est la devise par défaut, empêcher la désactivation
            if ($currency->is_default && $currency->is_active) {
                return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                    ->with('error', __('La devise par défaut ne peut pas être désactivée.'));
            }
            
            $currency->is_active = !$currency->is_active;
            $currency->save();
            
            $message = $currency->is_active 
                ? __('Devise activée avec succès!') 
                : __('Devise désactivée avec succès!');
            
            return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.currencies.index', ['locale' => $locale])
                ->with('error', __('Erreur lors du changement de statut: ') . $e->getMessage());
        }
    }
} 