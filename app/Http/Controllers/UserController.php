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
        return view('user.account', [
            'user' => Auth::user(),
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
        return view('user.packages', [
            'user' => Auth::user(),
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
