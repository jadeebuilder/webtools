<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Services\AdService;
use App\Services\ToolTemplateService;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
