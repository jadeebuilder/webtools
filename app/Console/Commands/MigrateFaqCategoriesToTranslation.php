<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FaqCategory;
use App\Models\FaqCategoryTranslation;
use App\Models\Language;
use Illuminate\Support\Str;

class MigrateFaqCategoriesToTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faq:migrate-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migre les données des catégories FAQ existantes vers le système de traduction multi-langue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début de la migration des catégories FAQ vers le système de traduction...');
        
        // Récupérer la langue par défaut
        $defaultLanguage = Language::getDefault();
        
        if (!$defaultLanguage) {
            $this->error('Aucune langue par défaut trouvée. Veuillez configurer une langue par défaut avant de continuer.');
            return 1;
        }
        
        $this->info('Langue par défaut: ' . $defaultLanguage->name . ' (ID: ' . $defaultLanguage->id . ')');
        
        // Récupérer toutes les catégories de FAQ
        $categories = FaqCategory::all();
        $this->info('Nombre de catégories trouvées: ' . $categories->count());
        
        $bar = $this->output->createProgressBar($categories->count());
        $bar->start();
        
        $createdCount = 0;
        $skippedCount = 0;
        
        foreach ($categories as $category) {
            // Vérifier si une traduction existe déjà pour cette catégorie dans la langue par défaut
            $existingTranslation = FaqCategoryTranslation::where('faq_category_id', $category->id)
                ->where('language_id', $defaultLanguage->id)
                ->first();
                
            if ($existingTranslation) {
                $skippedCount++;
            } else {
                // Créer une traduction pour cette catégorie dans la langue par défaut
                FaqCategoryTranslation::create([
                    'faq_category_id' => $category->id,
                    'language_id' => $defaultLanguage->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'slug' => $category->slug,
                ]);
                
                $createdCount++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info('Migration terminée:');
        $this->info('- ' . $createdCount . ' traductions créées');
        $this->info('- ' . $skippedCount . ' catégories déjà traduites (ignorées)');
        
        return 0;
    }
} 