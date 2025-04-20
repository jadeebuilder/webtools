<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Devises') }}</h1>
            <a href="{{ route('admin.payments.currencies.create', ['locale' => app()->getLocale()]) }}" 
               class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <i class="fas fa-plus mr-1"></i> {{ __('Nouvelle devise') }}
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
                            {{ __('Devise') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Code') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Symbole') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Format') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Statut') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($currencies as $currency)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($currency->country)
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center">
                                            <img src="{{ asset('images/flags/' . strtolower($currency->country->iso2) . '.svg') }}" 
                                                alt="{{ $currency->country->name }}" 
                                                class="h-6 w-auto" 
                                                onerror="this.onerror=null;this.src='{{ asset('images/flags/unknown.svg') }}';">
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $currency->name }}
                                            @if($currency->is_default)
                                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                                    {{ __('Par défaut') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $currency->country ? $currency->country->name : __('Pas de pays associé') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $currency->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $currency->symbol }} / {{ $currency->symbol_native }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $currency->symbol_first ? __('Symbole avant') : __('Symbole après') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $currency->format(1234.56) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('Précision') }}: {{ $currency->precision }}, 
                                    {{ __('Séparateur décimal') }}: "{{ $currency->decimal_mark }}", 
                                    {{ __('Séparateur de milliers') }}: "{{ $currency->thousands_separator }}"
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if (!$currency->is_default)
                                    <form action="{{ route('admin.payments.currencies.toggle', ['locale' => app()->getLocale(), 'currency' => $currency->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="flex items-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $currency->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                {{ $currency->is_active ? __('Actif') : __('Inactif') }}
                                            </span>
                                            <i class="fas fa-exchange-alt ml-2 text-gray-500 dark:text-gray-400 text-xs"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        {{ __('Actif') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.payments.currencies.show', ['locale' => app()->getLocale(), 'currency' => $currency->id]) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.payments.currencies.edit', ['locale' => app()->getLocale(), 'currency' => $currency->id]) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$currency->is_default)
                                        <form action="{{ route('admin.payments.currencies.set-default', ['locale' => app()->getLocale(), 'currency' => $currency->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                        <form class="inline" action="{{ route('admin.payments.currencies.destroy', ['locale' => app()->getLocale(), 'currency' => $currency->id]) }}" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette devise?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Aucune devise trouvée') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout> 