<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;

class URL
{
    /**
     * Génère une URL localisée pour une route nommée.
     *
     * @param string $name Nom de la route
     * @param array $parameters Paramètres de la route
     * @param string|null $locale Code de langue (par défaut: locale actuelle)
     * @param bool $absolute URL absolue ou relative
     * @return string
     */
    public static function localizedRoute(string $name, array $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        // Si aucune locale n'est spécifiée, utilisez la locale actuelle
        if (!$locale) {
            $locale = App::getLocale();
        }

        // Assurez-vous que 'locale' est dans les paramètres et a priorité sur toute valeur existante
        $parameters['locale'] = $locale;

        // Générer l'URL
        return route($name, $parameters, $absolute);
    }

    /**
     * Obtenir l'URL actuelle avec une locale différente.
     *
     * @param string $locale Nouvelle locale
     * @return string
     */
    public static function getLocalizedUrl(string $locale): string
    {
        // Obtenir le chemin actuel
        $currentPath = Request::path();
        $segments = explode('/', $currentPath);

        // Si nous avons au moins un segment, remplacer le premier (la langue) par la nouvelle langue
        if (count($segments) > 0) {
            $segments[0] = $locale;
        }

        // Reconstruire le chemin
        $newPath = implode('/', $segments);

        // Construire l'URL complète
        return url($newPath);
    }

    /**
     * Vérifie si une route existe.
     *
     * @param string $name Nom de la route
     * @return bool
     */
    public static function routeExists(string $name): bool
    {
        return Route::has($name);
    }
} 