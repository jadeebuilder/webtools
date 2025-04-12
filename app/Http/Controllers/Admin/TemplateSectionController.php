<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ToolTemplateSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TemplateSectionController extends Controller
{
    /**
     * Affiche la liste des sections de template.
     */
    public function index()
    {
        $sections = ToolTemplateSection::orderBy('order')->get();
        
        return view('admin.templates.sections.index', compact('sections'));
    }

    /**
     * Affiche le formulaire de création de section.
     */
    public function create()
    {
        return view('admin.templates.sections.create');
    }

    /**
     * Stocke une nouvelle section.
     */
    public function store(Request $request)
    {
        $validated = $this->validateSection($request);
        
        // Ajouter le préfixe partials. si ce n'est pas déjà fait
        if (!str_starts_with($validated['partial_path'], 'partials.')) {
            $validated['partial_path'] = 'partials.' . $validated['partial_path'];
        }
        
        // Convertir is_active en booléen
        $validated['is_active'] = $request->has('is_active');
        
        ToolTemplateSection::create($validated);
        
        return redirect()->route('admin.templates.sections.index')
            ->with('success', __('La section a été créée avec succès'));
    }

    /**
     * Affiche le formulaire d'édition de section.
     */
    public function edit(ToolTemplateSection $section)
    {
        return view('admin.templates.sections.edit', compact('section'));
    }

    /**
     * Met à jour la section spécifiée.
     */
    public function update(Request $request, ToolTemplateSection $section)
    {
        $validated = $this->validateSection($request, $section->id);
        
        // Ajouter le préfixe partials. si ce n'est pas déjà fait
        if (!str_starts_with($validated['partial_path'], 'partials.')) {
            $validated['partial_path'] = 'partials.' . $validated['partial_path'];
        }
        
        // Convertir is_active en booléen
        $validated['is_active'] = $request->has('is_active');
        
        $section->update($validated);
        
        return redirect()->route('admin.templates.sections.index')
            ->with('success', __('La section a été mise à jour avec succès'));
    }

    /**
     * Active ou désactive une section.
     */
    public function toggle(ToolTemplateSection $section)
    {
        $section->update([
            'is_active' => !$section->is_active
        ]);
        
        return redirect()->route('admin.templates.sections.index')
            ->with('success', $section->is_active 
                ? __('La section a été activée avec succès') 
                : __('La section a été désactivée avec succès'));
    }

    /**
     * Supprime la section spécifiée.
     */
    public function destroy(ToolTemplateSection $section)
    {
        // Vérifier si la section est utilisée dans des templates
        if ($section->toolTemplates()->exists()) {
            return redirect()->route('admin.templates.sections.index')
                ->with('error', __('Impossible de supprimer cette section car elle est utilisée par des outils'));
        }
        
        $section->delete();
        
        return redirect()->route('admin.templates.sections.index')
            ->with('success', __('La section a été supprimée avec succès'));
    }
    
    /**
     * Valide les données de section.
     */
    private function validateSection(Request $request, $sectionId = null)
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'partial_path' => [
                'required', 
                'string', 
                'max:255',
                // Vérifier l'unicité du chemin, en ignorant l'enregistrement actuel si on est en édition
                Rule::unique('tool_template_sections', 'partial_path')->ignore($sectionId)
            ],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
} 