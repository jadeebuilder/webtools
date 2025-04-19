<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Configuration des packages') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('Configurez les paramètres généraux des packages et abonnements.') }}</p>
    </x-slot>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.packages.configuration.update', ['locale' => app()->getLocale()]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Paramètres généraux des packages') }}</h3>
                    
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="packages_enabled" id="packages_enabled" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" 
                               value="1" {{ isset($packageSettings['packages_enabled']) && $packageSettings['packages_enabled'] ? 'checked' : '' }}>
                        <label for="packages_enabled" class="ml-2 block text-sm text-gray-700">{{ __('Activer les packages et abonnements') }}</label>
                    </div>
                    <p class="ml-6 text-xs text-gray-500 mb-4">{{ __('Lorsque cette option est désactivée, la fonctionnalité de packages et abonnements sera masquée sur le site.') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="free_trial_days" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Jours d\'essai gratuit par défaut') }}</label>
                            <input type="number" name="free_trial_days" id="free_trial_days" min="0" max="90" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" value="{{ $packageSettings['free_trial_days'] ?? 7 }}">
                            <p class="mt-1 text-xs text-gray-500">{{ __('Nombre de jours d\'essai gratuit pour les nouveaux abonnements.') }}</p>
                        </div>
                        
                        <div>
                            <label for="default_currency" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Devise par défaut') }}</label>
                            <select name="default_currency" id="default_currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                <option value="EUR" {{ ($packageSettings['default_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="USD" {{ ($packageSettings['default_currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="GBP" {{ ($packageSettings['default_currency'] ?? '') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                <option value="CAD" {{ ($packageSettings['default_currency'] ?? '') == 'CAD' ? 'selected' : '' }}>CAD (C$)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Devise utilisée pour l\'affichage des prix.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Restrictions des essais gratuits') }}</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" name="trial_restrict_downloads" id="trial_restrict_downloads" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" value="1" {{ isset($packageSettings['trial_restrict_downloads']) && $packageSettings['trial_restrict_downloads'] ? 'checked' : '' }}>
                            <label for="trial_restrict_downloads" class="ml-2 block text-sm text-gray-700">{{ __('Limiter les téléchargements pendant la période d\'essai') }}</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="trial_restrict_exports" id="trial_restrict_exports" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" value="1" {{ isset($packageSettings['trial_restrict_exports']) && $packageSettings['trial_restrict_exports'] ? 'checked' : '' }}>
                            <label for="trial_restrict_exports" class="ml-2 block text-sm text-gray-700">{{ __('Limiter les exports pendant la période d\'essai') }}</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="trial_restrict_ai" id="trial_restrict_ai" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" value="1" {{ isset($packageSettings['trial_restrict_ai']) && $packageSettings['trial_restrict_ai'] ? 'checked' : '' }}>
                            <label for="trial_restrict_ai" class="ml-2 block text-sm text-gray-700">{{ __('Limiter l\'accès aux fonctionnalités IA pendant la période d\'essai') }}</label>
                        </div>
                    </div>
                    
                    <p class="mt-2 text-xs text-gray-500">{{ __('Ces restrictions seront appliquées par défaut aux nouveaux packages avec essai gratuit.') }}</p>
                </div>

                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Intégration de paiement') }}</h3>
                    
                    <div class="mb-4">
                        <label for="payment_gateway" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Passerelle de paiement') }}</label>
                        <select name="payment_gateway" id="payment_gateway" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            <option value="stripe" {{ ($packageSettings['payment_gateway'] ?? '') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="paypal" {{ ($packageSettings['payment_gateway'] ?? '') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="razorpay" {{ ($packageSettings['payment_gateway'] ?? '') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                            <option value="custom" {{ ($packageSettings['payment_gateway'] ?? '') == 'custom' ? 'selected' : '' }}>{{ __('Personnalisé') }}</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">{{ __('Passerelle de paiement utilisée pour traiter les abonnements.') }}</p>
                    </div>
                    
                    <div id="stripe_settings" class="space-y-4 border p-4 rounded-lg">
                        <div>
                            <label for="stripe_key" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Clé publique Stripe') }}</label>
                            <input type="text" name="stripe_key" id="stripe_key" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" placeholder="pk_test_..." value="{{ $packageSettings['stripe_key'] ?? '' }}">
                        </div>
                        
                        <div>
                            <label for="stripe_secret" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Clé secrète Stripe') }}</label>
                            <input type="password" name="stripe_secret" id="stripe_secret" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" placeholder="sk_test_..." value="{{ $packageSettings['stripe_secret'] ? '••••••••••••••••••••••••••' : '' }}">
                        </div>
                        
                        <div>
                            <label for="stripe_webhook_secret" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Secret de webhook Stripe') }}</label>
                            <input type="password" name="stripe_webhook_secret" id="stripe_webhook_secret" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" placeholder="whsec_..." value="{{ $packageSettings['stripe_webhook_secret'] ? '••••••••••••••••••••••••••' : '' }}">
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    {{ __('Pour une configuration complète des passerelles de paiement, veuillez consulter la documentation.') }}
                                    <a href="#" class="text-blue-600 underline">{{ __('En savoir plus') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:border-purple-800 focus:ring focus:ring-purple-200 disabled:opacity-25 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('Enregistrer les modifications') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentGateway = document.getElementById('payment_gateway');
            const stripeSettings = document.getElementById('stripe_settings');
            
            function togglePaymentSettings() {
                if (paymentGateway.value === 'stripe') {
                    stripeSettings.classList.remove('hidden');
                } else {
                    stripeSettings.classList.add('hidden');
                }
            }
            
            paymentGateway.addEventListener('change', togglePaymentSettings);
            togglePaymentSettings();
        });
    </script>
</x-admin-layout> 