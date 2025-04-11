<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Récupère la locale depuis l'URL (paramètre de route)
                $locale = $request->route('locale');
                
                // Si la locale n'est pas définie, utiliser la locale par défaut
                if (!$locale) {
                    $locale = App::getLocale();
                }
                
                // Construire l'URL complète avec le préfixe de langue
                return redirect('/' . $locale . RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
