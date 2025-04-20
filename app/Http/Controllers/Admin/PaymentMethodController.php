<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentMethodController extends Controller
{
    /**
     * Affiche la liste des méthodes de paiement.
     *
     * @param string $locale
     * @return \Illuminate\View\View
     */
    public function index(string $locale)
    {
        try {
            $paymentMethods = PaymentMethod::orderBy('display_order')->orderBy('name')->get();
            return view('admin.payments.methods.index', compact('paymentMethods'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des méthodes de paiement: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard', ['locale' => $locale])
                ->with('error', __('Impossible de charger les méthodes de paiement. ') . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de création d'une méthode de paiement.
     *
     * @param string $locale
     * @return \Illuminate\View\View
     */
    public function create(string $locale)
    {
        try {
            return view('admin.payments.methods.create');
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire de création: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le formulaire. ') . $e->getMessage());
        }
    }

    /**
     * Enregistre une nouvelle méthode de paiement.
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
                'code' => 'required|string|max:100|unique:payment_methods,code',
                'description' => 'nullable|string',
                'is_active' => 'sometimes|boolean',
                'processor_class' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:100',
                'display_order' => 'nullable|integer|min:0',
                'settings' => 'nullable|array',
            ]);

            // Convertir les paramètres du formulaire en structure de settings
            $settings = [];
            if (isset($validatedData['settings']) && is_array($validatedData['settings'])) {
                foreach ($validatedData['settings'] as $key => $value) {
                    if (!empty($key) && isset($value)) {
                        $settings[$key] = $value;
                    }
                }
            }

            DB::beginTransaction();

            try {
                // Créer la méthode de paiement
                $paymentMethod = PaymentMethod::create([
                    'name' => $validatedData['name'],
                    'code' => strtolower($validatedData['code']),
                    'description' => $validatedData['description'] ?? null,
                    'is_active' => $validatedData['is_active'] ?? false,
                    'processor_class' => $validatedData['processor_class'] ?? null,
                    'icon' => $validatedData['icon'] ?? 'fas fa-credit-card',
                    'display_order' => $validatedData['display_order'] ?? 0,
                    'settings' => $settings,
                ]);

                DB::commit();

                return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                    ->with('success', __('Méthode de paiement créée avec succès!'));
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
     * Affiche les détails d'une méthode de paiement.
     *
     * @param string $locale
     * @param PaymentMethod $method
     * @return \Illuminate\View\View
     */
    public function show(string $locale, PaymentMethod $method)
    {
        try {
            $currencies = $method->currencies;
            return view('admin.payments.methods.show', compact('method', 'currencies'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des détails: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger les détails. ') . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire d'édition d'une méthode de paiement.
     *
     * @param string $locale
     * @param PaymentMethod $method
     * @return \Illuminate\View\View
     */
    public function edit(string $locale, PaymentMethod $method)
    {
        try {
            return view('admin.payments.methods.edit', compact('method'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire d\'édition: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le formulaire. ') . $e->getMessage());
        }
    }

    /**
     * Met à jour la méthode de paiement.
     *
     * @param Request $request
     * @param string $locale
     * @param PaymentMethod $method
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $locale, PaymentMethod $method)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:100|unique:payment_methods,code,' . $method->id,
                'description' => 'nullable|string',
                'is_active' => 'sometimes|boolean',
                'processor_class' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:100',
                'display_order' => 'nullable|integer|min:0',
                'settings' => 'nullable|array',
            ]);

            // Convertir les paramètres du formulaire en structure de settings
            $settings = [];
            if (isset($validatedData['settings']) && is_array($validatedData['settings'])) {
                foreach ($validatedData['settings'] as $key => $value) {
                    if (!empty($key) && isset($value)) {
                        $settings[$key] = $value;
                    }
                }
            }

            DB::beginTransaction();

            try {
                // Mettre à jour la méthode de paiement
                $method->update([
                    'name' => $validatedData['name'],
                    'code' => strtolower($validatedData['code']),
                    'description' => $validatedData['description'] ?? null,
                    'is_active' => $validatedData['is_active'] ?? false,
                    'processor_class' => $validatedData['processor_class'] ?? null,
                    'icon' => $validatedData['icon'] ?? 'fas fa-credit-card',
                    'display_order' => $validatedData['display_order'] ?? 0,
                    'settings' => $settings,
                ]);

                DB::commit();

                return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                    ->with('success', __('Méthode de paiement mise à jour avec succès!'));
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
     * Supprime la méthode de paiement.
     *
     * @param string $locale
     * @param PaymentMethod $method
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $locale, PaymentMethod $method)
    {
        try {
            // Vérifier si la méthode est utilisée dans des transactions
            $isUsed = DB::table('transactions')->where('payment_method', $method->code)->exists();
            
            if ($isUsed) {
                return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                    ->with('error', __('Cette méthode de paiement est utilisée dans des transactions et ne peut pas être supprimée.'));
            }
            
            DB::beginTransaction();
            
            try {
                // Supprimer les relations avec les devises
                $method->currencies()->detach();
                
                // Supprimer la méthode
                $method->delete();
                
                DB::commit();
                
                return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                    ->with('success', __('Méthode de paiement supprimée avec succès!'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur transaction: ' . $e->getMessage());
                return back()->with('error', __('Erreur lors de la suppression: ') . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression: ' . $e->getMessage());
            return back()->with('error', __('Erreur lors de la suppression: ') . $e->getMessage());
        }
    }

    /**
     * Active ou désactive une méthode de paiement.
     *
     * @param string $locale
     * @param PaymentMethod $method
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(string $locale, PaymentMethod $method)
    {
        try {
            $method->update(['is_active' => !$method->is_active]);
            
            $message = $method->is_active 
                ? __('Méthode de paiement activée avec succès!') 
                : __('Méthode de paiement désactivée avec succès!');
            
            return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                ->with('error', __('Erreur lors du changement de statut: ') . $e->getMessage());
        }
    }

    /**
     * Affiche la page de gestion des devises pour une méthode de paiement.
     *
     * @param string $locale
     * @param PaymentMethod $method
     * @return \Illuminate\View\View
     */
    public function currencies(string $locale, PaymentMethod $method)
    {
        try {
            // Récupérer toutes les devises
            $allCurrencies = Currency::orderBy('is_default', 'desc')->orderBy('code')->get();
            
            // Récupérer les devises associées à cette méthode de paiement
            $methodCurrencies = $method->currencies()->get();
            
            // Créer un tableau pour le statut de chaque devise
            $currencyStatuses = [];
            foreach ($allCurrencies as $currency) {
                $methodCurrency = $methodCurrencies->where('id', $currency->id)->first();
                
                $currencyStatuses[$currency->id] = [
                    'currency' => $currency,
                    'is_active' => $methodCurrency ? $methodCurrency->pivot->is_active : false,
                    'display_order' => $methodCurrency ? $methodCurrency->pivot->display_order : 0,
                    'settings' => $methodCurrency ? $methodCurrency->pivot->settings : null,
                ];
            }
            
            return view('admin.payments.methods.currencies', [
                'method' => $method,
                'currencies' => $allCurrencies,
                'currencyStatuses' => $currencyStatuses,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des devises: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.methods.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger les devises. ') . $e->getMessage());
        }
    }

    /**
     * Met à jour les devises associées à une méthode de paiement.
     *
     * @param Request $request
     * @param string $locale
     * @param PaymentMethod $method
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCurrencies(Request $request, string $locale, PaymentMethod $method)
    {
        try {
            $validatedData = $request->validate([
                'currencies' => 'required|array',
                'currencies.*.is_active' => 'sometimes|boolean',
                'currencies.*.display_order' => 'nullable|integer|min:0',
                'currencies.*.settings' => 'nullable|array',
            ]);
            
            DB::beginTransaction();
            
            try {
                // Détacher toutes les devises existantes
                $method->currencies()->detach();
                
                // Attacher les devises avec leurs statuts
                foreach ($validatedData['currencies'] as $currencyId => $data) {
                    if (isset($data['is_active'])) {
                        $settings = isset($data['settings']) ? $data['settings'] : [];
                        
                        $method->currencies()->attach($currencyId, [
                            'is_active' => $data['is_active'] ?? false,
                            'display_order' => $data['display_order'] ?? 0,
                            'settings' => json_encode($settings),
                        ]);
                    }
                }
                
                DB::commit();
                
                return redirect()->route('admin.payments.methods.currencies', ['locale' => $locale, 'method' => $method->id])
                    ->with('success', __('Devises mises à jour avec succès!'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur transaction: ' . $e->getMessage());
                return back()->with('error', __('Erreur lors de la mise à jour des devises: ') . $e->getMessage())->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Erreur de validation: ' . $e->getMessage());
            return back()->with('error', __('Erreur de validation: ') . $e->getMessage())->withInput();
        }
    }
} 