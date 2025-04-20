<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'package_id',
        'status',
        'payment_provider',
        'payment_method',
        'subscription_id',
        'cycle',
        'quantity',
        'currency_id',
        'amount',
        'next_billing_date',
        'trial_ends_at',
        'cancelled_at',
        'ends_at',
        'meta',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'next_billing_date' => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'ends_at' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package that this subscription belongs to.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the currency for this subscription.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get all transactions for this subscription.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Détermine si l'abonnement est actif.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' &&
            ($this->ends_at === null || $this->ends_at->isFuture());
    }

    /**
     * Détermine si l'abonnement est annulé.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled' || 
            $this->cancelled_at !== null;
    }

    /**
     * Détermine si l'abonnement est expiré.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
            ($this->ends_at !== null && $this->ends_at->isPast());
    }

    /**
     * Détermine si l'abonnement est en période d'essai.
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at !== null && 
            $this->trial_ends_at->isFuture();
    }

    /**
     * Obtenir le montant formaté avec le symbole de la devise.
     */
    public function getFormattedAmount(): string
    {
        if ($this->currency) {
            return $this->currency->symbol . ' ' . number_format($this->amount, 2);
        }
        
        return number_format($this->amount, 2);
    }

    /**
     * Obtenir le statut de l'abonnement formaté.
     */
    public function getStatus(): string
    {
        // Priorité à l'état stocké
        if ($this->status === 'active' && $this->isExpired()) {
            return 'expired';
        }
        
        if ($this->status === 'active' && $this->isCancelled()) {
            return 'cancelled';
        }
        
        return $this->status;
    }

    /**
     * Obtenir la date de fin formatée.
     */
    public function getEndsAtFormatted(): string
    {
        return $this->ends_at ? $this->ends_at->format('d/m/Y H:i') : 'N/A';
    }

    /**
     * Obtenir la prochaine date de facturation formatée.
     */
    public function getNextBillingDateFormatted(): string
    {
        return $this->next_billing_date ? $this->next_billing_date->format('d/m/Y') : 'N/A';
    }

    /**
     * Obtenir le nombre de jours restants avant expiration.
     */
    public function getDaysRemaining(): int
    {
        if (!$this->ends_at) {
            return 0;
        }
        
        return max(0, Carbon::now()->diffInDays($this->ends_at, false));
    }
}
