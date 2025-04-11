<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté et est un administrateur
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            // Récupérer la locale de la session
            $locale = session('locale', config('app.fallback_locale'));
            
            // Rediriger vers la page d'accueil avec un message d'erreur
            return redirect()->route('home', ['locale' => $locale])
                ->with('error', __('You do not have permission to access this page.'));
        }

        return $next($request);
    }
}
