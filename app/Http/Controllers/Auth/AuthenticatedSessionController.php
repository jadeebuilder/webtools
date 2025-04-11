<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\App;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Récupérer la locale de la session ou utiliser celle par défaut
        $locale = session('locale', config('app.fallback_locale'));
        App::setLocale($locale);
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        // Récupérer la locale de la session
        $locale = session('locale', config('app.fallback_locale'));
        
        // Rediriger vers la page d'accueil avec le préfixe de langue
        return redirect()->intended($locale === config('app.fallback_locale') 
            ? route('home', ['locale' => $locale]) 
            : route('home', ['locale' => $locale]));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Récupérer la locale de la session avant de la détruire
        $locale = session('locale', config('app.fallback_locale'));
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Rediriger vers la page d'accueil avec le préfixe de langue
        return redirect(route('home', ['locale' => $locale]));
    }
}
