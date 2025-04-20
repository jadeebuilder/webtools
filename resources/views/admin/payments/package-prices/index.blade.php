<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Gestion des prix des packages') }}</h1>
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

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Sélectionnez un package et une devise pour gérer les prix') }}</h2>
            
            <form action="{{ route('admin.payments.package-prices.edit', ['locale' => app()->getLocale()]) }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sélection du package -->
                    <div>
                        <label for="package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Package') }} <span class="text-red-500">*</span></label>
                        <select name="package_id" id="package_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            <option value="">{{ __('Sélectionnez un package') }}</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->getName() }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Sélection de la devise -->
                    <div>
                        <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Devise') }} <span class="text-red-500">*</span></label>
                        <select name="currency_id" id="currency_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            <option value="">{{ __('Sélectionnez une devise') }}</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}">
                                    {{ $currency->name }} ({{ $currency->code }})
                                    @if($currency->is_default) - {{ __('Par défaut') }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        {{ __('Éditer les prix') }}
                    </button>
                </div>
            </form>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Tableau des prix') }}</h2>
            
            <!-- Tableau des prix -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ __('Package') }}
                            </th>
                            @foreach($currencies as $currency)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ $currency->code }} ({{ $currency->symbol }})
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($packages as $package)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $package->color }}"></div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $package->getName() }}</div>
                                    </div>
                                </td>
                                
                                @foreach($currencies as $currency)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $price = \App\Models\PackagePrice::where('package_id', $package->id)
                                                ->where('currency_id', $currency->id)
                                                ->first();
                                        @endphp

                                        @if($price)
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                <div><span class="font-medium">{{ __('Mensuel') }}:</span> {{ $price->getFormattedPrice('monthly') }}</div>
                                                <div><span class="font-medium">{{ __('Annuel') }}:</span> {{ $price->getFormattedPrice('annual') }}</div>
                                                <div><span class="font-medium">{{ __('À vie') }}:</span> {{ $price->getFormattedPrice('lifetime') }}</div>
                                            </div>
                                            
                                            <a href="{{ route('admin.payments.package-prices.edit', ['locale' => app()->getLocale(), 'package_id' => $package->id, 'currency_id' => $currency->id]) }}" 
                                                class="inline-flex items-center px-2 py-1 mt-2 border border-transparent text-xs font-medium rounded text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                <i class="fas fa-edit mr-1"></i> {{ __('Éditer') }}
                                            </a>
                                        @else
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Non configuré') }}</div>
                                            
                                            <a href="{{ route('admin.payments.package-prices.edit', ['locale' => app()->getLocale(), 'package_id' => $package->id, 'currency_id' => $currency->id]) }}" 
                                                class="inline-flex items-center px-2 py-1 mt-2 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <i class="fas fa-plus mr-1"></i> {{ __('Configurer') }}
                                            </a>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout> 