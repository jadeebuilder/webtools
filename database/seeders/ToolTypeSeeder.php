<?php

namespace Database\Seeders;

use App\Models\SiteLanguage;
use App\Models\ToolType;
use App\Models\ToolTypeTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste des types d'outils à créer
        $toolTypes = [
            [
                'slug' => 'basic',
                'icon' => 'fas fa-tools',
                'color' => '#3498db',
                'order' => 10,
                'translations' => [
                    'fr' => [
                        'name' => 'Standard',
                        'description' => 'Outils de base accessibles à tous les utilisateurs',
                    ],
                    'en' => [
                        'name' => 'Basic',
                        'description' => 'Basic tools accessible to all users',
                    ],
                    'es' => [
                        'name' => 'Básico',
                        'description' => 'Herramientas básicas accesibles para todos los usuarios',
                    ],
                ],
            ],
            [
                'slug' => 'vip',
                'icon' => 'fas fa-crown',
                'color' => '#f1c40f',
                'order' => 20,
                'translations' => [
                    'fr' => [
                        'name' => 'VIP',
                        'description' => 'Outils premium réservés aux utilisateurs VIP',
                    ],
                    'en' => [
                        'name' => 'VIP',
                        'description' => 'Premium tools reserved for VIP users',
                    ],
                    'es' => [
                        'name' => 'VIP',
                        'description' => 'Herramientas premium reservadas para usuarios VIP',
                    ],
                ],
            ],
            [
                'slug' => 'ai',
                'icon' => 'fas fa-robot',
                'color' => '#9b59b6',
                'order' => 30,
                'translations' => [
                    'fr' => [
                        'name' => 'Intelligence Artificielle',
                        'description' => 'Outils basés sur l\'intelligence artificielle',
                    ],
                    'en' => [
                        'name' => 'Artificial Intelligence',
                        'description' => 'Tools powered by artificial intelligence',
                    ],
                    'es' => [
                        'name' => 'Inteligencia Artificial',
                        'description' => 'Herramientas basadas en inteligencia artificial',
                    ],
                ],
            ],
            [
                'slug' => 'premium',
                'icon' => 'fas fa-star',
                'color' => '#e74c3c',
                'order' => 40,
                'translations' => [
                    'fr' => [
                        'name' => 'Premium',
                        'description' => 'Outils avancés réservés aux abonnements premium',
                    ],
                    'en' => [
                        'name' => 'Premium',
                        'description' => 'Advanced tools reserved for premium subscriptions',
                    ],
                    'es' => [
                        'name' => 'Premium',
                        'description' => 'Herramientas avanzadas reservadas para suscripciones premium',
                    ],
                ],
            ],
        ];

        // Récupérer les langues actives
        $languages = SiteLanguage::where('is_active', true)->get()->keyBy('code');

        DB::beginTransaction();

        try {
            foreach ($toolTypes as $typeData) {
                // Vérifier si le type d'outil existe déjà
                $existingType = ToolType::where('slug', $typeData['slug'])->first();
                
                if ($existingType) {
                    // Mettre à jour le type existant
                    $existingType->update([
                        'icon' => $typeData['icon'],
                        'color' => $typeData['color'],
                        'order' => $typeData['order'],
                    ]);
                    
                    $toolType = $existingType;
                } else {
                    // Créer un nouveau type d'outil
                    $toolType = ToolType::create([
                        'slug' => $typeData['slug'],
                        'icon' => $typeData['icon'],
                        'color' => $typeData['color'],
                        'is_active' => true,
                        'order' => $typeData['order'],
                    ]);
                }
                
                // Ajouter les traductions
                foreach ($typeData['translations'] as $locale => $translationData) {
                    // Vérifier si la langue existe
                    if (!isset($languages[$locale])) {
                        continue;
                    }
                    
                    // Vérifier si la traduction existe déjà
                    $existingTranslation = ToolTypeTranslation::where('tool_type_id', $toolType->id)
                        ->where('locale', $locale)
                        ->first();
                    
                    if ($existingTranslation) {
                        // Mettre à jour la traduction existante
                        $existingTranslation->update([
                            'name' => $translationData['name'],
                            'description' => $translationData['description'],
                        ]);
                    } else {
                        // Créer une nouvelle traduction
                        ToolTypeTranslation::create([
                            'tool_type_id' => $toolType->id,
                            'locale' => $locale,
                            'name' => $translationData['name'],
                            'description' => $translationData['description'],
                        ]);
                    }
                }
            }
            
            DB::commit();
            $this->command->info('Types d\'outils créés avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Erreur lors de la création des types d\'outils: ' . $e->getMessage());
        }
    }
}
