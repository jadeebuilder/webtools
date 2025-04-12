<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\ToolTranslation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        $languages = Language::where('is_active', true)->get();
        
        return view('admin.tools.create', compact('categories', 'languages'));
    }

    /**
     * Enregistre un nouvel outil.
     */
    public function store(Request $request)
    {
        $defaultLocale = Language::where('is_default', true)->first()->code ?? 'fr';
        
        $validatedData = $request->validate([
            'slug' => 'required|unique:tools,slug|max:255|regex:/^[a-z0-9\-]+$/',
            'icon' => 'required|string|max:50',
            'tool_category_id' => 'required|exists:tool_categories,id',
            'is_active' => 'sometimes|boolean',
            'is_premium' => 'sometimes|boolean',
            'order' => 'nullable|integer|min:0',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.locale' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'required|string',
            'translations.*.meta_title' => 'nullable|string|max:70',
            'translations.*.meta_description' => 'nullable|string|max:160',
            'translations.*.custom_h1' => 'nullable|string|max:255',
            'translations.*.custom_description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Créer l'outil
            $tool = Tool::create([
                'slug' => $validatedData['slug'],
                'icon' => $validatedData['icon'],
                'tool_category_id' => $validatedData['tool_category_id'],
                'is_active' => $request->has('is_active'),
                'is_premium' => $request->has('is_premium'),
                'order' => $validatedData['order'] ?? 0,
            ]);
            
            // Créer les traductions
            foreach ($validatedData['translations'] as $locale => $translation) {
                ToolTranslation::create([
                    'tool_id' => $tool->id,
                    'locale' => $locale,
                    'name' => $translation['name'],
                    'description' => $translation['description'],
                    'meta_title' => $translation['meta_title'] ?? null,
                    'meta_description' => $translation['meta_description'] ?? null,
                    'custom_h1' => $translation['custom_h1'] ?? null,
                    'custom_description' => $translation['custom_description'] ?? null,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.tools.index')
                ->with('success', __('L\'outil a été créé avec succès'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', __('Une erreur est survenue lors de la création de l\'outil'))
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire d'édition d'un outil.
     */
    public function edit(Tool $tool)
    {
        $tool->load('translations', 'category');
        $categories = ToolCategory::where('is_active', true)->orderBy('order')->get();
        $languages = Language::where('is_active', true)->get();
        
        // Organiser les traductions par locale pour un accès facile dans la vue
        $translations = $tool->translations->keyBy('locale')->toArray();
        
        return view('admin.tools.edit', compact('tool', 'categories', 'languages', 'translations'));
    }

    /**
     * Met à jour l'outil spécifié.
     */
    public function update(Request $request, Tool $tool)
    {
        $validatedData = $request->validate([
            'slug' => ['required', 'max:255', 'regex:/^[a-z0-9\-]+$/', Rule::unique('tools')->ignore($tool->id)],
            'icon' => 'required|string|max:50',
            'tool_category_id' => 'required|exists:tool_categories,id',
            'is_active' => 'sometimes|boolean',
            'is_premium' => 'sometimes|boolean',
            'order' => 'nullable|integer|min:0',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.locale' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'required|string',
            'translations.*.meta_title' => 'nullable|string|max:70',
            'translations.*.meta_description' => 'nullable|string|max:160',
            'translations.*.custom_h1' => 'nullable|string|max:255',
            'translations.*.custom_description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Mettre à jour l'outil
            $tool->update([
                'slug' => $validatedData['slug'],
                'icon' => $validatedData['icon'],
                'tool_category_id' => $validatedData['tool_category_id'],
                'is_active' => $request->has('is_active'),
                'is_premium' => $request->has('is_premium'),
                'order' => $validatedData['order'] ?? 0,
            ]);
            
            // Mettre à jour les traductions
            foreach ($validatedData['translations'] as $locale => $translationData) {
                $translation = $tool->translations()->where('locale', $locale)->first();
                
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
                        'locale' => $locale,
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
            
            return redirect()->route('admin.tools.index')
                ->with('success', __('L\'outil a été mis à jour avec succès'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', __('Une erreur est survenue lors de la mise à jour de l\'outil'))
                ->withInput();
        }
    }

    /**
     * Supprime l'outil spécifié.
     */
    public function destroy(Tool $tool)
    {
        try {
            // Vérifier si l'outil est utilisé dans des relations importantes
            // Ajouter ici les vérifications nécessaires avant suppression
            
            // Supprimer les traductions et l'outil
            $tool->translations()->delete();
            $tool->delete();
            
            return redirect()->route('admin.tools.index')
                ->with('success', __('L\'outil a été supprimé avec succès'));
        } catch (\Exception $e) {
            return redirect()->route('admin.tools.index')
                ->with('error', __('Une erreur est survenue lors de la suppression de l\'outil'));
        }
    }

    /**
     * Activer ou désactiver rapidement un outil.
     */
    public function toggleStatus(Tool $tool)
    {
        $tool->is_active = !$tool->is_active;
        $tool->save();
        
        return response()->json([
            'success' => true,
            'is_active' => $tool->is_active,
            'message' => $tool->is_active 
                ? __('L\'outil a été activé') 
                : __('L\'outil a été désactivé')
        ]);
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