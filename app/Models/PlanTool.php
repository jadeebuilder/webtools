<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlanTool extends Pivot
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
        'plan_id',
        'tool_id',
    ];

    /**
     * Obtenir le plan associé.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Obtenir l'outil associé.
     */
    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }
}
