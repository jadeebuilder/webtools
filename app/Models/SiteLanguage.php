<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteLanguage extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'site_languages';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'flag',
        'is_active',
        'is_default',
        'is_rtl',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'is_rtl' => 'boolean',
    ];

    /**
     * Obtenir les traductions d'outils pour cette langue.
     */
    public function toolTranslations(): HasMany
    {
        return $this->hasMany(ToolTranslation::class, 'locale', 'code');
    }
    
    /**
     * Obtenir les traductions de témoignages pour cette langue.
     */
    public function testimonialTranslations(): HasMany
    {
        return $this->hasMany(TestimonialTranslation::class);
    }

    /**
     * Obtenir la langue par défaut.
     *
     * @return self|null
     */
    public static function getDefault(): ?self
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Obtenir toutes les langues actives.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('is_default', 'desc')->orderBy('name')->get();
    }

    /**
     * Obtenir les codes de toutes les langues actives.
     *
     * @return array
     */
    public static function getActiveCodes(): array
    {
        return self::where('is_active', true)->pluck('code')->toArray();
    }

    /**
     * Obtenir le code de la langue par défaut.
     *
     * @return string
     */
    public static function getDefaultCode(): string
    {
        $default = self::getDefault();
        return $default ? $default->code : 'fr';
    }
}
