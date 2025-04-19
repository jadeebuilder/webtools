<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\SiteLanguage;

class FaqCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
        'order',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
    
    /**
     * Get the faqs related to this category.
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }
    
    /**
     * Get the translations for this category.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(FaqCategoryTranslation::class);
    }
    
    /**
     * Scope for active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope for ordered categories.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
    
    /**
     * Get translated name for the current language.
     */
    public function getNameAttribute(): string
    {
        return $this->getName();
    }
    
    /**
     * Get translated description for the current language.
     */
    public function getDescriptionAttribute(): ?string
    {
        return $this->getDescription();
    }
    
    /**
     * Get translated slug for the current language.
     */
    public function getSlugAttribute(): string
    {
        return $this->getSlug();
    }
    
    /**
     * Méthode explicite pour obtenir le nom traduit.
     */
    public function getName(): string
    {
        $defaultLanguage = SiteLanguage::getDefault();
        $currentLanguage = app()->getLocale();
        
        // Vérifier d'abord la langue actuelle
        $translation = $this->translations()
            ->whereHas('language', function ($query) use ($currentLanguage) {
                $query->where('code', $currentLanguage);
            })
            ->first();
            
        // Si pas de traduction dans la langue actuelle, essayer la langue par défaut
        if (!$translation || empty($translation->name)) {
            $translation = $this->translations()
                ->whereHas('language', function ($query) use ($defaultLanguage) {
                    $query->where('id', $defaultLanguage->id);
                })
                ->first();
        }
        
        // Si une traduction valide existe, l'utiliser
        if ($translation && !empty($translation->name)) {
            return $translation->name;
        }
        
        // Fallback : utiliser le nom de base s'il existe
        if (!empty($this->attributes['name'])) {
            return $this->attributes['name'];
        }
        
        // Dernier recours : utiliser un nom générique basé sur l'ID
        return 'Catégorie ' . $this->id;
    }
    
    /**
     * Méthode explicite pour obtenir la description traduite.
     */
    public function getDescription(): ?string
    {
        $defaultLanguage = SiteLanguage::getDefault();
        $currentLanguage = app()->getLocale();
        
        // Vérifier d'abord la langue actuelle
        $translation = $this->translations()
            ->whereHas('language', function ($query) use ($currentLanguage) {
                $query->where('code', $currentLanguage);
            })
            ->first();
            
        // Si pas de traduction dans la langue actuelle, essayer la langue par défaut
        if (!$translation || empty($translation->description)) {
            $translation = $this->translations()
                ->whereHas('language', function ($query) use ($defaultLanguage) {
                    $query->where('id', $defaultLanguage->id);
                })
                ->first();
        }
        
        // Si une traduction valide existe avec une description, l'utiliser
        if ($translation && !empty($translation->description)) {
            return $translation->description;
        }
        
        // Fallback : utiliser la description de base si elle existe
        if (!empty($this->attributes['description'])) {
            return $this->attributes['description'];
        }
        
        // Dernier recours : retourner null
        return null;
    }
    
    /**
     * Méthode explicite pour obtenir le slug traduit.
     */
    public function getSlug(): string
    {
        $defaultLanguage = SiteLanguage::getDefault();
        $currentLanguage = app()->getLocale();
        
        // Vérifier d'abord la langue actuelle
        $translation = $this->translations()
            ->whereHas('language', function ($query) use ($currentLanguage) {
                $query->where('code', $currentLanguage);
            })
            ->first();
            
        // Si pas de traduction dans la langue actuelle, essayer la langue par défaut
        if (!$translation || empty($translation->slug)) {
            $translation = $this->translations()
                ->whereHas('language', function ($query) use ($defaultLanguage) {
                    $query->where('id', $defaultLanguage->id);
                })
                ->first();
        }
        
        // Si une traduction valide existe avec un slug, l'utiliser
        if ($translation && !empty($translation->slug)) {
            return $translation->slug;
        }
        
        // Fallback : utiliser le slug de base s'il existe
        if (!empty($this->attributes['slug'])) {
            return $this->attributes['slug'];
        }
        
        // Dernier recours : utiliser 'category-' suivi de l'ID
        return 'category-' . $this->id;
    }
    
    /**
     * Set the slug attribute.
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value ?: $this->attributes['name'] ?? '');
    }
} 