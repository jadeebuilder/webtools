<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FaqCategory;
use App\Models\FaqCategoryTranslation;
use App\Models\SiteLanguage;
use Illuminate\Support\Str;

class FaqCategoryTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les langues
        $frenchLanguage = SiteLanguage::where('code', 'fr')->first();
        $englishLanguage = SiteLanguage::where('code', 'en')->first();
        $spanishLanguage = SiteLanguage::where('code', 'es')->first();

        if (!$frenchLanguage || !$englishLanguage || !$spanishLanguage) {
            $this->command->error('Les langues nécessaires ne sont pas disponibles dans la base de données.');
            return;
        }

        // Récupérer toutes les catégories de FAQ existantes
        $categories = FaqCategory::all();

        // Pour chaque catégorie, créer les traductions
        foreach ($categories as $category) {
            // Créer la traduction française (à partir des données de base)
            FaqCategoryTranslation::updateOrCreate(
                ['faq_category_id' => $category->id, 'language_id' => $frenchLanguage->id],
                [
                    'name' => $category->name,
                    'description' => $category->description,
                    'slug' => $category->slug
                ]
            );

            // Créer les traductions pour chaque catégorie en anglais et en espagnol
            switch ($category->id) {
                case 1: // Informations générales
                    // Traduction anglaise
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $englishLanguage->id],
                        [
                            'name' => 'General Information',
                            'description' => 'Basic information about our platform and services',
                            'slug' => 'general-information'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $spanishLanguage->id],
                        [
                            'name' => 'Información General',
                            'description' => 'Información básica sobre nuestra plataforma y servicios',
                            'slug' => 'informacion-general'
                        ]
                    );
                    break;

                case 2: // Compte et utilisateur
                    // Traduction anglaise
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $englishLanguage->id],
                        [
                            'name' => 'Account and User',
                            'description' => 'Questions regarding account management and user data',
                            'slug' => 'account-and-user'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $spanishLanguage->id],
                        [
                            'name' => 'Cuenta y Usuario',
                            'description' => 'Preguntas sobre la gestión de cuentas y datos de usuario',
                            'slug' => 'cuenta-y-usuario'
                        ]
                    );
                    break;

                case 3: // Utilisation des outils
                    // Traduction anglaise
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $englishLanguage->id],
                        [
                            'name' => 'Using the Tools',
                            'description' => 'How to effectively use the tools available on our platform',
                            'slug' => 'using-the-tools'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $spanishLanguage->id],
                        [
                            'name' => 'Uso de las Herramientas',
                            'description' => 'Cómo utilizar eficazmente las herramientas disponibles en nuestra plataforma',
                            'slug' => 'uso-de-las-herramientas'
                        ]
                    );
                    break;

                case 4: // Facturation et abonnements
                    // Traduction anglaise
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $englishLanguage->id],
                        [
                            'name' => 'Billing and Subscriptions',
                            'description' => 'Questions related to payments, subscriptions, and billing',
                            'slug' => 'billing-and-subscriptions'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $spanishLanguage->id],
                        [
                            'name' => 'Facturación y Suscripciones',
                            'description' => 'Preguntas relacionadas con pagos, suscripciones y facturación',
                            'slug' => 'facturacion-y-suscripciones'
                        ]
                    );
                    break;

                case 5: // Support technique
                    // Traduction anglaise
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $englishLanguage->id],
                        [
                            'name' => 'Technical Support',
                            'description' => 'Technical assistance and resolution of common problems',
                            'slug' => 'technical-support'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $spanishLanguage->id],
                        [
                            'name' => 'Soporte Técnico',
                            'description' => 'Asistencia técnica y resolución de problemas comunes',
                            'slug' => 'soporte-tecnico'
                        ]
                    );
                    break;

                default:
                    // Pour les catégories non spécifiées, créer des traductions génériques
                    // Traduction anglaise
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $englishLanguage->id],
                        [
                            'name' => '[EN] ' . $category->name,
                            'description' => '[EN] ' . $category->description,
                            'slug' => 'en-' . $category->slug
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqCategoryTranslation::updateOrCreate(
                        ['faq_category_id' => $category->id, 'language_id' => $spanishLanguage->id],
                        [
                            'name' => '[ES] ' . $category->name,
                            'description' => '[ES] ' . $category->description,
                            'slug' => 'es-' . $category->slug
                        ]
                    );
                    break;
            }
        }

        $this->command->info('Les traductions des catégories de FAQ ont été créées avec succès.');
    }
} 