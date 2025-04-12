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
            Route::get('/checker', [ToolController::class, 'checker'])->name('tools.checker');
            Route::get('/text', [ToolController::class, 'text'])->name('tools.text');
            Route::get('/converter', [ToolController::class, 'converter'])->name('tools.converter');
            Route::get('/generator', [ToolController::class, 'generator'])->name('tools.generator');
            Route::get('/developer', [ToolController::class, 'developer'])->name('tools.developer');
            Route::get('/image', [ToolController::class, 'image'])->name('tools.image');
            Route::get('/unit', [ToolController::class, 'unit'])->name('tools.unit');
            Route::get('/time', [ToolController::class, 'time'])->name('tools.time');
            Route::get('/data', [ToolController::class, 'data'])->name('tools.data');
            Route::get('/color', [ToolController::class, 'color'])->name('tools.color');
            Route::get('/misc', [ToolController::class, 'misc'])->name('tools.misc');
        });

        // Route pour l'utilisation d'un outil spécifique
        Route::get('/tool/{slug}', [ToolController::class, 'show'])->name('tool.show');

        // Routes pour les outils spécifiques
        Route::prefix('tools')->group(function () {
            // Case Converter
            Route::get('/case-converter', [CaseConverterController::class, 'show'])->name('tools.case-converter');
            Route::post('/case-converter/process', [CaseConverterController::class, 'process'])->name('tools.case-converter.process');
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
            Route::resource('tools', AdminToolController::class);
            Route::post('tools/generate-slug', [AdminToolController::class, 'generateSlug'])->name('tools.generate-slug');
            Route::put('tools/{tool}/toggle-status', [AdminToolController::class, 'toggleStatus'])->name('tools.toggle-status');
            
            // Configuration des publicités par outil
            Route::get('tools/{tool}/ads', [ToolAdSettingController::class, 'edit'])->name('tools.ads.edit');
            Route::put('tools/{tool}/ads', [ToolAdSettingController::class, 'update'])->name('tools.ads.update');
            
            // Gestion des catégories d'outils
            Route::resource('tool-categories', ToolCategoryController::class);
            Route::put('tool-categories/{category}/toggle-status', [ToolCategoryController::class, 'toggleStatus'])->name('tool-categories.toggle-status');
            
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
        });

        // Newsletter subscription
        Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    });

// Authentification (routes sans préfixe de langue)
require __DIR__ . '/auth.php';
