<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PackageTool extends Pivot
{
    use HasFactory;

    /**
     * Indique si les timestamps sont automatiquement gérés par Eloquent.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_id',
        'tool_id',
        'is_vip',
        'is_ai',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_vip' => 'boolean',
        'is_ai' => 'boolean',
    ];

    /**
     * Obtenir le package associé.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Obtenir l'outil associé.
     */
    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }
} 