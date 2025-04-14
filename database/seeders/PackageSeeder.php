<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\PackageTranslation;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Exécute le seeder pour créer des packages.
     */
    public function run(): void
    {
        // Désactiver les clés étrangères pendant le seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Vider les tables pour éviter les doublons
        DB::table('package_translations')->truncate();
        DB::table('package_tools')->truncate();
        DB::table('packages')->truncate();
        
        // Récupérer quelques outils pour les associer aux packages
        $tools = Tool::take(15)->get();
        
        // Définition des packages
        $packages = [
            [
                'slug' => 'starter',
                'color' => '#4f46e5', // Indigo
                'is_active' => true,
                'is_default' => true,
                'show_ads' => true,
                'cycle_type' => Package::CYCLE_MONTH,
                'cycle_count' => 1,
                'monthly_price' => 0,
                'annual_price' => 0,
                'lifetime_price' => 0,
                'order' => 1,
                'translations' => [
                    'fr' => [
                        'name' => 'Starter',
                        'description' => 'Parfait pour commencer avec les outils essentiels.',
                        'features' => "Accès aux outils de base\nLimité à 5 utilisations par jour\nPublicités incluses\nSupport par email"
                    ],
                    'en' => [
                        'name' => 'Starter',
                        'description' => 'Perfect to get started with essential tools.',
                        'features' => "Access to basic tools\nLimited to 5 uses per day\nAds included\nEmail support"
                    ],
                    'es' => [
                        'name' => 'Iniciante',
                        'description' => 'Perfecto para comenzar con herramientas esenciales.',
                        'features' => "Acceso a herramientas básicas\nLimitado a 5 usos por día\nPublicidad incluida\nSoporte por correo electrónico"
                    ]
                ]
            ],
            [
                'slug' => 'pro',
                'color' => '#8b5cf6', // Violet
                'is_active' => true,
                'is_default' => false,
                'show_ads' => false,
                'cycle_type' => Package::CYCLE_MONTH,
                'cycle_count' => 1,
                'monthly_price' => 9.99,
                'annual_price' => 99.99,
                'lifetime_price' => 0,
                'order' => 2,
                'translations' => [
                    'fr' => [
                        'name' => 'Pro',
                        'description' => 'Pour les professionnels qui ont besoin de plus de puissance.',
                        'features' => "Accès à tous les outils\nUtilisations illimitées\nSans publicités\nSupport prioritaire\nExportation des résultats\nIntégration API basique"
                    ],
                    'en' => [
                        'name' => 'Pro',
                        'description' => 'For professionals who need more power.',
                        'features' => "Access to all tools\nUnlimited uses\nAd-free experience\nPriority support\nResults export\nBasic API integration"
                    ],
                    'es' => [
                        'name' => 'Profesional',
                        'description' => 'Para profesionales que necesitan más potencia.',
                        'features' => "Acceso a todas las herramientas\nUsos ilimitados\nSin publicidad\nSoporte prioritario\nExportación de resultados\nIntegración básica de API"
                    ]
                ]
            ],
            [
                'slug' => 'ultimate',
                'color' => '#ec4899', // Rose
                'is_active' => true,
                'is_default' => false,
                'show_ads' => false,
                'cycle_type' => Package::CYCLE_MONTH,
                'cycle_count' => 1,
                'monthly_price' => 19.99,
                'annual_price' => 199.99,
                'lifetime_price' => 499.99,
                'order' => 3,
                'translations' => [
                    'fr' => [
                        'name' => 'Ultimate',
                        'description' => 'La solution complète pour les entreprises et les power users.',
                        'features' => "Accès VIP à tous les outils\nAccès anticipé aux nouveautés\nPriorité maximale au support\nUtilisations illimitées\nSans publicités\nExportation avancée\nIntégration API complète\nMarque blanche disponible"
                    ],
                    'en' => [
                        'name' => 'Ultimate',
                        'description' => 'The complete solution for businesses and power users.',
                        'features' => "VIP access to all tools\nEarly access to new features\nMaximum priority support\nUnlimited uses\nAd-free experience\nAdvanced export options\nFull API integration\nWhite-label options"
                    ],
                    'es' => [
                        'name' => 'Ultimate',
                        'description' => 'La solución completa para empresas y usuarios avanzados.',
                        'features' => "Acceso VIP a todas las herramientas\nAcceso anticipado a nuevas funciones\nSoporte con máxima prioridad\nUsos ilimitados\nSin publicidad\nOpciones de exportación avanzadas\nIntegración completa de API\nOpciones de marca blanca"
                    ]
                ]
            ]
        ];

        // Création des packages et de leurs traductions
        foreach ($packages as $packageData) {
            $translations = $packageData['translations'];
            unset($packageData['translations']);
            
            // Créer le package
            $package = Package::create($packageData);
            
            // Ajouter les traductions
            foreach ($translations as $locale => $translationData) {
                PackageTranslation::create([
                    'package_id' => $package->id,
                    'locale' => $locale,
                    'name' => $translationData['name'],
                    'description' => $translationData['description'],
                    'features' => $translationData['features'],
                ]);
            }
            
            // Associer des outils au package
            if ($packageData['slug'] === 'starter') {
                // Pour Starter, ajouter 5 outils de base (non VIP, non AI)
                foreach ($tools->take(5) as $tool) {
                    $package->tools()->attach($tool->id, [
                        'is_vip' => false,
                        'is_ai' => false
                    ]);
                }
            } elseif ($packageData['slug'] === 'pro') {
                // Pour Pro, ajouter 10 outils (certains VIP, certains AI)
                foreach ($tools->take(10) as $index => $tool) {
                    $package->tools()->attach($tool->id, [
                        'is_vip' => $index % 3 === 0, // 1 sur 3 sont VIP
                        'is_ai' => $index % 4 === 0   // 1 sur 4 sont AI
                    ]);
                }
            } elseif ($packageData['slug'] === 'ultimate') {
                // Pour Ultimate, ajouter tous les outils (beaucoup en VIP et AI)
                foreach ($tools as $index => $tool) {
                    $package->tools()->attach($tool->id, [
                        'is_vip' => $index % 2 === 0, // 1 sur 2 sont VIP
                        'is_ai' => $index % 3 === 0   // 1 sur 3 sont AI
                    ]);
                }
            }
        }
        
        // Réactiver les clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Packages créés avec succès !');
    }
} 