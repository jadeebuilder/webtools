<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolTranslation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tool_id',
        'locale',
        'name',
        'description',
        'custom_h1',
        'custom_description',
        'meta_title',
        'meta_description',
    ];

    /**
     * Obtenir l'outil associé à cette traduction.
     */
    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
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
     * Si non défini, utilise le nom de l'outil.
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
     * Si non définie, utilise la description de l'outil.
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

    /**
     * Obtenir le H1 personnalisé pour cette traduction.
     * Si non défini, utilise le nom de l'outil.
     *
     * @return string
     */
    public function getCustomH1(): string
    {
        if (!empty($this->custom_h1)) {
            return $this->custom_h1;
        }

        return $this->name;
    }

    /**
     * Obtenir la description personnalisée pour cette traduction.
     * Si non définie, utilise la description de l'outil.
     *
     * @return string|null
     */
    public function getCustomDescription(): ?string
    {
        if (!empty($this->custom_description)) {
            return $this->custom_description;
        }

        return $this->description;
    }
}
