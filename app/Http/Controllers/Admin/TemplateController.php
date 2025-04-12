<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\ToolTemplate;
use App\Models\ToolTemplateSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    /**
     * Affiche la liste des outils pour la gestion des templates.
     */
    public function index(Request $request)
    {
        $query = Tool::query()->with(['category', 'templates']);
        
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
        
        $tools = $query->orderBy('slug')->paginate(15);
        $categories = ToolCategory::orderBy('order')->get();
        
        return view('admin.templates.index', compact('tools', 'categories'));
    }

    /**
     * Affiche le formulaire de configuration du template d'un outil.
     */
    public function edit(Tool $tool)
    {
        // Récupérer toutes les sections actives
        $allSections = ToolTemplateSection::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        // Récupérer les sections de l'outil et leurs données
        $toolTemplates = ToolTemplate::where('tool_id', $tool->id)
            ->with('section')
            ->orderBy('order')
            ->get();
        
        // Formater les données pour la vue (pour Alpine.js)
        $toolSections = $toolTemplates->map(function($template) {
            return [
                'id' => $template->id,
                'section_id' => $template->section_id,
                'is_active' => $template->is_active,
                'order' => $template->order,
                'settings' => $template->settings,
                'section' => $template->section,
            ];
        });
        
        // Filtrer les sections disponibles (celles qui ne sont pas déjà dans le template de l'outil)
        $usedSectionIds = $toolTemplates->pluck('section_id')->toArray();
        $availableSections = $allSections->whereNotIn('id', $usedSectionIds);
        
        return view('admin.templates.edit', compact('tool', 'availableSections', 'toolSections'));
    }

    /**
     * Met à jour le template d'un outil.
     */
    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.section_id' => 'required|exists:tool_template_sections,id',
            'sections.*.is_active' => 'required|boolean',
            'sections.*.order' => 'required|integer|min:0',
            'sections.*.settings' => 'sometimes|array',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Supprimer toutes les sections existantes pour cet outil
            ToolTemplate::where('tool_id', $tool->id)->delete();
            
            // Ajouter les nouvelles sections
            foreach ($request->sections as $index => $sectionData) {
                ToolTemplate::create([
                    'tool_id' => $tool->id,
                    'section_id' => $sectionData['section_id'],
                    'is_active' => $sectionData['is_active'],
                    'order' => $sectionData['order'] ?? $index,
                    'settings' => $sectionData['settings'] ?? [],
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => __('Le template a été mis à jour avec succès'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('Une erreur est survenue lors de la mise à jour du template'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 