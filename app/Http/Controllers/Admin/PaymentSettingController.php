<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PaymentSettingController extends Controller
{
    /**
     * Affiche la page des paramètres de paiement
     */
    public function index(string $locale)
    {
        try {
            $paymentMethods = PaymentMethod::orderBy('display_order')->get();
            $currencies = Currency::where('is_active', true)->orderBy('name')->get();
            
            $settings = Cache::remember('payment_settings', 3600, function () {
                return DB::table('settings')
                    ->where('group', 'payment')
                    ->pluck('value', 'key')
                    ->toArray();
            });
            
            return view('admin.payments.settings.index', [
                'paymentMethods' => $paymentMethods,
                'currencies' => $currencies,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des paramètres de paiement: ' . $e->getMessage());
            return redirect()->route('admin.dashboard', ['locale' => $locale])
                ->with('error', __('Impossible de charger les paramètres de paiement. ') . $e->getMessage());
        }
    }
    
    /**
     * Met à jour les paramètres de paiement généraux
     */
    public function updateGeneral(string $locale, Request $request)
    {
        try {
            $validated = $request->validate([
                'default_currency' => 'required|exists:currencies,code',
                'tax_rate' => 'nullable|numeric|min:0|max:100',
                'invoice_prefix' => 'nullable|string|max:10',
                'invoice_footer_text' => 'nullable|string|max:1000',
                'payment_terms' => 'nullable|string|max:2000',
            ]);
            
            DB::beginTransaction();
            
            foreach ($validated as $key => $value) {
                DB::table('settings')
                    ->updateOrInsert(
                        ['key' => $key, 'group' => 'payment'],
                        ['value' => $value]
                    );
            }
            
            // Mettre à jour les paramètres booléens qui peuvent ne pas être présents dans la requête
            $booleanSettings = [
                'enable_invoices' => $request->has('enable_invoices') ? 1 : 0,
                'enable_receipts' => $request->has('enable_receipts') ? 1 : 0,
                'allow_manual_payments' => $request->has('allow_manual_payments') ? 1 : 0,
                'require_billing_address' => $request->has('require_billing_address') ? 1 : 0,
            ];
            
            foreach ($booleanSettings as $key => $value) {
                DB::table('settings')
                    ->updateOrInsert(
                        ['key' => $key, 'group' => 'payment'],
                        ['value' => $value]
                    );
            }
            
            DB::commit();
            
            // Vider le cache des paramètres
            Cache::forget('payment_settings');
            
            return redirect()->route('admin.payments.settings.index', ['locale' => $locale])
                ->with('success', __('Paramètres de paiement mis à jour avec succès'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour des paramètres de paiement: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.settings.index', ['locale' => $locale])
                ->with('error', __('Erreur lors de la mise à jour des paramètres. ') . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Active ou désactive une méthode de paiement
     */
    public function togglePaymentMethod(string $locale, $id)
    {
        try {
            $method = PaymentMethod::findOrFail($id);
            $method->is_active = !$method->is_active;
            $method->save();
            
            return redirect()->route('admin.payments.settings.index', ['locale' => $locale])
                ->with('success', __('Méthode de paiement mise à jour avec succès'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la méthode de paiement: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.settings.index', ['locale' => $locale])
                ->with('error', __('Erreur lors de la mise à jour de la méthode de paiement. ') . $e->getMessage());
        }
    }
    
    /**
     * Met à jour les paramètres d'une méthode de paiement
     */
    public function updatePaymentMethod(string $locale, Request $request, $id)
    {
        try {
            $method = PaymentMethod::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'display_order' => 'required|integer|min:0',
                'description' => 'nullable|string|max:1000',
                'settings' => 'nullable|array',
            ]);
            
            // Fusionner les paramètres existants avec les nouveaux
            $settings = $method->settings ?? [];
            if ($request->has('settings')) {
                $settings = array_merge($settings, $request->settings);
            }
            
            $method->update([
                'name' => $validated['name'],
                'display_order' => $validated['display_order'],
                'description' => $validated['description'] ?? $method->description,
                'settings' => $settings,
            ]);
            
            return redirect()->route('admin.payments.settings.index', ['locale' => $locale])
                ->with('success', __('Méthode de paiement mise à jour avec succès'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la méthode de paiement: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.settings.index', ['locale' => $locale])
                ->with('error', __('Erreur lors de la mise à jour de la méthode de paiement. ') . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Gère les devises supportées par une méthode de paiement
     */
    public function updatePaymentMethodCurrencies(string $locale, Request $request, $id)
    {
        try {
            $method = PaymentMethod::findOrFail($id);
            
            $validated = $request->validate([
                'currencies' => 'required|array',
                'currencies.*' => 'exists:currencies,id',
            ]);
            
            // Synchroniser les devises
            $method->currencies()->sync($validated['currencies']);
            
            return redirect()->route('admin.payments.settings.index', ['locale' => $locale, '#payment-method-' . $method->id])
                ->with('success', __('Devises de la méthode de paiement mises à jour avec succès'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des devises: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.settings.index', ['locale' => $locale])
                ->with('error', __('Erreur lors de la mise à jour des devises. ') . $e->getMessage())
                ->withInput();
        }
    }
} 