<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Données pour les outils populaires
        $popularTools = [
            [
                'icon' => 'search',
                'name' => 'IP Lookup',
                'description' => 'Get approximate IP details.',
                'views' => 3,
                'likes' => 0,
                'slug' => 'ip-lookup'
            ],
            [
                'icon' => 'refresh',
                'name' => 'Reverse IP Lookup',
                'description' => 'Take an IP and try to look for the domain/host associated.',
                'views' => 3,
                'likes' => 0,
                'slug' => 'reverse-ip-lookup'
            ],
            // Autres outils populaires...
        ];

        // Données pour les catégories d'outils
        $toolCategories = [
            [
                'icon' => 'check-circle',
                'name' => 'Checker tools',
                'description' => 'A collection of great checker-type tools to help you check & verify different types of things.',
                'slug' => 'checker'
            ],
            [
                'icon' => 'text',
                'name' => 'Text tools',
                'description' => 'A collection of text content related tools to help you create, modify & improve text type of content.',
                'slug' => 'text'
            ],
            [
                'icon' => 'exchange',
                'name' => 'Converter tools',
                'description' => 'A collection of tools that help you easily convert data.',
                'slug' => 'converter'
            ],
            [
                'icon' => 'code',
                'name' => 'Generator tools',
                'description' => 'A collection of the most useful generator tools that you can generate data with.',
                'slug' => 'generator'
            ],
            [
                'icon' => 'terminal',
                'name' => 'Developer tools',
                'description' => 'A collection of highly useful tools mainly for developers and not only.',
                'slug' => 'developer'
            ],
            [
                'icon' => 'image',
                'name' => 'Image manipulation tools',
                'description' => 'A collection of tools that help modify & convert image files.',
                'slug' => 'image'
            ]
        ];

        return view('home', compact('popularTools', 'toolCategories'));
    }
}
