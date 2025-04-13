<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class RepairToolCategories extends Command
{
    /**
     * Le nom et la signature de la commande console.
     *
     * @var string
     */
    protected $signature = 'tools:repair-categories';

    /**
     * La description de la commande console.
     *
     * @var string
     */
    protected $description = 'Répare complètement les problèmes liés aux catégories d\'outils';

    /**
     * Exécuter la commande console.
     */
    public function handle()
    {
        $this->info('Réparation des catégories d\'outils...');
        
        try {
            // 1. Vérifier l'état actuel des catégories
            $this->info('1. Vérification de l\'état actuel des catégories...');
            
            $categories = DB::table('tool_categories')->get();
            $this->info('Nombre de catégories trouvées: ' . $categories->count());
            
            // 2. Vérifier si des slugs en double existent
            $this->info('2. Vérification des slugs en double...');
            
            $slugCounts = DB::table('tool_categories')
                ->select('slug', DB::raw('COUNT(*) as count'))
                ->groupBy('slug')
                ->having('count', '>', 1)
                ->get();
            
            if ($slugCounts->count() > 0) {
                $this->warn('Slugs en double trouvés:');
                foreach ($slugCounts as $item) {
                    $this->warn("- {$item->slug} ({$item->count} occurrences)");
                }
                
                $this->info('Suppression des doublons...');
                foreach ($slugCounts as $item) {
                    $duplicates = DB::table('tool_categories')
                        ->where('slug', $item->slug)
                        ->orderBy('id', 'desc')
                        ->get();
                    
                    // Garder le premier, supprimer les autres
                    $keep = $duplicates->first();
                    $this->info("Conservation de la catégorie {$keep->id} ({$keep->slug})");
                    
                    foreach ($duplicates->slice(1) as $dupe) {
                        $this->info("Suppression de la catégorie {$dupe->id} ({$dupe->slug})");
                        
                        // Mettre à jour les outils pour qu'ils pointent vers la catégorie conservée
                        $toolsUpdated = DB::table('tools')
                            ->where('tool_category_id', $dupe->id)
                            ->update(['tool_category_id' => $keep->id]);
                        $this->info("- {$toolsUpdated} outils mis à jour");
                        
                        // Supprimer les traductions
                        $translationsDeleted = DB::table('tool_category_translations')
                            ->where('tool_category_id', $dupe->id)
                            ->delete();
                        $this->info("- {$translationsDeleted} traductions supprimées");
                        
                        // Supprimer la catégorie
                        DB::table('tool_categories')
                            ->where('id', $dupe->id)
                            ->delete();
                    }
                }
            } else {
                $this->info('Aucun slug en double trouvé.');
            }
            
            // 3. Vérifier les slugs nécessaires par rapport aux liens
            $this->info('3. Vérification des slugs nécessaires...');
            
            $requiredSlugs = [
                'checker', 'text', 'converter', 'generator', 'developer', 
                'image', 'unit', 'time', 'data', 'color', 'misc'
            ];
            
            $missingRequiredSlugs = [];
            foreach ($requiredSlugs as $slug) {
                $exists = DB::table('tool_categories')->where('slug', $slug)->exists();
                if (!$exists) {
                    $missingRequiredSlugs[] = $slug;
                    $this->warn("Slug requis manquant: {$slug}");
                    
                    // Vérifier si une version au pluriel existe
                    $pluralSlug = $slug . 's';
                    $pluralExists = DB::table('tool_categories')->where('slug', $pluralSlug)->exists();
                    
                    if ($pluralExists) {
                        $this->info("Correction: Renommer {$pluralSlug} en {$slug}");
                        DB::table('tool_categories')
                            ->where('slug', $pluralSlug)
                            ->update(['slug' => $slug]);
                    }
                }
            }
            
            // Si tous les slugs requis sont présents
            if (empty($missingRequiredSlugs)) {
                $this->info('Tous les slugs requis sont présents.');
            }
            
            // 4. Assurez-vous que toutes les catégories sont actives
            $this->info('4. Activation de toutes les catégories...');
            
            $inactiveCount = DB::table('tool_categories')
                ->where('is_active', 0)
                ->count();
                
            if ($inactiveCount > 0) {
                $this->warn("{$inactiveCount} catégories inactives trouvées, activation...");
                DB::table('tool_categories')
                    ->where('is_active', 0)
                    ->update(['is_active' => 1]);
            } else {
                $this->info('Toutes les catégories sont déjà actives.');
            }
            
            // 5. Vidage de tous les caches
            $this->info('5. Vidage des caches...');
            
            Artisan::call('cache:clear');
            $this->info('- Cache application vidé');
            
            Artisan::call('route:clear');
            $this->info('- Cache des routes vidé');
            
            Artisan::call('config:clear');
            $this->info('- Cache de configuration vidé');
            
            Artisan::call('view:clear');
            $this->info('- Cache des vues vidé');
            
            if (function_exists('opcache_reset')) {
                opcache_reset();
                $this->info('- OPCache vidé');
            }
            
            $this->newLine();
            $this->info('Réparation des catégories terminée avec succès!');
            $this->info('Les pages de catégories devraient maintenant fonctionner correctement.');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
} 