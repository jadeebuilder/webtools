<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdBlockSetting extends Model
{
    use HasFactory;

    /**
     * Les attributs assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enabled',
        'block_content',
        'show_message',
        'message_title',
        'message_text',
        'message_button',
        'countdown',
    ];

    /**
     * Les attributs qui doivent être convertis en types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enabled' => 'boolean',
        'block_content' => 'boolean',
        'show_message' => 'boolean',
        'countdown' => 'integer',
    ];

    /**
     * Récupère les paramètres de détection d'AdBlock
     *
     * @return array
     */
    public static function getSettings(): array
    {
        $settings = self::first();
        
        if (!$settings) {
            // Si aucun réglage n'existe, créer les réglages par défaut
            $settings = self::create([
                'enabled' => true,
                'block_content' => false,
                'show_message' => true,
                'message_title' => 'Nous avons détecté que vous utilisez un bloqueur de publicités',
                'message_text' => 'Notre site est gratuit et ne survit que grâce à la publicité. Merci de désactiver votre bloqueur de publicités pour continuer.',
                'message_button' => 'J\'ai désactivé mon AdBlock',
                'countdown' => 10,
            ]);
        }
        
        return $settings->toArray();
    }
}
