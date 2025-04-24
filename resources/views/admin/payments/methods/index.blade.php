<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Méthodes de paiement') }}</h1>
            <a href="{{ route('admin.payments.methods.create', ['locale' => app()->getLocale()]) }}" 
               class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <i class="fas fa-plus mr-1"></i> {{ __('Nouvelle méthode') }}
            </a>
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

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Nom') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Code') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Statut') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Devises') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Ordre') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actif') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($paymentMethods as $method)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center text-lg text-gray-500 dark:text-gray-400">
                                        <i class="{{ $method->icon ?? 'fas fa-credit-card' }}"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $method->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $method->description ?? __('Aucune description') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $method->code }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate" style="max-width: 200px;">
                                    {{ $method->processor_class ?? __('Aucune classe de processeur') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.payments.methods.toggle', ['locale' => app()->getLocale(), 'method' => $method->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="flex items-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $method->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                            {{ $method->is_active ? __('Actif') : __('Inactif') }}
                                        </span>
                                        <i class="fas fa-exchange-alt ml-2 text-gray-500 dark:text-gray-400 text-xs"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $method->currencies->count() }} {{ __('devises') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    @foreach($method->currencies->take(3) as $currency)
                                        <span class="mr-1">{{ $currency->code }}</span>
                                    @endforeach
                                    @if($method->currencies->count() > 3)
                                        <span>...</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $method->display_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <form action="{{ route('admin.payments.methods.toggleStatus', ['locale' => app()->getLocale(), 'method' => $method->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" value="" class="sr-only peer" 
                                            onchange="this.form.submit()"
                                            {{ $method->is_active ? 'checked' : '' }}>
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                    </label>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.payments.methods.show', ['locale' => app()->getLocale(), 'method' => $method->id]) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.payments.methods.edit', ['locale' => app()->getLocale(), 'method' => $method->id]) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.payments.methods.currencies', ['locale' => app()->getLocale(), 'method' => $method->id]) }}" 
                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>
                                    <form class="inline" action="{{ route('admin.payments.methods.destroy', ['locale' => app()->getLocale(), 'method' => $method->id]) }}" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette méthode de paiement?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Aucune méthode de paiement trouvée') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout> 