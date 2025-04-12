<?php

namespace Database\Seeders;

use App\Models\ToolCategory;
use App\Models\ToolCategoryTranslation;
use Illuminate\Database\Seeder;

class ToolCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'text',
                'icon' => 'fa-solid fa-font',
                'is_active' => true,
                'order' => 1,
                'translations' => [
                    'fr' => [
                        'name' => 'Outils de texte',
                        'description' => 'Outils pour manipuler, formater et analyser du texte.',
                    ],
                    'en' => [
                        'name' => 'Text Tools',
                        'description' => 'Tools to manipulate, format and analyze text.',
                    ],
                    'es' => [
                        'name' => 'Herramientas de texto',
                        'description' => 'Herramientas para manipular, formatear y analizar texto.',
                    ],
                ],
            ],
            [
                'slug' => 'converters',
                'icon' => 'fa-solid fa-exchange-alt',
                'is_active' => true,
                'order' => 2,
                'translations' => [
                    'fr' => [
                        'name' => 'Convertisseurs',
                        'description' => 'Outils pour convertir des données d\'un format à un autre.',
                    ],
                    'en' => [
                        'name' => 'Converters',
                        'description' => 'Tools to convert data from one format to another.',
                    ],
                    'es' => [
                        'name' => 'Convertidores',
                        'description' => 'Herramientas para convertir datos de un formato a otro.',
                    ],
                ],
            ],
            [
                'slug' => 'generators',
                'icon' => 'fa-solid fa-magic',
                'is_active' => true,
                'order' => 3,
                'translations' => [
                    'fr' => [
                        'name' => 'Générateurs',
                        'description' => 'Outils pour générer des données, du texte ou des codes.',
                    ],
                    'en' => [
                        'name' => 'Generators',
                        'description' => 'Tools to generate data, text or codes.',
                    ],
                    'es' => [
                        'name' => 'Generadores',
                        'description' => 'Herramientas para generar datos, texto o códigos.',
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $translations = $categoryData['translations'];
            unset($categoryData['translations']);

            // Créer ou mettre à jour la catégorie
            $category = ToolCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            // Ajouter les traductions
            foreach ($translations as $locale => $translationData) {
                ToolCategoryTranslation::updateOrCreate(
                    [
                        'tool_category_id' => $category->id,
                        'locale' => $locale,
                    ],
                    $translationData
                );
            }
        }
    }
}
