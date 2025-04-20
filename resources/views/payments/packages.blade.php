<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Nos forfaits disponibles') }}</h1>
            <p class="text-gray-600">{{ __('Choisissez le forfait qui correspond à vos besoins') }}</p>
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
            @foreach($packages as $package)
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col">
                    <div class="flex-grow">
                        <div class="h-2 w-full rounded-t-lg" style="background-color: {{ $package->color }}"></div>
                        <h2 class="text-xl font-bold mb-2 mt-4" style="color: {{ $package->color }}">{{ $package->getName() }}</h2>
                        <p class="text-gray-600 mb-4">{{ $package->getDescription() }}</p>
                        
                        <div class="mb-6">
                            @if($package->monthly_price > 0)
                                <div class="mb-2">
                                    <span class="font-bold">{{ __('Mensuel') }}:</span> 
                                    <span class="text-lg">{{ $package->getFormattedMonthlyPrice() }}</span>
                                </div>
                            @endif
                            
                            @if($package->annual_price > 0)
                                <div class="mb-2">
                                    <span class="font-bold">{{ __('Annuel') }}:</span> 
                                    <span class="text-lg">{{ $package->getFormattedAnnualPrice() }}</span>
                                    @php $annualSavings = $package->getAnnualSavings(); @endphp
                                    @if($annualSavings['percent'] > 0)
                                        <span class="text-green-600 text-sm ml-2">({{ __('Économisez') }} {{ $annualSavings['percent'] }}%)</span>
                                    @endif
                                </div>
                            @endif
                            
                            @if($package->lifetime_price > 0)
                                <div class="mb-2">
                                    <span class="font-bold">{{ __('À vie') }}:</span> 
                                    <span class="text-lg">{{ $package->getFormattedLifetimePrice() }}</span>
                                    @php $lifetimeSavings = $package->getLifetimeSavings(); @endphp
                                    @if($lifetimeSavings['percent'] > 0)
                                        <span class="text-green-600 text-sm ml-2">({{ __('Économisez') }} {{ $lifetimeSavings['percent'] }}%)</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="space-y-2 mb-6">
                            <h3 class="font-semibold">{{ __('Caractéristiques') }}:</h3>
                            @php $features = explode("\n", $package->getFeatures()); @endphp
                            @foreach($features as $feature)
                                @if(!empty(trim($feature)))
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="space-y-2">
                            <h3 class="font-semibold">{{ __('Types d\'outils') }}:</h3>
                            @foreach($package->toolTypes as $toolType)
                                @php
                                    $toolsCount = $package->getToolsCountByType($toolType->slug);
                                    $toolsLimit = $package->getToolsLimitByType($toolType->slug);
                                    $displayCount = $toolsLimit && $toolsLimit > 0 ? min($toolsCount, $toolsLimit) : $toolsCount;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center mr-2" style="background-color: {{ $toolType->color }}20;">
                                        <span class="text-xs font-semibold" style="color: {{ $toolType->color }}">{{ $displayCount }}</span>
                                    </div>
                                    <span>{{ $toolType->getName() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="space-y-2">
                            @if($package->monthly_price > 0)
                                <a href="{{ route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'monthly']) }}" 
                                   class="block text-center py-2 px-4 rounded text-white transition-all duration-300" 
                                   style="background-color: {{ $package->color }}">
                                    {{ __('Souscrire mensuellement') }}
                                </a>
                            @endif
                            
                            @if($package->annual_price > 0)
                                <a href="{{ route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'annual']) }}" 
                                   class="block text-center py-2 px-4 rounded text-white transition-all duration-300" 
                                   style="background-color: {{ $package->color }}">
                                    {{ __('Souscrire annuellement') }}
                                </a>
                            @endif
                            
                            @if($package->lifetime_price > 0)
                                <a href="{{ route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'lifetime']) }}" 
                                   class="block text-center py-2 px-4 rounded text-white transition-all duration-300" 
                                   style="background-color: {{ $package->color }}">
                                    {{ __('Acheter à vie') }}
                                </a>
                            @endif
                            
                            @if($package->has_trial)
                                <a href="{{ route('trial.start', ['locale' => app()->getLocale(), 'slug' => $package->slug]) }}" 
                                   class="block text-center py-2 px-4 rounded border transition-all duration-300" 
                                   style="border-color: {{ $package->color }}; color: {{ $package->color }}">
                                    {{ __('Essayer gratuitement') }} ({{ $package->getTrialText() }})
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout> 