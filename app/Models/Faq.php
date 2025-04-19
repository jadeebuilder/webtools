<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faq extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question',
        'answer',
        'faq_category_id',
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
     * Get the category that owns the faq.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
    
    /**
     * Get the translations for this FAQ.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }
    
    /**
     * Scope for active faqs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope for ordered faqs.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
    
    /**
     * Get translated question for the current language.
     */
    public function getQuestionAttribute(): string
    {
        return $this->getQuestion();
    }
    
    /**
     * Get translated answer for the current language.
     */
    public function getAnswerAttribute(): string
    {
        return $this->getAnswer();
    }
    
    /**
     * Méthode explicite pour obtenir la question traduite.
     */
    public function getQuestion(): string
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
        if (!$translation || empty($translation->question)) {
            $translation = $this->translations()
                ->whereHas('language', function ($query) use ($defaultLanguage) {
                    $query->where('id', $defaultLanguage->id);
                })
                ->first();
        }
        
        // Si une traduction valide existe, l'utiliser
        if ($translation && !empty($translation->question)) {
            return $translation->question;
        }
        
        // Fallback : utiliser la question de base si elle existe
        if (!empty($this->attributes['question'])) {
            return $this->attributes['question'];
        }
        
        // Dernier recours : retourner une chaîne vide
        return '';
    }
    
    /**
     * Méthode explicite pour obtenir la réponse traduite.
     */
    public function getAnswer(): string
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
        if (!$translation || empty($translation->answer)) {
            $translation = $this->translations()
                ->whereHas('language', function ($query) use ($defaultLanguage) {
                    $query->where('id', $defaultLanguage->id);
                })
                ->first();
        }
        
        // Si une traduction valide existe, l'utiliser
        if ($translation && !empty($translation->answer)) {
            return $translation->answer;
        }
        
        // Fallback : utiliser la réponse de base si elle existe
        if (!empty($this->attributes['answer'])) {
            return $this->attributes['answer'];
        }
        
        // Dernier recours : retourner une chaîne vide
        return '';
    }
} 