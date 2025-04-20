<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Finaliser votre commande') }}</h1>
            <p class="text-gray-600">{{ __('Vous avez choisi le forfait') }} <span class="font-medium" style="color: {{ $package->color }}">{{ $package->getName() }}</span></p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Options de paiement') }}</h2>
                    
                    <form action="{{ route('checkout.currency-cycle', ['locale' => app()->getLocale()]) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="currency_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Devise') }}</label>
                            <select id="currency_id" name="currency_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ $selectedCurrency->id == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->code }} ({{ $currency->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Cycle de facturation') }}</label>
                            <div class="space-y-2">
                                @foreach($cycles as $cycleKey => $cycleName)
                                    <div class="flex items-center">
                                        <input type="radio" id="cycle_{{ $cycleKey }}" name="cycle" value="{{ $cycleKey }}" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary" {{ $loop->first ? 'checked' : '' }}>
                                        <label for="cycle_{{ $cycleKey }}" class="ml-2 block text-sm text-gray-700">
                                            {{ $cycleName }}
                                            @php
                                                $price = match($cycleKey) {
                                                    'monthly' => $package->getFormattedMonthlyPrice(),
                                                    'annual' => $package->getFormattedAnnualPrice(),
                                                    'lifetime' => $package->getFormattedLifetimePrice(),
                                                    default => ''
                                                };
                                                
                                                $savings = match($cycleKey) {
                                                    'annual' => $package->getAnnualSavings(),
                                                    'lifetime' => $package->getLifetimeSavings(),
                                                    default => null
                                                };
                                            @endphp
                                            <span class="font-medium"> - {{ $price }}</span>
                                            @if($savings && $savings['percent'] > 0)
                                                <span class="text-green-600 text-xs ml-1">({{ __('Économisez') }} {{ $savings['percent'] }}%)</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-opacity-90 transition-all duration-200">
                                {{ __('Continuer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div>
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Récapitulatif') }}</h2>
                    
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">{{ __('Package') }}:</span>
                            <span class="font-medium">{{ $package->getName() }}</span>
                        </div>
                        <div class="text-sm text-gray-500">{{ $package->getDescription() }}</div>
                    </div>
                    
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="space-y-2">
                            <h3 class="font-semibold text-sm">{{ __('Caractéristiques incluses') }}:</h3>
                            @php $features = explode("\n", $package->getFeatures()); @endphp
                            @foreach(array_slice($features, 0, 5) as $feature)
                                @if(!empty(trim($feature)))
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if(count($features) > 5)
                                <div class="text-xs text-gray-500">{{ __('Et') }} {{ count($features) - 5 }} {{ __('autres fonctionnalités') }}</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="font-medium text-right">
                        <div class="text-sm text-gray-600 mb-1">{{ __('Prix total') }}:</div>
                        <div class="text-xl" id="total-price" style="color: {{ $package->color }}">
                            {{ $package->getFormattedMonthlyPrice() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cycleRadios = document.querySelectorAll('input[name="cycle"]');
            const totalPrice = document.getElementById('total-price');
            
            // Tableau associatif des prix
            const prices = {
                'monthly': "{{ $package->getFormattedMonthlyPrice() }}",
                'annual': "{{ $package->getFormattedAnnualPrice() }}",
                'lifetime': "{{ $package->getFormattedLifetimePrice() }}"
            };
            
            // Mettre à jour le prix total
            function updateTotalPrice() {
                const selectedCycle = document.querySelector('input[name="cycle"]:checked').value;
                totalPrice.innerHTML = prices[selectedCycle];
            }
            
            // Ajouter un écouteur d'événement à chaque radio
            cycleRadios.forEach(radio => {
                radio.addEventListener('change', updateTotalPrice);
            });
        });
    </script>
    @endpush
</x-app-layout> 