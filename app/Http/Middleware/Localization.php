<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::debug('Localization middleware running', [
            'session_has_locale' => Session::has('locale'),
            'session_locale' => Session::get('locale'),
            'current_app_locale' => App::getLocale(),
            'path' => $request->path(),
        ]);

        if (Session::has('locale')) {
            $locale = Session::get('locale');
            App::setLocale($locale);

            Log::debug('Setting locale from session', [
                'new_locale' => $locale,
                'app_locale_after_set' => App::getLocale()
            ]);
        }

        return $next($request);
    }
}
