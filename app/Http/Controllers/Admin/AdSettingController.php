<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSetting;
use App\Services\AdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        $displayOn = $request->input('display_on', []);
        \Log::debug("Options d'affichage reçues (store): ", $displayOn);
        
        // S'assurer que le format JSON est correct pour whereJsonContains
        $validated['display_on'] = json_encode($displayOn, JSON_UNESCAPED_UNICODE);
        \Log::debug("JSON encodé (store): " . $validated['display_on']);
        
        // Définir le statut actif
        $validated['active'] = $request->has('active');
        
        // Traiter l'image selon sa source (upload, URL externe, chemin relatif)
        if ($request->input('type') === 'image') {
            $imageType = $request->input('image_type', 'path');
            
            if ($imageType === 'upload' && $request->hasFile('image_file')) {
                // Traitement de l'image téléversée
                $file = $request->file('image_file');
                $fileName = 'ad_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('images/ads', $fileName, 'public');
                $validated['image'] = 'storage/' . $filePath;
            } elseif ($imageType === 'external') {
                // Utilisation d'une URL externe
                $validated['image'] = $request->input('image_url');
            }
            // Pour le type 'path', l'image est déjà dans $validated['image']
        }
        
        AdSetting::create($validated);
        
        // Vider le cache des publicités
        app(AdService::class)->clearCache();
        
        return redirect()->route('admin.ads.index', ['locale' => app()->getLocale()])
            ->with('success', __('La publicité a été créée avec succès.'));
    }

    /**
     * Affiche une publicité spécifique.
     */
    public function show($id)
    {
        $ad = AdSetting::findOrFail($id);
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Affiche le formulaire de modification de publicité.
     */
    public function edit(Request $request, $ad_id)
    {
        try {
            // Débogage approfondi
            \Log::info("=========== DÉBUT DÉBUG EDIT ===========");
            \Log::info("Edit appelé avec ID route: " . $ad_id . " (type: " . gettype($ad_id) . ")");
            \Log::info("Edit URL complète: " . $request->fullUrl());
            \Log::info("Edit route name: " . $request->route()->getName());
            \Log::info("Edit tous les paramètres de route: ", $request->route()->parameters());
            \Log::info("Edit données request: ", $request->all());
            
            // Si l'ID est une chaîne qui ressemble à un nombre, la convertir
            if (is_string($ad_id) && ctype_digit($ad_id)) {
                $ad_id = (int)$ad_id;
                \Log::info("ID converti en entier: " . $ad_id);
            }
            
            // Peut-être que l'ID est dans le formulaire?
            $formId = $request->input('ad_id');
            if ($formId) {
                \Log::info("ID trouvé dans le formulaire: " . $formId);
                $ad_id = $formId;
            }
            
            // Vérifier si l'ID est numérique
            if (!is_numeric($ad_id) || $ad_id <= 0) {
                \Log::warning("L'ID n'est pas valide: " . $ad_id);
                
                // Rechercher l'ID dans la requête
                foreach ($request->all() as $key => $value) {
                    if (is_numeric($value) && $value > 0) {
                        \Log::info("Possible ID trouvé dans la requête sous la clé '{$key}': {$value}");
                    }
                }
                
                throw new \Exception("L'ID fourni n'est pas valide: " . $ad_id);
            }
            
            // Rechercher l'enregistrement avec une requête explicite
            $ad = AdSetting::where('id', $ad_id)->first();
            
            // Vérifier si l'enregistrement existe
            if (!$ad) {
                \Log::warning("Aucun enregistrement trouvé avec l'ID: " . $ad_id);
                throw new \Exception("Aucun enregistrement trouvé avec l'ID: " . $ad_id);
            }
            
            \Log::info("Publicité trouvée avec ID: " . $ad->id);
            
            $positions = $this->getPositions();
            $displayOptions = $this->getDisplayOptions();
            
            // Déterminer le type d'image (external, upload ou path)
            $imageType = 'path'; // Valeur par défaut
            
            if ($ad->type === 'image') {
                if (Str::startsWith($ad->image, 'http')) {
                    $imageType = 'external';
                } elseif (Str::startsWith($ad->image, 'storage/')) {
                    $imageType = 'upload';
                }
                
                // Vérifier si l'image existe
                if (($imageType === 'upload' || $imageType === 'path') && $ad->image) {
                    if (!file_exists(public_path($ad->image))) {
                        \Log::warning("Image non trouvée à l'emplacement: " . public_path($ad->image));
                    }
                }
            }
            
            \Log::info("=========== FIN DÉBUG EDIT ===========");
            
            return view('admin.ads.edit', compact('ad', 'positions', 'displayOptions', 'imageType'));
        } catch (\Exception $e) {
            // Déboggage - journaliser l'erreur
            \Log::error("Erreur dans la méthode edit: " . $e->getMessage());
            \Log::error("Trace: " . $e->getTraceAsString());
            
            // Rediriger vers la liste avec un message d'erreur
            return redirect()->route('admin.ads.index', ['locale' => app()->getLocale()])
                ->with('error', __('Une erreur est survenue lors de l\'édition: ') . $e->getMessage());
        }
    }

    /**
     * Met à jour la publicité spécifiée dans la base de données.
     */
    public function update(Request $request, $ad_id)
    {
        try {
            // Débogage - journaliser les données reçues
            \Log::info("Update appelé avec ID route: " . $ad_id);
            \Log::info("Update données request: ", $request->all());
            
            // Essayer d'abord avec l'ID de la route
            $adId = $ad_id;
            
            // Vérifier si l'ID est numérique
            if (!is_numeric($adId) || $adId <= 0) {
                throw new \Exception("L'ID fourni n'est pas valide: " . $adId);
            }
            
            // Rechercher l'enregistrement avec une requête explicite
            $ad = AdSetting::where('id', $adId)->first();
            
            // Vérifier si l'enregistrement existe
            if (!$ad) {
                throw new \Exception("Aucun enregistrement trouvé avec l'ID: " . $adId);
            }
            
            $validated = $this->validateAd($request, $adId);
            
            // Gérer les options d'affichage
            $displayOn = $request->input('display_on', []);
            \Log::debug("Options d'affichage reçues (update): ", $displayOn);
            
            // S'assurer que le format JSON est correct pour whereJsonContains
            $validated['display_on'] = json_encode($displayOn, JSON_UNESCAPED_UNICODE);
            \Log::debug("JSON encodé (update): " . $validated['display_on']);
            
            // Définir le statut actif
            $validated['active'] = $request->has('active');
            
            // Traiter l'image selon sa source (upload, URL externe, chemin relatif)
            if ($request->input('type') === 'image') {
                $imageType = $request->input('image_type', 'path');
                
                if ($imageType === 'upload' && $request->hasFile('image_file')) {
                    // Supprimer l'ancienne image si elle existe et est stockée localement
                    if ($ad->image && Str::startsWith($ad->image, 'storage/') && Storage::disk('public')->exists(Str::after($ad->image, 'storage/'))) {
                        Storage::disk('public')->delete(Str::after($ad->image, 'storage/'));
                    }
                    
                    // Traitement de l'image téléversée
                    $file = $request->file('image_file');
                    $fileName = 'ad_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('images/ads', $fileName, 'public');
                    $validated['image'] = 'storage/' . $filePath;
                } elseif ($imageType === 'external') {
                    // Utilisation d'une URL externe
                    $validated['image'] = $request->input('image_url');
                } elseif ($imageType === 'path') {
                    // Pour le type 'path', l'image est déjà dans $validated['image']
                    // Aucune action supplémentaire nécessaire
                }
            }
            
            // Journalisation pour le débogage
            \Log::info("Mise à jour de la publicité ID " . $adId . " avec données: ", $validated);
            
            // Mettre à jour l'enregistrement
            $updated = $ad->update($validated);
            
            if (!$updated) {
                throw new \Exception("Échec de la mise à jour de la publicité");
            }
            
            // Vider le cache des publicités
            app(AdService::class)->clearCache();
            
            return redirect()->route('admin.ads.index', ['locale' => app()->getLocale()])
                ->with('success', __('La publicité a été mise à jour avec succès.'));
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la mise à jour de la publicité ID " . ($adId ?? $ad_id) . ": " . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Une erreur est survenue lors de la mise à jour de la publicité: ') . $e->getMessage());
        }
    }

    /**
     * Active ou désactive une publicité.
     * 
     * @param int $ad_id ID de la publicité à basculer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(Request $request, $ad_id)
    {
        try {
            // Débogage - journaliser les données reçues
            \Log::info("Toggle appelé avec ID route: " . $ad_id);
            \Log::info("Toggle données formulaire: ", $request->all());
            
            // Essayer d'abord avec l'ID de la route
            $adId = $ad_id;
            
            // Si l'ID de la route n'est pas valide, essayer avec l'ID du formulaire
            if (!is_numeric($adId) || $adId <= 0) {
                $adId = $request->input('ad_id');
                \Log::info("Utilisation de l'ID du formulaire: " . $adId);
            }
            
            // Vérifier si l'ID est numérique
            if (!is_numeric($adId) || $adId <= 0) {
                throw new \Exception("L'ID fourni n'est pas valide: " . $adId);
            }
            
            // Rechercher l'enregistrement avec une requête explicite
            $ad = AdSetting::where('id', $adId)->first();
            
            // Vérifier si l'enregistrement existe
            if (!$ad) {
                throw new \Exception("Aucun enregistrement trouvé avec l'ID: " . $adId);
            }
            
            // Basculer l'état actif
            $active = !$ad->active;
            
            // Mettre à jour l'enregistrement
            $ad->update(['active' => $active]);
            
            // Vider le cache des publicités
            app(AdService::class)->clearCache();
            
            return redirect()->route('admin.ads.index', ['locale' => app()->getLocale()])
                ->with('success', $active 
                    ? __('La publicité a été activée avec succès.') 
                    : __('La publicité a été désactivée avec succès.'));
        } catch (\Exception $e) {
            // Journaliser l'erreur
            \Log::error("Erreur dans la méthode toggle: " . $e->getMessage());
            
            return redirect()->route('admin.ads.index', ['locale' => app()->getLocale()])
                ->with('error', __('Une erreur est survenue lors de la modification du statut de la publicité: ') . $e->getMessage());
        }
    }

    /**
     * Supprime la publicité spécifiée de la base de données.
     */
    public function destroy(Request $request, $ad_id)
    {
        try {
            // Débogage - journaliser les données reçues
            \Log::info("Destroy appelé avec ID route: " . $ad_id);
            \Log::info("Destroy données formulaire: ", $request->all());
            
            // Essayer d'abord avec l'ID de la route
            $adId = $ad_id;
            
            // Si l'ID de la route n'est pas valide, essayer avec l'ID du formulaire
            if (!is_numeric($adId) || $adId <= 0) {
                $adId = $request->input('ad_id');
                \Log::info("Utilisation de l'ID du formulaire: " . $adId);
            }
            
            // Vérifier si l'ID est numérique
            if (!is_numeric($adId) || $adId <= 0) {
                throw new \Exception("L'ID fourni n'est pas valide: " . $adId);
            }
            
            // Rechercher l'enregistrement avec une requête explicite
            $ad = AdSetting::where('id', $adId)->first();
            
            // Vérifier si l'enregistrement existe
            if (!$ad) {
                throw new \Exception("Aucun enregistrement trouvé avec l'ID: " . $adId);
            }
            
            // Suppression de l'image si elle existe et est stockée localement
            if ($ad->type === 'image' && $ad->image && Str::startsWith($ad->image, 'storage/') && Storage::disk('public')->exists(Str::after($ad->image, 'storage/'))) {
                Storage::disk('public')->delete(Str::after($ad->image, 'storage/'));
            }
            
            // Supprimer l'enregistrement
            $ad->delete();
            
            // Vider le cache des publicités
            app(AdService::class)->clearCache();
            
            return redirect()->route('admin.ads.index', ['locale' => app()->getLocale()])
                ->with('success', __('La publicité a été supprimée avec succès.'));
        } catch (\Exception $e) {
            // Journaliser l'erreur
            \Log::error("Erreur dans la méthode destroy: " . $e->getMessage());
            
            return redirect()->route('admin.ads.index', ['locale' => app()->getLocale()])
                ->with('error', __('Une erreur est survenue lors de la suppression de la publicité: ') . $e->getMessage());
        }
    }
    
    /**
     * Affiche la page de configuration globale des emplacements publicitaires.
     */
    public function globalSettings()
    {
        try {
            $positions = $this->getPositions();
            
            // Récupérer les types de pages sans l'administration
            $pageTypes = $this->getDisplayOptions(false);
            
            // Récupérer toutes les publicités existantes
            $allAds = AdSetting::all();
            
            // Initialiser la structure des paramètres
            $settings = [];
            foreach (array_keys($positions) as $position) {
                foreach (array_keys($pageTypes) as $pageType) {
                    $settings[$position][$pageType] = false;
                }
            }
            
            // Analyser les publicités pour déterminer les réglages actifs
            foreach ($allAds as $ad) {
                // Amélioration de la gestion du display_on pour s'assurer d'avoir un tableau
                $displayOn = [];
                
                if (is_array($ad->display_on)) {
                    $displayOn = $ad->display_on;
                } elseif (is_string($ad->display_on)) {
                    try {
                        $decoded = json_decode($ad->display_on, true);
                        $displayOn = is_array($decoded) ? $decoded : [$ad->display_on];
                    } catch (\Exception $e) {
                        \Log::warning("Impossible de décoder display_on pour l'annonce ID {$ad->id}: " . $e->getMessage());
                        $displayOn = [$ad->display_on];
                    }
                }
                
                // Pour chaque type de page dans display_on
                foreach ($displayOn as $pageType) {
                    if ($pageType !== 'admin' && isset($settings[$ad->position][$pageType])) {
                        $settings[$ad->position][$pageType] = true;
                    }
                }
            }
            
            \Log::debug("Réglages actuels pour la configuration globale: ", $settings);
            
            return view('admin.ads.global-settings', compact('positions', 'pageTypes', 'settings'));
        } catch (\Exception $e) {
            \Log::error("Erreur lors du chargement de la page de configuration globale: " . $e->getMessage());
            return redirect()->route('admin.ads.index', ['locale' => app()->getLocale()])
                ->with('error', __('Une erreur est survenue lors du chargement de la configuration globale: ') . $e->getMessage());
        }
    }
    
    /**
     * Met à jour la configuration globale des emplacements publicitaires.
     */
    public function updateGlobalSettings(Request $request)
    {
        try {
            // Journalisation des données reçues
            \Log::info("updateGlobalSettings appelé avec données: ", $request->all());
            
            // Récupérer toutes les positions et types de pages
            $positions = array_keys($this->getPositions());
            $pageTypes = array_keys($this->getDisplayOptions(false));
            
            // Récupérer toutes les publicités existantes, regroupées par position
            $allAds = AdSetting::all()->groupBy('position');
            
            // Préparer la structure des paramètres reçus
            $settings = [];
            if ($request->has('settings') && is_array($request->input('settings'))) {
                foreach ($positions as $position) {
                    foreach ($pageTypes as $pageType) {
                        // Par défaut, aucun affichage
                        $settings[$position][$pageType] = false;
                    }
                }
                
                // Mettre à jour avec les valeurs du formulaire
                foreach ($request->input('settings') as $position => $pageSettings) {
                    if (in_array($position, $positions) && is_array($pageSettings)) {
                        foreach ($pageSettings as $pageType => $value) {
                            if (in_array($pageType, $pageTypes)) {
                                $settings[$position][$pageType] = (bool)$value;
                            }
                        }
                    }
                }
            }
            
            // Appliquer les changements pour chaque publicité
            $updateCount = 0;
            foreach ($allAds as $position => $ads) {
                foreach ($ads as $ad) {
                    // Récupérer les pages d'affichage actuelles
                    $displayOn = [];
                    
                    if (is_array($ad->display_on)) {
                        $displayOn = $ad->display_on;
                    } elseif (is_string($ad->display_on)) {
                        try {
                            $decoded = json_decode($ad->display_on, true);
                            $displayOn = is_array($decoded) ? $decoded : [$ad->display_on];
                        } catch (\Exception $e) {
                            \Log::warning("Impossible de décoder display_on pour l'annonce ID {$ad->id}: " . $e->getMessage());
                            $displayOn = [$ad->display_on];
                        }
                    }
                    
                    // Vérifier si 'admin' est présent et le préserver
                    $adminEnabled = in_array('admin', $displayOn);
                    
                    // Nouveau tableau pour les pages d'affichage
                    $newDisplayOn = $adminEnabled ? ['admin'] : [];
                    
                    // Pour chaque type de page (sauf admin)
                    foreach ($pageTypes as $pageType) {
                        // Si la configuration existe et est active pour cette position et type de page
                        if (isset($settings[$position][$pageType]) && $settings[$position][$pageType]) {
                            // Ajouter le type de page aux pages d'affichage s'il n'y est pas déjà
                            if (!in_array($pageType, $newDisplayOn)) {
                                $newDisplayOn[] = $pageType;
                            }
                        }
                        // Si le paramètre est désactivé, ne pas ajouter ce type de page
                    }
                    
                    // Si les pages d'affichage ont changé, mettre à jour la publicité
                    if (json_encode($newDisplayOn) !== $ad->display_on) {
                        $ad->update([
                            'display_on' => json_encode($newDisplayOn)
                        ]);
                        $updateCount++;
                        \Log::debug("Publicité ID {$ad->id} mise à jour, display_on: " . json_encode($newDisplayOn));
                        
                        // Si aucune page n'est sélectionnée sauf peut-être admin, désactiver la publicité
                        if (count($newDisplayOn) === 0 || (count($newDisplayOn) === 1 && $adminEnabled)) {
                            $ad->update(['active' => false]);
                            \Log::debug("Publicité ID {$ad->id} désactivée, aucune page publique sélectionnée");
                        }
                    }
                }
            }
            
            // Vider le cache des publicités
            app(AdService::class)->clearCache();
            
            \Log::info("Mise à jour des paramètres globaux terminée, $updateCount publicités mises à jour");
            
            return redirect()->back()->with('success', __('Les paramètres globaux ont été mis à jour.'));
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la mise à jour des paramètres globaux: " . $e->getMessage());
            return redirect()->back()->with('error', __('Une erreur est survenue lors de la mise à jour des paramètres globaux: ') . $e->getMessage());
        }
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
            $imageType = $request->input('image_type', 'path');
            
            if ($imageType === 'upload') {
                // Règles pour l'upload de fichier image
                if (!$adId || $request->hasFile('image_file')) {
                    // Si c'est une création ou si un fichier est fourni lors de la mise à jour
                    $rules['image_file'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
                }
            } elseif ($imageType === 'external') {
                // Règles pour l'URL externe
                $rules['image_url'] = ['required', 'url'];
            } else {
                // Règles pour le chemin relatif
                $rules['image'] = ['required', 'string'];
            }
            
            $rules['link'] = ['nullable', 'url'];
            $rules['alt'] = ['nullable', 'string'];
        } else {
            $rules['code'] = ['required', 'string'];
        }
        
        // Journalisation des données pour le débogage
        \Log::debug("Données de validation reçues pour AdSetting ID " . ($adId ?? 'nouveau') . ": ", $request->all());
        
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
     * 
     * @param bool $includeAdmin Indique si l'option admin doit être incluse
     * @return array
     */
    private function getDisplayOptions($includeAdmin = true)
    {
        $options = [
            'home' => __('Page d\'accueil'),
            'tool' => __('Pages d\'outils'),
            'category' => __('Pages de catégories'),
        ];
        
        if ($includeAdmin) {
            $options['admin'] = __('Administration');
        }
        
        return $options;
    }
}
