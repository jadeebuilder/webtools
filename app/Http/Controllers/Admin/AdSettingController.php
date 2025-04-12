<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdSettingController extends Controller
{
    /**
     * Affiche la liste des publicités.
     */
    public function index()
    {
        // Récupérer les publicités regroupées par position
        $adSettingsByPosition = AdSetting::all()->groupBy('position');
        
        return view('admin.ads.index', compact('adSettingsByPosition'));
    }

    /**
     * Affiche le formulaire de création de publicité.
     */
    public function create()
    {
        $positions = $this->getPositions();
        $displayOptions = $this->getDisplayOptions();
        
        return view('admin.ads.create', compact('positions', 'displayOptions'));
    }

    /**
     * Stocke une nouvelle publicité dans la base de données.
     */
    public function store(Request $request)
    {
        $validated = $this->validateAd($request);
        
        // Gérer les options d'affichage
        $validated['display_on'] = json_encode($request->input('display_on', []));
        
        // Définir le statut actif
        $validated['active'] = $request->has('active');
        
        AdSetting::create($validated);
        
        return redirect()->route('admin.ads.index')
            ->with('success', __('La publicité a été créée avec succès.'));
    }

    /**
     * Affiche une publicité spécifique.
     */
    public function show(AdSetting $ad)
    {
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Affiche le formulaire de modification de publicité.
     */
    public function edit(AdSetting $ad)
    {
        $positions = $this->getPositions();
        $displayOptions = $this->getDisplayOptions();
        
        return view('admin.ads.edit', compact('ad', 'positions', 'displayOptions'));
    }

    /**
     * Met à jour la publicité spécifiée dans la base de données.
     */
    public function update(Request $request, AdSetting $ad)
    {
        $validated = $this->validateAd($request, $ad->id);
        
        // Gérer les options d'affichage
        $validated['display_on'] = json_encode($request->input('display_on', []));
        
        // Définir le statut actif
        $validated['active'] = $request->has('active');
        
        $ad->update($validated);
        
        return redirect()->route('admin.ads.index')
            ->with('success', __('La publicité a été mise à jour avec succès.'));
    }

    /**
     * Active ou désactive une publicité.
     */
    public function toggle(AdSetting $ad)
    {
        $ad->update([
            'active' => !$ad->active
        ]);
        
        return redirect()->route('admin.ads.index')
            ->with('success', $ad->active 
                ? __('La publicité a été activée avec succès.') 
                : __('La publicité a été désactivée avec succès.'));
    }

    /**
     * Supprime la publicité spécifiée.
     */
    public function destroy(AdSetting $ad)
    {
        $ad->delete();
        
        return redirect()->route('admin.ads.index')
            ->with('success', __('La publicité a été supprimée avec succès.'));
    }
    
    /**
     * Valide les données de publicité.
     */
    private function validateAd(Request $request, $adId = null)
    {
        $rules = [
            'position' => ['required', Rule::in(array_keys($this->getPositions()))],
            'type' => ['required', Rule::in(['image', 'adsense'])],
            'priority' => ['required', 'integer', 'min:0'],
            'display_on' => ['required', 'array'],
            'display_on.*' => [Rule::in(array_keys($this->getDisplayOptions()))],
        ];
        
        // Règles spécifiques au type de publicité
        if ($request->input('type') === 'image') {
            $rules['image'] = ['required', 'string'];
            $rules['link'] = ['nullable', 'url'];
            $rules['alt'] = ['nullable', 'string'];
        } else {
            $rules['code'] = ['required', 'string'];
        }
        
        return $request->validate($rules);
    }
    
    /**
     * Retourne la liste des positions de publicités disponibles.
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
    
    /**
     * Retourne les options d'affichage disponibles.
     */
    private function getDisplayOptions()
    {
        return [
            'home' => __('Page d\'accueil'),
            'tool' => __('Pages d\'outils'),
            'category' => __('Pages de catégories'),
            'admin' => __('Administration'),
        ];
    }
}
