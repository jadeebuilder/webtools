<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'annual_price',
        'lifetime_price',
        'is_active',
        'is_featured',
        'is_default',
        'order',
        'features',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monthly_price' => 'decimal:2',
        'annual_price' => 'decimal:2',
        'lifetime_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer',
        'features' => 'array',
    ];

    /**
     * Obtenir les outils associés à ce plan.
     */
    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'plan_tools')
            ->withTimestamps();
    }

    /**
     * Obtenir les abonnements associés à ce plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Vérifier si le plan est gratuit.
     *
     * @return bool
     */
    public function isFree(): bool
    {
        return $this->monthly_price == 0 && $this->annual_price == 0 && $this->lifetime_price == 0;
    }

    /**
     * Obtenir le prix selon la fréquence.
     *
     * @param string $frequency monthly|annual|lifetime
     * @return float
     */
    public function getPriceByFrequency(string $frequency): float
    {
        return match ($frequency) {
            'monthly' => (float) $this->monthly_price,
            'annual' => (float) $this->annual_price,
            'lifetime' => (float) $this->lifetime_price,
            default => 0.0,
        };
    }

    /**
     * Obtenir le plan par défaut.
     *
     * @return self|null
     */
    public static function getDefault(): ?self
    {
        return self::where('is_default', true)->where('is_active', true)->first();
    }

    /**
     * Obtenir tous les plans actifs.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();
    }
}
