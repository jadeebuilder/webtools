<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position',
        'content',
        'avatar',
        'rating',
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
        'rating' => 'integer',
    ];

    /**
     * Get the translations for this testimonial.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TestimonialTranslation::class);
    }

    /**
     * Scope for active testimonials.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered testimonials.
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
     * Get translated position for the current language.
     */
    public function getPositionAttribute(): string
    {
        return $this->getPosition();
    }

    /**
     * Get translated content for the current language.
     */
    public function getContentAttribute(): string
    {
        return $this->getContent();
    }

    /**
     * Méthode explicite pour obtenir le nom traduit.
     */
    public function getName(): string
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        
        if ($currentLanguage) {
            $translation = $this->translations()->where('language_id', $currentLanguage->id)->first();
            if ($translation && !empty($translation->name)) {
                return $translation->name;
            }
        }
        
        // Si pas de traduction, retourner la valeur par défaut
        return $this->getOriginal('name');
    }

    /**
     * Méthode explicite pour obtenir la position traduite.
     */
    public function getPosition(): string
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        
        if ($currentLanguage) {
            $translation = $this->translations()->where('language_id', $currentLanguage->id)->first();
            if ($translation && !empty($translation->position)) {
                return $translation->position;
            }
        }
        
        // Si pas de traduction, retourner la valeur par défaut
        return $this->getOriginal('position');
    }

    /**
     * Méthode explicite pour obtenir le contenu traduit.
     */
    public function getContent(): string
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        
        if ($currentLanguage) {
            $translation = $this->translations()->where('language_id', $currentLanguage->id)->first();
            if ($translation && !empty($translation->content)) {
                return $translation->content;
            }
        }
        
        // Si pas de traduction, retourner la valeur par défaut
        return $this->getOriginal('content');
    }
} 