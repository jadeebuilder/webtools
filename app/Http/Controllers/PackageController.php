<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Affiche la liste des packages disponibles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $packages = Package::getActive();
        
        // Grouper les packages par prix mensuel pour l'affichage
        $packagesByPrice = $packages->groupBy(function($package) {
            return $package->monthly_price == 0 ? 'free' : 'paid';
        });
        
        return view('packages', compact('packagesByPrice'));
    }
} 