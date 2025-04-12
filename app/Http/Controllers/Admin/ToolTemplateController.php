<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolTemplate;
use App\Models\ToolTemplateSection;
use App\Services\ToolTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolTemplateController extends Controller
{
    protected $templateService;

    /**
     * Constructeur du contrôleur.
     *
     * @param ToolTemplateService $templateService
     */
    public function __construct(ToolTemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    /**
     * Afficher la page de gestion du template pour un outil spécifique.
     *
     * @param Tool $tool
     * @return View
     */
    public function edit(Tool $tool): View
    {
        $toolSections = $this->templateService->getToolSections($tool);
        $availableSections = $this->templateService->getAvailableSections($tool);

        return view('admin.tools.template.edit', [
            'tool' => $tool,
            'toolSections' => $toolSections,
            'availableSections' => $availableSections,
        ]);
    }

    /**
     * Ajouter une section au template d'un outil.
     *
     * @param Request $request
     * @param Tool $tool
     * @return JsonResponse
     */
    public function addSection(Request $request, Tool $tool): JsonResponse
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:tool_template_sections,id',
            'order' => 'sometimes|integer|min:0',
        ]);

        $order = $validated['order'] ?? 999; // Par défaut à la fin
        $template = $this->templateService->addSectionToTool($tool, $validated['section_id'], $order);

        return response()->json([
            'success' => true,
            'message' => 'Section ajoutée avec succès',
            'template' => $template->load('section'),
        ]);
    }

    /**
     * Mettre à jour l'ordre des sections du template.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*' => 'integer|exists:tool_templates,id',
        ]);

        $orderedIds = [];
        foreach ($validated['sections'] as $index => $id) {
            $orderedIds[$id] = $index;
        }

        $this->templateService->updateSectionsOrder($orderedIds);

        return response()->json([
            'success' => true,
            'message' => 'Ordre des sections mis à jour',
        ]);
    }

    /**
     * Supprimer une section du template.
     *
     * @param ToolTemplate $template
     * @return JsonResponse
     */
    public function removeSection(ToolTemplate $template): JsonResponse
    {
        $success = $this->templateService->removeSection($template->id);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Section supprimée' : 'Erreur lors de la suppression',
        ]);
    }

    /**
     * Activer ou désactiver une section du template.
     *
     * @param Request $request
     * @param ToolTemplate $template
     * @return JsonResponse
     */
    public function toggleSection(Request $request, ToolTemplate $template): JsonResponse
    {
        $validated = $request->validate([
            'active' => 'required|boolean',
        ]);

        $success = $this->templateService->toggleSectionStatus($template->id, $validated['active']);

        return response()->json([
            'success' => $success,
            'message' => $success 
                ? ($validated['active'] ? 'Section activée' : 'Section désactivée') 
                : 'Erreur lors de la mise à jour',
        ]);
    }

    /**
     * Gérer les sections disponibles.
     *
     * @return View
     */
    public function manageSections(): View
    {
        $sections = ToolTemplateSection::orderBy('order')->get();
        
        return view('admin.tools.template.sections', [
            'sections' => $sections,
        ]);
    }

    /**
     * Créer une nouvelle section.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'partial_path' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'nullable|integer|min:0',
        ]);

        ToolTemplateSection::create($validated);

        return redirect()->route('admin.tools.template.sections')
            ->with('success', 'Section créée avec succès');
    }

    /**
     * Mettre à jour une section.
     *
     * @param Request $request
     * @param ToolTemplateSection $section
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSection(Request $request, ToolTemplateSection $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'partial_path' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $section->update($validated);

        return redirect()->route('admin.tools.template.sections')
            ->with('success', 'Section mise à jour avec succès');
    }

    /**
     * Récupérer les détails d'une section pour l'édition via API.
     *
     * @param ToolTemplateSection $section
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSection(ToolTemplateSection $section)
    {
        return response()->json([
            'success' => true,
            'section' => $section
        ]);
    }
} 