<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackagePrice extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_id',
        'currency_id',
        'monthly_price',
        'annual_price',
        'lifetime_price',
        'payment_provider_ids',
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
        'payment_provider_ids' => 'array',
    ];

    /**
     * Le package associé à ce prix.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * La devise associée à ce prix.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Obtenir le prix formaté pour un cycle donné.
     *
     * @param string $cycle monthly, annual, lifetime
     * @param bool $includeSymbol Inclure ou non le symbole de la devise
     * @param bool $includeCode Inclure ou non le code de la devise
     * @return string
     */
    public function getFormattedPrice(string $cycle, bool $includeSymbol = true, bool $includeCode = false): string
    {
        $price = $this->getPriceForCycle($cycle);
        
        if ($price <= 0) {
            return __('Gratuit');
        }
        
        if ($includeSymbol) {
            return $this->currency->format($price, $includeCode);
        }
        
        return number_format($price, $this->currency->precision, $this->currency->decimal_mark, $this->currency->thousands_separator);
    }

    /**
     * Obtenir le prix pour un cycle donné.
     *
     * @param string $cycle monthly, annual, lifetime
     * @return float
     */
    public function getPriceForCycle(string $cycle): float
    {
        return match($cycle) {
            'monthly' => (float) $this->monthly_price,
            'annual' => (float) $this->annual_price,
            'lifetime' => (float) $this->lifetime_price,
            default => 0,
        };
    }

    /**
     * Obtenir l'ID du produit chez un fournisseur de paiement pour un cycle donné.
     *
     * @param string $provider Code du fournisseur (stripe, paypal, etc.)
     * @param string $cycle monthly, annual, lifetime
     * @return string|null
     */
    public function getPaymentProviderId(string $provider, string $cycle): ?string
    {
        if (!isset($this->payment_provider_ids[$provider][$cycle])) {
            return null;
        }
        
        return $this->payment_provider_ids[$provider][$cycle];
    }

    /**
     * Définir l'ID du produit chez un fournisseur de paiement pour un cycle donné.
     *
     * @param string $provider Code du fournisseur (stripe, paypal, etc.)
     * @param string $cycle monthly, annual, lifetime
     * @param string $id ID du produit chez le fournisseur
     * @return void
     */
    public function setPaymentProviderId(string $provider, string $cycle, string $id): void
    {
        $ids = $this->payment_provider_ids ?? [];
        $ids[$provider][$cycle] = $id;
        $this->payment_provider_ids = $ids;
    }
} 