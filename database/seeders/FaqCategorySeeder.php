<?php

namespace Database\Seeders;

use App\Models\FaqCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FaqCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Informations générales',
                'icon' => 'fas fa-info-circle',
                'description' => 'Informations de base sur notre plateforme et nos services',
                'order' => 1,
            ],
            [
                'name' => 'Compte et utilisateur',
                'icon' => 'fas fa-user',
                'description' => 'Questions concernant la gestion de votre compte et vos données utilisateur',
                'order' => 2,
            ],
            [
                'name' => 'Utilisation des outils',
                'icon' => 'fas fa-tools',
                'description' => 'Comment utiliser efficacement les outils disponibles sur notre plateforme',
                'order' => 3,
            ],
            [
                'name' => 'Facturation et abonnements',
                'icon' => 'fas fa-credit-card',
                'description' => 'Questions relatives aux paiements, abonnements et facturation',
                'order' => 4,
            ],
            [
                'name' => 'Support technique',
                'icon' => 'fas fa-headset',
                'description' => 'Assistance technique et résolution des problèmes courants',
                'order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            FaqCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
                'description' => $category['description'],
                'is_active' => true,
                'order' => $category['order'],
            ]);
        }
    }
} 