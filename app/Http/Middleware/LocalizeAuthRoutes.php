<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LocalizeAuthRoutes
{
    /**
     * Gérer la localisation des routes d'authentification avec préfixe de langue.
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
        
        Log::debug('LocalizeAuthRoutes middleware running', [
            'requested_locale' => $locale,
            'available_locales' => $availableLocales,
            'current_app_locale' => App::getLocale(),
            'path' => $request->path()
        ]);
        
        if ($locale && in_array($locale, $availableLocales)) {
            // Définit la locale de l'application
            App::setLocale($locale);
            // Enregistre la locale dans la session
            Session::put('locale', $locale);
            
            Log::debug('Auth route: Locale set from URL', [
                'locale' => $locale
            ]);
            
            return $next($request);
        }
        
        // Si la locale est déjà définie dans la session, on l'utilise
        if (Session::has('locale')) {
            $sessionLocale = Session::get('locale');
            
            // Si on est dans le cas où la route n'a pas de locale et qu'on a une session
            if (!$locale && in_array($sessionLocale, $availableLocales)) {
                App::setLocale($sessionLocale);
                
                Log::debug('Auth route: Using locale from session', [
                    'locale' => $sessionLocale
                ]);
                
                // Rediriger vers la route avec la locale de la session
                $path = $request->path();
                $url = "/{$sessionLocale}/{$path}";
                
                Log::debug('Auth route: Redirecting to localized URL', [
                    'from' => $request->fullUrl(),
                    'to' => $url
                ]);
                
                return redirect($url);
            }
        }
        
        // Si aucune locale n'est définie, utiliser la locale par défaut
        if (!$locale) {
            $defaultLocale = config('app.fallback_locale');
            
            Log::debug('Auth route: No locale detected, using default', [
                'default_locale' => $defaultLocale
            ]);
            
            // Rediriger vers la route avec la locale par défaut
            $path = $request->path();
            $url = "/{$defaultLocale}/{$path}";
            
            Log::debug('Auth route: Redirecting to default localized URL', [
                'from' => $request->fullUrl(),
                'to' => $url
            ]);
            
            return redirect($url);
        }
        
        return $next($request);
    }
}
