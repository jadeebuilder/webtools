<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\ToolTranslation;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenir l'ID de la catégorie "converters"
        $convertersCategory = ToolCategory::where('slug', 'converters')->first();
        $textCategory = ToolCategory::where('slug', 'text')->first();

        if ($convertersCategory && $textCategory) {
            $tools = [
                [
                    'slug' => 'case-converter',
                    'icon' => 'fa-solid fa-font',
                    'tool_category_id' => $textCategory->id,
                    'is_active' => true,
                    'is_premium' => false,
                    'order' => 1,
                    'translations' => [
                        'fr' => [
                            'name' => 'Convertisseur de Casse',
                            'description' => 'Transformez facilement votre texte en majuscules, minuscules, casse de titre et plus encore avec notre convertisseur de casse en ligne gratuit.',
                            'custom_h1' => 'Convertisseur de Casse en Ligne',
                            'custom_description' => 'Transformez le format de votre texte en majuscules, minuscules, camelCase, PascalCase, snake_case et bien plus encore, gratuitement et sans limite.',
                            'meta_title' => 'Convertisseur de Casse | Transformez le Texte en Majuscules, Minuscules et Plus',
                            'meta_description' => 'Utilisez notre outil gratuit de conversion de casse pour transformer instantanément votre texte en majuscules, minuscules, casse de titre, camelCase, PascalCase, snake_case et autres formats.',
                        ],
                        'en' => [
                            'name' => 'Case Converter',
                            'description' => 'Easily transform your text to uppercase, lowercase, title case, and more with our free online case converter tool.',
                            'custom_h1' => 'Online Case Converter',
                            'custom_description' => 'Transform your text format to uppercase, lowercase, camelCase, PascalCase, snake_case and more, for free with no limits.',
                            'meta_title' => 'Case Converter | Transform Text to Uppercase, Lowercase and More',
                            'meta_description' => 'Use our free case conversion tool to instantly transform your text to uppercase, lowercase, title case, camelCase, PascalCase, snake_case and other formats.',
                        ],
                        'es' => [
                            'name' => 'Convertidor de Mayúsculas y Minúsculas',
                            'description' => 'Transforma fácilmente tu texto a mayúsculas, minúsculas, formato título y más con nuestra herramienta gratuita de conversión de casos.',
                            'custom_h1' => 'Convertidor de Mayúsculas y Minúsculas Online',
                            'custom_description' => 'Transforma el formato de tu texto a mayúsculas, minúsculas, camelCase, PascalCase, snake_case y más, gratis y sin límites.',
                            'meta_title' => 'Convertidor de Mayúsculas y Minúsculas | Transforma Texto a Mayúsculas, Minúsculas y Más',
                            'meta_description' => 'Utiliza nuestra herramienta gratuita de conversión de casos para transformar instantáneamente tu texto a mayúsculas, minúsculas, formato título, camelCase, PascalCase, snake_case y otros formatos.',
                        ],
                    ],
                ],
                // Ajouter d'autres outils au besoin
            ];

            foreach ($tools as $toolData) {
                $translations = $toolData['translations'];
                unset($toolData['translations']);

                // Créer ou mettre à jour l'outil
                $tool = Tool::updateOrCreate(
                    ['slug' => $toolData['slug']],
                    $toolData
                );

                // Ajouter les traductions
                foreach ($translations as $locale => $translationData) {
                    ToolTranslation::updateOrCreate(
                        [
                            'tool_id' => $tool->id,
                            'locale' => $locale,
                        ],
                        $translationData
                    );
                }
            }
        }
    }
}
