<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_name',
        'site_description',
        'contact_email',
        'default_timezone',
        'default_locale',
        'tools_per_page',
        'tools_order',
        
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_author',
        'opengraph_image',
        
        'logo_light',
        'logo_dark',
        'logo_email',
        'favicon',
        'homepage_cover',
        
        'google_analytics_id',
        'facebook_pixel_id',
        'cookie_banner_enabled',
        'dark_mode_enabled',
        
        'maintenance_mode',
        'maintenance_message',
        'maintenance_end_date',
        'maintenance_allowed_ips',
        
        'sitemap_auto_generation',
        'sitemap_include_tools',
        'sitemap_include_blog',
        'sitemap_frequency',
        'sitemap_priority',
        'sitemap_last_generated',
        
        'company_name',
        'company_registration_number',
        'company_vat_number',
        'company_address',
        'company_phone',
        'company_email',
        'company_opening_hours',
        
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_linkedin',
        'social_youtube',
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs.
     *
     * @var array
     */
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'cookie_banner_enabled' => 'boolean',
        'dark_mode_enabled' => 'boolean',
        'sitemap_auto_generation' => 'boolean',
        'sitemap_include_tools' => 'boolean',
        'sitemap_include_blog' => 'boolean',
        'maintenance_end_date' => 'datetime',
        'sitemap_last_generated' => 'datetime',
        'sitemap_priority' => 'float',
        'tools_per_page' => 'integer',
    ];

    /**
     * Durée du cache pour les paramètres en secondes (1 jour).
     *
     * @var int
     */
    protected static $cacheTime = 86400;

    /**
     * Préfixe du cache pour les paramètres.
     *
     * @var string
     */
    protected static $cachePrefix = 'settings_';

    /**
     * Obtenir tous les paramètres par groupe.
     *
     * @param string $group
     * @return array
     */
    public static function getByGroup(string $group): array
    {
        $cacheKey = self::$cachePrefix . 'group_' . $group;

        return Cache::remember($cacheKey, self::$cacheTime, function () use ($group) {
            $settings = self::where('group', $group)->get();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }

            return $result;
        });
    }

    /**
     * Obtenir un paramètre par sa clé.
     *
     * @param string $key
     * @param mixed $default Valeur par défaut si le paramètre n'existe pas
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = self::$cachePrefix . 'key_' . $key;

        return Cache::remember($cacheKey, self::$cacheTime, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Mettre à jour ou créer un paramètre.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param bool $isPublic
     * @param bool $isTranslatable
     * @return self
     */
    public static function set(string $key, $value, string $group = 'general', bool $isPublic = false, bool $isTranslatable = false): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $value,
                'is_public' => $isPublic,
                'is_translatable' => $isTranslatable,
            ]
        );

        // Effacer le cache
        Cache::forget(self::$cachePrefix . 'key_' . $key);
        Cache::forget(self::$cachePrefix . 'group_' . $group);
        Cache::forget(self::$cachePrefix . 'all');

        return $setting;
    }

    /**
     * Supprimer un paramètre.
     *
     * @param string $key
     * @return bool
     */
    public static function remove(string $key): bool
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            // Effacer le cache
            Cache::forget(self::$cachePrefix . 'key_' . $key);
            Cache::forget(self::$cachePrefix . 'group_' . $setting->group);
            Cache::forget(self::$cachePrefix . 'all');

            return $setting->delete();
        }

        return false;
    }

    /**
     * Obtenir tous les paramètres publics.
     *
     * @return array
     */
    public static function getPublic(): array
    {
        $cacheKey = self::$cachePrefix . 'public';

        return Cache::remember($cacheKey, self::$cacheTime, function () {
            $settings = self::where('is_public', true)->get();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = $setting->value;
            }

            return $result;
        });
    }

    /**
     * Vider le cache des paramètres.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        $settings = self::all();

        foreach ($settings as $setting) {
            Cache::forget(self::$cachePrefix . 'key_' . $setting->key);
            Cache::forget(self::$cachePrefix . 'group_' . $setting->group);
        }

        Cache::forget(self::$cachePrefix . 'all');
        Cache::forget(self::$cachePrefix . 'public');
    }

    /**
     * Obtenir une instance unique des paramètres
     *
     * @return self
     */
    public static function getInstance(): self
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'site_name' => 'WebTools',
                'default_timezone' => 'UTC',
                'default_locale' => 'fr',
                'tools_per_page' => 12,
                'tools_order' => 'DESC',
                'sitemap_frequency' => 'weekly',
                'sitemap_priority' => 0.8,
            ]);
        }
        
        return $settings;
    }

    /**
     * Vérifier si le mode maintenance est actif
     *
     * @return bool
     */
    public function isInMaintenanceMode(): bool
    {
        if (!$this->maintenance_mode) {
            return false;
        }

        if ($this->maintenance_end_date && now()->greaterThan($this->maintenance_end_date)) {
            $this->update(['maintenance_mode' => false]);
            return false;
        }

        return true;
    }

    /**
     * Vérifier si une adresse IP est autorisée en mode maintenance
     *
     * @param string $ip
     * @return bool
     */
    public function isIpAllowedInMaintenance(string $ip): bool
    {
        if (!$this->maintenance_allowed_ips) {
            return false;
        }

        $allowedIps = explode(',', $this->maintenance_allowed_ips);
        $allowedIps = array_map('trim', $allowedIps);
        
        return in_array($ip, $allowedIps, true);
    }

    /**
     * Obtenir le tableau des adresses IP autorisées
     * 
     * @return array
     */
    public function getAllowedIpsArray(): array
    {
        if (!$this->maintenance_allowed_ips) {
            return [];
        }
        
        $allowedIps = explode(',', $this->maintenance_allowed_ips);
        return array_map('trim', $allowedIps);
    }
}
