<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupToolCategories extends Command
{
    /**
     * Le nom et la signature de la commande console.
     *
     * @var string
     */
    protected $signature = 'tools:setup-categories {--meta-only : Mettre à jour uniquement les métadonnées SEO}';

    /**
     * La description de la commande console.
     *
     * @var string
     */
    protected $description = 'Configure les catégories d\'outils (ajoute les manquantes et remplit les métadonnées)';

    /**
     * Exécuter la commande console.
     */
    public function handle()
    {
        if (!$this->option('meta-only')) {
            $this->info('Étape 1/2: Ajout des catégories d\'outils manquantes...');
            
            try {
                Artisan::call('db:seed', [
                    '--class' => 'Database\\Seeders\\ToolCategorySeeder',
                    '--force' => true
                ]);
                
                $this->info('✓ Les catégories d\'outils manquantes ont été ajoutées avec succès!');
            } catch (\Exception $e) {
                $this->error('✗ Une erreur est survenue lors de l\'ajout des catégories: ' . $e->getMessage());
                return Command::FAILURE;
            }
        }
        
        $this->info($this->option('meta-only') ? 'Mise à jour des métadonnées SEO...' : 'Étape 2/2: Mise à jour des métadonnées SEO des catégories existantes...');
        
        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ToolCategoryMetaSeeder',
                '--force' => true
            ]);
            
            $this->info('✓ Les métadonnées SEO des catégories d\'outils ont été mises à jour avec succès!');
        } catch (\Exception $e) {
            $this->error('✗ Une erreur est survenue lors de la mise à jour des métadonnées: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        $this->info('');
        $this->info('Configuration des catégories d\'outils terminée!');
        $this->info('');
        $this->info('Formats utilisés:');
        $this->info('- meta_title: "Nom de la catégorie - WebTools | Outils web en ligne"');
        $this->info('- meta_description: Version SEO optimisée de la description par langue');
        
        return Command::SUCCESS;
    }
} 