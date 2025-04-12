<?php

namespace Database\Seeders;

use App\Models\ToolTemplateSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToolTemplateSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'Témoignages',
                'partial_path' => 'partials.home.testimonials',
                'icon' => 'chat-bubble-left-right',
                'description' => 'Section des témoignages clients',
                'order' => 1,
            ],
            [
                'name' => 'FAQ',
                'partial_path' => 'partials.home.faq',
                'icon' => 'question-mark-circle',
                'description' => 'Questions fréquemment posées',
                'order' => 2,
            ],
            [
                'name' => 'Outils populaires',
                'partial_path' => 'partials.home.popular-tools',
                'icon' => 'star',
                'description' => 'Affiche les outils les plus populaires',
                'order' => 3,
            ],
            [
                'name' => 'Catégories d\'outils',
                'partial_path' => 'partials.home.tool-categories',
                'icon' => 'folder',
                'description' => 'Liste des catégories d\'outils disponibles',
                'order' => 4,
            ],
            [
                'name' => 'Forfaits',
                'partial_path' => 'partials.home.packages',
                'icon' => 'currency-dollar',
                'description' => 'Présentation des forfaits disponibles',
                'order' => 5,
            ],
        ];

        foreach ($sections as $section) {
            ToolTemplateSection::create($section);
        }
    }
} 