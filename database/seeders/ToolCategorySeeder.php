<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ToolCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Catégories à ajouter
        $categories = [
            [
                'slug' => 'checker',
                'icon' => 'fa-solid fa-check-circle',
                'is_active' => 1,
                'order' => 0,
                'translations' => [
                    'fr' => [
                        'name' => 'Outils de vérification',
                        'description' => 'Une collection d\'excellents outils de vérification pour vous aider à contrôler et vérifier différents types de données.'
                    ],
                    'en' => [
                        'name' => 'Checker tools',
                        'description' => 'A collection of great checker-type tools to help you check & verify different types of things.'
                    ],
                    'es' => [
                        'name' => 'Herramientas de verificación',
                        'description' => 'Una colección de excelentes herramientas de verificación para ayudarte a verificar diferentes tipos de datos.'
                    ]
                ]
            ],
            [
                'slug' => 'developer',
                'icon' => 'fa-solid fa-code',
                'is_active' => 1,
                'order' => 4,
                'translations' => [
                    'fr' => [
                        'name' => 'Outils de développement',
                        'description' => 'Une collection d\'outils très utiles principalement pour les développeurs et pas seulement.'
                    ],
                    'en' => [
                        'name' => 'Developer tools',
                        'description' => 'A collection of highly useful tools mainly for developers and not only.'
                    ],
                    'es' => [
                        'name' => 'Herramientas para desarrolladores',
                        'description' => 'Una colección de herramientas muy útiles principalmente para desarrolladores y no solo.'
                    ]
                ]
            ],
            [
                'slug' => 'image',
                'icon' => 'fa-solid fa-image',
                'is_active' => 1,
                'order' => 5,
                'translations' => [
                    'fr' => [
                        'name' => 'Outils d\'image',
                        'description' => 'Une collection d\'outils qui aident à modifier et convertir des fichiers image.'
                    ],
                    'en' => [
                        'name' => 'Image tools',
                        'description' => 'A collection of tools that help modify & convert image files.'
                    ],
                    'es' => [
                        'name' => 'Herramientas de imagen',
                        'description' => 'Una colección de herramientas que ayudan a modificar y convertir archivos de imagen.'
                    ]
                ]
            ],
            [
                'slug' => 'unit',
                'icon' => 'fa-solid fa-ruler',
                'is_active' => 1,
                'order' => 6,
                'translations' => [
                    'fr' => [
                        'name' => 'Convertisseurs d\'unités',
                        'description' => 'Une collection des outils les plus populaires et utiles qui vous aident à convertir facilement des données quotidiennes.'
                    ],
                    'en' => [
                        'name' => 'Unit converter tools',
                        'description' => 'A collection of the most popular and useful tools that help you easily convert day-to-day data.'
                    ],
                    'es' => [
                        'name' => 'Convertidores de unidades',
                        'description' => 'Una colección de las herramientas más populares y útiles que te ayudan a convertir fácilmente datos cotidianos.'
                    ]
                ]
            ],
            [
                'slug' => 'time',
                'icon' => 'fa-solid fa-clock',
                'is_active' => 1,
                'order' => 7,
                'translations' => [
                    'fr' => [
                        'name' => 'Convertisseurs de temps',
                        'description' => 'Une collection d\'outils liés à la conversion de date et d\'heure.'
                    ],
                    'en' => [
                        'name' => 'Time converter tools',
                        'description' => 'A collection of date & time conversion related tools.'
                    ],
                    'es' => [
                        'name' => 'Convertidores de tiempo',
                        'description' => 'Una colección de herramientas relacionadas con la conversión de fecha y hora.'
                    ]
                ]
            ],
            [
                'slug' => 'data',
                'icon' => 'fa-solid fa-database',
                'is_active' => 1,
                'order' => 8,
                'translations' => [
                    'fr' => [
                        'name' => 'Convertisseurs de données',
                        'description' => 'Une collection d\'outils de conversion de données informatiques et de dimensionnement.'
                    ],
                    'en' => [
                        'name' => 'Data converter tools',
                        'description' => 'A collection of computer data & sizing converter tools.'
                    ],
                    'es' => [
                        'name' => 'Convertidores de datos',
                        'description' => 'Una colección de herramientas de conversión de datos informáticos y dimensionamiento.'
                    ]
                ]
            ],
            [
                'slug' => 'color',
                'icon' => 'fa-solid fa-palette',
                'is_active' => 1,
                'order' => 9,
                'translations' => [
                    'fr' => [
                        'name' => 'Convertisseurs de couleurs',
                        'description' => 'Une collection d\'outils qui aident à convertir les couleurs entre les formats HEX, RGBA, RGB, HSLA, HSL, etc.'
                    ],
                    'en' => [
                        'name' => 'Color converter tools',
                        'description' => 'A collection of tools that help convert colors between HEX, RGBA, RGB, HSLA, HSL, etc. formats.'
                    ],
                    'es' => [
                        'name' => 'Convertidores de color',
                        'description' => 'Una colección de herramientas que ayudan a convertir colores entre formatos HEX, RGBA, RGB, HSLA, HSL, etc.'
                    ]
                ]
            ],
            [
                'slug' => 'misc',
                'icon' => 'fa-solid fa-toolbox',
                'is_active' => 1,
                'order' => 10,
                'translations' => [
                    'fr' => [
                        'name' => 'Outils divers',
                        'description' => 'Une collection d\'autres outils aléatoires, mais excellents et utiles.'
                    ],
                    'en' => [
                        'name' => 'Misc tools',
                        'description' => 'A collection of other random, but great & useful tools.'
                    ],
                    'es' => [
                        'name' => 'Herramientas diversas',
                        'description' => 'Una colección de otras herramientas aleatorias, pero excelentes y útiles.'
                    ]
                ]
            ],
            [
                'slug' => 'converter',
                'icon' => 'fa-solid fa-exchange-alt',
                'is_active' => 1,
                'order' => 2,
                'translations' => [
                    'fr' => [
                        'name' => 'Outils de conversion',
                        'description' => 'Une collection d\'outils qui vous aident à convertir facilement des données.'
                    ],
                    'en' => [
                        'name' => 'Converter tools',
                        'description' => 'A collection of tools that help you easily convert data.'
                    ],
                    'es' => [
                        'name' => 'Herramientas de conversión',
                        'description' => 'Una colección de herramientas que te ayudan a convertir datos fácilmente.'
                    ]
                ]
            ]
        ];

        // Insérer les catégories et leurs traductions
        foreach ($categories as $category) {
            // Vérifier si la catégorie existe déjà
            $exists = DB::table('tool_categories')->where('slug', $category['slug'])->exists();
            
            if (!$exists) {
                // Insérer la catégorie
                $categoryId = DB::table('tool_categories')->insertGetId([
                    'slug' => $category['slug'],
                    'icon' => $category['icon'],
                    'is_active' => $category['is_active'],
                    'order' => $category['order'],
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                
                // Insérer les traductions
                foreach ($category['translations'] as $locale => $translation) {
                    // Préparer les métadonnées SEO
                    $metaTitle = "{$translation['name']} - WebTools | Outils web en ligne";
                    
                    switch ($locale) {
                        case 'fr':
                            $metaDescription = "Découvrez nos {$translation['name']}. {$translation['description']} Utilisez-les gratuitement en ligne sans inscription.";
                            break;
                        case 'en':
                            $metaDescription = "Discover our {$translation['name']}. {$translation['description']} Use them online for free without registration.";
                            break;
                        case 'es':
                            $metaDescription = "Descubre nuestras {$translation['name']}. {$translation['description']} Utilízalas en línea gratis sin registro.";
                            break;
                        default:
                            $metaDescription = $translation['description'];
                    }
                    
                    DB::table('tool_category_translations')->insert([
                        'tool_category_id' => $categoryId,
                        'locale' => $locale,
                        'name' => $translation['name'],
                        'description' => $translation['description'],
                        'meta_title' => $metaTitle,
                        'meta_description' => $metaDescription,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                }
            }
        }
    }
}
