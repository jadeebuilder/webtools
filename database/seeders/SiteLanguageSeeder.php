<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'name' => 'Français',
                'code' => 'fr',
                'flag' => 'fr',
                'is_active' => true,
                'is_default' => true,
                'is_rtl' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'English',
                'code' => 'en',
                'flag' => 'gb',
                'is_active' => true,
                'is_default' => false,
                'is_rtl' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Español',
                'code' => 'es',
                'flag' => 'es',
                'is_active' => true,
                'is_default' => false,
                'is_rtl' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'العربية',
                'code' => 'ar',
                'flag' => 'sa',
                'is_active' => false,
                'is_default' => false,
                'is_rtl' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Tronquer la table pour éviter les doublons
        DB::table('site_languages')->truncate();

        // Insérer les langues
        DB::table('site_languages')->insert($languages);
    }
} 