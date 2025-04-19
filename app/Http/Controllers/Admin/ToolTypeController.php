<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteLanguage;
use App\Models\ToolType;
use App\Models\ToolTypeTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ToolTypeController extends Controller
{
    /**
     * Afficher la liste des types d'outils.
     *
     * @param  string  $locale
     * @return \Illuminate\View\View
     */
    public function index(string $locale)
    {
        try {
            $toolTypes = ToolType::orderBy('order')
                ->with('translations')
                ->get();
            
            return view('admin.tool-types.index', [
                'toolTypes' => $toolTypes
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des types d\'outils: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard', ['locale' => $locale])
                ->with('error', __('Impossible de charger les types d\'outils.'));
        }
    }

    /**
     * Afficher le formulaire de création d'un type d'outil.
     *
     * @param  string  $locale
     * @return \Illuminate\View\View
     */
    public function create(string $locale)
    {
        try {
            $languages = SiteLanguage::orderBy('is_default', 'desc')->get();
            
            return view('admin.tool-types.create', [
                'languages' => $languages
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire: ' . $e->getMessage());
            
            return redirect()->route('admin.tool-types.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le formulaire.'));
        }
    }

    /**
     * Enregistrer un nouveau type d'outil.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, string $locale)
    {
        try {
            $validatedData = $request->validate([
                'slug' => ['required', 'alpha_dash', 'max:50', 'unique:tool_types'],
                'icon' => ['nullable', 'string', 'max:50'],
                'color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
                'is_active' => ['boolean'],
                'order' => ['nullable', 'integer', 'min:0'],
                'translations' => ['required', 'array'],
                'translations.*.locale' => ['required', 'string', 'exists:site_languages,code'],
                'translations.*.name' => ['required', 'string', 'max:100'],
                'translations.*.description' => ['nullable', 'string'],
            ]);
            
            DB::beginTransaction();
            
            // Créer le type d'outil
            $toolType = ToolType::create([
                'slug' => $validatedData['slug'],
                'icon' => $validatedData['icon'] ?? null,
                'color' => $validatedData['color'],
                'is_active' => $validatedData['is_active'] ?? true,
                'order' => $validatedData['order'] ?? 0,
            ]);
            
            // Créer les traductions
            foreach ($validatedData['translations'] as $locale => $translationData) {
                ToolTypeTranslation::create([
                    'tool_type_id' => $toolType->id,
                    'locale' => $locale,
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.tool-types.index', ['locale' => $locale])
                ->with('success', __('Type d\'outil créé avec succès.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du type d\'outil: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', __('Impossible de créer le type d\'outil: ') . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire d'édition d'un type d'outil.
     *
     * @param  string  $locale
     * @param  mixed  $toolType
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $locale, $toolType)
    {
        try {
            $toolTypeId = is_object($toolType) ? $toolType->id : $toolType;
            
            $toolTypeData = DB::table('tool_types')->where('id', $toolTypeId)->first();
            
            if (!$toolTypeData) {
                return redirect()->route('admin.tool-types.index', ['locale' => $locale])
                    ->with('error', __('Type d\'outil introuvable.'));
            }
            
            // Charger le modèle avec ses traductions
            $toolType = ToolType::find($toolTypeId);
            $toolType->load('translations');
            
            // Préparer les traductions pour la vue
            $translations = [];
            foreach ($toolType->translations as $translation) {
                $translations[$translation->locale] = [
                    'name' => $translation->name,
                    'description' => $translation->description,
                ];
            }
            
            $languages = SiteLanguage::orderBy('is_default', 'desc')->get();
            
            return view('admin.tool-types.edit', [
                'toolType' => $toolType,
                'translations' => $translations,
                'languages' => $languages,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du type d\'outil: ' . $e->getMessage());
            
            return redirect()->route('admin.tool-types.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le type d\'outil.'));
        }
    }

    /**
     * Mettre à jour un type d'outil.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @param  mixed  $toolType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $locale, $toolType)
    {
        try {
            $toolTypeId = is_object($toolType) ? $toolType->id : $toolType;
            
            $validatedData = $request->validate([
                'slug' => ['required', 'alpha_dash', 'max:50', Rule::unique('tool_types')->ignore($toolTypeId)],
                'icon' => ['nullable', 'string', 'max:50'],
                'color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
                'is_active' => ['boolean'],
                'order' => ['nullable', 'integer', 'min:0'],
                'translations' => ['required', 'array'],
                'translations.*.locale' => ['required', 'string', 'exists:site_languages,code'],
                'translations.*.name' => ['required', 'string', 'max:100'],
                'translations.*.description' => ['nullable', 'string'],
            ]);
            
            DB::beginTransaction();
            
            // Mettre à jour le type d'outil
            $toolType = ToolType::findOrFail($toolTypeId);
            $toolType->update([
                'slug' => $validatedData['slug'],
                'icon' => $validatedData['icon'] ?? null,
                'color' => $validatedData['color'],
                'is_active' => $validatedData['is_active'] ?? true,
                'order' => $validatedData['order'] ?? 0,
            ]);
            
            // Mettre à jour les traductions
            foreach ($validatedData['translations'] as $locale => $translationData) {
                $translation = $toolType->translations()->where('locale', $locale)->first();
                
                if ($translation) {
                    $translation->update([
                        'name' => $translationData['name'],
                        'description' => $translationData['description'] ?? null,
                    ]);
                } else {
                    ToolTypeTranslation::create([
                        'tool_type_id' => $toolType->id,
                        'locale' => $locale,
                        'name' => $translationData['name'],
                        'description' => $translationData['description'] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.tool-types.index', ['locale' => $locale])
                ->with('success', __('Type d\'outil mis à jour avec succès.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du type d\'outil: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', __('Impossible de mettre à jour le type d\'outil: ') . $e->getMessage());
        }
    }

    /**
     * Supprimer un type d'outil.
     *
     * @param  string  $locale
     * @param  mixed  $toolType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $locale, $toolType)
    {
        try {
            $toolTypeId = is_object($toolType) ? $toolType->id : $toolType;
            
            DB::beginTransaction();
            
            // Supprimer le type d'outil et ses traductions (cascade)
            ToolType::findOrFail($toolTypeId)->delete();
            
            DB::commit();
            
            return redirect()->route('admin.tool-types.index', ['locale' => $locale])
                ->with('success', __('Type d\'outil supprimé avec succès.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du type d\'outil: ' . $e->getMessage());
            
            return back()
                ->with('error', __('Impossible de supprimer le type d\'outil: ') . $e->getMessage());
        }
    }

    /**
     * Basculer le statut actif d'un type d'outil.
     *
     * @param  string  $locale
     * @param  mixed  $toolType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(string $locale, $toolType)
    {
        try {
            $toolTypeId = is_object($toolType) ? $toolType->id : $toolType;
            
            // Récupérer le type d'outil
            $toolType = ToolType::findOrFail($toolTypeId);
            
            // Inverser le statut
            $toolType->update([
                'is_active' => !$toolType->is_active
            ]);
            
            return redirect()->route('admin.tool-types.index', ['locale' => $locale])
                ->with('success', __('Statut du type d\'outil mis à jour avec succès.'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut: ' . $e->getMessage());
            
            return back()
                ->with('error', __('Impossible de modifier le statut: ') . $e->getMessage());
        }
    }
} 