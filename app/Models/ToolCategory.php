<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToolCategory extends Model
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
     * Obtenir les outils appartenant à cette catégorie.
     */
    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class, 'tool_category_id');
    }

    /**
     * Obtenir les traductions pour cette catégorie.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ToolCategoryTranslation::class);
    }

    /**
     * Obtenir la traduction pour une locale spécifique.
     *
     * @param string $locale
     * @return ToolCategoryTranslation|null
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
        $defaultLocale = Language::where('is_default', true)->first()->code ?? 'fr';
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
        $defaultLocale = Language::where('is_default', true)->first()->code ?? 'fr';
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        return $defaultTranslation ? $defaultTranslation->description : null;
    }

    /**
     * Obtenir le meta title traduit pour la locale actuelle ou par défaut.
     *
     * @param string|null $locale
     * @return string
     */
    public function getMetaTitle(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->getTranslation($locale);
        
        if ($translation && $translation->meta_title) {
            return $translation->meta_title;
        }
        
        // Fallback au nom si meta_title n'existe pas
        if ($translation && $translation->name) {
            return $translation->name;
        }
        
        // Fallback à la langue par défaut si la traduction n'existe pas
        $defaultLocale = Language::where('is_default', true)->first()->code ?? 'fr';
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        // Utiliser le meta_title ou le nom de la traduction par défaut, ou le slug en dernier recours
        if ($defaultTranslation) {
            return $defaultTranslation->meta_title ?? $defaultTranslation->name ?? $this->slug;
        }
        
        return $this->slug;
    }

    /**
     * Obtenir la meta description traduite pour la locale actuelle ou par défaut.
     *
     * @param string|null $locale
     * @return string|null
     */
    public function getMetaDescription(string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        $translation = $this->getTranslation($locale);
        
        if ($translation && $translation->meta_description) {
            return $translation->meta_description;
        }
        
        // Fallback à la description si meta_description n'existe pas
        if ($translation && $translation->description) {
            return $translation->description;
        }
        
        // Fallback à la langue par défaut si la traduction n'existe pas
        $defaultLocale = Language::where('is_default', true)->first()->code ?? 'fr';
        $defaultTranslation = $this->getTranslation($defaultLocale);
        
        // Utiliser la meta_description ou la description de la traduction par défaut
        if ($defaultTranslation) {
            return $defaultTranslation->meta_description ?? $defaultTranslation->description;
        }
        
        return null;
    }

    /**
     * Obtenir toutes les catégories actives.
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
