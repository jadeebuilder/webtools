<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Afficher la page des paramètres généraux
     */
    public function general()
    {
        $settings = Setting::getByGroup('general');
        $timezones = \DateTimeZone::listIdentifiers();
        $available_locales = config('app.available_locales', []);
        $currencies = Currency::orderBy('name')->get();
        
        return view('admin.settings.general', [
            'settings' => $settings,
            'timezones' => $timezones,
            'available_locales' => $available_locales,
            'currencies' => $currencies,
            'title' => __('Paramètres généraux'),
            'pageTitle' => __('Paramètres généraux') . ' - ' . config('app.name'),
            'metaDescription' => __('Gérer les paramètres généraux du site')
        ]);
    }
    
    /**
     * Mettre à jour les paramètres généraux
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:1000',
            'site_logo_light' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_logo_email' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'site_opengraph_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'site_home_cover' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_author' => 'nullable|string|max:255',
            'contact_email' => 'required|email',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            'enable_cookie_banner' => 'nullable|boolean',
            'enable_dark_mode' => 'nullable|boolean',
            'default_timezone' => 'required|string|in:' . implode(',', \DateTimeZone::listIdentifiers()),
            'default_locale' => 'required|string|in:' . implode(',', array_keys(config('app.available_locales', ['fr' => 'Français']))),
            'default_currency' => 'required|exists:currencies,id',
            'tools_per_page' => 'required|integer|min:6|max:100',
            'tools_order' => 'required|string|in:asc,desc',
        ]);
        
        try {
            // Démarrer une transaction pour garantir que toutes les modifications sont appliquées ou aucune
            DB::beginTransaction();
            
            // Gestion des images
            $imageFields = [
                'site_logo_light' => 'public/images/logo-light',
                'site_logo_dark' => 'public/images/logo-dark',
                'site_logo_email' => 'public/images/logo-email',
                'site_favicon' => 'public/images',
                'site_opengraph_image' => 'public/images/opengraph',
                'site_home_cover' => 'public/images/covers',
            ];
            
            foreach ($imageFields as $field => $path) {
                if ($request->hasFile($field)) {
                    $filePath = $request->file($field)->store($path);
                    Setting::set($field, Storage::url($filePath), 'general', true, false);
                }
            }
            
            // Mise à jour des autres paramètres
            $settings = [
                'site_name' => $request->site_name,
                'site_description' => $request->site_description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'meta_author' => $request->meta_author,
                'contact_email' => $request->contact_email,
                'google_analytics_id' => $request->google_analytics_id,
                'facebook_pixel_id' => $request->facebook_pixel_id,
                'enable_cookie_banner' => $request->has('enable_cookie_banner') ? 1 : 0,
                'enable_dark_mode' => $request->has('enable_dark_mode') ? 1 : 0,
                'default_timezone' => $request->default_timezone,
                'default_locale' => $request->default_locale,
                'default_currency' => $request->default_currency,
                'tools_per_page' => $request->tools_per_page,
                'tools_order' => $request->tools_order,
            ];
            
            foreach ($settings as $key => $value) {
                Setting::set($key, $value, 'general', true, false);
            }
            
            // Confirmer la transaction
            DB::commit();
            
            return redirect()->route('admin.settings.general', ['locale' => app()->getLocale()])
                ->with('success', __('Les paramètres généraux ont été mis à jour avec succès.'));
                
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollback();
            
            // Journaliser l'erreur
            \Log::error('Erreur lors de la mise à jour des paramètres généraux', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.settings.general', ['locale' => app()->getLocale()])
                ->with('error', __('Une erreur est survenue lors de la mise à jour des paramètres généraux. Veuillez réessayer.'));
        }
    }
    
    /**
     * Afficher la page de configuration du mode maintenance
     */
    public function maintenance()
    {
        $settings = Setting::getByGroup('maintenance');
        
        return view('admin.settings.maintenance', [
            'settings' => $settings,
            'title' => __('Mode maintenance'),
            'pageTitle' => __('Mode maintenance') . ' - ' . config('app.name'),
            'metaDescription' => __('Gérer le mode maintenance du site')
        ]);
    }
    
    /**
     * Mettre à jour les paramètres du mode maintenance
     */
    public function updateMaintenance(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'required|in:0,1',
            'maintenance_message' => 'nullable|string|max:1000',
            'maintenance_end_date' => 'nullable|date',
            'maintenance_allow_ips' => 'nullable|string',
        ]);
        
        // Enregistrer l'état actuel du mode maintenance pour le journal
        $currentMode = Setting::get('maintenance_mode', 0);
        $newMode = (int) $request->maintenance_mode;
        
        // Mise à jour des paramètres
        $settings = [
            'maintenance_mode' => $newMode,
            'maintenance_message' => $request->maintenance_message,
            'maintenance_end_date' => $request->maintenance_end_date,
            'maintenance_allow_ips' => $request->maintenance_allow_ips,
        ];
        
        foreach ($settings as $key => $value) {
            Setting::set($key, $value, 'maintenance', true, false);
        }
        
        // Vider le cache des paramètres pour s'assurer que les changements sont appliqués immédiatement
        Setting::clearCache();
        \Cache::forget('maintenance_settings');
        
        $successMessage = $newMode == 1 
            ? __('Le mode maintenance a été activé avec succès.') 
            : __('Le mode maintenance a été désactivé avec succès.');
        
        return redirect()->route('admin.settings.maintenance', ['locale' => app()->getLocale()])
            ->with('success', $successMessage);
    }
    
    /**
     * Afficher la page de configuration du sitemap
     */
    public function sitemap()
    {
        $settings = Setting::getByGroup('sitemap');
        
        return view('admin.settings.sitemap', [
            'settings' => $settings,
            'title' => __('Sitemap'),
            'pageTitle' => __('Sitemap') . ' - ' . config('app.name'),
            'metaDescription' => __('Gérer le sitemap du site')
        ]);
    }
    
    /**
     * Mettre à jour les paramètres du sitemap et générer le sitemap
     */
    public function updateSitemap(Request $request)
    {
        $request->validate([
            'sitemap_auto_generation' => 'nullable|boolean',
            'sitemap_include_tools' => 'nullable|boolean',
            'sitemap_include_blog' => 'nullable|boolean',
            'sitemap_frequency' => 'required|string|in:always,hourly,daily,weekly,monthly,yearly,never',
            'sitemap_priority' => 'required|numeric|between:0,1',
        ]);
        
        $settings = [
            'sitemap_auto_generation' => $request->has('sitemap_auto_generation') ? 1 : 0,
            'sitemap_include_tools' => $request->has('sitemap_include_tools') ? 1 : 0,
            'sitemap_include_blog' => $request->has('sitemap_include_blog') ? 1 : 0,
            'sitemap_frequency' => $request->sitemap_frequency,
            'sitemap_priority' => $request->sitemap_priority,
            'sitemap_last_generated' => now()->toDateTimeString(),
        ];
        
        foreach ($settings as $key => $value) {
            Setting::set($key, $value, 'sitemap', true, false);
        }
        
        // Générer le sitemap
        \Artisan::call('sitemap:generate');
        
        return redirect()->route('admin.settings.sitemap', ['locale' => app()->getLocale()])
            ->with('success', __('Les paramètres du sitemap ont été mis à jour et le sitemap a été généré avec succès.'));
    }
    
    /**
     * Afficher la page des détails de l'entreprise
     */
    public function company()
    {
        $settings = Setting::getByGroup('company');
        
        return view('admin.settings.company', [
            'settings' => $settings,
            'title' => __('Détails entreprise'),
            'pageTitle' => __('Détails entreprise') . ' - ' . config('app.name'),
            'metaDescription' => __('Gérer les informations de l\'entreprise')
        ]);
    }
    
    /**
     * Mettre à jour les détails de l'entreprise
     */
    public function updateCompany(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:1000',
            'company_phone' => 'required|string|max:50',
            'company_email' => 'required|email',
            'company_vat' => 'nullable|string|max:50',
            'company_registration' => 'nullable|string|max:50',
            'company_opening_hours' => 'nullable|string|max:500',
            'company_social_facebook' => 'nullable|url',
            'company_social_twitter' => 'nullable|url',
            'company_social_instagram' => 'nullable|url',
            'company_social_linkedin' => 'nullable|url',
            'company_social_youtube' => 'nullable|url',
        ]);
        
        $settings = $request->except(['_token', '_method']);
        
        foreach ($settings as $key => $value) {
            Setting::set($key, $value, 'company', true, true);
        }
        
        return redirect()->route('admin.settings.company', ['locale' => app()->getLocale()])
            ->with('success', __('Les détails de l\'entreprise ont été mis à jour avec succès.'));
    }
    
    /**
     * Réinitialiser les paramètres à leurs valeurs par défaut
     */
    public function resetToDefaults()
    {
        // Valeurs par défaut pour chaque groupe de paramètres
        $defaultSettings = [
            'general' => [
                'site_name' => 'WebTools',
                'site_description' => 'Une plateforme d\'outils web gratuits',
                'contact_email' => 'contact@example.com',
                'default_timezone' => 'Europe/Paris',
                'default_locale' => 'fr',
                'default_currency' => Currency::where('code', 'EUR')->first()->id ?? 1,
                'tools_per_page' => 12,
                'tools_order' => 'DESC',
                'meta_title' => 'WebTools - Outils web gratuits',
                'meta_description' => 'Une collection d\'outils web gratuits pour vous aider dans vos tâches quotidiennes',
                'meta_keywords' => 'outils web, conversion, générateur, gratuit',
                'meta_author' => 'WebTools',
                'google_analytics_id' => null,
                'facebook_pixel_id' => null,
                'enable_cookie_banner' => 1,
                'enable_dark_mode' => 1,
            ],
            'maintenance' => [
                'maintenance_mode' => 0,
                'maintenance_message' => 'Notre site est actuellement en maintenance. Nous serons bientôt de retour!',
                'maintenance_end_date' => null,
                'maintenance_allow_ips' => '127.0.0.1',
            ],
            'sitemap' => [
                'sitemap_auto_generation' => 1,
                'sitemap_include_tools' => 1,
                'sitemap_include_blog' => 1,
                'sitemap_frequency' => 'weekly',
                'sitemap_priority' => 0.8,
                'sitemap_last_generated' => null,
            ],
            'company' => [
                'company_name' => 'WebTools',
                'company_address' => '',
                'company_phone' => '',
                'company_email' => 'contact@example.com',
                'company_vat' => '',
                'company_registration' => '',
                'company_opening_hours' => '',
                'company_social_facebook' => '',
                'company_social_twitter' => '',
                'company_social_instagram' => '',
                'company_social_linkedin' => '',
                'company_social_youtube' => '',
            ]
        ];
        
        // Réinitialiser chaque groupe de paramètres
        foreach ($defaultSettings as $group => $settings) {
            foreach ($settings as $key => $value) {
                Setting::set($key, $value, $group, true, in_array($key, ['site_name', 'site_description']));
            }
        }
        
        // Vider le cache pour appliquer les changements immédiatement
        Setting::clearCache();
        Artisan::call('cache:clear');
        
        return redirect()->route('admin.settings.general', ['locale' => app()->getLocale()])
            ->with('success', __('Tous les paramètres ont été réinitialisés à leurs valeurs par défaut.'));
    }
    
    /**
     * Vider le cache de l'application
     */
    public function clearCache()
    {
        // Exécuter les commandes de nettoyage de cache
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        return redirect()->back()->with('success', __('Le cache a été vidé avec succès.'));
    }
} 