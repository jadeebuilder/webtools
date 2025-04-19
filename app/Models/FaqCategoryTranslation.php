<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqCategoryTranslation extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faq_category_id',
        'language_id',
        'name',
        'description',
        'slug',
    ];

    /**
     * Get the category that owns the translation.
     */
    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    /**
     * Get the language that owns the translation.
     */
    public function language()
    {
        return $this->belongsTo(SiteLanguage::class, 'language_id');
    }
} 