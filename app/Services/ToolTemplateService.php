<?php

namespace App\Services;

use App\Models\Tool;
use App\Models\ToolTemplate;
use App\Models\ToolTemplateSection;
use Illuminate\Support\Collection;

class ToolTemplateService
{
    /**
     * Récupérer toutes les sections disponibles.
     *
     * @return Collection
     */
    public function getAllSections(): Collection
    {
        return ToolTemplateSection::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Récupérer toutes les sections pour un outil spécifique.
     *
     * @param Tool $tool
     * @return Collection
     */
    public function getToolSections(Tool $tool): Collection
    {
        return $tool->templates()
            ->with('section')
            ->orderBy('order')
            ->get();
    }

    /**
     * Récupérer les sections non utilisées pour un outil.
     *
     * @param Tool $tool
     * @return Collection
     */
    public function getAvailableSections(Tool $tool): Collection
    {
        $usedSectionIds = $tool->templates()
            ->pluck('section_id')
            ->toArray();

        return ToolTemplateSection::where('is_active', true)
            ->whereNotIn('id', $usedSectionIds)
            ->orderBy('order')
            ->get();
    }

    /**
     * Ajouter une section à un outil.
     *
     * @param Tool $tool
     * @param int $sectionId
     * @param int $order
     * @return ToolTemplate
     */
    public function addSectionToTool(Tool $tool, int $sectionId, int $order = 0): ToolTemplate
    {
        return ToolTemplate::create([
            'tool_id' => $tool->id,
            'section_id' => $sectionId,
            'order' => $order,
            'is_active' => true,
        ]);
    }

    /**
     * Mettre à jour l'ordre des sections d'un outil.
     *
     * @param array $orderedIds Liste des IDs des templates avec leur ordre [id => order]
     * @return bool
     */
    public function updateSectionsOrder(array $orderedIds): bool
    {
        foreach ($orderedIds as $id => $order) {
            ToolTemplate::where('id', $id)
                ->update(['order' => $order]);
        }

        return true;
    }

    /**
     * Supprimer une section d'un outil.
     *
     * @param int $templateId
     * @return bool
     */
    public function removeSection(int $templateId): bool
    {
        return ToolTemplate::destroy($templateId) > 0;
    }

    /**
     * Activer ou désactiver une section pour un outil.
     *
     * @param int $templateId
     * @param bool $active
     * @return bool
     */
    public function toggleSectionStatus(int $templateId, bool $active): bool
    {
        return ToolTemplate::where('id', $templateId)
            ->update(['is_active' => $active]);
    }

    /**
     * Rendre les sections d'un outil (génération du HTML).
     *
     * @param Tool $tool
     * @return string
     */
    public function renderToolSections(Tool $tool): string
    {
        $sections = $this->getToolSections($tool);
        $html = '';

        foreach ($sections as $template) {
            if ($template->is_active && $template->section->is_active) {
                $partial = str_replace('.', '/', $template->section->partial_path);
                $html .= view($template->section->partial_path)->render();
            }
        }

        return $html;
    }
} 