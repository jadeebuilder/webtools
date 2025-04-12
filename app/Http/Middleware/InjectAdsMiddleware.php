<?php

namespace App\Http\Middleware;

use App\Models\AdSetting;
use Closure;
use Illuminate\Http\Request;

class InjectAdsMiddleware
{
    /**
     * Injecte les publicités dans la vue.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Déterminer le type de page (home, tool, category, admin)
        $pageType = $this->getPageType($request);
        
        // Récupérer les publicités actives pour ce type de page
        $ads = AdSetting::where('active', true)
            ->whereRaw('JSON_CONTAINS(display_on, ?)', [json_encode($pageType)])
            ->orderByDesc('priority')
            ->get()
            ->groupBy('position');
        
        // Partager la variable avec toutes les vues
        view()->share('adSettings', $ads);
        
        return $next($request);
    }
    
    /**
     * Détermine le type de page en fonction de la route actuelle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function getPageType(Request $request)
    {
        $routeName = $request->route()->getName();
        
        if (str_starts_with($routeName, 'admin.')) {
            return 'admin';
        } elseif (str_starts_with($routeName, 'tools.')) {
            return 'tool';
        } elseif (str_starts_with($routeName, 'categories.')) {
            return 'category';
        } else {
            return 'home';
        }
    }
}
