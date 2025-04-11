<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    /**
     * Handle newsletter subscription.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        // Si l'utilisateur existe déjà, mettre à jour son statut d'abonnement
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $user->update([
                'newsletter_subscribed' => true
            ]);
            
            return back()->with('success', __('You have been subscribed to our newsletter.'));
        }
        
        // Pour l'instant, nous enregistrons simplement l'email (pourrait être amélioré avec une table dédiée)
        Log::info('Newsletter subscription request', [
            'email' => $request->email
        ]);
        
        return back()->with('success', __('Thank you for subscribing to our newsletter.'));
    }
}
