<?php

namespace Database\Seeders;

use App\Models\AdSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des exemples de publicités pour chaque position
        $positions = [
            'before_nav', 'after_nav', 'before_tool_title', 'after_tool_description',
            'before_tool', 'after_tool', 'left_sidebar', 'right_sidebar',
            'bottom_tool', 'before_footer', 'after_footer'
        ];

        foreach ($positions as $position) {
            // Exemple avec une image
            AdSetting::create([
                'position' => $position,
                'active' => false, // Désactivé par défaut
                'type' => 'image',
                'image' => 'images/ads/sample-ad-' . $position . '.jpg',
                'link' => 'https://example.com',
                'alt' => 'Sample advertisement',
                'display_on' => ['home', 'tool', 'category'],
                'priority' => 10,
            ]);

            // Exemple avec AdSense
            AdSetting::create([
                'position' => $position,
                'active' => false, // Désactivé par défaut
                'type' => 'adsense',
                'code' => '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX" crossorigin="anonymous"></script>
                         <ins class="adsbygoogle"
                              style="display:block"
                              data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                              data-ad-slot="XXXXXXXXXX"
                              data-ad-format="auto"
                              data-full-width-responsive="true"></ins>
                         <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>',
                'display_on' => ['home', 'tool', 'category'],
                'priority' => 20,
            ]);
        }
    }
}
