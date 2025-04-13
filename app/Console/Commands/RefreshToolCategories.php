<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshToolCategories extends Command
{
    /**
     * Le nom et la signature de la commande console.
     *
     * @var string
     */
    protected $signature = 'tools:refresh-categories';

    /**
     * La description de la commande console.
     *
     * @var string
     */
    protected $description = 'Rafraîchit les catégories d\'outils en ajoutant les catégories manquantes';

    /**
     * Exécuter la commande console.
     */
    public function handle()
    {
        $this->info('Rafraîchissement des catégories d\'outils...');
        
        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ToolCategorySeeder',
                '--force' => true
            ]);
            
            $this->info('Les catégories d\'outils ont été rafraîchies avec succès!');
            $this->info('Nouvelles catégories ajoutées:');
            $this->info('- checker (Outils de vérification)');
            $this->info('- developer (Outils de développement)');
            $this->info('- image (Outils d\'image)');
            $this->info('- unit (Convertisseurs d\'unités)');
            $this->info('- time (Convertisseurs de temps)');
            $this->info('- data (Convertisseurs de données)');
            $this->info('- color (Convertisseurs de couleurs)');
            $this->info('- misc (Outils divers)');
            $this->info('- converter (Outils de conversion)');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
} 