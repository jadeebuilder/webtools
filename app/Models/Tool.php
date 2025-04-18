<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tool extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'icon',
        'tool_category_id',
        'is_active',
        'is_premium',
        'order',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Obtenir la catégorie de l'outil.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ToolCategory::class, 'tool_category_id');
    }

    /**
     * Obtenir les types d'outils associés.
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(ToolType::class, 'tool_type_tools')
            ->withTimestamps();
    }

    /**
     * Vérifier si cet outil appartient à un type spécifique.
     *
     * @param string $typeSlug
     * @return bool
     */
    public function hasType(string $typeSlug): bool
    {
        return $this->types()->where('slug', $typeSlug)->exists();
    }

    /**
     * Obtenir les plans auxquels cet outil est associé.
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_tools')
            ->withTimestamps();
    }

    /**
     * Obtenir les traductions pour cet outil.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ToolTranslation::class);
    }

    /**
     * Obtenir la traduction pour une locale spécifique.
     *
     * @param string $locale
     * @return ToolTranslation|null
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
        $defaultLocale = SiteLanguage::where('is_default', true)->first()->code ?? 'fr';
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        return $defaultTranslation ? $defaultTranslation->name : $this->slug;
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
        $defaultLocale = SiteLanguage::where('is_default', true)->first()->code ?? 'fr';
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        return $defaultTranslation ? $defaultTranslation->description : null;
    }

    /**
     * Vérifier si l'outil est accessible pour un plan donné.
     *
     * @param int|null $planId
     * @return bool
     */
    public function isAccessibleWithPlan(?int $planId): bool
    {
        if (!$this->is_premium) {
            return true;
        }

        if (!$planId) {
            return false;
        }

        return $this->plans()->where('plans.id', $planId)->exists();
    }

    /**
     * Obtenir les templates associés à cet outil.
     */
    public function templates()
    {
        return $this->hasMany(ToolTemplate::class)
            ->where('is_active', true)
            ->orderBy('order')
            ->with('section');
    }

    /**
     * Obtenir les configurations d'emplacements publicitaires spécifiques à cet outil.
     */
    public function adSettings(): HasMany
    {
        return $this->hasMany(ToolAdSetting::class);
    }

    /**
     * Vérifier si l'outil possède une route personnalisée.
     * 
     * @return bool
     */
    public function hasCustomRoute(): bool
    {
        // Liste des outils avec des routes personnalisées
        $customRouteTools = [
            'case-converter',
            // Ajouter d'autres outils avec des routes personnalisées ici
        ];
        
        return in_array($this->slug, $customRouteTools);
    }
    
    /**
     * Obtenir le nom de la route pour cet outil.
     * 
     * @return string
     */
    public function getRouteName(): string
    {
        if ($this->hasCustomRoute()) {
            return 'tools.' . $this->slug;
        }
        
        return 'tool.show';
    }
    
    /**
     * Obtenir les paramètres de route pour cet outil.
     * 
     * @return array
     */
    public function getRouteParameters(): array
    {
        $params = ['locale' => app()->getLocale()];
        
        if (!$this->hasCustomRoute()) {
            $params['slug'] = $this->slug;
        }
        
        return $params;
    }
}
