<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $fillable = [
        'country_id',
        'state_id',
        'name',
        'country_code',
     ];

    /**
     * Get the country that owns the city.
     *
     * @return BelongsTo
     */
     public function country(): BelongsTo
     {
         return $this->belongsTo(Country::class);
     }

    /**
     * Get the state that owns the city.
     *
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}