<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ToolCategory;
use App\Models\ToolCategoryTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ToolCategoryController extends Controller
{
    /**
     * Affiche la liste des catégories d'outils.
     */
    public function index()
    {
        $categories = ToolCategory::with('translations')->orderBy('order')->get();
        
        return view('admin.tool-categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création d'une catégorie.
     */
    public function create()
    {
        $languages = Language::where('is_active', true)->get();
        
        return view('admin.tool-categories.create', compact('languages'));
    }

    /**
     * Enregistre une nouvelle catégorie.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'slug' => 'required|unique:tool_categories,slug|max:255|regex:/^[a-z0-9\-]+$/',
            'icon' => 'required|string|max:50',
            'is_active' => 'sometimes|boolean',
            'order' => 'nullable|integer|min:0',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.locale' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'required|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Créer la catégorie
            $category = ToolCategory::create([
                'slug' => $validatedData['slug'],
                'icon' => $validatedData['icon'],
                'is_active' => $request->has('is_active'),
                'order' => $validatedData['order'] ?? 0,
            ]);
            
            // Ajouter les traductions
            foreach ($validatedData['translations'] as $translationData) {
                $category->translations()->create([
                    'locale' => $translationData['locale'],
                    'name' => $translationData['name'],
                    'description' => $translationData['description'],
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.tool-categories.index', ['locale' => app()->getLocale()])
                ->with('success', __('Catégorie créée avec succès'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', __('Une erreur est survenue lors de la création de la catégorie'));
        }
    }

    /**
     * Affiche le formulaire d'édition d'une catégorie.
     */
    public function edit(ToolCategory $toolCategory)
    {
        $toolCategory->load('translations');
        $languages = Language::where('is_active', true)->get();
        
        // Organiser les traductions par locale pour un accès facile dans la vue
        $translations = $toolCategory->translations->keyBy('locale')->toArray();
        
        return view('admin.tool-categories.edit', compact('toolCategory', 'languages', 'translations'));
    }

    /**
     * Met à jour la catégorie spécifiée.
     */
    public function update(Request $request, ToolCategory $toolCategory)
    {
        $validatedData = $request->validate([
            'slug' => ['required', 'max:255', 'regex:/^[a-z0-9\-]+$/', Rule::unique('tool_categories')->ignore($toolCategory->id)],
            'icon' => 'required|string|max:50',
            'is_active' => 'sometimes|boolean',
            'order' => 'nullable|integer|min:0',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.locale' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'required|string',
        ]);

        DB::beginTransaction();
        
        try {
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
            
            return redirect()->route('admin.tool-categories.index', ['locale' => app()->getLocale()])
                ->with('success', __('Catégorie mise à jour avec succès'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', __('Une erreur est survenue lors de la mise à jour de la catégorie'));
        }
    }

    /**
     * Supprime la catégorie spécifiée.
     */
    public function destroy(ToolCategory $toolCategory)
    {
        // Vérifier si la catégorie contient des outils
        if ($toolCategory->tools()->count() > 0) {
            return back()->with('error', __('Impossible de supprimer cette catégorie car elle contient des outils'));
        }
        
        try {
            // Supprimer les traductions et la catégorie
            $toolCategory->translations()->delete();
            $toolCategory->delete();
            
            return back()->with('success', __('Catégorie supprimée avec succès'));
        } catch (\Exception $e) {
            return back()->with('error', __('Une erreur est survenue lors de la suppression de la catégorie'));
        }
    }

    /**
     * Active ou désactive une catégorie.
     */
    public function toggleStatus(ToolCategory $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();
        
        return back()->with('success', __('Statut de la catégorie mis à jour avec succès'));
    }
} 