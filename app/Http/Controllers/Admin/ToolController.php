<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\ToolTranslation;
use App\Models\SiteLanguage;
use App\Models\ToolType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ToolController extends Controller
{
    /**
     * Affiche la liste des outils.
     */
    public function index(Request $request)
    {
        $query = Tool::query()->with(['category', 'translations']);
        
        // Filtrer par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('slug', 'like', "%{$search}%");
        }
        
        // Filtrer par catégorie
        if ($request->filled('category')) {
            $query->where('tool_category_id', $request->category);
        }
        
        // Filtrer par statut
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Filtrer par type (premium ou gratuit)
        if ($request->filled('type')) {
            $query->where('is_premium', $request->type === 'premium');
        }
        
        $tools = $query->orderBy('order')->paginate(15);
        $categories = ToolCategory::orderBy('order')->get();
        
        return view('admin.tools.index', compact('tools', 'categories'));
    }

    /**
     * Affiche le formulaire de création d'un outil.
     */
    public function create()
    {
        $categories = ToolCategory::where('is_active', true)->orderBy('order')->get();
        $languages = SiteLanguage::where('is_active', true)->get();
        $toolTypes = ToolType::where('is_active', true)->orderBy('order')->get();
        
        return view('admin.tools.create', compact('categories', 'languages', 'toolTypes'));
    }

    /**
     * Enregistre un nouvel outil.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, string $locale)
    {
        try {
            $defaultLocale = SiteLanguage::where('is_default', true)->first()->code ?? 'fr';
            
            $validatedData = $request->validate([
                'slug' => 'required|unique:tools,slug|max:255|regex:/^[a-z0-9\-]+$/',
                'icon' => 'required|string|max:50',
                'tool_category_id' => 'required|exists:tool_categories,id',
                'is_active' => 'sometimes',
                'is_premium' => 'sometimes',
                'order' => 'nullable|integer|min:0',
                'tool_types' => 'nullable|array',
                'tool_types.*' => 'exists:tool_types,id',
                'translations' => 'required|array',
                'translations.*' => 'required|array',
                'translations.*.locale' => 'required|string|exists:site_languages,code',
                'translations.*.name' => 'required|string|max:255',
                'translations.*.description' => 'required|string',
                'translations.*.meta_title' => 'nullable|string',
                'translations.*.meta_description' => 'nullable|string',
                'translations.*.custom_h1' => 'nullable|string|max:255',
                'translations.*.custom_description' => 'nullable|string',
            ]);
            
            DB::beginTransaction();
            
            try {
                // Créer l'outil
                $tool = Tool::create([
                    'slug' => $validatedData['slug'],
                    'icon' => $validatedData['icon'],
                    'tool_category_id' => $validatedData['tool_category_id'],
                    'is_active' => $request->has('is_active'),
                    'is_premium' => $request->has('is_premium'),
                    'order' => $validatedData['order'] ?? 0,
                ]);
                
                // Associer les types d'outils sélectionnés
                if ($request->has('tool_types')) {
                    $tool->types()->attach($request->tool_types);
                }
                
                // Créer les traductions
                foreach ($validatedData['translations'] as $localeCode => $translation) {
                    ToolTranslation::create([
                        'tool_id' => $tool->id,
                        'locale' => $localeCode,
                        'name' => $translation['name'],
                        'description' => $translation['description'],
                        'meta_title' => $translation['meta_title'] ?? null,
                        'meta_description' => $translation['meta_description'] ?? null,
                        'custom_h1' => $translation['custom_h1'] ?? null,
                        'custom_description' => $translation['custom_description'] ?? null,
                    ]);
                }
                
                DB::commit();
                
                return redirect()->route('admin.tools.index', ['locale' => $locale])
                    ->with('success', __('L\'outil a été créé avec succès'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur lors de la création de l\'outil: ' . $e->getMessage());
                
                return redirect()->back()
                    ->with('error', __('Une erreur est survenue lors de la création de l\'outil: ') . $e->getMessage())
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Erreur de validation: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', __('Erreur de validation: ') . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire d'édition d'un outil.
     */
    public function edit(string $locale, $tool)
    {
        try {
            $toolId = is_object($tool) ? $tool->id : $tool;
            $tool = Tool::findOrFail($toolId);
            
            $tool->load('translations', 'category', 'types');
            $categories = ToolCategory::where('is_active', true)->orderBy('order')->get();
            $languages = SiteLanguage::where('is_active', true)->get();
            $toolTypes = ToolType::where('is_active', true)->orderBy('order')->get();
            
            // Organiser les traductions par locale pour un accès facile dans la vue
            $translations = $tool->translations->keyBy('locale')->toArray();
            
            // Récupérer les IDs des types d'outils sélectionnés
            $selectedToolTypes = $tool->types->pluck('id')->toArray();
            
            return view('admin.tools.edit', compact('tool', 'categories', 'languages', 'translations', 'toolTypes', 'selectedToolTypes'));
        } catch (\Exception $e) {
            return redirect()->route('admin.tools.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger l\'outil.'));
        }
    }

    /**
     * Met à jour l'outil spécifié.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @param  mixed  $tool
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $locale, $tool)
    {
        try {
            // Vérifier si tool est un ID ou un objet
            $toolId = is_object($tool) ? $tool->id : $tool;
            
            // Récupérer le modèle
            $tool = Tool::findOrFail($toolId);
            
            $validatedData = $request->validate([
                'slug' => ['required', 'max:255', 'regex:/^[a-z0-9\-]+$/', Rule::unique('tools')->ignore($toolId)],
                'icon' => 'required|string|max:50',
                'tool_category_id' => 'required|exists:tool_categories,id',
                'is_active' => 'sometimes',
                'is_premium' => 'sometimes',
                'order' => 'nullable|integer|min:0',
                'tool_types' => 'nullable|array',
                'tool_types.*' => 'exists:tool_types,id',
                'translations' => 'required|array',
                'translations.*' => 'required|array',
                'translations.*.locale' => 'required|string|exists:site_languages,code',
                'translations.*.name' => 'required|string|max:255',
                'translations.*.description' => 'required|string',
                'translations.*.meta_title' => 'nullable|string',
                'translations.*.meta_description' => 'nullable|string',
                'translations.*.custom_h1' => 'nullable|string|max:255',
                'translations.*.custom_description' => 'nullable|string',
            ]);
            
            DB::beginTransaction();
            
            try {
                // Mettre à jour l'outil
                $tool->update([
                    'slug' => $validatedData['slug'],
                    'icon' => $validatedData['icon'],
                    'tool_category_id' => $validatedData['tool_category_id'],
                    'is_active' => $request->has('is_active'),
                    'is_premium' => $request->has('is_premium'),
                    'order' => $validatedData['order'] ?? 0,
                ]);
                
                // Mettre à jour les types d'outils associés
                $tool->types()->sync($request->tool_types ?? []);
                
                // Mettre à jour les traductions
                foreach ($validatedData['translations'] as $localeCode => $translationData) {
                    $translation = $tool->translations()->where('locale', $localeCode)->first();
                    
                    if ($translation) {
                        // Mettre à jour la traduction existante
                        $translation->update([
                            'name' => $translationData['name'],
                            'description' => $translationData['description'],
                            'meta_title' => $translationData['meta_title'] ?? null,
                            'meta_description' => $translationData['meta_description'] ?? null,
                            'custom_h1' => $translationData['custom_h1'] ?? null,
                            'custom_description' => $translationData['custom_description'] ?? null,
                        ]);
                    } else {
                        // Créer une nouvelle traduction
                        ToolTranslation::create([
                            'tool_id' => $tool->id,
                            'locale' => $localeCode,
                            'name' => $translationData['name'],
                            'description' => $translationData['description'],
                            'meta_title' => $translationData['meta_title'] ?? null,
                            'meta_description' => $translationData['meta_description'] ?? null,
                            'custom_h1' => $translationData['custom_h1'] ?? null,
                            'custom_description' => $translationData['custom_description'] ?? null,
                        ]);
                    }
                }
                
                DB::commit();
                
                return redirect()->route('admin.tools.index', ['locale' => $locale])
                    ->with('success', __('L\'outil a été mis à jour avec succès'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur transaction: ' . $e->getMessage());
                return back()->with('error', __('Erreur lors de la mise à jour: ') . $e->getMessage())->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Erreur de validation: ' . $e->getMessage());
            return back()->with('error', __('Erreur de validation: ') . $e->getMessage())->withInput();
        }
    }

    /**
     * Supprime l'outil spécifié.
     *
     * @param  string  $locale
     * @param  mixed  $tool
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $locale, $tool)
    {
        try {
            // Vérifier si tool est un ID ou un objet
            $toolId = is_object($tool) ? $tool->id : $tool;
            
            // Récupérer le modèle
            $tool = Tool::findOrFail($toolId);
            
            DB::beginTransaction();
            
            try {
                // Vérifier si l'outil est utilisé dans des relations importantes
                // Ajouter ici les vérifications nécessaires avant suppression
                
                // Supprimer les traductions et l'outil
                $tool->translations()->delete();
                $tool->delete();
                
                DB::commit();
                
                return redirect()->route('admin.tools.index', ['locale' => $locale])
                    ->with('success', __('L\'outil a été supprimé avec succès'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur lors de la suppression de l\'outil: ' . $e->getMessage());
                
                return redirect()->route('admin.tools.index', ['locale' => $locale])
                    ->with('error', __('Une erreur est survenue lors de la suppression de l\'outil: ') . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Outil introuvable: ' . $e->getMessage());
            
            return redirect()->route('admin.tools.index', ['locale' => $locale])
                ->with('error', __('Outil introuvable'));
        }
    }

    /**
     * Active ou désactive un outil.
     */
    public function toggleStatus(string $locale, $tool)
    {
        try {
            $toolId = is_object($tool) ? $tool->id : $tool;
            $tool = Tool::findOrFail($toolId);
            
            $tool->update(['is_active' => !$tool->is_active]);
            
            return redirect()->route('admin.tools.index', ['locale' => $locale])
                ->with('success', $tool->is_active ? __('L\'outil a été activé avec succès.') : __('L\'outil a été désactivé avec succès.'));
        } catch (\Exception $e) {
            return redirect()->route('admin.tools.index', ['locale' => $locale])
                ->with('error', __('Une erreur est survenue lors de la modification du statut de l\'outil.'));
        }
    }

    /**
     * Méthode d'aide pour générer un slug à partir d'un nom.
     */
    public function generateSlug(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        
        $slug = Str::slug($request->name);
        
        // Vérifier si le slug existe déjà et ajouter un suffixe numérique si nécessaire
        $count = 1;
        $originalSlug = $slug;
        
        while (Tool::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        return response()->json(['slug' => $slug]);
    }
} 