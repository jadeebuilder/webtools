<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ToolCategory;
use App\Models\ToolCategoryTranslation;
use Illuminate\Support\Facades\DB;

class AddCategoryTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:add-category-translations {--category= : Slug de la catégorie à traiter (toutes si omis)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ajoute les traductions manquantes pour les catégories d\'outils';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Ajout des traductions de catégories...');

        $categorySlug = $this->option('category');
        
        // Définir les catégories à traiter
        $query = ToolCategory::query();
        if ($categorySlug) {
            $query->where('slug', $categorySlug);
        }
        
        $categories = $query->get();
        
        if ($categories->isEmpty()) {
            $this->error('Aucune catégorie trouvée.');
            return 1;
        }
        
        $this->info(sprintf('Traitement de %d catégories...', $categories->count()));
        
        // Définir les langues à traiter
        $locales = ['en', 'fr', 'es'];
        
        // Définitions des traductions par défaut
        $defaultTranslations = [
            'checker' => [
                'en' => [
                    'name' => 'Checker Tools',
                    'description' => 'Tools for checking and validating various types of data.',
                    'meta_title' => 'Online Check & Validation Tools | Easy to Use Web Checkers',
                    'meta_description' => 'Our collection of online checker tools helps you validate data, verify information, and ensure accuracy across multiple formats and standards.',
                ],
                'fr' => [
                    'name' => 'Outils de vérification',
                    'description' => 'Outils pour vérifier et valider différents types de données.',
                    'meta_title' => 'Outils de vérification en ligne | Validateurs web faciles à utiliser',
                    'meta_description' => 'Notre collection d\'outils de vérification en ligne vous aide à valider des données, vérifier des informations et assurer la précision à travers de multiples formats et standards.',
                ],
                'es' => [
                    'name' => 'Herramientas de verificación',
                    'description' => 'Herramientas para verificar y validar diferentes tipos de datos.',
                    'meta_title' => 'Herramientas de verificación en línea | Validadores web fáciles de usar',
                    'meta_description' => 'Nuestra colección de herramientas de verificación en línea te ayuda a validar datos, verificar información y asegurar la precisión en múltiples formatos y estándares.',
                ],
            ],
            // Ajouter d'autres catégories si nécessaire
        ];
        
        // Traiter chaque catégorie
        foreach ($categories as $category) {
            $this->info(sprintf('Traitement de la catégorie "%s"...', $category->slug));
            
            // Vérifier si des traductions par défaut existent pour cette catégorie
            $hasDefaults = isset($defaultTranslations[$category->slug]);
            
            foreach ($locales as $locale) {
                // Vérifier si la traduction existe déjà
                $translation = ToolCategoryTranslation::where('tool_category_id', $category->id)
                    ->where('locale', $locale)
                    ->first();
                
                if (!$translation) {
                    // Si pas de traduction, en créer une nouvelle
                    if ($hasDefaults) {
                        // Utiliser les valeurs par défaut si disponibles
                        $data = $defaultTranslations[$category->slug][$locale];
                    } else {
                        // Sinon, utiliser le slug comme nom par défaut
                        $data = [
                            'name' => ucfirst($category->slug) . ' Tools',
                            'description' => 'Tools for ' . $category->slug . '.',
                            'meta_title' => ucfirst($category->slug) . ' Tools | Online Web Tools',
                            'meta_description' => 'Our ' . $category->slug . ' tools help you perform various tasks online easily and efficiently.',
                        ];
                    }
                    
                    // Créer la traduction
                    ToolCategoryTranslation::create([
                        'tool_category_id' => $category->id,
                        'locale' => $locale,
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'meta_title' => $data['meta_title'],
                        'meta_description' => $data['meta_description'],
                    ]);
                    
                    $this->info(sprintf('  - Traduction créée pour "%s" (%s)', $category->slug, $locale));
                } else {
                    // Si la traduction existe mais que certains champs sont vides, les remplir
                    $updated = false;
                    $updates = [];
                    
                    // Vérifier et mettre à jour le meta_title si vide
                    if (empty($translation->meta_title)) {
                        if ($hasDefaults) {
                            $translation->meta_title = $defaultTranslations[$category->slug][$locale]['meta_title'];
                        } else {
                            $translation->meta_title = $translation->name;
                        }
                        $updates[] = 'meta_title';
                        $updated = true;
                    }
                    
                    // Vérifier et mettre à jour la meta_description si vide
                    if (empty($translation->meta_description)) {
                        if ($hasDefaults) {
                            $translation->meta_description = $defaultTranslations[$category->slug][$locale]['meta_description'];
                        } else {
                            $translation->meta_description = $translation->description;
                        }
                        $updates[] = 'meta_description';
                        $updated = true;
                    }
                    
                    if ($updated) {
                        $translation->save();
                        $this->info(sprintf('  - Traduction mise à jour pour "%s" (%s): %s', 
                            $category->slug, 
                            $locale, 
                            implode(', ', $updates)
                        ));
                    } else {
                        $this->info(sprintf('  - Traduction existante complète pour "%s" (%s)', $category->slug, $locale));
                    }
                }
            }
        }
        
        $this->info('Traitement des traductions terminé avec succès.');
        return 0;
    }
}
