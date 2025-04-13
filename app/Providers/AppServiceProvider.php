<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Étendre la fonction URL::route pour inclure automatiquement la locale
        URL::macro('localizedRoute', function ($name, $parameters = [], $absolute = true, $locale = null) {
            // Utiliser la locale fournie ou celle de l'application
            $locale = $locale ?: App::getLocale();

            // S'assurer que les paramètres sont un tableau
            if (!is_array($parameters)) {
                $parameters = [$parameters];
            }

            // Ajouter la locale aux paramètres
            $parameters['locale'] = $locale;

            // Générer l'URL avec la locale
            return URL::route($name, $parameters, $absolute);
        });
    }
}

// Ajouter un helper global localizedRoute
if (!function_exists('localizedRoute')) {
    function localizedRoute($name, $parameters = [], $absolute = true, $locale = null)
    {
        return URL::localizedRoute($name, $parameters, $absolute, $locale);
    }
}
