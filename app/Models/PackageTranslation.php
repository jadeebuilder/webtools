<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageTranslation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_id',
        'locale',
        'name',
        'description',
        'features',
    ];

    /**
     * Obtenir le package associé à cette traduction.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
} 