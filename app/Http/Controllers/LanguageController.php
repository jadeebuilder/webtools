<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Switch the application's language.
     *
     * @param  string  $lang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($lang)
    {
        $availableLocales = config('app.available_locales', []);

        Log::info('Switching language', [
            'requested_lang' => $lang,
            'available_locales' => $availableLocales,
            'current_locale' => App::getLocale()
        ]);

        if (array_key_exists($lang, $availableLocales)) {
            Session::put('locale', $lang);
            App::setLocale($lang);

            Log::info('Language switched successfully', [
                'new_locale' => $lang,
                'session_locale' => Session::get('locale'),
                'app_locale' => App::getLocale()
            ]);
        } else {
            Log::warning('Invalid language requested', [
                'requested_lang' => $lang
            ]);
        }

        return redirect()->back();
    }
}
