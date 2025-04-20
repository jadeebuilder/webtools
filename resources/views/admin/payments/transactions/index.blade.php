<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Transactions') }}</h1>
            
            <div class="flex space-x-2">
                <form action="{{ route('admin.payments.transactions.export', ['locale' => app()->getLocale()]) }}" method="GET" class="inline">
                    @foreach($request->all() as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $k => $v)
                                <input type="hidden" name="{{$key}}[{{$k}}]" value="{{$v}}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{$key}}" value="{{$value}}">
                        @endif
                    @endforeach
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-file-export mr-1"></i> {{ __('Exporter CSV') }}
                    </button>
                </form>
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

        <!-- Filtres -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Filtres') }}</h2>
            
            <form action="{{ route('admin.payments.transactions.index', ['locale' => app()->getLocale()]) }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Statut -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Statut') }}</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            <option value="">{{ __('Tous les statuts') }}</option>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ $request->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Type') }}</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            <option value="">{{ __('Tous les types') }}</option>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ $request->type == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Méthode de paiement -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Méthode de paiement') }}</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            <option value="">{{ __('Toutes les méthodes') }}</option>
                            @foreach($paymentMethods as $code => $name)
                                <option value="{{ $code }}" {{ $request->payment_method == $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Recherche utilisateur -->
                    <div>
                        <label for="user" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Utilisateur (nom ou email)') }}</label>
                        <input type="text" name="user" id="user" value="{{ $request->user }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Plage de dates -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Date de début') }}</label>
                        <input type="date" name="date_from" id="date_from" value="{{ $request->date_from }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Date de fin') }}</label>
                        <input type="date" name="date_to" id="date_to" value="{{ $request->date_to }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('admin.payments.transactions.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        {{ __('Réinitialiser') }}
                    </a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        {{ __('Filtrer') }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Résultats -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="{{ route('admin.payments.transactions.index', array_merge($request->all(), ['sort' => 'id', 'direction' => $request->sort == 'id' && $request->direction == 'asc' ? 'desc' : 'asc', 'locale' => app()->getLocale()])) }}" class="flex items-center">
                                ID
                                @if($request->sort == 'id')
                                    <i class="fas fa-sort-{{ $request->direction == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Utilisateur') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="{{ route('admin.payments.transactions.index', array_merge($request->all(), ['sort' => 'type', 'direction' => $request->sort == 'type' && $request->direction == 'asc' ? 'desc' : 'asc', 'locale' => app()->getLocale()])) }}" class="flex items-center">
                                {{ __('Type') }}
                                @if($request->sort == 'type')
                                    <i class="fas fa-sort-{{ $request->direction == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Détails') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="{{ route('admin.payments.transactions.index', array_merge($request->all(), ['sort' => 'amount', 'direction' => $request->sort == 'amount' && $request->direction == 'asc' ? 'desc' : 'asc', 'locale' => app()->getLocale()])) }}" class="flex items-center">
                                {{ __('Montant') }}
                                @if($request->sort == 'amount')
                                    <i class="fas fa-sort-{{ $request->direction == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="{{ route('admin.payments.transactions.index', array_merge($request->all(), ['sort' => 'status', 'direction' => $request->sort == 'status' && $request->direction == 'asc' ? 'desc' : 'asc', 'locale' => app()->getLocale()])) }}" class="flex items-center">
                                {{ __('Statut') }}
                                @if($request->sort == 'status')
                                    <i class="fas fa-sort-{{ $request->direction == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <a href="{{ route('admin.payments.transactions.index', array_merge($request->all(), ['sort' => 'created_at', 'direction' => $request->sort == 'created_at' && $request->direction == 'asc' ? 'desc' : 'asc', 'locale' => app()->getLocale()])) }}" class="flex items-center">
                                {{ __('Date') }}
                                @if($request->sort == 'created_at' || !$request->sort)
                                    <i class="fas fa-sort-{{ $request->direction == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->id }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->uuid }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-300">
                                                {{ substr($transaction->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Utilisateur supprimé') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->type == 'payment' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                    {{ $transaction->type == 'refund' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                    {{ $transaction->type == 'subscription' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                    {{ $transaction->type == 'credit' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : '' }}
                                ">
                                    {{ $types[$transaction->type] ?? $transaction->type }}
                                </span>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $transaction->payment_method }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @if($transaction->package)
                                        {{ $transaction->package->getName() }}
                                    @else
                                        {{ __('Package supprimé') }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction->cycle ? __('Cycle') . ': ' . $transaction->cycle : '' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaction->getFormattedAmount() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->status == 'completed' || $transaction->status == 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                    {{ $transaction->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : '' }}
                                    {{ $transaction->status == 'failed' || $transaction->status == 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                    {{ $transaction->status == 'refunded' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                ">
                                    {{ $statuses[$transaction->status] ?? $transaction->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div>{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                                @if($transaction->paid_at)
                                    <div class="text-xs">{{ __('Payé le') }}: {{ $transaction->paid_at->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.payments.transactions.show', ['locale' => app()->getLocale(), 'transaction' => $transaction->id]) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Aucune transaction trouvée') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-admin-layout> 