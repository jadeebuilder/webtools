<?php

namespace Database\Seeders;

use App\Models\SiteLanguage;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
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
            ],
            [
                'name' => 'English',
                'code' => 'en',
                'flag' => 'gb',
                'is_active' => true,
                'is_default' => false,
                'is_rtl' => false,
            ],
            [
                'name' => 'Español',
                'code' => 'es',
                'flag' => 'es',
                'is_active' => true,
                'is_default' => false,
                'is_rtl' => false,
            ],
            [
                'name' => 'العربية',
                'code' => 'ar',
                'flag' => 'sa',
                'is_active' => false,
                'is_default' => false,
                'is_rtl' => true,
            ],
        ];

        foreach ($languages as $language) {
            SiteLanguage::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
