<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'iso2',
        'name',
        'status',
        'phone_code',
        'iso3',
        'region',
        'subregion',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
      'status' => 'boolean',
    ];

    /**
     * Get the states associated with the country.
     *
     * @return HasMany
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    /**
     * Get the cities associated with the country.
     *
     * @return HasMany
     */
     public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get the currency associated with the country.
     *
     * @return HasOne
     */
     public function currency(): HasOne
     {
         return $this->hasOne(Currency::class);
     }
}