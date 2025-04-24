<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Affiche le compte de l'utilisateur.
     */
    public function account(): View
    {
        $user = Auth::user();
        $activeSubscription = null;
        $package = null;
        
        // Récupérer l'abonnement actif de l'utilisateur
        $subscription = $user->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();
            
        if ($subscription) {
            $activeSubscription = $subscription;
            $package = $subscription->package;
        }
        
        return view('user.account', [
            'user' => $user,
            'subscription' => $activeSubscription,
            'package' => $package,
        ]);
    }

    /**
     * Affiche les paramètres du compte.
     */
    public function settings(): View
    {
        return view('user.settings', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Affiche les packages de l'utilisateur.
     */
    public function packages(): View
    {
        $user = Auth::user();
        
        // Récupération de l'abonnement actif de l'utilisateur
        $activeSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();
            
        // Récupération de tous les packages disponibles pour upgrade
        $availablePackages = \App\Models\Package::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        // Si l'utilisateur a un abonnement actif, récupérer son package
        $currentPackage = null;
        if ($activeSubscription) {
            $currentPackage = $activeSubscription->package;
        }
        
        // Récupération de l'historique des transactions de l'utilisateur
        $transactions = \App\Models\Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('user.packages', [
            'user' => $user,
            'subscription' => $activeSubscription,
            'currentPackage' => $currentPackage,
            'availablePackages' => $availablePackages,
            'transactions' => $transactions
        ]);
    }

    /**
     * Affiche l'historique d'activité de l'utilisateur.
     */
    public function history(): View
    {
        return view('user.history', [
            'user' => Auth::user(),
        ]);
    }
}
