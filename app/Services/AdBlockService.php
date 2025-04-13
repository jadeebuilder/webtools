<?php

namespace App\Services;

use App\Models\AdBlockSetting;
use Illuminate\Support\Facades\Cache;

class AdBlockService
{
    /**
     * Durée de mise en cache des paramètres (en secondes)
     */
    protected const CACHE_TTL = 43200; // 12 heures
    
    /**
     * Récupère les paramètres de détection d'AdBlock
     *
     * @return array
     */
    public function getSettings(): array
    {
        $cacheKey = "adblock_settings";
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $settings = AdBlockSetting::getSettings();
        
        Cache::put($cacheKey, $settings, self::CACHE_TTL);
        
        return $settings;
    }
    
    /**
     * Met à jour les paramètres de détection d'AdBlock
     *
     * @param array $data
     * @return bool
     */
    public function updateSettings(array $data): bool
    {
        $settings = AdBlockSetting::first();
        
        if (!$settings) {
            $settings = new AdBlockSetting();
        }
        
        $success = $settings->fill($data)->save();
        
        if ($success) {
            $this->clearCache();
        }
        
        return $success;
    }
    
    /**
     * Vide le cache des paramètres
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget("adblock_settings");
    }
    
    /**
     * Crée un script pour injecter les paramètres de détection 
     * d'AdBlock dans une page
     * 
     * @return string
     */
    public function getScript(): string
    {
        $settings = $this->getSettings();
        
        if (!$settings['enabled']) {
            return '';
        }
        
        $jsonSettings = json_encode([
            'enabled' => $settings['enabled'],
            'block_content' => $settings['block_content'],
            'show_message' => $settings['show_message'],
            'message_title' => $settings['message_title'],
            'message_text' => $settings['message_text'],
            'message_button' => $settings['message_button'],
            'countdown' => $settings['countdown'],
        ]);
        
        return "<script>window.adBlockSettings = {$jsonSettings};</script>";
    }
} 