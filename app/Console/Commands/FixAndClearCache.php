<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FixAndClearCache extends Command
{
    /**
     * Le nom et la signature de la commande console.
     *
     * @var string
     */
    protected $signature = 'tools:fix-and-clear';

    /**
     * La description de la commande console.
     *
     * @var string
     */
    protected $description = 'Corrige les slugs des catégories d\'outils et vide le cache';

    /**
     * Exécuter la commande console.
     */
    public function handle()
    {
        $this->info('Correction des slugs et vidage du cache...');
        
        try {
            // Étape 1: Corriger les slugs
            $this->info('Étape 1/2: Correction des slugs des catégories...');
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ToolCategorySlugFixSeeder',
                '--force' => true
            ]);
            $this->info('✓ Les slugs des catégories ont été corrigés.');
            
            // Étape 2: Vider les caches
            $this->info('Étape 2/2: Vidage des caches...');
            
            Artisan::call('cache:clear');
            $this->info('✓ Cache application vidé.');
            
            Artisan::call('route:clear');
            $this->info('✓ Cache des routes vidé.');
            
            Artisan::call('config:clear');
            $this->info('✓ Cache de configuration vidé.');
            
            Artisan::call('view:clear');
            $this->info('✓ Cache des vues vidé.');
            
            $this->info('');
            $this->info('Toutes les opérations ont été effectuées avec succès!');
            $this->info('Les pages de catégories devraient maintenant fonctionner correctement.');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
} 