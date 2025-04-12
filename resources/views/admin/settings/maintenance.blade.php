<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Mode maintenance') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('Configurez le mode maintenance de votre site.') }}</p>
    </x-slot>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="mb-4 bg-gray-50 border rounded-md p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-2">{{ __('État actuel du mode maintenance') }}</h3>
                <div class="flex items-center">
                    <div class="mr-2 h-3 w-3 rounded-full {{ isset($settings['maintenance_mode']) && $settings['maintenance_mode'] == 1 ? 'bg-red-500' : 'bg-green-500' }}"></div>
                    <p class="text-sm text-gray-600">{{ isset($settings['maintenance_mode']) && $settings['maintenance_mode'] == 1 ? __('Mode maintenance activé') : __('Mode maintenance désactivé') }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ __('Valeur actuelle dans la base de données:') }} <code class="px-1 py-0.5 bg-gray-100 rounded text-xs font-mono">{{ isset($settings['maintenance_mode']) ? $settings['maintenance_mode'] : 'non défini' }}</code></p>
            </div>
            <form action="{{ route('admin.settings.maintenance.update', ['locale' => app()->getLocale()]) }}" method="POST">
                @csrf

                <!-- Statut du mode maintenance -->
                <div class="mb-6 border-b pb-6">
                    <div class="flex items-center mb-4">
                        <input type="hidden" name="maintenance_mode" value="0">
                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" 
                               {{ !empty($settings['maintenance_mode']) && $settings['maintenance_mode'] == 1 ? 'checked' : '' }}>
                        <label for="maintenance_mode" class="ml-2 block text-base font-medium text-gray-700">{{ __('Activer le mode maintenance') }}</label>
                    </div>
                    
                    <div class="ml-7">
                        <p class="text-sm text-gray-600 mb-3">{{ __('Lorsque le mode maintenance est activé, les visiteurs verront une page de maintenance au lieu du site normal.') }}</p>
                        
                        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-700">
                                        {{ __('Les administrateurs resteront capables d\'accéder au site en utilisant le lien de contournement qui vous sera fourni.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message de maintenance -->
                <div class="mb-6">
                    <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Message de maintenance') }}</label>
                    <textarea name="maintenance_message" id="maintenance_message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ $settings['maintenance_message'] ?? __('Notre site est actuellement en maintenance. Nous serons bientôt de retour!') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">{{ __('Ce message sera affiché sur la page de maintenance.') }}</p>
                    @error('maintenance_message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de fin prévue -->
                <div class="mb-6">
                    <label for="maintenance_end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date de fin prévue') }}</label>
                    <input type="datetime-local" name="maintenance_end_date" id="maintenance_end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                           value="{{ $settings['maintenance_end_date'] ?? '' }}">
                    <p class="mt-1 text-xs text-gray-500">{{ __('Optionnel. Permet d\'afficher un compte à rebours sur la page de maintenance.') }}</p>
                    @error('maintenance_end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- IPs autorisées -->
                <div class="mb-6">
                    <label for="maintenance_allow_ips" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Adresses IP autorisées') }}</label>
                    <textarea name="maintenance_allow_ips" id="maintenance_allow_ips" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" placeholder="127.0.0.1, 192.168.1.1">{{ $settings['maintenance_allow_ips'] ?? '' }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">{{ __('Optionnel. Liste d\'adresses IP séparées par des virgules qui pourront accéder au site en mode maintenance.') }}</p>
                    @error('maintenance_allow_ips')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Page de maintenance personnalisée -->
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Personnalisation avancée') }}</h3>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-sm text-gray-600">{{ __('Pour personnaliser davantage la page de maintenance, vous pouvez modifier le template situé dans:') }}</p>
                        <code class="block mt-2 p-2 bg-gray-100 rounded text-sm font-mono">resources/views/errors/maintenance.blade.php</code>
                        
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('admin.settings.maintenance-template', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md font-medium text-xs text-gray-700 hover:text-gray-500 focus:outline-none focus:border-purple-300 focus:ring focus:ring-purple-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                {{ __('Télécharger le template actuel') }}
                            </a>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600">{{ __('Vous pouvez utiliser des variables comme') }} <code class="px-1 py-0.5 bg-gray-100 rounded text-xs font-mono">@{{ $exception->wrapperMessage }}</code> {{ __('pour afficher le message personnalisé.') }}</p>
                </div>

                <!-- Test des IPs autorisées -->
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Tester une adresse IP') }}</h3>
                    
                    <div x-data="{ 
                        ip: '', 
                        result: null, 
                        isAllowed: false,
                        testIp() {
                            const allowedIps = document.getElementById('maintenance_allow_ips').value.split(',').map(ip => ip.trim());
                            this.isAllowed = allowedIps.includes(this.ip);
                            this.result = true;
                        }
                    }">
                        <div class="flex items-end gap-4">
                            <div class="flex-grow">
                                <label for="test_ip" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Adresse IP à tester') }}</label>
                                <input x-model="ip" type="text" id="test_ip" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" placeholder="Exemple: 192.168.1.1">
                            </div>
                            <button @click="testIp()" type="button" class="px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:border-purple-800 focus:ring focus:ring-purple-200 disabled:opacity-25 transition">
                                {{ __('Tester') }}
                            </button>
                        </div>
                        
                        <div x-show="result !== null" x-cloak class="mt-3">
                            <div x-show="isAllowed" class="bg-green-50 border-l-4 border-green-500 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">{{ __('L\'adresse IP est autorisée et pourra accéder au site en mode maintenance.') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div x-show="!isAllowed" class="bg-red-50 border-l-4 border-red-500 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">{{ __('L\'adresse IP n\'est pas autorisée et verra la page de maintenance.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    @if(!empty($settings['maintenance_mode']) && $settings['maintenance_mode'] == 1)
                        <div class="flex space-x-2">
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-purple-300 focus:ring focus:ring-purple-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition" onclick="window.open('{{ url('/') }}', '_blank')">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ __('Prévisualiser le site') }}
                            </button>
                            
                            <a href="{{ route('admin.settings.clear-cache', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-purple-300 focus:ring focus:ring-purple-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __('Vider le cache') }}
                            </a>
                        </div>
                    @else
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.settings.clear-cache', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-purple-300 focus:ring focus:ring-purple-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __('Vider le cache') }}
                            </a>
                        </div>
                    @endif
                    
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
</x-admin-layout> 