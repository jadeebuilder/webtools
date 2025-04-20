<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Currency;

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
        'has_trial',
        'trial_days',
        'trial_restrictions',
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
        'has_trial' => 'boolean',
        'monthly_price' => 'decimal:2',
        'annual_price' => 'decimal:2',
        'lifetime_price' => 'decimal:2',
        'order' => 'integer',
        'cycle_count' => 'integer',
        'trial_days' => 'integer',
        'trial_restrictions' => 'array',
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
        
        // Fallback à la langue par défaut si la traduction n'existe pas
        $defaultLocale = SiteLanguage::getDefaultCode();
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        return $defaultTranslation ? $defaultTranslation->name : '';
    }

    /**
     * Obtenir la description traduite pour la locale actuelle ou par défaut.
     *
     * @param string|null $locale
     * @return string|null
     */
    public function getDescription(string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->getTranslation($locale);
        
        if ($translation && $translation->description) {
            return $translation->description;
        }
        
        // Fallback à la langue par défaut si la traduction n'existe pas
        $defaultLocale = SiteLanguage::getDefaultCode();
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        return $defaultTranslation ? $defaultTranslation->description : null;
    }

    /**
     * Obtenir les fonctionnalités traduites pour la locale actuelle ou par défaut.
     *
     * @param string|null $locale
     * @return string|null
     */
    public function getFeatures(string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->getTranslation($locale);
        
        if ($translation && $translation->features) {
            return $translation->features;
        }
        
        // Fallback à la langue par défaut si la traduction n'existe pas
        $defaultLocale = SiteLanguage::getDefaultCode();
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        return $defaultTranslation ? $defaultTranslation->features : null;
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

    /**
     * Vérifie si le package propose une période d'essai.
     *
     * @return bool
     */
    public function hasTrial(): bool
    {
        return $this->has_trial && $this->trial_days > 0;
    }

    /**
     * Obtient la durée de la période d'essai en jours.
     *
     * @return int
     */
    public function getTrialDays(): int
    {
        return $this->hasTrial() ? $this->trial_days : 0;
    }

    /**
     * Obtient les restrictions liées à la période d'essai.
     *
     * @return array
     */
    public function getTrialRestrictions(): array
    {
        return $this->trial_restrictions ?? [];
    }

    /**
     * Vérifie si une restriction spécifique s'applique pendant la période d'essai.
     *
     * @param string $restriction
     * @return bool
     */
    public function hasTrialRestriction(string $restriction): bool
    {
        return in_array($restriction, $this->getTrialRestrictions());
    }
    
    /**
     * Obtient le texte de la période d'essai.
     *
     * @return string
     */
    public function getTrialText(): string
    {
        if (!$this->hasTrial()) {
            return '';
        }
        
        return trans_choice(':count jour d\'essai|:count jours d\'essai', $this->trial_days, ['count' => $this->trial_days]);
    }

    /**
     * Obtenir les types d'outils associés à ce package.
     */
    public function toolTypes(): BelongsToMany
    {
        return $this->belongsToMany(ToolType::class, 'package_tool_types')
            ->withPivot('tools_limit')
            ->withTimestamps();
    }

    /**
     * Obtenir les outils d'un type spécifique pour ce package.
     *
     * @param string $typeSlug
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getToolsByType(string $typeSlug)
    {
        $toolType = ToolType::where('slug', $typeSlug)->first();
        
        if (!$toolType) {
            return collect();
        }
        
        return $this->tools()
            ->whereHas('types', function ($query) use ($typeSlug) {
                $query->where('tool_types.slug', $typeSlug);
            })
            ->get();
    }

    /**
     * Obtenir le nombre d'outils d'un type spécifique pour ce package.
     *
     * @param string $typeSlug
     * @return int
     */
    public function getToolsCountByType(string $typeSlug): int
    {
        return $this->getToolsByType($typeSlug)->count();
    }

    /**
     * Obtenir la limite d'outils pour un type spécifique.
     *
     * @param string $typeSlug
     * @return int|null
     */
    public function getToolsLimitByType(string $typeSlug): ?int
    {
        $typeRelation = $this->toolTypes()
            ->where('tool_types.slug', $typeSlug)
            ->first();
        
        return $typeRelation ? $typeRelation->pivot->tools_limit : null;
    }

    /**
     * Vérifier si ce package a une limite pour un type d'outil spécifique.
     *
     * @param string $typeSlug
     * @return bool
     */
    public function hasToolTypeLimit(string $typeSlug): bool
    {
        $limit = $this->getToolsLimitByType($typeSlug);
        return $limit !== null && $limit > 0;
    }

    /**
     * Formater le prix mensuel avec le symbole de la devise par défaut.
     *
     * @param bool $includeSymbol Inclure le symbole de la devise
     * @param bool $includeCode Inclure le code de la devise
     * @return string
     */
    public function getFormattedMonthlyPrice(bool $includeSymbol = true, bool $includeCode = false): string
    {
        if ($this->monthly_price <= 0) {
            return __('Gratuit');
        }
        
        if ($includeSymbol) {
            return Currency::formatDefault($this->monthly_price, $includeCode);
        }
        
        return number_format($this->monthly_price, 2, ',', ' ');
    }
    
    /**
     * Formater le prix annuel avec le symbole de la devise par défaut.
     *
     * @param bool $includeSymbol Inclure le symbole de la devise
     * @param bool $includeCode Inclure le code de la devise
     * @return string
     */
    public function getFormattedAnnualPrice(bool $includeSymbol = true, bool $includeCode = false): string
    {
        if ($this->annual_price <= 0) {
            return __('Gratuit');
        }
        
        if ($includeSymbol) {
            return Currency::formatDefault($this->annual_price, $includeCode);
        }
        
        return number_format($this->annual_price, 2, ',', ' ');
    }
    
    /**
     * Formater le prix à vie avec le symbole de la devise par défaut.
     *
     * @param bool $includeSymbol Inclure le symbole de la devise
     * @param bool $includeCode Inclure le code de la devise
     * @return string
     */
    public function getFormattedLifetimePrice(bool $includeSymbol = true, bool $includeCode = false): string
    {
        if ($this->lifetime_price <= 0) {
            return __('Non disponible');
        }
        
        if ($includeSymbol) {
            return Currency::formatDefault($this->lifetime_price, $includeCode);
        }
        
        return number_format($this->lifetime_price, 2, ',', ' ');
    }
    
    /**
     * Calculer les économies annuelles par rapport au prix mensuel.
     *
     * @return array Tableau contenant les économies et le pourcentage d'économies
     */
    public function getAnnualSavings(): array
    {
        if ($this->monthly_price <= 0 || $this->annual_price <= 0) {
            return [
                'amount' => 0,
                'percent' => 0
            ];
        }
        
        $annualCostMonthly = $this->monthly_price * 12;
        $savings = $annualCostMonthly - $this->annual_price;
        $savingsPercent = round(($savings / $annualCostMonthly) * 100);
        
        return [
            'amount' => $savings,
            'percent' => $savingsPercent,
            'formatted' => Currency::formatDefault($savings, false)
        ];
    }
    
    /**
     * Calculer les économies à vie par rapport au prix mensuel sur 5 ans.
     *
     * @return array Tableau contenant les économies et le pourcentage d'économies
     */
    public function getLifetimeSavings(): array
    {
        if ($this->monthly_price <= 0 || $this->lifetime_price <= 0) {
            return [
                'amount' => 0,
                'percent' => 0
            ];
        }
        
        $lifetimeCostMonthly = $this->monthly_price * 60; // 5 ans
        $savings = $lifetimeCostMonthly - $this->lifetime_price;
        $savingsPercent = round(($savings / $lifetimeCostMonthly) * 100);
        
        return [
            'amount' => $savings,
            'percent' => $savingsPercent,
            'formatted' => Currency::formatDefault($savings, false)
        ];
    }

    /**
     * Scope pour filtrer uniquement les packages actifs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtenir les prix associés à ce package.
     */
    public function prices()
    {
        return $this->hasMany(PackagePrice::class);
    }

    /**
     * Obtenir le prix pour un cycle et une devise spécifiques.
     *
     * @param string $cycle
     * @param int|null $currencyId
     * @return PackagePrice|null
     */
    public function getPrice(string $cycle, ?int $currencyId = null)
    {
        $query = $this->prices();
        
        if ($currencyId) {
            $query->where('currency_id', $currencyId);
        } else {
            // Utiliser la devise par défaut
            $defaultCurrency = Currency::where('code', config('app.default_currency', 'USD'))->first();
            if ($defaultCurrency) {
                $query->where('currency_id', $defaultCurrency->id);
            }
        }
        
        return $query->where('cycle', $cycle)->first();
    }
} 