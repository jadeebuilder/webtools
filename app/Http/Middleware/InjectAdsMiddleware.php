<?php

namespace App\Http\Middleware;

use App\Models\AdSetting;
use App\Models\Tool;
use App\Models\ToolAdSetting;
use App\Services\AdService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InjectAdsMiddleware
{
    /**
     * Le service de publicités.
     *
     * @var AdService
     */
    protected $adService;

    /**
     * Constructeur.
     *
     * @param AdService $adService
     */
    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }

    /**
     * Injecte les publicités dans la vue.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vider le cache des publicités pour le débogage
        $this->adService->clearCache();
        
        // Déterminer le type de page (home, tool, category, admin)
        $pageType = $this->getPageType($request);
        
        // Journaliser le type de page détecté
        Log::debug("Page type détecté: " . $pageType);
        
        // Vérifier s'il s'agit d'un outil spécifique
        $tool = null;
        $slug = null;
        
        if ($pageType === 'tool' && $request->route('slug')) {
            $slug = $request->route('slug');
            $tool = Tool::where('slug', $slug)->first();
            Log::debug("Outil détecté: " . ($tool ? $tool->name : 'Aucun'));
        }
        
        // Récupérer les publicités pour ce type de page
        $ads = $this->adService->getAdsForPage($pageType);
        
        // Journaliser le nombre de publicités récupérées
        Log::debug("Nombre de publicités trouvées pour {$pageType}: " . count($ads));
        Log::debug("Publicités trouvées: " . json_encode($ads));
        
        // Vérifier les publicités actives dans la base de données
        $allActiveAds = AdSetting::where('active', true)->get();
        Log::debug("Publicités actives dans la base de données: " . $allActiveAds->count());
        
        foreach ($allActiveAds as $ad) {
            $displayOn = json_decode($ad->display_on, true);
            Log::debug("Publicité ID {$ad->id}, position: {$ad->position}, display_on: " . json_encode($displayOn));
            
            // Vérifier si le type de page est bien inclus
            if (is_array($displayOn) && in_array($pageType, $displayOn)) {
                Log::debug("Cette publicité devrait s'afficher sur cette page");
            } else {
                Log::debug("Cette publicité ne devrait PAS s'afficher sur cette page");
            }
        }
        
        // Si c'est un outil spécifique, appliquer les configurations spécifiques
        if ($tool) {
            // Récupérer les configurations spécifiques pour cet outil
            $toolAdSettings = ToolAdSetting::where('tool_id', $tool->id)
                ->where('enabled', false)
                ->get();
            
            Log::debug("Configurations spécifiques trouvées pour cet outil: " . $toolAdSettings->count());
            
            // Désactiver les emplacements configurés comme désactivés pour cet outil
            foreach ($toolAdSettings as $setting) {
                if (isset($ads[$setting->position])) {
                    unset($ads[$setting->position]);
                    Log::debug("Publicité désactivée pour la position: " . $setting->position);
                }
            }
        }
        
        // Partager la variable avec toutes les vues
        view()->share('adSettings', $ads);
        view()->share('currentToolSlug', $slug);
        
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
        if (!$request->route()) {
            return 'home';
        }
        
        $routeName = $request->route()->getName();
        Log::debug("Route actuelle: " . $routeName);
        
        if (str_starts_with($routeName, 'admin.')) {
            return 'admin';
        } elseif (str_starts_with($routeName, 'tools.') || $routeName === 'tool.show') {
            return 'tool';
        } elseif (str_starts_with($routeName, 'categories.')) {
            return 'category';
        } else {
            return 'home';
        }
    }
}
