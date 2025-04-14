<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\FaqTranslationsSeeder;

class SeedFaqTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed:faq-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importe les traductions des FAQs dans les différentes langues disponibles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Démarrage de l\'importation des traductions de FAQ...');
        
        // Exécuter le seeder de traductions de FAQ
        $seeder = new FaqTranslationsSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->info('Importation des traductions de FAQ terminée avec succès.');
        
        return 0;
    }
}
