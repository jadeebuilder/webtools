<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Affiche la liste des abonnements
     */
    public function index(string $locale, Request $request)
    {
        try {
            $query = Subscription::query()->with(['user', 'package', 'transactions']);

            // Filtres
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('user') && $request->user) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->user . '%')
                      ->orWhere('email', 'like', '%' . $request->user . '%');
                });
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Tri
            $sortField = $request->sort ?? 'created_at';
            $sortDirection = $request->direction ?? 'desc';
            $query->orderBy($sortField, $sortDirection);

            // Pagination
            $subscriptions = $query->paginate(15)->withQueryString();

            // Statuts pour le filtre
            $statuses = [
                'active' => __('Actif'),
                'cancelled' => __('Annulé'),
                'expired' => __('Expiré'),
                'pending' => __('En attente'),
            ];

            return view('admin.payments.subscriptions.index', [
                'subscriptions' => $subscriptions,
                'request' => $request,
                'statuses' => $statuses,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des abonnements: ' . $e->getMessage());
            return redirect()->route('admin.dashboard', ['locale' => $locale])
                ->with('error', __('Impossible de charger les abonnements. ') . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'un abonnement
     */
    public function show(string $locale, $subscription)
    {
        try {
            // Vérifier si subscription est un objet ou un ID
            $subscriptionId = is_object($subscription) ? $subscription->id : $subscription;
            
            // Vérifier l'existence via DB
            $subscriptionData = DB::table('subscriptions')->where('id', $subscriptionId)->first();
            
            if (!$subscriptionData) {
                return redirect()->route('admin.payments.subscriptions.index', ['locale' => $locale])
                    ->with('error', __('Abonnement introuvable'));
            }
            
            // Charger le modèle avec ses relations
            $subscription = Subscription::with(['user', 'package', 'transactions'])->find($subscriptionId);
            
            // Transactions liées à cet abonnement
            $transactions = Transaction::where('subscription_id', $subscriptionId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('admin.payments.subscriptions.show', [
                'subscription' => $subscription,
                'transactions' => $transactions,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de l\'abonnement: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.subscriptions.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger l\'abonnement. ') . $e->getMessage());
        }
    }
} 