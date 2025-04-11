<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LocalizeRoutes
{
    /**
     * Gérer la localisation des routes avec préfixe de langue.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Récupère la locale depuis l'URL (paramètre de route)
        $locale = $request->route('locale');

        // Vérifie si la locale est valide
        $availableLocales = array_keys(config('app.available_locales', []));

        Log::debug('LocalizeRoutes middleware running', [
            'requested_locale' => $locale,
            'available_locales' => $availableLocales,
            'current_app_locale' => App::getLocale(),
        ]);

        if (in_array($locale, $availableLocales)) {
            // Définit la locale de l'application
            App::setLocale($locale);
            // Enregistre la locale dans la session
            Session::put('locale', $locale);

            Log::debug('Locale set from URL', [
                'locale' => $locale,
                'app_locale_after_set' => App::getLocale()
            ]);
        } else {
            // Si la locale n'est pas valide, redirige vers la locale par défaut
            Log::warning('Invalid locale in URL', [
                'requested_locale' => $locale,
                'redirecting_to' => config('app.fallback_locale')
            ]);

            return redirect('/' . config('app.fallback_locale') . '/' . substr($request->path(), strlen($locale) + 1));
        }

        return $next($request);
    }
}
