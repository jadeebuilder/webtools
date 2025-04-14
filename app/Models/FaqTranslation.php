<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqTranslation extends Model
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
        'faq_id',
        'language_id',
        'question',
        'answer',
    ];

    /**
     * Get the FAQ that owns the translation.
     */
    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }

    /**
     * Get the language that owns the translation.
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
} 