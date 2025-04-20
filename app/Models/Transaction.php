<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Types de transactions
     */
    const TYPE_PAYMENT = 'payment';
    const TYPE_REFUND = 'refund';
    const TYPE_CREDIT = 'credit';
    const TYPE_SUBSCRIPTION = 'subscription';
    const TYPE_TRIAL = 'trial';

    /**
     * Statuts de transactions
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'package_id',
        'package_price_id',
        'payment_provider',
        'payment_method',
        'payment_id',
        'invoice_id',
        'subscription_id',
        'reference_id',
        'type',
        'cycle',
        'amount',
        'currency_id',
        'status',
        'meta',
        'paid_at',
        'ends_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'meta' => 'array',
        'paid_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Le hook "creating" du modèle.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
            
            // Si les dates sont nulles, leur définir une valeur par défaut
            if (!isset($model->paid_at)) {
                $model->paid_at = null;
            }
            
            if (!isset($model->ends_at)) {
                $model->ends_at = null;
            }
        });
    }

    /**
     * Obtenir l'utilisateur associé à cette transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir le package associé à cette transaction.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Obtenir le prix du package associé à cette transaction.
     */
    public function packagePrice(): BelongsTo
    {
        return $this->belongsTo(PackagePrice::class);
    }

    /**
     * Obtenir la devise associée à cette transaction.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Déterminer si la transaction est payée.
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_COMPLETED && $this->paid_at !== null;
    }

    /**
     * Déterminer si la transaction est en attente.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Déterminer si la transaction a échoué.
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Déterminer si la transaction a été remboursée.
     */
    public function isRefunded(): bool
    {
        return in_array($this->status, [self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED]);
    }

    /**
     * Marquer la transaction comme payée.
     */
    public function markAsPaid(): self
    {
        $this->status = self::STATUS_COMPLETED;
        $this->paid_at = now();
        $this->save();

        return $this;
    }

    /**
     * Marquer la transaction comme échouée.
     */
    public function markAsFailed(): self
    {
        $this->status = self::STATUS_FAILED;
        $this->save();

        return $this;
    }

    /**
     * Marquer la transaction comme remboursée.
     */
    public function markAsRefunded(bool $partial = false): self
    {
        $this->status = $partial ? self::STATUS_PARTIALLY_REFUNDED : self::STATUS_REFUNDED;
        $this->save();

        return $this;
    }

    /**
     * Générer une référence de transaction unique.
     *
     * @return string
     */
    public static function generateReferenceId(): string
    {
        $prefix = 'TRX-';
        $timestamp = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        
        $reference = $prefix . $timestamp . '-' . $random;
        
        // Vérifier si la référence existe déjà et régénérer si nécessaire
        if (self::where('reference_id', $reference)->exists()) {
            return self::generateReferenceId();
        }
        
        return $reference;
    }

    /**
     * Obtenir le montant formaté avec la devise.
     *
     * @param bool $includeSymbol Inclure ou non le symbole de la devise
     * @param bool $includeCode Inclure ou non le code de la devise
     * @return string
     */
    public function getFormattedAmount(bool $includeSymbol = true, bool $includeCode = false): string
    {
        if ($includeSymbol) {
            return $this->currency->format($this->amount, $includeCode);
        }
        
        return number_format(
            $this->amount,
            $this->currency->precision,
            $this->currency->decimal_mark,
            $this->currency->thousands_separator
        );
    }
} 