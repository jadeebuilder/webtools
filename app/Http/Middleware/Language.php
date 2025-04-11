<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Language
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
        Log::info('Language middleware', [
            'session_locale' => Session::get('applocale'),
            'current_locale' => App::getLocale(),
            'request_path' => $request->path()
        ]);

        if (Session::has('applocale')) {
            $locale = Session::get('applocale');
            App::setLocale($locale);

            Log::info('Locale set from session', [
                'locale' => $locale,
                'new_app_locale' => App::getLocale()
            ]);
        } else {
            Log::info('No locale in session, using default', [
                'default_locale' => config('app.locale')
            ]);
        }

        return $next($request);
    }
}
