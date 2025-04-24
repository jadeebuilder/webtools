<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ToolCategory;
use App\Models\ToolCategoryTranslation;
use App\Models\SiteLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ToolCategoryController extends Controller
{
    /**
     * Affiche la liste des catégories d'outils.
     */
    public function index(string $locale)
    {
        try {
            $categories = ToolCategory::with('translations')->orderBy('order')->get();
            
            return view('admin.tool-categories.index', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement des catégories: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard', ['locale' => $locale])
                ->with('error', __('Impossible de charger les catégories. ') . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de création d'une catégorie.
     */
    public function create(string $locale)
    {
        try {
            $languages = SiteLanguage::where('is_active', true)->get();
            
            return view('admin.tool-categories.create', compact('languages'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement du formulaire de création: ' . $e->getMessage());
            
            return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le formulaire de création. ') . $e->getMessage());
        }
    }

    /**
     * Enregistre une nouvelle catégorie.
     */
    public function store(Request $request, string $locale)
    {
        try {
            $validatedData = $request->validate([
                'slug' => 'required|unique:tool_categories,slug|max:255|regex:/^[a-z0-9\-]+$/',
                'icon' => 'required|string|max:50',
                'is_active' => 'sometimes|boolean',
                'order' => 'nullable|integer|min:0',
                'translations' => 'required|array',
                'translations.*' => 'required|array',
                'translations.*.locale' => 'required|string|exists:site_languages,code',
                'translations.*.name' => 'required|string|max:255',
                'translations.*.description' => 'required|string',
            ]);

            DB::beginTransaction();

            try {
                $category = ToolCategory::create([
                    'slug' => $validatedData['slug'],
                    'icon' => $validatedData['icon'],
                    'is_active' => $validatedData['is_active'] ?? true,
                    'order' => $validatedData['order'] ?? 0,
                ]);

                foreach ($validatedData['translations'] as $translation) {
                    $category->translations()->create([
                        'locale' => $translation['locale'],
                        'name' => $translation['name'],
                        'description' => $translation['description'],
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                    ->with('success', __('Catégorie créée avec succès'));
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la catégorie: ' . $e->getMessage());
            
            return back()->with('error', __('Impossible de créer la catégorie. ') . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire d'édition d'une catégorie.
     */
    public function edit(string $locale, $tool_category)
    {
        try {
            // Vérifier si tool_category est un ID ou un objet
            $toolCategoryId = is_object($tool_category) ? $tool_category->id : $tool_category;
            
            // Vérifier l'existence via DB
            $categoryData = DB::table('tool_categories')->where('id', $toolCategoryId)->first();
            
            if (!$categoryData) {
                return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                    ->with('error', __('Catégorie introuvable'));
            }
            
            // Charger le modèle avec ses relations
            $toolCategory = ToolCategory::find($toolCategoryId);
            $toolCategory->load('translations');
            
            $languages = SiteLanguage::where('is_active', true)->get();
            
            // Organiser les traductions par locale pour un accès facile dans la vue
            $translations = $toolCategory->translations->keyBy('locale')->toArray();
            
            return view('admin.tool-categories.edit', compact('toolCategory', 'languages', 'translations'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement de la catégorie: ' . $e->getMessage());
            
            return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger la catégorie. ') . $e->getMessage());
        }
    }

    /**
     * Met à jour la catégorie spécifiée.
     */
    public function update(Request $request, string $locale, $toolCategory)
    {
        try {
            // Vérifier si toolCategory est un ID ou un objet
            $toolCategoryId = is_object($toolCategory) ? $toolCategory->id : $toolCategory;
            
            // Vérifier l'existence via DB
            $categoryData = DB::table('tool_categories')->where('id', $toolCategoryId)->first();
            
            if (!$categoryData) {
                return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                    ->with('error', __('Catégorie introuvable'));
            }
            
            // Validation
            $validatedData = $request->validate([
                'slug' => ['required', 'max:255', 'regex:/^[a-z0-9\-]+$/', Rule::unique('tool_categories')->ignore($toolCategoryId)],
                'icon' => 'required|string|max:50',
                'is_active' => 'sometimes|boolean',
                'order' => 'nullable|integer|min:0',
                'translations' => 'required|array',
                'translations.*' => 'required|array',
                'translations.*.locale' => 'required|string|exists:site_languages,code',
                'translations.*.name' => 'required|string|max:255',
                'translations.*.description' => 'required|string',
            ]);

            DB::beginTransaction();
            
            try {
                // Charger le modèle
                $toolCategory = ToolCategory::find($toolCategoryId);
                
                // Mettre à jour la catégorie
                $toolCategory->update([
                    'slug' => $validatedData['slug'],
                    'icon' => $validatedData['icon'],
                    'is_active' => $request->has('is_active'),
                    'order' => $validatedData['order'] ?? 0,
                ]);
                
                // Mettre à jour les traductions
                foreach ($validatedData['translations'] as $translationData) {
                    $locale = $translationData['locale'];
                    
                    $translation = $toolCategory->translations()->firstOrNew(['locale' => $locale]);
                    $translation->name = $translationData['name'];
                    $translation->description = $translationData['description'];
                    $translation->save();
                }
                
                DB::commit();
                
                return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                    ->with('success', __('Catégorie mise à jour avec succès'));
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour de la catégorie: ' . $e->getMessage());
            
            return back()->withInput()->with('error', __('Une erreur est survenue lors de la mise à jour de la catégorie: ') . $e->getMessage());
        }
    }

    /**
     * Supprime une catégorie d'outils.
     */
    public function destroy(string $locale, $toolCategory)
    {
        try {
            // Vérifier si toolCategory est un ID ou un objet
            $categoryId = is_object($toolCategory) ? $toolCategory->id : $toolCategory;
            
            // Vérifier l'existence via DB
            $categoryExists = DB::table('tool_categories')->where('id', $categoryId)->exists();
            
            if (!$categoryExists) {
                return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                    ->with('error', __('Catégorie introuvable'));
            }
            
            // Charger le modèle avec ses relations
            $category = ToolCategory::findOrFail($categoryId);
            
            DB::beginTransaction();
            
            try {
                // Supprimer les traductions
                $category->translations()->delete();
                
                // Supprimer la catégorie
                $category->delete();
                
                DB::commit();
                
                return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                    ->with('success', __('Catégorie supprimée avec succès'));
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression de la catégorie: ' . $e->getMessage());
            
            return redirect()->route('admin.tool-categories.index', ['locale' => $locale])
                ->with('error', __('Impossible de supprimer la catégorie. ') . $e->getMessage());
        }
    }

    /**
     * Active ou désactive une catégorie.
     */
    public function toggleStatus(string $locale, $category)
    {
        try {
            // Vérifier si category est un ID ou un objet
            $categoryId = is_object($category) ? $category->id : $category;
            
            // Vérifier l'existence via DB
            $categoryData = DB::table('tool_categories')->where('id', $categoryId)->first();
            
            if (!$categoryData) {
                return response()->json([
                    'success' => false,
                    'message' => __('Catégorie introuvable')
                ], 404);
            }
            
            // Charger le modèle
            $category = ToolCategory::find($categoryId);
            
            // Toggle le statut
            $category->is_active = !$category->is_active;
            $category->save();
            
            return response()->json([
                'success' => true,
                'message' => __('Statut de la catégorie mis à jour avec succès'),
                'is_active' => $category->is_active
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du toggle du statut de la catégorie: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => __('Une erreur est survenue lors de la mise à jour du statut: ') . $e->getMessage()
            ], 500);
        }
    }
} 