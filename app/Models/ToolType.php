<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToolType extends Model
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
        'color',
        'is_active',
        'order',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Obtenir les outils associés à ce type.
     */
    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'tool_type_tools')
            ->withTimestamps();
    }

    /**
     * Obtenir les packages associés à ce type d'outil.
     */
    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_tool_types')
            ->withPivot('tools_limit')
            ->withTimestamps();
    }

    /**
     * Obtenir les traductions pour ce type d'outil.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ToolTypeTranslation::class);
    }

    /**
     * Obtenir la traduction pour une locale spécifique.
     *
     * @param string $locale
     * @return ToolTypeTranslation|null
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
        $defaultLocale = SiteLanguage::getDefaultCode();
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        return $defaultTranslation ? $defaultTranslation->description : null;
    }

    /**
     * Obtenir tous les types d'outils actifs.
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