<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Paramètres de paiement') }}</h1>
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

        <!-- Menu de navigation -->
        <div class="mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <a href="#general" class="border-purple-500 text-purple-600 dark:text-purple-400 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm" aria-current="page">
                        {{ __('Général') }}
                    </a>
                    <a href="#payment-methods" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                        {{ __('Méthodes de paiement') }}
                    </a>
                </nav>
            </div>
        </div>

        <!-- Paramètres généraux -->
        <div id="general" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Paramètres généraux') }}</h2>
            
            <form action="{{ route('admin.payments.settings.update-general', ['locale' => app()->getLocale()]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Devise par défaut -->
                    <div>
                        <label for="default_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Devise par défaut') }}</label>
                        <select name="default_currency" id="default_currency" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->code }}" {{ isset($settings['default_currency']) && $settings['default_currency'] == $currency->code ? 'selected' : '' }}>
                                    {{ $currency->name }} ({{ $currency->symbol }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Taux de taxe -->
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Taux de taxe (%)') }}</label>
                        <input type="number" step="0.01" min="0" max="100" name="tax_rate" id="tax_rate" value="{{ $settings['tax_rate'] ?? 0 }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                    
                    <!-- Préfixe de facture -->
                    <div>
                        <label for="invoice_prefix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Préfixe de facture') }}</label>
                        <input type="text" name="invoice_prefix" id="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV-' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="mt-4 space-y-4">
                    <!-- Options -->
                    <div>
                        <div class="flex items-start mb-2">
                            <div class="flex items-center h-5">
                                <input id="enable_invoices" name="enable_invoices" type="checkbox" class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300 rounded" {{ isset($settings['enable_invoices']) && $settings['enable_invoices'] ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="enable_invoices" class="font-medium text-gray-700 dark:text-gray-300">{{ __('Activer les factures') }}</label>
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Générer des factures pour les paiements.') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-2">
                            <div class="flex items-center h-5">
                                <input id="enable_receipts" name="enable_receipts" type="checkbox" class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300 rounded" {{ isset($settings['enable_receipts']) && $settings['enable_receipts'] ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="enable_receipts" class="font-medium text-gray-700 dark:text-gray-300">{{ __('Activer les reçus') }}</label>
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Générer des reçus pour les paiements.') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-2">
                            <div class="flex items-center h-5">
                                <input id="allow_manual_payments" name="allow_manual_payments" type="checkbox" class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300 rounded" {{ isset($settings['allow_manual_payments']) && $settings['allow_manual_payments'] ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="allow_manual_payments" class="font-medium text-gray-700 dark:text-gray-300">{{ __('Autoriser les paiements manuels') }}</label>
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Permettre aux administrateurs d\'enregistrer des paiements manuellement.') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="require_billing_address" name="require_billing_address" type="checkbox" class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300 rounded" {{ isset($settings['require_billing_address']) && $settings['require_billing_address'] ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="require_billing_address" class="font-medium text-gray-700 dark:text-gray-300">{{ __('Exiger l\'adresse de facturation') }}</label>
                                <p class="text-gray-500 dark:text-gray-400">{{ __('Demander l\'adresse de facturation lors des paiements.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Texte de pied de page de facture -->
                <div>
                    <label for="invoice_footer_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Texte de pied de page des factures') }}</label>
                    <textarea name="invoice_footer_text" id="invoice_footer_text" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ $settings['invoice_footer_text'] ?? '' }}</textarea>
                </div>
                
                <!-- Conditions de paiement -->
                <div>
                    <label for="payment_terms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Conditions de paiement') }}</label>
                    <textarea name="payment_terms" id="payment_terms" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ $settings['payment_terms'] ?? '' }}</textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        {{ __('Enregistrer les paramètres') }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Méthodes de paiement -->
        <div id="payment-methods" class="mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">{{ __('Méthodes de paiement') }}</h2>
            
            <div class="space-y-4">
                @foreach($paymentMethods as $method)
                    <div id="payment-method-{{ $method->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                @if($method->icon)
                                    <img src="{{ asset('storage/' . $method->icon) }}" alt="{{ $method->name }}" class="h-8 w-8 mr-3">
                                @else
                                    <div class="h-8 w-8 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center mr-3">
                                        <i class="fas fa-credit-card text-gray-500 dark:text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-md font-semibold text-gray-900 dark:text-white">{{ $method->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $method->code }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <span class="mr-4 px-2 py-1 text-xs rounded-full {{ $method->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                    {{ $method->is_active ? __('Actif') : __('Inactif') }}
                                </span>
                                
                                <form action="{{ route('admin.payments.settings.toggle-payment-method', ['locale' => app()->getLocale(), 'id' => $method->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 focus:outline-none">
                                        {{ $method->is_active ? __('Désactiver') : __('Activer') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <form action="{{ route('admin.payments.settings.update-payment-method', ['locale' => app()->getLocale(), 'id' => $method->id]) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div>
                                            <label for="name_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nom affiché') }}</label>
                                            <input type="text" name="name" id="name_{{ $method->id }}" value="{{ $method->name }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                        </div>
                                        
                                        <div>
                                            <label for="display_order_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Ordre d\'affichage') }}</label>
                                            <input type="number" name="display_order" id="display_order_{{ $method->id }}" value="{{ $method->display_order }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                        </div>
                                        
                                        <div>
                                            <label for="description_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Description') }}</label>
                                            <textarea name="description" id="description_{{ $method->id }}" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ $method->description }}</textarea>
                                        </div>
                                        
                                        <!-- Paramètres spécifiques à la méthode de paiement -->
                                        <div>
                                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Paramètres du processeur') }}</h4>
                                            
                                            @if($method->code == 'stripe')
                                                <div class="space-y-3">
                                                    <div>
                                                        <label for="settings_api_key_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Clé API') }}</label>
                                                        <input type="text" name="settings[api_key]" id="settings_api_key_{{ $method->id }}" value="{{ $method->settings['api_key'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="settings_secret_key_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Clé secrète') }}</label>
                                                        <input type="password" name="settings[secret_key]" id="settings_secret_key_{{ $method->id }}" value="{{ $method->settings['secret_key'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="settings_webhook_secret_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Secret Webhook') }}</label>
                                                        <input type="password" name="settings[webhook_secret]" id="settings_webhook_secret_{{ $method->id }}" value="{{ $method->settings['webhook_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                                    </div>
                                                </div>
                                            @elseif($method->code == 'paypal')
                                                <div class="space-y-3">
                                                    <div>
                                                        <label for="settings_client_id_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Client ID') }}</label>
                                                        <input type="text" name="settings[client_id]" id="settings_client_id_{{ $method->id }}" value="{{ $method->settings['client_id'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="settings_client_secret_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Client Secret') }}</label>
                                                        <input type="password" name="settings[client_secret]" id="settings_client_secret_{{ $method->id }}" value="{{ $method->settings['client_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                                    </div>
                                                    
                                                    <div>
                                                        <div class="flex items-start">
                                                            <div class="flex items-center h-5">
                                                                <input id="settings_sandbox_{{ $method->id }}" name="settings[sandbox]" type="checkbox" class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300 rounded" {{ isset($method->settings['sandbox']) && $method->settings['sandbox'] ? 'checked' : '' }}>
                                                            </div>
                                                            <div class="ml-3 text-sm">
                                                                <label for="settings_sandbox_{{ $method->id }}" class="font-medium text-gray-700 dark:text-gray-300">{{ __('Mode sandbox') }}</label>
                                                                <p class="text-gray-500 dark:text-gray-400">{{ __('Utiliser l\'environnement de test PayPal.') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($method->code == 'manual')
                                                <div>
                                                    <label for="settings_instructions_{{ $method->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Instructions de paiement') }}</label>
                                                    <textarea name="settings[instructions]" id="settings_instructions_{{ $method->id }}" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ $method->settings['instructions'] ?? '' }}</textarea>
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Aucun paramètre spécifique pour cette méthode de paiement.') }}</p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                                {{ __('Mettre à jour') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <div>
                                    <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('Devises supportées') }}</h4>
                                    
                                    <form action="{{ route('admin.payments.settings.update-payment-method-currencies', ['locale' => app()->getLocale(), 'id' => $method->id]) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="space-y-2 max-h-60 overflow-y-auto p-2 border border-gray-200 dark:border-gray-700 rounded-md">
                                            @foreach($currencies as $currency)
                                                <div class="flex items-center">
                                                    <input type="checkbox" id="currency_{{ $method->id }}_{{ $currency->id }}" name="currencies[]" value="{{ $currency->id }}" 
                                                        class="focus:ring-purple-500 h-4 w-4 text-purple-600 border-gray-300 rounded"
                                                        {{ $method->currencies->contains($currency->id) ? 'checked' : '' }}>
                                                    <label for="currency_{{ $method->id }}_{{ $currency->id }}" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $currency->name }} ({{ $currency->code }}) - {{ $currency->symbol }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                                {{ __('Mettre à jour les devises') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navigation
        const navLinks = document.querySelectorAll('nav a');
        const sections = document.querySelectorAll('#general, #payment-methods');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Mettre à jour les classes des liens
                navLinks.forEach(l => {
                    l.classList.remove('border-purple-500', 'text-purple-600', 'dark:text-purple-400');
                    l.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                });
                
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                this.classList.add('border-purple-500', 'text-purple-600', 'dark:text-purple-400');
                
                // Afficher la section correspondante
                const targetId = this.getAttribute('href').substring(1);
                sections.forEach(section => {
                    section.style.display = section.id === targetId ? 'block' : 'none';
                });
            });
        });
        
        // Vérifier si un hash est présent dans l'URL
        if (window.location.hash) {
            const targetLink = document.querySelector(`nav a[href="${window.location.hash}"]`);
            if (targetLink) {
                targetLink.click();
            }
            
            // Défiler jusqu'à l'élément spécifique si c'est un ID de méthode de paiement
            const paymentMethodId = window.location.hash.match(/payment-method-(\d+)/);
            if (paymentMethodId) {
                const element = document.getElementById(paymentMethodId[0]);
                if (element) {
                    element.scrollIntoView();
                    element.classList.add('ring-2', 'ring-purple-500', 'ring-opacity-50');
                    setTimeout(() => {
                        element.classList.remove('ring-2', 'ring-purple-500', 'ring-opacity-50');
                    }, 2000);
                }
            }
        }
    });
    </script>
</x-admin-layout> 