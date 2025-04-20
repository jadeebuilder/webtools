<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrialController extends Controller
{
    /**
     * Affiche la page d'information sur l'essai du package
     */
    public function start(string $locale, string $slug, string $cycle = 'monthly')
    {
        try {
            // Récupérer le package
            $package = Package::where('slug', $slug)
                ->where('is_active', true)
                ->where('has_trial', true)
                ->with('prices')
                ->firstOrFail();
            
            Log::info('Package trouvé pour essai: ' . $package->id . ' - ' . $package->name);
            
            // Vérifier que le cycle est valide
            if (!in_array($cycle, ['monthly', 'annual'])) {
                $cycle = 'monthly';
            }
            
            // Les packages à vie ne peuvent pas avoir de période d'essai
            if ($cycle == 'lifetime') {
                return redirect()->route('checkout', [
                    'locale' => $locale, 
                    'slug' => $slug, 
                    'cycle' => $cycle
                ]);
            }
            
            // Vérifier si l'utilisateur est connecté
            if (!Auth::check()) {
                // Rediriger vers l'inscription avec les paramètres du package
                session(['trial_redirect' => [
                    'slug' => $slug,
                    'cycle' => $cycle
                ]]);
                
                return redirect()->route('register', [
                    'locale' => $locale,
                    'package' => $slug
                ]);
            }
            
            // Vérifier si l'utilisateur a déjà un abonnement actif pour ce package
            $existingSubscription = Subscription::where('user_id', Auth::id())
                ->where('package_id', $package->id)
                ->where('status', 'active')
                ->first();
                
            if ($existingSubscription) {
                return redirect()->route('dashboard', ['locale' => $locale])
                    ->with('info', __('Vous avez déjà un abonnement actif pour ce package.'));
            }
            
            // Vérifier si l'utilisateur a déjà utilisé une période d'essai pour ce package
            $usedTrial = Transaction::where('user_id', Auth::id())
                ->where('package_id', $package->id)
                ->where('type', Transaction::TYPE_TRIAL)
                ->exists();
                
            if ($usedTrial) {
                // Rediriger vers la page de paiement normale
                return redirect()->route('checkout', [
                    'locale' => $locale, 
                    'slug' => $slug, 
                    'cycle' => $cycle
                ])->with('info', __('Vous avez déjà utilisé la période d\'essai pour ce package.'));
            }
            
            return view('trial.start', [
                'package' => $package,
                'cycle' => $cycle,
                'trial_days' => $package->trial_days
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du démarrage de l\'essai: ' . $e->getMessage());
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Une erreur est survenue lors de la préparation de votre période d\'essai.'));
        }
    }
    
    /**
     * Active la période d'essai pour l'utilisateur
     */
    public function activate(Request $request, string $locale, string $slug)
    {
        try {
            // Récupérer le package
            $package = Package::where('slug', $slug)
                ->where('is_active', true)
                ->where('has_trial', true)
                ->with('prices')
                ->firstOrFail();
            
            Log::info('Package trouvé pour essai: ' . $package->id . ' - ' . $package->name);
            
            $cycle = $request->input('cycle', 'monthly');
            
            // Vérifier que le cycle est valide
            if (!in_array($cycle, ['monthly', 'annual'])) {
                $cycle = 'monthly';
            }
            
            // Vérifier si l'utilisateur est connecté
            if (!Auth::check()) {
                return redirect()->route('login', ['locale' => $locale])
                    ->with('error', __('Vous devez être connecté pour activer une période d\'essai.'));
            }
            
            // Vérifier si l'utilisateur a déjà un abonnement actif pour ce package
            $existingSubscription = Subscription::where('user_id', Auth::id())
                ->where('package_id', $package->id)
                ->where('status', 'active')
                ->first();
                
            if ($existingSubscription) {
                return redirect()->route('dashboard', ['locale' => $locale])
                    ->with('info', __('Vous avez déjà un abonnement actif pour ce package.'));
            }
            
            // Vérifier si l'utilisateur a déjà utilisé une période d'essai pour ce package
            $usedTrial = Transaction::where('user_id', Auth::id())
                ->where('package_id', $package->id)
                ->where('type', Transaction::TYPE_TRIAL)
                ->exists();
                
            if ($usedTrial) {
                return redirect()->route('checkout', [
                    'locale' => $locale, 
                    'slug' => $slug, 
                    'cycle' => $cycle
                ])->with('info', __('Vous avez déjà utilisé la période d\'essai pour ce package.'));
            }
            
            // Créer une transaction pour l'essai
            DB::beginTransaction();
            
            // Paramètres prix
            $price = 0; // Les essais sont gratuits
            $defaultCurrency = config('app.default_currency', 'USD');
            
            // Récupérer l'ID de la devise par défaut
            $currencyId = Currency::where('code', $defaultCurrency)->first()?->id;
            
            if (!$currencyId) {
                // Utiliser la première devise disponible si la devise par défaut n'existe pas
                $currencyId = Currency::first()?->id;
                
                if (!$currencyId) {
                    throw new \Exception('Aucune devise n\'est configurée dans le système.');
                }
            }
            
            // Créer l'abonnement en essai
            $subscription = new Subscription();
            $subscription->user_id = Auth::id();
            $subscription->package_id = $package->id;
            $subscription->status = 'active';
            $subscription->cycle = $cycle;
            $subscription->payment_provider = 'system';
            $subscription->payment_method = 'trial';
            $subscription->subscription_id = 'trial_' . Str::uuid();
            $subscription->amount = $price;
            $subscription->currency_id = $currencyId;
            $subscription->trial_ends_at = now()->addDays($package->trial_days);
            $subscription->ends_at = now()->addDays($package->trial_days);
            $subscription->meta = [
                'trial' => true,
                'cycle_after_trial' => $cycle
            ];
            $subscription->save();
            
            // Créer la transaction pour l'essai
            $transaction = new Transaction();
            $transaction->uuid = Str::uuid();
            $transaction->user_id = Auth::id();
            $transaction->package_id = $package->id;
            
            // Générer une référence unique pour la transaction
            $transaction->reference_id = Transaction::generateReferenceId();
            
            // Récupérer le prix du package si disponible
            $packagePrice = $package->getPrice($cycle, $currencyId);
            $transaction->package_price_id = $packagePrice ? $packagePrice->id : null;
            
            $transaction->subscription_id = $subscription->id;
            $transaction->payment_provider = 'system';
            $transaction->payment_method = 'trial';
            $transaction->type = Transaction::TYPE_TRIAL;
            $transaction->cycle = $cycle;
            $transaction->amount = $price;
            $transaction->currency_id = $subscription->currency_id;
            $transaction->status = Transaction::STATUS_COMPLETED;
            $transaction->meta = [
                'trial_days' => $package->trial_days,
                'cycle_after_trial' => $cycle
            ];
            $transaction->paid_at = now();
            $transaction->save();
            
            DB::commit();
            
            // Rediriger vers le tableau de bord avec un message de succès
            return redirect()->route('dashboard', ['locale' => $locale])
                ->with('success', __('Votre période d\'essai a été activée avec succès! Profitez-en pendant :days jours.', ['days' => $package->trial_days]));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'activation de l\'essai: ' . $e->getMessage());
            return redirect()->route('packages', ['locale' => $locale])
                ->with('error', __('Une erreur est survenue lors de l\'activation de votre période d\'essai.'));
        }
    }
    
    /**
     * Gestionnaire de la fin de période d'essai
     * Cette méthode sera appelée par le job de vérification des essais expirés
     */
    public function handleExpiredTrial(Subscription $subscription)
    {
        try {
            // Vérifier si l'abonnement est toujours en période d'essai
            if (!$subscription->onTrial() || $subscription->status !== 'active') {
                return;
            }
            
            // Vérifier si l'essai est expiré
            if ($subscription->trial_ends_at->isFuture()) {
                return;
            }
            
            // Options:
            // 1. Convertir automatiquement en abonnement payant
            // 2. Expirer l'abonnement (approche par défaut)
            
            // Pour éviter de facturer automatiquement, on marque simplement l'abonnement comme expiré
            $subscription->status = 'expired';
            $subscription->save();
            
            // Notifier l'utilisateur que son essai a expiré
            // À implémenter: envoi d'e-mail, notification, etc.
            
            return $subscription;
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement de fin d\'essai: ' . $e->getMessage());
        }
    }
} 