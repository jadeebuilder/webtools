<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolAdSetting;
use App\Services\AdService;
use Illuminate\Http\Request;

class ToolAdSettingController extends Controller
{
    /**
     * Affiche la page de configuration des emplacements publicitaires pour un outil spécifique.
     * 
     * @param int $toolId
     * @return \Illuminate\View\View
     */
    public function edit($toolId)
    {
        $tool = Tool::with('adSettings')->findOrFail($toolId);
        
        // Récupérer toutes les positions publicitaires disponibles
        $positions = $this->getPositions();
        
        // Organiser les paramètres existants par position
        $toolAdSettings = $tool->adSettings->pluck('enabled', 'position')->toArray();
        
        return view('admin.tools.ads', compact('tool', 'positions', 'toolAdSettings'));
    }
    
    /**
     * Met à jour les configurations d'emplacements publicitaires pour un outil spécifique.
     * 
     * @param Request $request
     * @param int $toolId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $toolId)
    {
        $tool = Tool::findOrFail($toolId);
        
        // Récupérer toutes les positions disponibles
        $positions = array_keys($this->getPositions());
        
        // Supprimer d'abord toutes les configurations existantes pour cet outil
        ToolAdSetting::where('tool_id', $tool->id)->delete();
        
        // Créer de nouvelles configurations basées sur le formulaire
        foreach ($positions as $position) {
            ToolAdSetting::create([
                'tool_id' => $tool->id,
                'position' => $position,
                'enabled' => $request->has("positions.{$position}"),
            ]);
        }
        
        // Vider le cache des publicités
        app(AdService::class)->clearCache();
        
        return redirect()->route('admin.tools.edit', ['locale' => app()->getLocale(), 'tool' => $tool->id])
            ->with('success', __('Les paramètres publicitaires de l\'outil ont été mis à jour avec succès.'));
    }
    
    /**
     * Retourne la liste des positions de publicités disponibles.
     * 
     * @return array
     */
    private function getPositions()
    {
        return [
            'before_nav' => __('Avant la navigation'),
            'after_nav' => __('Après la navigation'),
            'before_tool_title' => __('Avant le titre de l\'outil'),
            'after_tool_description' => __('Après la description de l\'outil'),
            'before_tool' => __('Avant l\'outil'),
            'after_tool' => __('Après l\'outil'),
            'left_sidebar' => __('Barre latérale gauche'),
            'right_sidebar' => __('Barre latérale droite'),
            'bottom_tool' => __('En bas de l\'outil'),
            'before_footer' => __('Avant le pied de page'),
            'after_footer' => __('Après le pied de page'),
        ];
    }
} 