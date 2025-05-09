<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            LanguageSeeder::class,
            ToolCategorySeeder::class,
            ToolSeeder::class,
            AdSettingSeeder::class,
            ToolTemplateSectionSeeder::class,
            FaqCategorySeeder::class,
            FaqSeeder::class,
            FaqCategoryTranslationsSeeder::class,
            FaqTranslationsSeeder::class,
            TestimonialsSeeder::class,
            PackageSeeder::class,
            ToolTypeSeeder::class,
        ]);

        // Seeder du système de paiement
        $this->call(PaymentSystemSeeder::class);
    }
}
