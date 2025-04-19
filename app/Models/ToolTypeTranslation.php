<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolTypeTranslation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tool_type_id',
        'locale',
        'name',
        'description',
    ];

    /**
     * Obtenir le type d'outil associé à cette traduction.
     */
    public function toolType(): BelongsTo
    {
        return $this->belongsTo(ToolType::class);
    }
} 