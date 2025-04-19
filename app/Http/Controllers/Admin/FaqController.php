<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\FaqTranslation;
use App\Models\SiteLanguage;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::with('category', 'translations')->orderBy('order')->get();
        
        return view('admin.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = FaqCategory::active()->orderBy('name')->get();
        $languages = SiteLanguage::where('is_active', true)->get();
        
        return view('admin.faq.create', compact('categories', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'is_active' => 'required|in:0,1',
            'order' => 'nullable|integer|min:0',
            'translations' => 'required|array',
            'translations.*.language_id' => 'required|exists:site_languages,id',
            'translations.*.question' => 'required|string',
            'translations.*.answer' => 'required|string',
        ]);
        
        // Vérifier qu'au moins la langue par défaut est fournie
        $defaultLanguage = SiteLanguage::getDefault();
        $hasDefaultTranslation = false;
        
        if (isset($validated['translations'])) {
            foreach ($validated['translations'] as $translation) {
                if ($translation['language_id'] == $defaultLanguage->id && 
                    !empty($translation['question']) && 
                    !empty($translation['answer'])) {
                    $hasDefaultTranslation = true;
                    break;
                }
            }
        }
        
        if (!$hasDefaultTranslation) {
            return back()->withErrors(['translations' => __('La traduction dans la langue par défaut est requise.')]);
        }
        
        // Préparer les données pour la création de la FAQ
        $faqData = [
            'faq_category_id' => $validated['faq_category_id'],
            'is_active' => (bool) $validated['is_active'],
            'order' => $validated['order'] ?? 0,
            // Utiliser les valeurs de la langue par défaut pour les champs de base
            'question' => $validated['translations'][$defaultLanguage->id]['question'] ?? '',
            'answer' => $validated['translations'][$defaultLanguage->id]['answer'] ?? '',
        ];
        
        $faq = Faq::create($faqData);
        
        // Créer les traductions
        if (isset($validated['translations'])) {
            foreach ($validated['translations'] as $langId => $translation) {
                if (!empty($translation['question']) || !empty($translation['answer'])) {
                    $faq->translations()->create([
                        'language_id' => $translation['language_id'],
                        'question' => $translation['question'],
                        'answer' => $translation['answer'],
                    ]);
                }
            }
        }
        
        return redirect()
            ->route('admin.faq.index', ['locale' => app()->getLocale()])
            ->with('success', __('FAQ créée avec succès.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        $categories = FaqCategory::active()->orderBy('name')->get();
        $languages = SiteLanguage::where('is_active', true)->get();
        $faq->load('translations');
        
        // Format translations for easier handling in the view
        $translations = [];
        foreach ($faq->translations as $translation) {
            $translations[$translation->language_id] = $translation;
        }
        
        return view('admin.faq.edit', compact('faq', 'categories', 'languages', 'translations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'is_active' => 'required|in:0,1',
            'order' => 'nullable|integer|min:0',
            'translations' => 'required|array',
            'translations.*.language_id' => 'required|exists:site_languages,id',
            'translations.*.question' => 'required|string',
            'translations.*.answer' => 'required|string',
        ]);
        
        // Vérifier qu'au moins la langue par défaut est fournie
        $defaultLanguage = SiteLanguage::getDefault();
        $hasDefaultTranslation = false;
        
        if (isset($validated['translations'])) {
            foreach ($validated['translations'] as $translation) {
                if ($translation['language_id'] == $defaultLanguage->id && 
                    !empty($translation['question']) && 
                    !empty($translation['answer'])) {
                    $hasDefaultTranslation = true;
                    break;
                }
            }
        }
        
        if (!$hasDefaultTranslation) {
            return back()->withErrors(['translations' => __('La traduction dans la langue par défaut est requise.')]);
        }
        
        // Mettre à jour les données de base de la FAQ
        $faqData = [
            'faq_category_id' => $validated['faq_category_id'],
            'is_active' => (bool) $validated['is_active'],
            'order' => $validated['order'] ?? 0,
            // Utiliser les valeurs de la langue par défaut pour les champs de base
            'question' => $validated['translations'][$defaultLanguage->id]['question'] ?? $faq->question,
            'answer' => $validated['translations'][$defaultLanguage->id]['answer'] ?? $faq->answer,
        ];
        
        $faq->update($faqData);
        
        // Mettre à jour les traductions
        if (isset($validated['translations'])) {
            foreach ($validated['translations'] as $langId => $translationData) {
                if (!empty($translationData['question']) || !empty($translationData['answer'])) {
                    $translation = $faq->translations()
                        ->where('language_id', $translationData['language_id'])
                        ->first();
                    
                    if ($translation) {
                        $translation->update([
                            'question' => $translationData['question'],
                            'answer' => $translationData['answer'],
                        ]);
                    } else {
                        $faq->translations()->create([
                            'language_id' => $translationData['language_id'],
                            'question' => $translationData['question'],
                            'answer' => $translationData['answer'],
                        ]);
                    }
                }
            }
        }
        
        return redirect()
            ->route('admin.faq.index', ['locale' => app()->getLocale()])
            ->with('success', __('FAQ mise à jour avec succès.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        
        return redirect()
            ->route('admin.faq.index', ['locale' => app()->getLocale()])
            ->with('success', __('FAQ supprimée avec succès.'));
    }
    
    /**
     * Update the order of FAQs.
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:faqs,id',
            'orders.*.order' => 'required|integer|min:0',
        ]);
        
        foreach ($validated['orders'] as $item) {
            Faq::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        
        return response()->json(['success' => true]);
    }
} 