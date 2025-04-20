<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Choisir votre méthode de paiement') }}</h1>
            <p class="text-gray-600">{{ __('Sélectionnez votre méthode de paiement préférée pour finaliser l\'achat') }}</p>
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
                    <h2 class="text-xl font-semibold mb-4">{{ __('Méthodes de paiement disponibles') }}</h2>
                    
                    <form action="{{ route('payment.process', ['locale' => app()->getLocale()]) }}" method="POST">
                        @csrf
                        
                        <div class="space-y-4 mb-6">
                            @foreach($paymentMethods as $method)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition-all duration-200 cursor-pointer" onclick="document.getElementById('payment_method_{{ $method->id }}').checked = true;">
                                    <div class="flex items-center">
                                        <input type="radio" id="payment_method_{{ $method->id }}" name="payment_method" value="{{ $method->code }}" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary" {{ $loop->first ? 'checked' : '' }}>
                                        <label for="payment_method_{{ $method->id }}" class="ml-2 flex items-center flex-grow cursor-pointer">
                                            @if($method->icon)
                                                <img src="{{ asset($method->icon) }}" alt="{{ $method->name }}" class="h-8 mr-3">
                                            @else
                                                <div class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-md mr-3">
                                                    <i class="fas fa-credit-card text-gray-500"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="font-medium block">{{ $method->name }}</span>
                                                <span class="text-sm text-gray-500">{{ $method->description }}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-opacity-90 transition-all duration-200">
                                {{ __('Procéder au paiement') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div>
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Récapitulatif de la commande') }}</h2>
                    
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">{{ __('Package') }}:</span>
                            <span class="font-medium">{{ $package->getName() }}</span>
                        </div>
                        <div class="text-sm text-gray-500 mb-3">{{ $package->getDescription() }}</div>
                        
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-600">{{ __('Devise') }}:</span>
                            <span>{{ $currency->code }} ({{ $currency->symbol }})</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Cycle') }}:</span>
                            <span>
                                @php
                                    $cycleName = match($cycle) {
                                        'monthly' => __('Mensuel'),
                                        'annual' => __('Annuel'),
                                        'lifetime' => __('À vie'),
                                        default => $cycle
                                    };
                                @endphp
                                {{ $cycleName }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <h3 class="font-semibold text-sm mb-2">{{ __('Détails du paiement') }}:</h3>
                        
                        @php
                            $price = match($cycle) {
                                'monthly' => $packagePrice->monthly_price,
                                'annual' => $packagePrice->annual_price,
                                'lifetime' => $packagePrice->lifetime_price,
                                default => 0
                            };
                        @endphp
                        
                        <div class="flex justify-between mb-1 text-sm">
                            <span>{{ $package->getName() }} ({{ $cycleName }})</span>
                            <span>{{ $currency->format($price) }}</span>
                        </div>
                        
                        <!-- Taxes et autres frais pourraient être ajoutés ici -->
                    </div>
                    
                    <div class="font-medium">
                        <div class="flex justify-between mb-1">
                            <span>{{ __('Total') }}:</span>
                            <span class="text-xl" style="color: {{ $package->color }}">{{ $currency->format($price) }}</span>
                        </div>
                        
                        @if($cycle === 'monthly')
                            <div class="text-xs text-gray-500 text-right">
                                {{ __('Facturation mensuelle') }}
                            </div>
                        @elseif($cycle === 'annual')
                            <div class="text-xs text-gray-500 text-right">
                                {{ __('Facturation annuelle') }}
                                @php $savings = $package->getAnnualSavings(); @endphp
                                @if($savings['percent'] > 0)
                                    <span class="text-green-600">({{ __('Économisez') }} {{ $savings['percent'] }}%)</span>
                                @endif
                            </div>
                        @elseif($cycle === 'lifetime')
                            <div class="text-xs text-gray-500 text-right">
                                {{ __('Paiement unique') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 