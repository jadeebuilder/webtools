<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\Tool;
use App\Models\Post;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère le sitemap XML du site pour toutes les langues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(__('Génération des sitemaps...'));

        // Récupérer les paramètres du sitemap depuis la base de données
        $settings = Setting::getByGroup('sitemap');
        $includeTools = isset($settings['sitemap_include_tools']) && $settings['sitemap_include_tools'] == 1;
        $includeBlog = isset($settings['sitemap_include_blog']) && $settings['sitemap_include_blog'] == 1;
        $frequency = $settings['sitemap_frequency'] ?? 'weekly';
        $priority = (float)($settings['sitemap_priority'] ?? 0.8);

        // Récupérer toutes les langues disponibles
        $availableLocales = config('app.available_locales', ['fr' => 'Français']);

        // Créer un SitemapIndex pour regrouper tous les sitemaps par langue
        $sitemapIndex = SitemapIndex::create();
        
        // Générer un sitemap pour chaque langue
        foreach (array_keys($availableLocales) as $locale) {
            $this->info(__('Génération du sitemap pour la langue:') . " {$locale}");
            
            // Initialiser le sitemap pour cette langue
            $sitemap = Sitemap::create();
            
            // Ajouter les pages statiques pour cette langue
            $this->addStaticPages($sitemap, $locale, $frequency, $priority);
            
            // Ajouter les outils et les articles de blog si demandé
            if ($includeTools) {
                $this->addToolsToSitemap($sitemap, $locale, $frequency, $priority * 0.9);
            }
            
            if ($includeBlog) {
                $this->addBlogToSitemap($sitemap, $locale, $frequency, $priority * 0.8);
            }
            
            // Sauvegarder le sitemap de cette langue
            $sitemapPath = "sitemap-{$locale}.xml";
            $sitemap->writeToFile(public_path($sitemapPath));
            
            // Ajouter ce sitemap à l'index
            $sitemapIndex->add(url($sitemapPath));
            
            $this->info(str_replace('{$locale}', $locale, __('Sitemap pour {$locale} généré avec succès!')));
        }
        
        // Sauvegarder l'index des sitemaps
        $sitemapIndex->writeToFile(public_path('sitemap.xml'));
        
        // Mettre à jour la date de dernière génération
        Setting::set('sitemap_last_generated', now()->toDateTimeString(), 'sitemap', true, false);
        
        $this->info(__('Tous les sitemaps ont été générés avec succès!'));
        
        return Command::SUCCESS;
    }

    /**
     * Ajouter les pages statiques au sitemap
     */
    private function addStaticPages(Sitemap $sitemap, string $locale, string $frequency, float $priority)
    {
        // Liste des pages statiques avec leurs priorités relatives
        $staticPages = [
            '' => 1.0, // Page d'accueil (priorité maximale)
            'tools' => 0.9,
            'tools/text' => 0.8,
            'tools/converter' => 0.8,
            'tools/generator' => 0.8,
            'tools/developer' => 0.8,
            'tools/image' => 0.8,
            'tools/checker' => 0.8,
            'tools/unit' => 0.8,
            'tools/time' => 0.8,
            'tools/data' => 0.8,
            'tools/color' => 0.8,
            'tools/misc' => 0.8,
            'about' => 0.7,
            'terms' => 0.6,
            'privacy' => 0.6,
            'cookies' => 0.6,
        ];

        foreach ($staticPages as $page => $relativePriority) {
            $url = empty($page) ? url("{$locale}") : url("{$locale}/{$page}");
            
            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate(now())
                    ->setChangeFrequency($frequency)
                    ->setPriority($priority * $relativePriority)
            );
        }
    }

    /**
     * Ajouter les outils au sitemap
     */
    private function addToolsToSitemap(Sitemap $sitemap, string $locale, string $frequency, float $priority)
    {
        // Récupérer tous les outils actifs
        $tools = Tool::where('is_active', true)->get();
        
        foreach ($tools as $tool) {
            $url = url("{$locale}/tool/{$tool->slug}");
            
            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate($tool->updated_at)
                    ->setChangeFrequency($frequency)
                    ->setPriority($priority)
            );
        }
    }

    /**
     * Ajouter les articles de blog au sitemap
     */
    private function addBlogToSitemap(Sitemap $sitemap, string $locale, string $frequency, float $priority)
    {
        // Vérifier si le modèle Post existe
        if (class_exists(Post::class)) {
            // Récupérer tous les articles de blog publiés
            $posts = Post::where('is_published', true)->get();
            
            foreach ($posts as $post) {
                $url = url("{$locale}/blog/{$post->slug}");
                
                $sitemap->add(
                    Url::create($url)
                        ->setLastModificationDate($post->updated_at)
                        ->setChangeFrequency($frequency)
                        ->setPriority($priority)
                );
            }
        }
    }
} 