<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tools\CaseConverterRequest;
use App\Models\Tool;
use App\Services\Tools\CaseConverterProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseConverterController extends Controller
{
    /**
     * Instance du processeur de convertisseur de casse.
     *
     * @var CaseConverterProcessor
     */
    protected $processor;

    /**
     * Créer une nouvelle instance du contrôleur.
     *
     * @param CaseConverterProcessor $processor
     * @return void
     */
    public function __construct(CaseConverterProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Afficher la page du convertisseur de casse.
     *
     * @param Request $request
     * @return View
     */
    public function show(Request $request): View
    {
        $tool = Tool::where('slug', 'case-converter')->first();
        $locale = app()->getLocale();
        
        // Si l'outil n'existe pas, on pourrait rediriger ou montrer une erreur
        // Dans cet exemple, nous continuons avec des valeurs par défaut
        
        $translation = $tool ? $tool->getTranslation($locale) : null;
        
        // SEO et contenu
        $pageTitle = $translation ? $translation->getMetaTitle() : __('tools.case_converter.title');
        $metaDescription = $translation ? $translation->getMetaDescription() : __('tools.case_converter.description');
        $customH1 = $translation ? $translation->getCustomH1() : __('tools.case_converter.h1');
        $customDescription = $translation ? $translation->getCustomDescription() : __('tools.case_converter.description');
        
        return view('tools.case-converter', [
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'customH1' => $customH1,
            'customDescription' => $customDescription,
            'conversionTypes' => [
                CaseConverterProcessor::LOWERCASE => __('tools.case_converter.types.lowercase'),
                CaseConverterProcessor::UPPERCASE => __('tools.case_converter.types.uppercase'),
                CaseConverterProcessor::TITLE_CASE => __('tools.case_converter.types.title_case'),
                CaseConverterProcessor::SENTENCE_CASE => __('tools.case_converter.types.sentence_case'),
                CaseConverterProcessor::CAPITALIZE => __('tools.case_converter.types.capitalize'),
                CaseConverterProcessor::ALTERNATING_CASE => __('tools.case_converter.types.alternating_case'),
                CaseConverterProcessor::INVERSE_CASE => __('tools.case_converter.types.inverse_case'),
                CaseConverterProcessor::PASCAL_CASE => __('tools.case_converter.types.pascal_case'),
                CaseConverterProcessor::CAMEL_CASE => __('tools.case_converter.types.camel_case'),
                CaseConverterProcessor::SNAKE_CASE => __('tools.case_converter.types.snake_case'),
                CaseConverterProcessor::KEBAB_CASE => __('tools.case_converter.types.kebab_case'),
            ],
        ]);
    }

    /**
     * Traiter la requête du convertisseur de casse.
     *
     * @param CaseConverterRequest $request
     * @return JsonResponse
     */
    public function process(CaseConverterRequest $request): JsonResponse
    {
        $result = $this->processor->process($request->validated());
        
        return response()->json($result);
    }
}
