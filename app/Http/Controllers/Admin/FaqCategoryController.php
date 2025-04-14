<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\FaqCategoryTranslation;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FaqCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $categories = FaqCategory::query()
            ->orderBy('order')
            ->paginate(10);

        return view('admin.faq_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $languages = Language::getActive();
        return view('admin.faq_categories.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $defaultLanguage = Language::getDefault();

        $request->validate([
            'translations.' . $defaultLanguage->id . '.name' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $category = FaqCategory::create([
            'is_active' => $request->boolean('is_active', true),
        ]);

        $languages = Language::getActive();
        
        foreach ($languages as $language) {
            if (isset($request->translations[$language->id])) {
                $translationData = $request->translations[$language->id];
                
                // Assurez-vous que le slug est basé sur le nom
                $slug = Str::slug($translationData['name'] ?? '');
                
                FaqCategoryTranslation::create([
                    'faq_category_id' => $category->id,
                    'language_id' => $language->id,
                    'name' => $translationData['name'] ?? '',
                    'description' => $translationData['description'] ?? '',
                    'slug' => $slug,
                ]);
            }
        }

        return redirect()->route('admin.faq_categories.index')
            ->with('success', 'Catégorie de FAQ ajoutée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FaqCategory $faqCategory): View
    {
        $languages = Language::getActive();
        $translations = $faqCategory->translations->keyBy('language_id');
        
        return view('admin.faq_categories.edit', compact('faqCategory', 'languages', 'translations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FaqCategory $faqCategory): RedirectResponse
    {
        $defaultLanguage = Language::getDefault();

        $request->validate([
            'translations.' . $defaultLanguage->id . '.name' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $faqCategory->update([
            'is_active' => $request->boolean('is_active', true),
        ]);

        $languages = Language::getActive();
        
        foreach ($languages as $language) {
            if (isset($request->translations[$language->id])) {
                $translationData = $request->translations[$language->id];
                
                // Assurez-vous que le slug est basé sur le nom
                $slug = Str::slug($translationData['name'] ?? '');
                
                FaqCategoryTranslation::updateOrCreate(
                    [
                        'faq_category_id' => $faqCategory->id,
                        'language_id' => $language->id,
                    ],
                    [
                        'name' => $translationData['name'] ?? '',
                        'description' => $translationData['description'] ?? '',
                        'slug' => $slug,
                    ]
                );
            }
        }

        return redirect()->route('admin.faq_categories.index')
            ->with('success', 'Catégorie de FAQ mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FaqCategory $faqCategory): RedirectResponse
    {
        $faqCategory->translations()->delete();
        $faqCategory->delete();

        return redirect()->route('admin.faq_categories.index')
            ->with('success', 'Catégorie de FAQ supprimée avec succès.');
    }

    /**
     * Update the order of FAQ categories.
     */
    public function updateOrder(Request $request): RedirectResponse
    {
        $categories = $request->input('categories', []);

        foreach ($categories as $order => $categoryId) {
            FaqCategory::where('id', $categoryId)->update(['order' => $order]);
        }

        return redirect()->route('admin.faq_categories.index')
            ->with('success', 'Ordre des catégories mis à jour avec succès.');
    }
} 