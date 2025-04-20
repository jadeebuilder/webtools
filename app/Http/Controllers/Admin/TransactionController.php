<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Currency;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Affiche la liste des transactions.
     *
     * @param string $locale
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(string $locale, Request $request)
    {
        try {
            $query = Transaction::with(['user', 'currency', 'package']);
            
            // Filtres
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->filled('user')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->user . '%')
                      ->orWhere('email', 'like', '%' . $request->user . '%');
                });
            }
            
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            
            if ($request->filled('min_amount')) {
                $query->where('amount', '>=', $request->min_amount);
            }
            
            if ($request->filled('max_amount')) {
                $query->where('amount', '<=', $request->max_amount);
            }
            
            // Tri
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            
            $allowedSortFields = ['id', 'created_at', 'amount', 'status', 'type', 'payment_method'];
            if (!in_array($sortField, $allowedSortFields)) {
                $sortField = 'created_at';
            }
            
            $query->orderBy($sortField, $sortDirection);
            
            // Pagination
            $transactions = $query->paginate(15)->withQueryString();
            
            // Données pour les filtres
            $statuses = [
                Transaction::STATUS_PENDING => __('En attente'),
                Transaction::STATUS_COMPLETED => __('Complété'),
                Transaction::STATUS_FAILED => __('Échoué'),
                Transaction::STATUS_REFUNDED => __('Remboursé'),
                Transaction::STATUS_PARTIALLY_REFUNDED => __('Partiellement remboursé'),
                Transaction::STATUS_CANCELLED => __('Annulé')
            ];
            
            $types = [
                Transaction::TYPE_PAYMENT => __('Paiement'),
                Transaction::TYPE_REFUND => __('Remboursement'),
                Transaction::TYPE_CREDIT => __('Crédit'),
                Transaction::TYPE_SUBSCRIPTION => __('Abonnement')
            ];
            
            $paymentMethods = PaymentMethod::where('is_active', true)->pluck('name', 'code');
            
            return view('admin.payments.transactions.index', [
                'transactions' => $transactions,
                'request' => $request,
                'statuses' => $statuses,
                'types' => $types,
                'paymentMethods' => $paymentMethods
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des transactions: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard', ['locale' => $locale])
                ->with('error', __('Impossible de charger les transactions. ') . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'une transaction.
     *
     * @param string $locale
     * @param Transaction $transaction
     * @return \Illuminate\View\View
     */
    public function show(string $locale, Transaction $transaction)
    {
        try {
            $transaction->load(['user', 'currency', 'package', 'packagePrice']);
            
            return view('admin.payments.transactions.show', compact('transaction'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des détails de la transaction: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.transactions.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger les détails de la transaction. ') . $e->getMessage());
        }
    }

    /**
     * Marque une transaction comme remboursée.
     *
     * @param string $locale
     * @param Transaction $transaction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRefunded(string $locale, Transaction $transaction)
    {
        try {
            if ($transaction->status === Transaction::STATUS_REFUNDED) {
                return redirect()->route('admin.payments.transactions.show', ['locale' => $locale, 'transaction' => $transaction->id])
                    ->with('info', __('Cette transaction est déjà marquée comme remboursée.'));
            }
            
            if (!in_array($transaction->status, [Transaction::STATUS_COMPLETED, Transaction::STATUS_PARTIALLY_REFUNDED])) {
                return redirect()->route('admin.payments.transactions.show', ['locale' => $locale, 'transaction' => $transaction->id])
                    ->with('error', __('Seules les transactions complétées peuvent être remboursées.'));
            }
            
            DB::beginTransaction();
            
            try {
                // Marquer la transaction comme remboursée
                $transaction->status = Transaction::STATUS_REFUNDED;
                $transaction->save();
                
                // Créer une transaction de remboursement liée
                Transaction::create([
                    'user_id' => $transaction->user_id,
                    'package_id' => $transaction->package_id,
                    'package_price_id' => $transaction->package_price_id,
                    'payment_provider' => $transaction->payment_provider,
                    'payment_method' => $transaction->payment_method,
                    'payment_id' => null,
                    'invoice_id' => null,
                    'subscription_id' => $transaction->subscription_id,
                    'type' => Transaction::TYPE_REFUND,
                    'cycle' => $transaction->cycle,
                    'amount' => -1 * $transaction->amount, // Montant négatif pour le remboursement
                    'currency_id' => $transaction->currency_id,
                    'status' => Transaction::STATUS_COMPLETED,
                    'meta' => [
                        'original_transaction_id' => $transaction->id,
                        'refund_reason' => 'admin_manual_refund',
                        'refunded_by' => auth()->id(),
                        'refunded_at' => now()->toDateTimeString()
                    ],
                    'paid_at' => now(),
                ]);
                
                DB::commit();
                
                return redirect()->route('admin.payments.transactions.show', ['locale' => $locale, 'transaction' => $transaction->id])
                    ->with('success', __('La transaction a été marquée comme remboursée avec succès.'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur transaction: ' . $e->getMessage());
                return back()->with('error', __('Erreur lors du remboursement: ') . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du remboursement: ' . $e->getMessage());
            return back()->with('error', __('Erreur lors du remboursement: ') . $e->getMessage());
        }
    }
    
    /**
     * Exporte les transactions en CSV.
     *
     * @param string $locale
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(string $locale, Request $request)
    {
        try {
            $query = Transaction::with(['user', 'currency', 'package']);
            
            // Appliquer les mêmes filtres que pour l'index
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->filled('user')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->user . '%')
                      ->orWhere('email', 'like', '%' . $request->user . '%');
                });
            }
            
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            
            $transactions = $query->orderBy('created_at', 'desc')->get();
            
            $filename = 'transactions_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];
            
            $callback = function() use ($transactions) {
                $file = fopen('php://output', 'w');
                
                // Entêtes CSV
                fputcsv($file, [
                    'ID',
                    __('UUID'),
                    __('Utilisateur'),
                    __('Email'),
                    __('Package'),
                    __('Type'),
                    __('Cycle'),
                    __('Montant'),
                    __('Devise'),
                    __('Méthode de paiement'),
                    __('Statut'),
                    __('Date de paiement'),
                    __('Date de création'),
                ]);
                
                // Lignes de données
                foreach ($transactions as $transaction) {
                    fputcsv($file, [
                        $transaction->id,
                        $transaction->uuid,
                        $transaction->user ? $transaction->user->name : __('Utilisateur supprimé'),
                        $transaction->user ? $transaction->user->email : '',
                        $transaction->package ? $transaction->package->getName() : __('Package supprimé'),
                        $transaction->type,
                        $transaction->cycle,
                        $transaction->amount,
                        $transaction->currency ? $transaction->currency->code : '',
                        $transaction->payment_method,
                        $transaction->status,
                        $transaction->paid_at ? $transaction->paid_at->format('Y-m-d H:i:s') : '',
                        $transaction->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'exportation des transactions: ' . $e->getMessage());
            
            return redirect()->route('admin.payments.transactions.index', ['locale' => $locale])
                ->with('error', __('Impossible d\'exporter les transactions. ') . $e->getMessage());
        }
    }
} 