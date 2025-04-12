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
        
        $ads = AdSetting::where('active', true)
            ->whereJsonContains('display_on', $pageType)
            ->orderBy('priority', 'desc')
            ->get();
        
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