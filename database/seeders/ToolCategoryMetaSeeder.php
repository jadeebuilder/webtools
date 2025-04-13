<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolCategoryMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer toutes les traductions existantes
        $translations = DB::table('tool_category_translations')->get();
        
        foreach ($translations as $translation) {
            // Le meta_title est simplement le nom de la catégorie avec un suffixe approprié
            $metaTitle = "{$translation->name} - WebTools | Outils web en ligne";
            
            // Le meta_description est une version améliorée de la description pour optimiser le SEO
            switch ($translation->locale) {
                case 'fr':
                    $metaDescription = "Découvrez nos {$translation->name}. {$translation->description} Utilisez-les gratuitement en ligne sans inscription.";
                    break;
                case 'en':
                    $metaDescription = "Discover our {$translation->name}. {$translation->description} Use them online for free without registration.";
                    break;
                case 'es':
                    $metaDescription = "Descubre nuestras {$translation->name}. {$translation->description} Utilízalas en línea gratis sin registro.";
                    break;
                default:
                    $metaDescription = $translation->description;
            }
            
            // Mettre à jour la traduction avec les nouvelles valeurs meta
            DB::table('tool_category_translations')
                ->where('id', $translation->id)
                ->update([
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDescription,
                    'updated_at' => now()
                ]);
        }
        
        $this->command->info('Les meta_title et meta_description des catégories d\'outils ont été mis à jour avec succès!');
    }
} 