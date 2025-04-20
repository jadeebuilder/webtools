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
    public function create(Request $request, string $locale): View
    {
        // Récupérer les paramètres de l'URL
        $redirectTo = $request->query('redirect_to');
        $intent = $request->query('intent');
        
        return view('auth.login', [
            'redirectTo' => $redirectTo,
            'intent' => $intent
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, string $locale): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Vérifier s'il y a une URL de redirection soumise dans le formulaire
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo) {
            return redirect()->to($redirectTo);
        }

        return redirect()->intended(route(RouteServiceProvider::HOME, ['locale' => $locale]));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
