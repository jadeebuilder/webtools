<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\SiteLanguage;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display FAQs for frontend.
     */
    public function index()
    {
        $locale = app()->getLocale();
        $language = SiteLanguage::where('code', $locale)->first();
        
        $faqs = Faq::with(['category', 'translations' => function($query) use ($language) {
                    if ($language) {
                        $query->where('language_id', $language->id);
                    }
                }])
                ->active()
                ->ordered()
                ->get();
                  
        $categories = FaqCategory::active()
                ->ordered()
                ->with(['translations'])
                ->with(['faqs' => function($query) {
                    $query->active()->ordered();
                }])
                ->get();
        
        foreach ($categories as $category) {
            if (empty($category->slug)) {
                $category->slug = 'category-' . $category->id;
            }
        }
                              
        return view('faq.index', compact('faqs', 'categories', 'locale'));
    }
    
    /**
     * Display FAQs for a specific category.
     */
    public function category($slug)
    {
        $locale = app()->getLocale();
        $language = SiteLanguage::where('code', $locale)->first();
        
        // Vérifier si le slug est au format 'category-ID'
        if (preg_match('/^category-(\d+)$/', $slug, $matches)) {
            $categoryId = $matches[1];
            $category = FaqCategory::where('id', $categoryId)->active()->firstOrFail();
        } else {
            // Find by slug in translations
            $categoryByTranslation = FaqCategory::whereHas('translations', function($query) use ($slug, $language) {
                $query->where('slug', $slug);
                if ($language) {
                    $query->where('language_id', $language->id);
                }
            })->active()->first();
            
            if ($categoryByTranslation) {
                $category = $categoryByTranslation;
            } else {
                // Si aucune catégorie trouvée, chercher dans toutes les traductions indépendamment de la langue
                $category = FaqCategory::whereHas('translations', function($query) use ($slug) {
                    $query->where('slug', $slug);
                })->active()->firstOrFail();
            }
        }
        
        $category->load(['translations' => function($query) use ($language) {
            if ($language) {
                $query->where('language_id', $language->id);
            }
        }]);
        
        $faqs = Faq::where('faq_category_id', $category->id)
                ->with(['translations' => function($query) use ($language) {
                    if ($language) {
                        $query->where('language_id', $language->id);
                    }
                }])
                ->active()
                ->ordered()
                ->get();
                 
        return view('faq.category', compact('category', 'faqs', 'locale'));
    }
} 