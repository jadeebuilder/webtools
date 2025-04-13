<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Services\AdService;
use App\Services\ToolTemplateService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ToolCategory;

class ToolController extends Controller
{
    protected $adService;
    protected $templateService;

    /**
     * Constructeur du contrôleur.
     *
     * @param AdService $adService
     * @param ToolTemplateService $templateService
     */
    public function __construct(AdService $adService, ToolTemplateService $templateService)
    {
        $this->adService = $adService;
        $this->templateService = $templateService;
    }

    public function index()
    {
        return view('tools.index');
    }

    public function checker()
    {
        return view('tools.checker');
    }

    public function text()
    {
        return view('tools.text');
    }

    public function converter()
    {
        return view('tools.converter');
    }

    public function generator()
    {
        return view('tools.generator');
    }

    public function developer()
    {
        return view('tools.developer');
    }

    public function image()
    {
        return view('tools.image');
    }

    public function unit()
    {
        return view('tools.unit');
    }

    public function time()
    {
        return view('tools.time');
    }

    public function data()
    {
        return view('tools.data');
    }

    public function color()
    {
        return view('tools.color');
    }

    public function misc()
    {
        return view('tools.misc');
    }

    /**
     * Affiche les outils d'une catégorie spécifique.
     *
     * @param string $slug
     * @return View
     */
    public function category($slug)
    {
        try {
            // Debug - Journaliser les valeurs
            \Log::info('Accès à la catégorie', [
                'slug_reçu' => $slug, 
                'url' => request()->url(), 
                'path' => request()->path(),
                'url_segments' => explode('/', request()->path()),
                'locale' => app()->getLocale()
            ]);
            
            // Si le slug semble être une locale, essayer de l'extraire de l'URL
            if (in_array($slug, ['en', 'fr', 'es'])) {
                $path = request()->path();
                $segments = explode('/', $path);
                if (count($segments) >= 4) {
                    $possibleSlug = end($segments);
                    \Log::info('Tentative de correction du slug', ['ancien' => $slug, 'nouveau' => $possibleSlug]);
                    $slug = $possibleSlug;
                }
            }
            
            // Vérifier explicitement si la catégorie existe avec dump SQL
            \Log::info('Toutes les catégories disponibles', ['catégories' => \App\Models\ToolCategory::pluck('slug', 'id')->toArray()]);
            
            // Simplifier la recherche pour le diagnostic
            $category = ToolCategory::where('slug', $slug)->first();
            
            if ($category) {
                \Log::info('Catégorie trouvée directement', [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'is_active' => $category->is_active
                ]);
            } else {
                \Log::warning('Catégorie non trouvée directement', ['slug' => $slug]);
            }
            
            // Si la catégorie n'existe pas, vérifier les alternatives
            if (!$category) {
                // Créer un mapping pour les slug potentiels
                $alternativeSlug = null;
                
                if ($slug === 'generator') {
                    $alternativeSlug = 'generators';
                } elseif ($slug === 'converter') {
                    $alternativeSlug = 'converters';
                } elseif ($slug === 'generators') {
                    $alternativeSlug = 'generator';
                } elseif ($slug === 'converters') {
                    $alternativeSlug = 'converter';
                }
                
                if ($alternativeSlug) {
                    $category = ToolCategory::where('slug', $alternativeSlug)->first();
                    \Log::info('Recherche alternative', ['slug_initial' => $slug, 'slug_alternatif' => $alternativeSlug, 'trouvé' => ($category !== null)]);
                }
            }
            
            // Si la catégorie n'est toujours pas trouvée, retourner une erreur 404
            if (!$category) {
                \Log::error('Catégorie non trouvée après alternatives', ['slug' => $slug]);
                return response()->view('errors.404', ['message' => "La catégorie '$slug' n'existe pas."], 404);
            }
            
            // Journaliser les informations de la catégorie trouvée
            \Log::info('Catégorie trouvée', [
                'id' => $category->id,
                'slug' => $category->slug,
                'is_active' => $category->is_active
            ]);
            
            $locale = app()->getLocale();
            
            // Récupérer les outils (uniquement si la catégorie est active)
            if ($category->is_active) {
                $tools = Tool::where('tool_category_id', $category->id)
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get();
            } else {
                $tools = collect();
                \Log::warning('Catégorie inactive', ['id' => $category->id, 'slug' => $category->slug]);
            }
            
            // SEO et contenu
            $pageTitle = $category->getMetaTitle($locale);
            $metaDescription = $category->getMetaDescription($locale);
            
            // Journaliser les informations SEO
            \Log::info('Informations SEO de la catégorie', [
                'pageTitle' => $pageTitle,
                'metaDescription' => $metaDescription,
                'locale' => $locale,
                'category_id' => $category->id
            ]);
            
            // Publicités
            $adSettings = $this->adService->getAdsForPage('category');
            
            return view('tools.category', [
                'category' => $category,
                'tools' => $tools,
                'pageTitle' => $pageTitle,
                'metaDescription' => $metaDescription,
                'adSettings' => $adSettings,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur dans la méthode category', [
                'slug' => $slug,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->view('errors.500', [
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un outil spécifique.
     *
     * @param string $slug
     * @return View
     */
    public function show($slug)
    {
        $tool = Tool::where('slug', $slug)->firstOrFail();
        $locale = app()->getLocale();
        
        $translation = $tool->getTranslation($locale);
        
        // SEO et contenu
        $pageTitle = $translation ? $translation->getMetaTitle() : $tool->getName();
        $metaDescription = $translation ? $translation->getMetaDescription() : '';
        $customH1 = $translation ? $translation->getCustomH1() : $tool->getName();
        $customDescription = $translation ? $translation->getCustomDescription() : '';
        
        // Publicités
        $adSettings = $this->adService->getAdsForPage('tool');
        
        // Générer les sections supplémentaires pour cet outil
        $additionalSections = $this->templateService->renderToolSections($tool);
        
        return view('tools.show', [
            'tool' => $tool,
            'slug' => $slug,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'customH1' => $customH1,
            'customDescription' => $customDescription,
            'adSettings' => $adSettings,
            'additionalSections' => $additionalSections,
        ]);
    }
}
