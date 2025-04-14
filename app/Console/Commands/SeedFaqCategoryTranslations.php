<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\FaqCategoryTranslationsSeeder;

class SeedFaqCategoryTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed:faq-category-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importe les traductions des catégories de FAQ dans les différentes langues disponibles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Démarrage de l\'importation des traductions des catégories de FAQ...');
        
        // Exécuter le seeder de traductions des catégories de FAQ
        $seeder = new FaqCategoryTranslationsSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->info('Importation des traductions des catégories de FAQ terminée avec succès.');
        
        return 0;
    }
} 