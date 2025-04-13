<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FixToolCategorySlugs extends Command
{
    /**
     * Le nom et la signature de la commande console.
     *
     * @var string
     */
    protected $signature = 'tools:fix-category-slugs';

    /**
     * La description de la commande console.
     *
     * @var string
     */
    protected $description = 'Corrige les incohérences entre les slugs des catégories d\'outils';

    /**
     * Exécuter la commande console.
     */
    public function handle()
    {
        $this->info('Correction des slugs des catégories d\'outils...');
        
        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ToolCategorySlugFixSeeder',
                '--force' => true
            ]);
            
            $this->info('Les slugs des catégories d\'outils ont été corrigés avec succès!');
            $this->info('Corrections effectuées:');
            $this->info('- converters -> converter');
            $this->info('- generators -> generator');
            
            $this->warn('Important: Vous devrez peut-être vider le cache de votre application:');
            $this->line('  php artisan cache:clear');
            $this->line('  php artisan route:clear');
            $this->line('  php artisan config:clear');
            $this->line('  php artisan view:clear');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Une erreur est survenue: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
} 