<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdSetting extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'ad_settings';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position',      // Position de la publicité (before_nav, after_nav, etc.)
        'active',        // Statut actif ou inactif
        'type',          // Type de publicité (image, adsense)
        'image',         // Chemin de l'image (pour le type image)
        'link',          // Lien de l'image (pour le type image)
        'alt',           // Texte alternatif (pour le type image)
        'code',          // Code JavaScript pour AdSense
        'display_on',    // Pages où afficher cette publicité (home, tool, category, admin)
        'priority',      // Priorité d'affichage
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'display_on' => 'array',
    ];
} 