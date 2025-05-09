<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Tools\CaseConverterController;
use App\Http\Controllers\Admin\AdSettingController;
use App\Http\Controllers\Admin\ToolController as AdminToolController;
use App\Http\Controllers\Admin\ToolTemplateController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\TemplateSectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ToolCategoryController;
use App\Http\Controllers\Admin\ToolAdSettingController;
use App\Http\Controllers\Admin\AdTestController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdBlockController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeFaqController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\Admin\PaymentMethodController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route pour rediriger automatiquement vers la langue par défaut
Route::get('/', function () {
    return redirect('/' . app()->getLocale());
});

// Routes localisées avec préfixe de langue
Route::prefix('{locale}')
    ->where(['locale' => '[a-zA-Z]{2}'])
    ->middleware('localize')
    ->group(function () {
        // Page d'accueil
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Routes pour les différentes catégories d'outils
        Route::prefix('tools')->group(function () {
            Route::get('/', [ToolController::class, 'index'])->name('tools.index');
            Route::get('checker', [ToolController::class, 'checker'])->name('tools.checker');
            Route::get('text', [ToolController::class, 'text'])->name('tools.text');
            Route::get('converter', [ToolController::class, 'converter'])->name('tools.converter');
            Route::get('generator', [ToolController::class, 'generator'])->name('tools.generator');
            Route::get('developer', [ToolController::class, 'developer'])->name('tools.developer');
            Route::get('image', [ToolController::class, 'image'])->name('tools.image');
            Route::get('unit', [ToolController::class, 'unit'])->name('tools.unit');
            Route::get('time', [ToolController::class, 'time'])->name('tools.time');
            Route::get('data', [ToolController::class, 'data'])->name('tools.data');
            Route::get('color', [ToolController::class, 'color'])->name('tools.color');
            Route::get('misc', [ToolController::class, 'misc'])->name('tools.misc');
            Route::get('category/{slug}', [ToolController::class, 'category'])->name('tools.category');
        });

        // Route pour l'utilisation d'un outil spécifique
        Route::get('tool/{slug}', [ToolController::class, 'show'])->name('tool.show');

        // Routes pour les outils spécifiques
        Route::prefix('tools')->group(function () {
            // Case Converter
            Route::get('case-converter', [CaseConverterController::class, 'show'])->name('tools.case-converter');
            Route::post('case-converter/process', [CaseConverterController::class, 'process'])->name('tools.case-converter.process');
        });

        // Dashboard et routes authentifiées
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');

        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            
            // Nouvelles routes pour le menu utilisateur
            Route::get('/account', [UserController::class, 'account'])->name('user.account');
            Route::get('/settings', [UserController::class, 'settings'])->name('user.settings');
            Route::get('/packages', [UserController::class, 'packages'])->name('user.packages');
            Route::get('/history', [UserController::class, 'history'])->name('user.history');
        });

        // Routes pour l'administration
        Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        });

        // Routes pour les pages du footer
        Route::get('/about', function () {
            return view('pages.about');
        })->name('about');

        Route::get('/terms', function () {
            return view('pages.terms');
        })->name('terms');

        Route::get('/privacy', function () {
            return view('pages.privacy');
        })->name('privacy');

        Route::get('/cookies', function () {
            return view('pages.cookies');
        })->name('cookies');

        // Routes d'administration
        Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
            // Dashboard
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            
            // Gestion des packages
            Route::get('packages', [AdminPackageController::class, 'index'])->name('packages.index');
            Route::get('packages/create', [AdminPackageController::class, 'create'])->name('packages.create');
            Route::post('packages', [AdminPackageController::class, 'store'])->name('packages.store');
            Route::get('packages/{id}', [AdminPackageController::class, 'show'])->name('packages.show');
            Route::get('packages/{id}/edit', [AdminPackageController::class, 'edit'])->name('packages.edit');
            Route::put('packages/{id}', [AdminPackageController::class, 'update'])->name('packages.update');
            Route::delete('packages/{id}', [AdminPackageController::class, 'destroy'])->name('packages.destroy');
            Route::post('packages/generate-slug', [AdminPackageController::class, 'generateSlug'])->name('packages.generate-slug');
            Route::put('packages/{id}/toggle-status', [AdminPackageController::class, 'toggleStatus'])->name('packages.toggle-status');
            Route::get('packages-configuration', [AdminPackageController::class, 'configuration'])->name('packages.configuration');
            Route::put('packages-configuration', [AdminPackageController::class, 'updateConfiguration'])->name('packages.configuration.update');
            
            // Gestion des types d'outils
            Route::get('tool-types', [App\Http\Controllers\Admin\ToolTypeController::class, 'index'])->name('tool-types.index');
            Route::get('tool-types/create', [App\Http\Controllers\Admin\ToolTypeController::class, 'create'])->name('tool-types.create');
            Route::post('tool-types', [App\Http\Controllers\Admin\ToolTypeController::class, 'store'])->name('tool-types.store');
            Route::get('tool-types/{id}/edit', [App\Http\Controllers\Admin\ToolTypeController::class, 'edit'])->name('tool-types.edit');
            Route::put('tool-types/{id}', [App\Http\Controllers\Admin\ToolTypeController::class, 'update'])->name('tool-types.update');
            Route::delete('tool-types/{id}', [App\Http\Controllers\Admin\ToolTypeController::class, 'destroy'])->name('tool-types.destroy');
            Route::post('tool-types/{id}/toggle-status', [App\Http\Controllers\Admin\ToolTypeController::class, 'toggleStatus'])->name('tool-types.toggle-status');
            
            // Route de test pour le déboggage
            Route::get('/ads-test', function() {
                return response()->json(['success' => true, 'message' => 'Route de test fonctionnelle']);
            })->name('ads.test');
            
            // Routes des publicités définies manuellement plutôt qu'avec Route::resource
            // Créé des routes explicites avec un préfixe pour les regrouper clairement
            Route::prefix('ads')->name('ads.')->group(function () {
                // Routes sans paramètre (doivent être définies avant les routes avec paramètres)
                Route::get('/', [AdSettingController::class, 'index'])->name('index');
                Route::post('/', [AdSettingController::class, 'store'])->name('store');
                Route::get('/create', [AdSettingController::class, 'create'])->name('create');
                Route::get('/global-settings', [AdSettingController::class, 'globalSettings'])->name('global-settings');
                Route::post('/global-settings', [AdSettingController::class, 'updateGlobalSettings'])->name('global-settings.update');
                
                // Page de test des publicités
                Route::get('/test', [AdTestController::class, 'index'])->name('test');
                Route::get('/test/repair', [AdTestController::class, 'repairAds'])->name('test.repair');
                
                // Définir les routes avec des noms de paramètres plus explicites
                Route::get('/edit/{ad_id}', [AdSettingController::class, 'edit'])->name('edit')->where('ad_id', '[0-9]+');
                Route::put('/update/{ad_id}', [AdSettingController::class, 'update'])->name('update')->where('ad_id', '[0-9]+');
                Route::delete('/delete/{ad_id}', [AdSettingController::class, 'destroy'])->name('destroy')->where('ad_id', '[0-9]+');
                Route::put('/toggle/{ad_id}', [AdSettingController::class, 'toggle'])->name('toggle')->where('ad_id', '[0-9]+');
                
                // Garder cette route pour rétrocompatibilité
                Route::get('/{id}', [AdSettingController::class, 'show'])->name('show')->where('id', '[0-9]+');
            });
            
            // Routes pour la détection d'AdBlock
            Route::prefix('adblock')->name('adblock.')->group(function () {
                Route::get('/', [AdBlockController::class, 'index'])->name('index');
                Route::put('/', [AdBlockController::class, 'update'])->name('update');
                Route::get('/test', [AdBlockController::class, 'test'])->name('test');
            });
            
            // Gestion des templates d'outils
            Route::get('templates', [TemplateController::class, 'index'])->name('templates.index');
            Route::get('templates/{tool}/edit', [TemplateController::class, 'edit'])->name('templates.edit');
            Route::post('templates/{tool}', [TemplateController::class, 'update'])->name('templates.update');
            
            // Gestion des sections de templates
            Route::get('templates/sections', [TemplateSectionController::class, 'index'])->name('templates.sections.index');
            Route::get('templates/sections/create', [TemplateSectionController::class, 'create'])->name('templates.sections.create');
            Route::post('templates/sections', [TemplateSectionController::class, 'store'])->name('templates.sections.store');
            Route::get('templates/sections/{section}/edit', [TemplateSectionController::class, 'edit'])->name('templates.sections.edit');
            Route::put('templates/sections/{section}', [TemplateSectionController::class, 'update'])->name('templates.sections.update');
            Route::delete('templates/sections/{section}', [TemplateSectionController::class, 'destroy'])->name('templates.sections.destroy');
            Route::put('templates/sections/{section}/toggle', [TemplateSectionController::class, 'toggle'])->name('templates.sections.toggle');
            
            // Gestion des outils
            Route::resource('tools', AdminToolController::class)->except(['update', 'destroy']);
            Route::put('tools/{tool}', [AdminToolController::class, 'update'])->name('tools.update');
            Route::delete('tools/{tool}', [AdminToolController::class, 'destroy'])->name('tools.destroy');
            Route::post('tools/generate-slug', [AdminToolController::class, 'generateSlug'])->name('tools.generate-slug');
            Route::put('tools/{tool}/toggle-status', [AdminToolController::class, 'toggleStatus'])->name('tools.toggle-status');
            
            // Configuration des publicités par outil
            Route::get('tools/{tool}/ads', [ToolAdSettingController::class, 'edit'])->name('tools.ads.edit');
            Route::put('tools/{tool}/ads', [ToolAdSettingController::class, 'update'])->name('tools.ads.update');
            
            // Gestion des catégories d'outils
            Route::resource('tool-categories', ToolCategoryController::class);
            Route::post('tool-categories/{category}/toggle', [ToolCategoryController::class, 'toggleStatus'])->name('tool-categories.toggle');
            
            // Configuration générale
            Route::get('settings/general', [SettingController::class, 'general'])->name('settings.general');
            Route::post('settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');
            Route::get('settings/maintenance', [SettingController::class, 'maintenance'])->name('settings.maintenance');
            Route::post('settings/maintenance', [SettingController::class, 'updateMaintenance'])->name('settings.maintenance.update');
            Route::get('settings/sitemap', [SettingController::class, 'sitemap'])->name('settings.sitemap');
            Route::post('settings/sitemap', [SettingController::class, 'updateSitemap'])->name('settings.sitemap.update');
            Route::get('settings/company', [SettingController::class, 'company'])->name('settings.company');
            Route::post('settings/company', [SettingController::class, 'updateCompany'])->name('settings.company.update');
            Route::get('settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');
            Route::get('settings/reset-defaults', [SettingController::class, 'resetToDefaults'])->name('settings.reset-defaults');
            Route::get('settings/maintenance-template', function() {
                return response()->download(resource_path('views/errors/maintenance.blade.php'));
            })->name('settings.maintenance-template');

            // Gestion des paiements
            Route::prefix('payments')->name('payments.')->group(function () {
                // Gestion des devises
                Route::resource('currencies', App\Http\Controllers\Admin\CurrencyController::class);
                Route::put('currencies/{currency}/toggle', [App\Http\Controllers\Admin\CurrencyController::class, 'toggleStatus'])->name('currencies.toggle');
                Route::put('currencies/{currency}/set-default', [App\Http\Controllers\Admin\CurrencyController::class, 'setDefault'])->name('currencies.set-default');
                
                // Méthodes de paiement
                Route::resource('methods', App\Http\Controllers\Admin\PaymentMethodController::class);
                Route::put('methods/{method}/toggle', [App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])->name('methods.toggle');
                Route::get('methods/{method}/currencies', [App\Http\Controllers\Admin\PaymentMethodController::class, 'currencies'])->name('methods.currencies');
                Route::put('methods/{method}/currencies', [App\Http\Controllers\Admin\PaymentMethodController::class, 'updateCurrencies'])->name('methods.currencies.update');
                
                // Transactions
                Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
                Route::get('transactions/export', [App\Http\Controllers\Admin\TransactionController::class, 'export'])->name('transactions.export');
                Route::get('transactions/{transaction}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('transactions.show');
                Route::post('transactions/{transaction}/mark-as-refunded', [App\Http\Controllers\Admin\TransactionController::class, 'markAsRefunded'])->name('transactions.mark-as-refunded');
                
                // Abonnements
                Route::get('subscriptions', [App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
                Route::get('subscriptions/{subscription}', [App\Http\Controllers\Admin\SubscriptionController::class, 'show'])->name('subscriptions.show');
                
                // Prix des packages
                Route::get('package-prices', [App\Http\Controllers\Admin\PackagePriceController::class, 'index'])->name('package-prices.index');
                Route::get('package-prices/edit', [App\Http\Controllers\Admin\PackagePriceController::class, 'edit'])->name('package-prices.edit');
                Route::post('package-prices/update', [App\Http\Controllers\Admin\PackagePriceController::class, 'update'])->name('package-prices.update');
                
                // Configuration générale des paiements
                Route::get('settings', [App\Http\Controllers\Admin\PaymentSettingController::class, 'index'])->name('settings.index');
                Route::post('settings', [App\Http\Controllers\Admin\PaymentSettingController::class, 'update'])->name('settings.update');

                // Paramètres de paiement
                Route::get('settings', [App\Http\Controllers\Admin\PaymentSettingController::class, 'index'])->name('settings.index');
                Route::put('settings/general', [App\Http\Controllers\Admin\PaymentSettingController::class, 'updateGeneral'])->name('settings.update-general');
                Route::patch('settings/payment-methods/{id}/toggle', [App\Http\Controllers\Admin\PaymentSettingController::class, 'togglePaymentMethod'])->name('settings.toggle-payment-method');
                Route::put('settings/payment-methods/{id}', [App\Http\Controllers\Admin\PaymentSettingController::class, 'updatePaymentMethod'])->name('settings.update-payment-method');
                Route::put('settings/payment-methods/{id}/currencies', [App\Http\Controllers\Admin\PaymentSettingController::class, 'updatePaymentMethodCurrencies'])->name('settings.update-payment-method-currencies');
            });
        });

        // Newsletter subscription
        Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

        // Route de diagnostic
        Route::get('/diagnostic/categories', [DiagnosticController::class, 'checkCategories'])->name('diagnostic.categories');
        Route::get('/diagnostic/category/{slug}', [DiagnosticController::class, 'testCategory'])->name('diagnostic.test-category')->where('slug', '[a-z0-9\-]+');
        
        // Route de test direct pour la vue category
        Route::get('/diagnostic/view/category', function() {
            $category = \App\Models\ToolCategory::where('slug', 'checker')->first();
            if (!$category) {
                return response()->json(['error' => 'Catégorie non trouvée'], 404);
            }
            
            $tools = \App\Models\Tool::where('tool_category_id', $category->id)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
                
            $adSettings = app(\App\Services\AdService::class)->getAdsForPage('category');
            
            $locale = app()->getLocale();
            $pageTitle = $category->getName($locale);
            $metaDescription = $category->getDescription($locale);
            
            return view('tools.category', [
                'category' => $category,
                'tools' => $tools,
                'pageTitle' => $pageTitle,
                'metaDescription' => $metaDescription,
                'adSettings' => $adSettings,
            ]);
        })->name('diagnostic.view-category');

        // Routes pour la FAQ en frontend
        Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
        Route::get('/faq/{slug}', [FaqController::class, 'category'])->name('faq.category');

        // Route pour la page de contact
        Route::get('/contact', function () {
            return view('pages.contact');
        })->name('contact');
        
        // Routes pour la FAQ dans l'administration
        Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
            // FAQ Categories
            Route::resource('faq_categories', App\Http\Controllers\Admin\FaqCategoryController::class, [
                'except' => ['show']
            ]);
            Route::put('faq_categories/update-order', [App\Http\Controllers\Admin\FaqCategoryController::class, 'updateOrder'])->name('faq_categories.update-order');
            
            // Gestion des FAQ
            Route::resource('faq', App\Http\Controllers\Admin\FaqController::class, [
                'except' => ['show']
            ]);
            Route::put('faq/update-order', [App\Http\Controllers\Admin\FaqController::class, 'updateOrder'])->name('faq.update-order');
            
            // Routes des témoignages
            Route::get('/testimonials', [App\Http\Controllers\Admin\TestimonialController::class, 'index'])->name('testimonials.index');
            Route::get('/testimonials/create', [App\Http\Controllers\Admin\TestimonialController::class, 'create'])->name('testimonials.create');
            Route::post('/testimonials', [App\Http\Controllers\Admin\TestimonialController::class, 'store'])->name('testimonials.store');
            Route::get('/testimonials/moderation', [App\Http\Controllers\Admin\TestimonialController::class, 'moderation'])->name('testimonials.moderation');
            Route::get('/testimonials/{testimonial}/edit', [App\Http\Controllers\Admin\TestimonialController::class, 'edit'])->name('testimonials.edit');
            Route::put('/testimonials/{testimonial}', [App\Http\Controllers\Admin\TestimonialController::class, 'update'])->name('testimonials.update');
            Route::delete('/testimonials/{testimonial}', [App\Http\Controllers\Admin\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
            Route::patch('/testimonials/{testimonial}/toggle-status', [App\Http\Controllers\Admin\TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
        });

        // Packages
        Route::get('packages', [App\Http\Controllers\PackageController::class, 'index'])->name('packages');

        // Routes liées au paiement
        Route::middleware(['auth'])->group(function() {
            // Parcours de paiement
            Route::get('packages', [PaymentController::class, 'packages'])->name('packages');
            Route::get('checkout/{slug}', [PaymentController::class, 'checkout'])->name('checkout');
            Route::post('checkout/currency-cycle', [PaymentController::class, 'selectCurrencyAndCycle'])->name('checkout.currency-cycle');
            Route::get('payment/methods', [PaymentController::class, 'paymentMethods'])->name('payment.methods');
            Route::post('payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
            Route::get('payment/success/{provider}', [PaymentController::class, 'success'])->name('payment.success');
            Route::get('payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
        });

        // Webhooks (pas besoin de préfixe de langue)
        Route::post('webhooks/payment/{provider}', [PaymentController::class, 'webhook'])
            ->name('payment.webhook')
            ->middleware('api')
            ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

        // Routes pour les périodes d'essai
        Route::get('/trial/{slug}/{cycle?}', [TrialController::class, 'start'])->name('trial.start');
        Route::post('/trial/{slug}/activate', [TrialController::class, 'activate'])->name('trial.activate');
    });

// Authentification (routes sans préfixe de langue)
require __DIR__ . '/auth.php';

// Route de test pour vérifier notre helper
Route::get('/test-helper', function () {
    return view('test-helper');
});

Route::prefix('{locale}/admin/payments/methods')->name('admin.payments.methods.')->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
    Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
    Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
    Route::get('/{method}', [PaymentMethodController::class, 'show'])->name('show');
    Route::get('/{method}/edit', [PaymentMethodController::class, 'edit'])->name('edit');
    Route::put('/{method}', [PaymentMethodController::class, 'update'])->name('update');
    Route::delete('/{method}', [PaymentMethodController::class, 'destroy'])->name('destroy');
    Route::get('/{method}/currencies', [PaymentMethodController::class, 'currencies'])->name('currencies');
    Route::post('/{method}/currencies', [PaymentMethodController::class, 'updateCurrencies'])->name('updateCurrencies');
    Route::post('/{method}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('toggleStatus');
});

