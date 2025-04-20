<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Détail de l\'abonnement') }} #{{ $subscription->id }}</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $subscription->subscription_id }}</p>
            </div>
            
            <div>
                <a href="{{ route('admin.payments.subscriptions.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-1"></i> {{ __('Retour') }}
                </a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informations de l'abonnement -->
            <div class="md:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Informations de l\'abonnement') }}</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Statut') }}</p>
                            <div class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $subscription->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                    {{ $subscription->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                    {{ $subscription->status == 'cancelled' ? 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100' : '' }}
                                    {{ $subscription->status == 'expired' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                ">
                                    {{ $subscription->getStatus() }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date de création') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Package') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($subscription->package)
                                    <a href="{{ route('admin.packages.show', ['locale' => app()->getLocale(), 'id' => $subscription->package->id]) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">
                                        {{ $subscription->package->getName() }}
                                    </a>
                                @else
                                    {{ __('Package supprimé') }}
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Cycle de paiement') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->cycle }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Montant') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">{{ $subscription->getFormattedAmount() }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Méthode de paiement') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->payment_method }} ({{ $subscription->payment_provider }})</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ID externe') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->subscription_id ?: 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Prochaine facturation') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->getNextBillingDateFormatted() }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date de fin') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $subscription->getEndsAtFormatted() }}
                                @if($subscription->ends_at && $subscription->ends_at->isFuture())
                                    <span class="text-xs ml-1">({{ $subscription->getDaysRemaining() }} {{ __('jours restants') }})</span>
                                @endif
                            </p>
                        </div>
                        
                        @if($subscription->trial_ends_at)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Fin de période d\'essai') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->trial_ends_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        
                        @if($subscription->cancelled_at)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date d\'annulation') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->cancelled_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Métadonnées -->
                @if($subscription->meta && count($subscription->meta) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Métadonnées') }}</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Clé') }}</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Valeur') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($subscription->meta as $key => $value)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $key }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                        @if(is_array($value))
                                            <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
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
                
                <!-- Transactions liées -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Transactions liées') }}</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Date') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Type') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Montant') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Statut') }}</th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->id }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $transaction->type == 'payment' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                            {{ $transaction->type == 'refund' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                            {{ $transaction->type == 'subscription' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                            {{ $transaction->type == 'credit' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : '' }}
                                        ">
                                            {{ $transaction->type }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->getFormattedAmount() }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $transaction->status == 'completed' || $transaction->status == 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                            {{ $transaction->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                            {{ $transaction->status == 'failed' || $transaction->status == 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                            {{ $transaction->status == 'refunded' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                        ">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('admin.payments.transactions.show', ['locale' => app()->getLocale(), 'transaction' => $transaction->id]) }}" 
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-2 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Aucune transaction trouvée') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Informations de l'utilisateur -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Informations de l\'utilisateur') }}</h2>
                    
                    @if($subscription->user)
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300 text-lg font-semibold">
                                {{ substr($subscription->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $subscription->user->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subscription->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 mt-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('ID utilisateur') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->user->id }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date d\'inscription') }}</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $subscription->user->created_at->format('d/m/Y') }}</p>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.users.show', ['locale' => app()->getLocale(), 'user' => $subscription->user->id]) }}" class="text-sm text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">
                                {{ __('Voir le profil complet') }} <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 dark:text-gray-400">{{ __('Utilisateur supprimé') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 