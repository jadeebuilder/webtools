<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Obtenir un paramètre de configuration par sa clé.
     *
     * @param string $key
     * @param mixed $default Valeur par défaut si le paramètre n'existe pas
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
} 