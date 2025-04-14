<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'color',
        'is_active',
        'is_default',
        'show_ads',
        'cycle_type',
        'cycle_count',
        'monthly_price',
        'annual_price',
        'lifetime_price',
        'order',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'show_ads' => 'boolean',
        'monthly_price' => 'decimal:2',
        'annual_price' => 'decimal:2',
        'lifetime_price' => 'decimal:2',
        'order' => 'integer',
        'cycle_count' => 'integer',
    ];

    /**
     * Les constantes pour les types de cycle.
     */
    const CYCLE_DAY = 'day';
    const CYCLE_MONTH = 'month';
    const CYCLE_YEAR = 'year';
    const CYCLE_LIFETIME = 'lifetime';

    /**
     * Obtenir les traductions pour ce package.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(PackageTranslation::class);
    }

    /**
     * Obtenir les outils associés à ce package.
     */
    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'package_tools')
            ->withPivot('is_vip', 'is_ai')
            ->withTimestamps();
    }

    /**
     * Obtenir la traduction pour une locale spécifique.
     *
     * @param string $locale
     * @return PackageTranslation|null
     */
    public function getTranslation(string $locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Obtenir le nom traduit pour la locale actuelle ou par défaut.
     *
     * @param string|null $locale
     * @return string
     */
    public function getName(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->getTranslation($locale);
        
        if ($translation && $translation->name) {
            return $translation->name;
        }
        
        // Fallback to default language
        $defaultLocale = Language::getDefaultCode();
        if ($locale !== $defaultLocale) {
            $defaultTranslation = $this->getTranslation($defaultLocale);
            if ($defaultTranslation && $defaultTranslation->name) {
                return $defaultTranslation->name;
            }
        }
        
        return $this->slug;
    }

    /**
     * Obtenir la description traduite pour la locale actuelle ou par défaut.
     *
     * @param string|null $locale
     * @return string
     */
    public function getDescription(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->getTranslation($locale);
        
        if ($translation && $translation->description) {
            return $translation->description;
        }
        
        // Fallback to default language
        $defaultLocale = Language::getDefaultCode();
        if ($locale !== $defaultLocale) {
            $defaultTranslation = $this->getTranslation($defaultLocale);
            if ($defaultTranslation && $defaultTranslation->description) {
                return $defaultTranslation->description;
            }
        }
        
        return '';
    }

    /**
     * Obtenir les fonctionnalités traduites pour la locale actuelle ou par défaut.
     *
     * @param string|null $locale
     * @return string
     */
    public function getFeatures(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->getTranslation($locale);
        
        if ($translation && $translation->features) {
            return $translation->features;
        }
        
        // Fallback to default language
        $defaultLocale = Language::getDefaultCode();
        if ($locale !== $defaultLocale) {
            $defaultTranslation = $this->getTranslation($defaultLocale);
            if ($defaultTranslation && $defaultTranslation->features) {
                return $defaultTranslation->features;
            }
        }
        
        return '';
    }

    /**
     * Obtenir le format de période en texte.
     *
     * @return string
     */
    public function getCyclePeriodText(): string
    {
        if ($this->cycle_type === self::CYCLE_LIFETIME) {
            return __('À vie');
        }
        
        $count = $this->cycle_count;
        
        return match($this->cycle_type) {
            self::CYCLE_DAY => trans_choice(':count jour|:count jours', $count, ['count' => $count]),
            self::CYCLE_MONTH => trans_choice(':count mois|:count mois', $count, ['count' => $count]),
            self::CYCLE_YEAR => trans_choice(':count an|:count ans', $count, ['count' => $count]),
            default => '',
        };
    }

    /**
     * Obtenir les outils VIP de ce package.
     */
    public function vipTools()
    {
        return $this->tools()->wherePivot('is_vip', true);
    }

    /**
     * Obtenir les outils AI de ce package.
     */
    public function aiTools()
    {
        return $this->tools()->wherePivot('is_ai', true);
    }

    /**
     * Obtenir tous les packages actifs.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();
    }
} 