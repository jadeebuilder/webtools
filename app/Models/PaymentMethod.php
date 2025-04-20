<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'settings',
        'processor_class',
        'icon',
        'display_order',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Les devises associées à cette méthode de paiement.
     */
    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'currency_payment_method')
            ->withPivot('is_active', 'settings', 'display_order')
            ->withTimestamps();
    }

    /**
     * Obtenir une instance du processeur de paiement pour cette méthode.
     *
     * @param array|null $config Configuration supplémentaire
     * @return mixed
     * @throws \Exception Si la classe de processeur n'existe pas
     */
    public function getProcessor(?array $config = null)
    {
        if (empty($this->processor_class) || !class_exists($this->processor_class)) {
            throw new \Exception("Classe de processeur de paiement non trouvée : {$this->processor_class}");
        }

        // Fusionner les configurations
        $mergedConfig = array_merge($this->settings ?? [], $config ?? []);
        
        return new $this->processor_class($mergedConfig);
    }

    /**
     * Les méthodes de paiement actives.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtenir le processeur de paiement pour un code donné.
     *
     * @param string $code Code de la méthode de paiement
     * @param array|null $config Configuration supplémentaire
     * @return mixed
     * @throws \Exception Si la méthode de paiement n'existe pas
     */
    public static function getProcessorByCode(string $code, ?array $config = null)
    {
        $method = self::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$method) {
            throw new \Exception("Méthode de paiement non trouvée : {$code}");
        }

        return $method->getProcessor($config);
    }
} 