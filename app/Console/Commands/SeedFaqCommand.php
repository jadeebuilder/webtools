<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedFaqCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webtools:seed-faq {--refresh : Vider les tables FAQ avant le seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remplit la base de données avec des exemples de FAQ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('refresh')) {
            $this->info('Suppression des données FAQ existantes...');
            \DB::table('faqs')->truncate();
            \DB::table('faq_categories')->truncate();
        }

        $this->info('Création des catégories de FAQ...');
        $this->call('db:seed', [
            '--class' => 'Database\\Seeders\\FaqCategorySeeder',
        ]);

        $this->info('Création des FAQs...');
        $this->call('db:seed', [
            '--class' => 'Database\\Seeders\\FaqSeeder',
        ]);

        $this->info('Les données de démonstration pour les FAQs ont été importées avec succès!');
    }
} 