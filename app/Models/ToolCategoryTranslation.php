<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolCategoryTranslation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tool_category_id',
        'locale',
        'name',
        'description',
        'meta_title',
        'meta_description',
    ];

    /**
     * Obtenir la catégorie d'outil associée à cette traduction.
     */
    public function toolCategory(): BelongsTo
    {
        return $this->belongsTo(ToolCategory::class);
    }

    /**
     * Obtenir la langue associée à cette traduction.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }

    /**
     * Obtenir le titre meta pour cette traduction.
     * Si non défini, utilise le nom de la catégorie.
     *
     * @return string
     */
    public function getMetaTitle(): string
    {
        if (!empty($this->meta_title)) {
            return $this->meta_title;
        }

        return $this->name;
    }

    /**
     * Obtenir la description meta pour cette traduction.
     * Si non définie, utilise la description de la catégorie.
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        if (!empty($this->meta_description)) {
            return $this->meta_description;
        }

        return $this->description;
    }
}
