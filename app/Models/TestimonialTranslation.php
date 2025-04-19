<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestimonialTranslation extends Model
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
        'testimonial_id',
        'language_id',
        'name',
        'position',
        'content',
    ];

    /**
     * Get the testimonial that owns the translation.
     */
    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }

    /**
     * Get the language that owns the translation.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(SiteLanguage::class, 'language_id');
    }
} 