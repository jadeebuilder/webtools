<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('admin.payments.transactions.index', ['locale' => app()->getLocale()]) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 mr-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Détails de la transaction') }} #{{ $transaction->id }}</h1>
                </div>
                
                @if($transaction->status == 'completed' || $transaction->status == 'paid')
                    <form action="{{ route('admin.payments.transactions.mark-as-refunded', ['locale' => app()->getLocale(), 'transaction' => $transaction->id]) }}" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir marquer cette transaction comme remboursée?') }}')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                            <i class="fas fa-undo mr-1"></i> {{ __('Marquer comme remboursée') }}
                        </button>
                    </form>
                @endif
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Informations principales') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ID') }}</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->id }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('UUID') }}</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->uuid }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Type') }}</p>
                            <p class="text-base font-medium">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->type == 'payment' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                    {{ $transaction->type == 'refund' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                    {{ $transaction->type == 'subscription' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                    {{ $transaction->type == 'credit' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : '' }}
                                ">
                                    {{ $types[$transaction->type] ?? $transaction->type }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Statut') }}</p>
                            <p class="text-base font-medium">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->status == 'completed' || $transaction->status == 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                    {{ $transaction->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                    {{ $transaction->status == 'failed' || $transaction->status == 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                    {{ $transaction->status == 'refunded' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                ">
                                    {{ $statuses[$transaction->status] ?? $transaction->status }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Montant') }}</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->getFormattedAmount() }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date de création') }}</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        
                        @if($transaction->paid_at)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date de paiement') }}</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->paid_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        @endif
                        
                        @if($transaction->ends_at)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date de fin') }}</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->ends_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Méthode de paiement') }}</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->payment_method }}</p>
                        </div>
                        
                        @if($transaction->payment_id)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ID de paiement') }}</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->payment_id }}</p>
                            </div>
                        @endif
                        
                        @if($transaction->invoice_id)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ID de facture') }}</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->invoice_id }}</p>
                            </div>
                        @endif
                        
                        @if($transaction->subscription_id)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ID d\'abonnement') }}</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->subscription_id }}</p>
                            </div>
                        @endif
                        
                        @if($transaction->cycle)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Cycle') }}</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->cycle }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($transaction->meta && is_array($transaction->meta) && count($transaction->meta) > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6">
                        <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Métadonnées') }}</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Clé') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Valeur') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($transaction->meta as $key => $value)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $key }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                @if(is_array($value) || is_object($value))
                                                    <pre class="whitespace-pre-wrap">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                
                @if($relatedTransactions && $relatedTransactions->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6">
                        <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Transactions liées') }}</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Type') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Montant') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Statut') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date') }}</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($relatedTransactions as $relatedTransaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $relatedTransaction->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $relatedTransaction->type == 'payment' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                                    {{ $relatedTransaction->type == 'refund' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                                    {{ $relatedTransaction->type == 'subscription' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                                    {{ $relatedTransaction->type == 'credit' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : '' }}
                                                ">
                                                    {{ $types[$relatedTransaction->type] ?? $relatedTransaction->type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $relatedTransaction->getFormattedAmount() }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $relatedTransaction->status == 'completed' || $relatedTransaction->status == 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                                    {{ $relatedTransaction->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                                    {{ $relatedTransaction->status == 'failed' || $relatedTransaction->status == 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                                    {{ $relatedTransaction->status == 'refunded' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                                ">
                                                    {{ $statuses[$relatedTransaction->status] ?? $relatedTransaction->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $relatedTransaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="{{ route('admin.payments.transactions.show', ['locale' => app()->getLocale(), 'transaction' => $relatedTransaction->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Informations complémentaires -->
            <div class="lg:col-span-1">
                <!-- Informations utilisateur -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Informations utilisateur') }}</h2>
                    
                    @if($transaction->user)
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-2xl text-gray-500 dark:text-gray-300">
                                    {{ substr($transaction->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $transaction->user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.users.show', ['locale' => app()->getLocale(), 'user' => $transaction->user_id]) }}" class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                {{ __('Voir le profil utilisateur') }}
                            </a>
                        </div>
                    @else
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Utilisateur supprimé') }}</div>
                    @endif
                </div>
                
                <!-- Informations package -->
                @if($transaction->package)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Informations package') }}</h2>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Nom du package') }}</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->package->getName() }}</p>
                        </div>
                        
                        @if($transaction->package_price)
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Prix') }}</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    @if($transaction->cycle == 'monthly')
                                        {{ $transaction->package_price->monthly_price }} {{ $transaction->currency->code }} / {{ __('mois') }}
                                    @elseif($transaction->cycle == 'yearly')
                                        {{ $transaction->package_price->annual_price }} {{ $transaction->currency->code }} / {{ __('an') }}
                                    @elseif($transaction->cycle == 'lifetime')
                                        {{ $transaction->package_price->lifetime_price }} {{ $transaction->currency->code }} / {{ __('à vie') }}
                                    @endif
                                </p>
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.packages.edit', ['locale' => app()->getLocale(), 'package' => $transaction->package_id]) }}" class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                {{ __('Voir le package') }}
                            </a>
                        </div>
                    </div>
                @endif
                
                <!-- Abonnement -->
                @if($transaction->subscription_id)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Abonnement') }}</h2>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('ID d\'abonnement') }}</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->subscription_id }}</p>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.payments.subscriptions.show', ['locale' => app()->getLocale(), 'id' => $transaction->subscription_id]) }}" class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                {{ __('Voir l\'abonnement') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout> 