<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolCategorySlugFixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping des slugs incorrects vers les slugs corrects
        $slugMapping = [
            'converters' => 'converter',  // Changer le pluriel en singulier
            'generators' => 'generator',  // Changer le pluriel en singulier
        ];

        foreach ($slugMapping as $oldSlug => $newSlug) {
            // Vérifier si le slug incorrect existe et que le slug correct n'existe pas déjà
            $oldCategory = DB::table('tool_categories')->where('slug', $oldSlug)->first();
            $newCategoryExists = DB::table('tool_categories')->where('slug', $newSlug)->exists();
            
            if ($oldCategory && !$newCategoryExists) {
                // Mise à jour du slug
                $this->command->info("Correction du slug: {$oldSlug} -> {$newSlug}");
                
                DB::table('tool_categories')
                    ->where('id', $oldCategory->id)
                    ->update([
                        'slug' => $newSlug,
                        'updated_at' => now()
                    ]);
            } elseif ($oldCategory && $newCategoryExists) {
                // Si le nouveau slug existe déjà, nous devons fusionner les catégories
                $this->command->info("Le slug {$newSlug} existe déjà, fusion des catégories...");
                
                $newCategory = DB::table('tool_categories')->where('slug', $newSlug)->first();
                
                // Mettre à jour les outils de l'ancienne catégorie vers la nouvelle
                DB::table('tools')
                    ->where('tool_category_id', $oldCategory->id)
                    ->update([
                        'tool_category_id' => $newCategory->id,
                        'updated_at' => now()
                    ]);
                
                // Transférer les traductions si elles n'existent pas déjà
                $translations = DB::table('tool_category_translations')
                    ->where('tool_category_id', $oldCategory->id)
                    ->get();
                
                foreach ($translations as $translation) {
                    $existingTranslation = DB::table('tool_category_translations')
                        ->where('tool_category_id', $newCategory->id)
                        ->where('locale', $translation->locale)
                        ->first();
                    
                    if (!$existingTranslation) {
                        DB::table('tool_category_translations')
                            ->insert([
                                'tool_category_id' => $newCategory->id,
                                'locale' => $translation->locale,
                                'name' => $translation->name,
                                'description' => $translation->description,
                                'meta_title' => $translation->meta_title,
                                'meta_description' => $translation->meta_description,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                    }
                }
                
                // Supprimer l'ancienne catégorie et ses traductions
                DB::table('tool_category_translations')
                    ->where('tool_category_id', $oldCategory->id)
                    ->delete();
                
                DB::table('tool_categories')
                    ->where('id', $oldCategory->id)
                    ->delete();
                
                $this->command->info("Catégorie {$oldSlug} supprimée après fusion.");
            } else {
                $this->command->info("Le slug {$oldSlug} n'existe pas, aucune action nécessaire.");
            }
        }
        
        $this->command->info('Correction des slugs des catégories terminée.');
    }
} 