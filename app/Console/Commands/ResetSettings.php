<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class ResetSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:reset {--force : Force reset without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Réinitialiser tous les paramètres du site à leurs valeurs par défaut';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres ? Cette action est irréversible.')) {
            $this->info('Opération annulée.');
            return 1;
        }

        // Valeurs par défaut pour chaque groupe de paramètres
        $defaultSettings = [
            'general' => [
                'site_name' => 'WebTools',
                'site_description' => 'Une plateforme d\'outils web gratuits',
                'contact_email' => 'contact@example.com',
                'default_timezone' => 'Europe/Paris',
                'default_locale' => 'fr',
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

        $this->withProgressBar(count($defaultSettings), function () use ($defaultSettings) {
            // Réinitialiser chaque groupe de paramètres
            foreach ($defaultSettings as $group => $settings) {
                foreach ($settings as $key => $value) {
                    Setting::set($key, $value, $group, true, in_array($key, ['site_name', 'site_description']));
                }
            }
        });

        // Vider le cache pour appliquer les changements immédiatement
        Setting::clearCache();
        $this->call('cache:clear');
        
        $this->newLine(2);
        $this->info('Tous les paramètres ont été réinitialisés à leurs valeurs par défaut.');
        
        return 0;
    }
} 