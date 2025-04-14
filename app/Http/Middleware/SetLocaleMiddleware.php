<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from route parameter
        $locale = $request->route('locale');
        
        // Check if the locale is valid
        $availableLocales = array_keys(config('app.available_locales', []));
        
        if ($locale && in_array($locale, $availableLocales)) {
            // Set the application locale
            App::setLocale($locale);
            // Store locale in session
            Session::put('locale', $locale);
        }
        
        return $next($request);
    }
} 