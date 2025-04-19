<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'states';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'name',
        'country_code',
    ];

    /**
     * Get the country that owns the state.
     *
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the cities associated with the state.
     *
     * @return HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get the addresses associated with the state.
     *
     * @return HasMany
     */
    public function addresses()
    {
        return $this->hasMany(CompanyAddress::class);
    }
}