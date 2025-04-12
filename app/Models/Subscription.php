<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    /**
     * Les constantes des statuts d'abonnement.
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Les constantes des fréquences d'abonnement.
     */
    const FREQUENCY_MONTHLY = 'monthly';
    const FREQUENCY_ANNUAL = 'annual';
    const FREQUENCY_LIFETIME = 'lifetime';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'processor',
        'processor_id',
        'status',
        'frequency',
        'amount',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'auto_renew',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    /**
     * Obtenir l'utilisateur associé à cet abonnement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir le plan associé à cet abonnement.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Vérifier si l'abonnement est actif.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Vérifier si l'abonnement est annulé.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Vérifier si l'abonnement est expiré.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Vérifier si l'abonnement est à vie.
     *
     * @return bool
     */
    public function isLifetime(): bool
    {
        return $this->frequency === self::FREQUENCY_LIFETIME;
    }

    /**
     * Vérifier si l'abonnement est en période d'essai.
     *
     * @return bool
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && now()->lt($this->trial_ends_at);
    }

    /**
     * Obtenir l'abonnement actif d'un utilisateur.
     *
     * @param int $userId
     * @return self|null
     */
    public static function getActiveSubscription(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('status', self::STATUS_ACTIVE)
            ->where(function ($query) {
                $query->where('ends_at', '>', now())
                    ->orWhereNull('ends_at');
            })
            ->latest()
            ->first();
    }
}
