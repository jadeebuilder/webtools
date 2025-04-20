<?php

namespace App\Console;

use App\Models\Setting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Génération automatique du sitemap si l'option est activée
        $schedule->call(function () {
            if (Setting::get('sitemap_auto_generation', false)) {
                \Artisan::call('sitemap:generate');
            }
        })->daily();

        // Vérifier les essais expirés tous les jours à 1h du matin
        $schedule->call(function () {
            // Récupérer les abonnements en période d'essai expiré
            $expiredTrials = \App\Models\Subscription::where('status', 'active')
                ->whereNotNull('trial_ends_at')
                ->where('trial_ends_at', '<', now())
                ->get();
                
            $trialController = new \App\Http\Controllers\TrialController();
            
            foreach ($expiredTrials as $subscription) {
                try {
                    $trialController->handleExpiredTrial($subscription);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Erreur lors du traitement des essais expirés: ' . $e->getMessage());
                }
            }
        })->dailyAt('01:00')->name('check-expired-trials');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
