<?php

namespace App\Services;

use App\Models\AdSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AdService
{
    /**
     * Durée de mise en cache des publicités (en secondes)
     */
    protected const CACHE_TTL = 3600; // 1 heure
    
    /**
     * Récupérer toutes les publicités pour une page spécifique.
     *
     * @param string $pageType  Type de page (home, tool, category, admin)
     * @param bool $useCache   Utiliser le cache ou non
     * @return array
     */
    public function getAdsForPage(string $pageType, bool $useCache = true): array
    {
        $cacheKey = "ads_{$pageType}";
        
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        // Journalisation pour le débogage
        \Log::debug("Recherche de publicités pour le type de page: {$pageType}");
        
        // Méthode 1: Avec whereJsonContains (approche standard)
        $ads = AdSetting::where('active', true)
            ->whereJsonContains('display_on', $pageType)
            ->orderBy('priority', 'desc')
            ->get();
        
        \Log::debug("Publicités trouvées avec whereJsonContains: " . $ads->count());
        
        // Si aucune publicité trouvée, essayer une méthode alternative
        if ($ads->count() === 0) {
            \Log::debug("Tentative avec méthode alternative de recherche");
            
            // Récupérer toutes les publicités actives
            $allActiveAds = AdSetting::where('active', true)->get();
            
            // Filtrer manuellement
            $ads = $allActiveAds->filter(function ($ad) use ($pageType) {
                if (is_array($ad->display_on)) {
                    return in_array($pageType, $ad->display_on);
                } elseif (is_string($ad->display_on)) {
                    try {
                        $displayOn = json_decode($ad->display_on, true);
                        return is_array($displayOn) && in_array($pageType, $displayOn);
                    } catch (\Exception $e) {
                        \Log::warning("Impossible de décoder display_on pour l'annonce ID {$ad->id}: " . $e->getMessage());
                        return false;
                    }
                }
                return false;
            });
            
            \Log::debug("Publicités trouvées avec méthode alternative: " . $ads->count());
        }
        
        $adsArray = $this->formatAdsToArray($ads);
        
        if ($useCache) {
            Cache::put($cacheKey, $adsArray, self::CACHE_TTL);
        }
        
        return $adsArray;
    }
    
    /**
     * Vider le cache des publicités.
     */
    public function clearCache(): void
    {
        $pageTypes = ['home', 'tool', 'category', 'admin'];
        
        foreach ($pageTypes as $pageType) {
            Cache::forget("ads_{$pageType}");
        }
    }
    
    /**
     * Formater les publicités en tableau associatif par position.
     *
     * @param Collection $ads
     * @return array
     */
    protected function formatAdsToArray(Collection $ads): array
    {
        $result = [];
        
        foreach ($ads as $ad) {
            // Pour chaque position, prendre la publicité avec la priorité la plus élevée
            if (!isset($result[$ad->position]) || $ad->priority > $result[$ad->position]['priority']) {
                $result[$ad->position] = [
                    'active' => $ad->active,
                    'type' => $ad->type,
                    'image' => $ad->image,
                    'link' => $ad->link,
                    'alt' => $ad->alt,
                    'code' => $ad->code,
                    'priority' => $ad->priority
                ];
            }
        }
        
        return $result;
    }
} 