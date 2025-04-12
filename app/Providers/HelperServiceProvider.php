<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\URL;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('url.helper', function () {
            return new URL();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enregistrer les alias pour utiliser la classe URL dans les vues
        Blade::directive('localizedRoute', function ($expression) {
            return "<?php echo \App\Helpers\URL::localizedRoute($expression); ?>";
        });
        
        // Enregistrer URL comme une fonction globale dans les vues
        Blade::directive('localizedUrl', function ($expression) {
            return "<?php echo \App\Helpers\URL::getLocalizedUrl($expression); ?>";
        });
    }
}
