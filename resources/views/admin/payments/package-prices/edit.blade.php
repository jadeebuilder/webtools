<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                {{ __('Édition des prix pour') }} : {{ $package->getName() }} ({{ $currency->code }})
            </h1>
            <a href="{{ route('admin.payments.package-prices.index', ['locale' => app()->getLocale()]) }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <i class="fas fa-arrow-left mr-1"></i> {{ __('Retour') }}
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

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('admin.payments.package-prices.update', ['locale' => app()->getLocale()]) }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">
                <input type="hidden" name="currency_id" value="{{ $currency->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Prix mensuel -->
                    <div>
                        <label for="monthly_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Prix mensuel') }} ({{ $currency->symbol }}) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="monthly_price" id="monthly_price" 
                               value="{{ old('monthly_price', $packagePrice->monthly_price ?? 0) }}" 
                               min="0" step="0.01" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                        @error('monthly_price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Prix annuel -->
                    <div>
                        <label for="annual_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Prix annuel') }} ({{ $currency->symbol }}) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="annual_price" id="annual_price" 
                               value="{{ old('annual_price', $packagePrice->annual_price ?? 0) }}" 
                               min="0" step="0.01" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                        @error('annual_price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Prix à vie -->
                    <div>
                        <label for="lifetime_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Prix à vie') }} ({{ $currency->symbol }}) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="lifetime_price" id="lifetime_price" 
                               value="{{ old('lifetime_price', $packagePrice->lifetime_price ?? 0) }}" 
                               min="0" step="0.01" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                        @error('lifetime_price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Identifiants de produits chez les fournisseurs de paiement -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Identifiants de produits chez les fournisseurs de paiement') }}</h3>
                    
                    <div class="space-y-6">
                        @foreach($paymentMethods as $method)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-800 dark:text-gray-200 mb-3">
                                    <i class="{{ $method->icon ?? 'fas fa-credit-card' }} mr-2"></i>
                                    {{ $method->name }}
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- ID du plan mensuel -->
                                    <div>
                                        <label for="payment_provider_ids_{{ $method->code }}_monthly" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            {{ __('ID du plan mensuel') }}
                                        </label>
                                        <input type="text" 
                                               name="payment_provider_ids[{{ $method->code }}][monthly]" 
                                               id="payment_provider_ids_{{ $method->code }}_monthly" 
                                               value="{{ old('payment_provider_ids.'.$method->code.'.monthly', $packagePrice->payment_provider_ids[$method->code]['monthly'] ?? '') }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('ID du produit/prix pour l\'abonnement mensuel') }}
                                        </p>
                                    </div>
                                    
                                    <!-- ID du plan annuel -->
                                    <div>
                                        <label for="payment_provider_ids_{{ $method->code }}_annual" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            {{ __('ID du plan annuel') }}
                                        </label>
                                        <input type="text" 
                                               name="payment_provider_ids[{{ $method->code }}][annual]" 
                                               id="payment_provider_ids_{{ $method->code }}_annual" 
                                               value="{{ old('payment_provider_ids.'.$method->code.'.annual', $packagePrice->payment_provider_ids[$method->code]['annual'] ?? '') }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('ID du produit/prix pour l\'abonnement annuel') }}
                                        </p>
                                    </div>
                                    
                                    <!-- ID du plan à vie -->
                                    <div>
                                        <label for="payment_provider_ids_{{ $method->code }}_lifetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            {{ __('ID du plan à vie') }}
                                        </label>
                                        <input type="text" 
                                               name="payment_provider_ids[{{ $method->code }}][lifetime]" 
                                               id="payment_provider_ids_{{ $method->code }}_lifetime" 
                                               value="{{ old('payment_provider_ids.'.$method->code.'.lifetime', $packagePrice->payment_provider_ids[$method->code]['lifetime'] ?? '') }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('ID du produit/prix pour l\'achat à vie') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex justify-end pt-6">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        {{ __('Enregistrer les modifications') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout> 