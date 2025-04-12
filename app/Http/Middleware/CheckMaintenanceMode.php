<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupérer les paramètres de maintenance depuis la base de données
        $settings = Setting::getByGroup('maintenance');
        
        // Si le mode maintenance n'est pas activé, on continue normalement
        if (empty($settings['maintenance_mode']) || $settings['maintenance_mode'] != 1) {
            return $next($request);
        }
        
        // Les administrateurs connectés peuvent toujours accéder au site
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }
        
        // Vérifier si l'IP du visiteur est dans la liste des IPs autorisées
        $allowedIps = !empty($settings['maintenance_allow_ips']) 
            ? array_map('trim', explode(',', $settings['maintenance_allow_ips'])) 
            : [];
            
        if (!empty($allowedIps) && in_array($request->ip(), $allowedIps)) {
            return $next($request);
        }
        
        // Autoriser l'accès à la page de login pour permettre aux admins de se connecter
        if ($request->is('login') || $request->is('*/login') || $request->is('admin/login') || $request->is('*/admin/login')) {
            return $next($request);
        }
        
        // Pour les requêtes AJAX, renvoyer un code 503
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => $settings['maintenance_message'] ?? 'Site en maintenance'], 503);
        }
        
        // Sinon afficher la page de maintenance
        $message = $settings['maintenance_message'] ?? null;
        $end_date = $settings['maintenance_end_date'] ?? null;
        
        return response()->view('errors.maintenance', [
            'message' => $message,
            'end_date' => $end_date
        ], 503);
    }
} 