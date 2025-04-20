<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Setting;

class Currency extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'name',
        'code',
        'precision',
        'symbol',
        'symbol_native',
        'symbol_first',
        'decimal_mark',
        'thousands_separator'
    ];
    
    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
      'symbol_first' => 'boolean',
    ];

    /**
     * Get the country that owns the currency.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    
    /**
     * Les méthodes de paiement associées à cette devise.
     *
     * @return BelongsToMany
     */
    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, 'currency_payment_method')
            ->withPivot('is_active', 'settings', 'display_order')
            ->withTimestamps();
    }
    
    /**
     * Relation avec les prix des packages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packagePrices()
    {
        return $this->hasMany(PackagePrice::class);
    }
    
    /**
     * Obtenir la devise par défaut configurée dans les paramètres du site.
     *
     * @return self|null
     */
    public static function getDefault(): ?self
    {
        $defaultCurrencyId = Setting::get('default_currency');
        
        if (!$defaultCurrencyId) {
            // Si aucune devise par défaut n'est définie, essayer de trouver l'EUR ou retourner la première devise
            return self::where('code', 'EUR')->first() ?? self::first();
        }
        
        return self::find($defaultCurrencyId);
    }
    
    /**
     * Formater un montant selon les règles de la devise.
     *
     * @param float $amount Le montant à formater
     * @param bool $includeCode Inclure ou non le code de la devise (ex: EUR)
     * @return string Le montant formaté
     */
    public function format(float $amount, bool $includeCode = false): string
    {
        $precision = $this->precision;
        $decimalMark = $this->decimal_mark;
        $thousandsSeparator = $this->thousands_separator;
        $symbol = $this->symbol;
        $symbolFirst = $this->symbol_first;
        
        // Formater le montant
        $formattedAmount = number_format($amount, $precision, $decimalMark, $thousandsSeparator);
        
        // Construire la chaîne de sortie
        if ($symbolFirst) {
            $output = $symbol . $formattedAmount;
        } else {
            $output = $formattedAmount . ' ' . $symbol;
        }
        
        // Ajouter le code de la devise si demandé
        if ($includeCode) {
            $output .= ' ' . $this->code;
        }
        
        return $output;
    }
    
    /**
     * Formater un montant selon les règles de la devise par défaut.
     *
     * @param float $amount Le montant à formater
     * @param bool $includeCode Inclure ou non le code de la devise (ex: EUR)
     * @return string Le montant formaté
     */
    public static function formatDefault(float $amount, bool $includeCode = false): string
    {
        $defaultCurrency = self::getDefault();
        
        if (!$defaultCurrency) {
            // Si pas de devise par défaut, retourner le montant brut
            return (string) $amount;
        }
        
        return $defaultCurrency->format($amount, $includeCode);
    }
}