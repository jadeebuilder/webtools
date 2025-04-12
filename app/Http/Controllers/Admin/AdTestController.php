<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSetting;
use App\Services\AdService;
use Illuminate\Http\Request;

class AdTestController extends Controller
{
    /**
     * Affiche la page de test des publicités.
     *
     * @param AdService $adService Le service de gestion des publicités
     * @return \Illuminate\View\View
     */
    public function index(AdService $adService)
    {
        // Récupérer toutes les publicités
        $allAds = AdSetting::all();
        
        return view('admin.ads.test', compact('allAds', 'adService'));
    }

    /**
     * Répare les entrées de publicités avec des problèmes de formatage JSON.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function repairAds()
    {
        try {
            $count = 0;
            $allAds = AdSetting::all();
            
            foreach ($allAds as $ad) {
                $needsUpdate = false;
                $displayOn = $ad->display_on;
                
                // Vérifier si le champ est déjà un tableau correctement formaté
                if (is_array($displayOn)) {
                    // Déjà en format tableau, pas besoin de mise à jour
                    continue;
                }
                
                // Si c'est une chaîne, essayer de la décoder
                if (is_string($displayOn)) {
                    try {
                        $decoded = json_decode($displayOn, true);
                        
                        // Si le décodage a échoué ou n'est pas un tableau
                        if ($decoded === null || !is_array($decoded)) {
                            // Essayer de traiter comme une seule valeur
                            $displayOn = [$displayOn];
                            $needsUpdate = true;
                        } else {
                            // Vérifier si le format du tableau est correct
                            if (json_encode($decoded) !== $displayOn) {
                                $displayOn = $decoded;
                                $needsUpdate = true;
                            }
                        }
                    } catch (\Exception $e) {
                        // En cas d'erreur, traiter comme une seule valeur
                        $displayOn = [$displayOn];
                        $needsUpdate = true;
                    }
                }
                
                // Mettre à jour si nécessaire
                if ($needsUpdate) {
                    $ad->update([
                        'display_on' => json_encode($displayOn, JSON_UNESCAPED_UNICODE)
                    ]);
                    $count++;
                }
            }
            
            // Vider le cache des publicités
            app(AdService::class)->clearCache();
            
            return redirect()->route('admin.ads.test', ['locale' => app()->getLocale()])
                ->with('success', __(':count publicités ont été réparées.', ['count' => $count]));
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la réparation des publicités: " . $e->getMessage());
            return redirect()->route('admin.ads.test', ['locale' => app()->getLocale()])
                ->with('error', __('Une erreur est survenue: ') . $e->getMessage());
        }
    }
} 