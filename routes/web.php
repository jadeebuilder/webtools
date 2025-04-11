<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UserController;

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
    });

// Newsletter subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Authentification (routes sans préfixe de langue)
require __DIR__ . '/auth.php';
