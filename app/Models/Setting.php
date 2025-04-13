<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group',
        'key',
        'value',
        'is_public',
        'is_translatable'
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs.
     *
     * @var array
     */
    protected $casts = [
        'is_public' => 'boolean',
        'is_translatable' => 'boolean'
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
                $value = $setting->value;
                
                // Tenter de décoder les valeurs JSON
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $result[$setting->key] = $decoded;
                        continue;
                    }
                }
                
                $result[$setting->key] = $value;
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
            
            if (!$setting) {
                return $default;
            }
            
            // Tenter de décoder les valeurs JSON
            $value = $setting->value;
            
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
            
            return $value;
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
        try {
            // Convertir les valeurs non-scalaires en JSON
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
            
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
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la sauvegarde du paramètre', [
                'key' => $key,
                'group' => $group,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
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
            try {
                DB::beginTransaction();
                
                // Créer le paramètre de base du site
                $settings = self::create([
                    'key' => 'site_name',
                    'group' => 'general',
                    'value' => 'WebTools',
                    'is_public' => true,
                    'is_translatable' => false
                ]);
                
                // Ajouter d'autres paramètres par défaut
                self::set('site_description', 'Une plateforme d\'outils web gratuits', 'general', true, true);
                self::set('default_timezone', 'UTC', 'general', true, false);
                self::set('default_locale', 'fr', 'general', true, false);
                self::set('tools_per_page', 12, 'general', true, false);
                self::set('tools_order', 'DESC', 'general', true, false);
                self::set('contact_email', 'contact@example.com', 'general', true, false);
                
                // Paramètres de maintenance
                self::set('maintenance_mode', 0, 'maintenance', true, false);
                self::set('maintenance_message', 'Site en maintenance', 'maintenance', true, true);
                
                // Paramètres SEO
                self::set('meta_title', 'WebTools - Outils web gratuits', 'general', true, true);
                self::set('meta_description', 'Une collection d\'outils web gratuits', 'general', true, true);
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollback();
                
                \Log::error('Erreur lors de la création des paramètres par défaut', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                throw $e;
            }
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
