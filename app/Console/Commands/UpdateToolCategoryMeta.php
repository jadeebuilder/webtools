<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateToolCategoryMeta extends Command
{
    /**
     * Le nom et la signature de la commande console.
     *
     * @var string
     */
    protected $signature = 'tools:update-category-meta';

    /**
     * La description de la commande console.
     *
     * @var string
     */
    protected $description = 'Met à jour les meta_title et meta_description des catégories d\'outils';

    /**
     * Exécuter la commande console.
     */
    public function handle()
    {
        $this->info('Mise à jour des métadonnées des catégories d\'outils...');
        
        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ToolCategoryMetaSeeder',
                '--force' => true
            ]);
            
            $this->info('Les métadonnées des catégories d\'outils ont été mises à jour avec succès!');
            $this->info('Champs mis à jour:');
            $this->info('- meta_title: "Nom de la catégorie - WebTools | Outils web en ligne"');
            $this->info('- meta_description: Version SEO optimisée de la description');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
} 