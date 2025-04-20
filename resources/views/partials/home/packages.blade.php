<!-- Packages Section -->
<div class="mb-16" x-data="{ billingPeriod: 'monthly' }">
    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{ __('Nos Forfaits') }}</h2>
    <p class="text-gray-600 text-center mb-6">{{ __('Choisissez le forfait qui correspond à vos besoins') }}</p>

    <!-- Sélecteur de période -->
    <div class="flex justify-center mb-8">
        <div class="bg-gray-100 p-1 rounded-lg inline-flex">
            <button 
                @click="billingPeriod = 'monthly'" 
                :class="{ 'bg-white shadow-md text-gray-900': billingPeriod === 'monthly', 'text-gray-600 hover:bg-gray-200': billingPeriod !== 'monthly' }"
                class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200">
                {{ __('Mensuel') }}
            </button>
            <button 
                @click="billingPeriod = 'annual'" 
                :class="{ 'bg-white shadow-md text-gray-900': billingPeriod === 'annual', 'text-gray-600 hover:bg-gray-200': billingPeriod !== 'annual' }"
                class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200">
                {{ __('Annuel') }}
            </button>
            <button 
                @click="billingPeriod = 'lifetime'" 
                :class="{ 'bg-white shadow-md text-gray-900': billingPeriod === 'lifetime', 'text-gray-600 hover:bg-gray-200': billingPeriod !== 'lifetime' }"
                class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-200">
                {{ __('À vie') }}
            </button>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        @php
            $packages = \App\Models\Package::where('is_active', true)->orderBy('order')->take(3)->get();
        @endphp
        
        @foreach($packages as $index => $package)
            @php
                // Déterminer le style et les classes en fonction du package
                $isPopular = $index === 1; // Le package du milieu est marqué comme populaire
                $headerColor = $package->color;
                $buttonColor = $package->color;
                $textColor = $package->color;
                
                // Récupérer les outils associés au package
                $toolsCount = $package->tools()->count();
                $vipToolsCount = $package->vipTools()->count();
                $aiToolsCount = $package->aiTools()->count();
                
                // Récupérer les informations de prix
                $monthlyPrice = $package->monthly_price;
                $annualPrice = $package->annual_price;
                $lifetimePrice = $package->lifetime_price;
                $hasAnnualPlan = $annualPrice > 0;
                $hasLifetimePlan = $lifetimePrice > 0;
                $isFreePlan = $monthlyPrice == 0 && $annualPrice == 0 && $lifetimePrice == 0;
                
                // Calcul des réductions
                $annualSavings = 0;
                $annualSavingsPercent = 0;
                $lifetimeSavings = 0;
                $lifetimeSavingsPercent = 0;
                
                if ($monthlyPrice > 0 && $annualPrice > 0) {
                    $annualCostMonthly = $monthlyPrice * 12;
                    $annualSavings = $annualCostMonthly - $annualPrice;
                    $annualSavingsPercent = round(($annualSavings / $annualCostMonthly) * 100);
                }
                
                if ($monthlyPrice > 0 && $lifetimePrice > 0) {
                    $lifetimeCostMonthly = $monthlyPrice * 60; // 5 ans équivalent
                    $lifetimeSavings = $lifetimeCostMonthly - $lifetimePrice;
                    $lifetimeSavingsPercent = round(($lifetimeSavings / $lifetimeCostMonthly) * 100);
                }
                
                // Extraire les caractéristiques
                $features = explode("\n", $package->getFeatures());
            @endphp
            
            <div class="bg-white rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group {{ $isPopular ? 'scale-105' : '' }} flex flex-col h-full">
                <!-- Barre de couleur en haut -->
                <div class="absolute top-0 left-0 w-full h-2" style="background-color: {{ $headerColor }}"></div>
                
                @if($isPopular)
                    <!-- Badge "populaire" pour le plan du milieu -->
                    <div class="absolute -right-12 -top-12 w-24 h-24" style="background-color: {{ $headerColor }}; transform: rotate(45deg)"></div>
                    <div class="absolute right-4 top-4 text-xs text-white font-bold tracking-wider">
                        {{ __('POPULAIRE') }}
                    </div>
                @endif
                
                <div class="text-center mb-8">
                    <h3 class="text-xl uppercase mb-2 tracking-wider" style="color: {{ $textColor }}">{{ $package->getName() }}</h3>
                    
                    <!-- Prix mensuel -->
                    <div x-show="billingPeriod === 'monthly'" class="text-4xl font-bold text-gray-800">
                        @if($isFreePlan || $monthlyPrice == 0)
                            {{ __('Gratuit') }}
                        @else
                            {!! $package->getFormattedMonthlyPrice() !!}<span class="text-base font-normal text-gray-600">{{ __('/mois') }}</span>
                        @endif
                    </div>
                    
                    <!-- Prix annuel -->
                    <div x-show="billingPeriod === 'annual'" class="text-4xl font-bold text-gray-800">
                        @if($isFreePlan)
                            {{ __('Gratuit') }}
                        @elseif(!$hasAnnualPlan)
                            <span class="text-xl text-gray-500">{{ __('Non disponible') }}</span>
                        @else
                            {!! $package->getFormattedAnnualPrice() !!}<span class="text-base font-normal text-gray-600">{{ __('/an') }}</span>
                            @php
                                $annualSavings = $package->getAnnualSavings();
                            @endphp
                            @if($annualSavings['percent'] > 0)
                                <div class="text-sm font-normal text-green-600 mt-1">
                                    {{ __('Économisez') }} {!! $annualSavings['formatted'] !!} ({{ $annualSavings['percent'] }}%)
                                </div>
                            @endif
                        @endif
                    </div>
                    
                    <!-- Prix à vie -->
                    <div x-show="billingPeriod === 'lifetime'" class="text-4xl font-bold text-gray-800">
                        @if($isFreePlan)
                            {{ __('Gratuit') }}
                        @elseif(!$hasLifetimePlan)
                            <span class="text-xl text-gray-500">{{ __('Non disponible') }}</span>
                        @else
                            {!! $package->getFormattedLifetimePrice() !!}<span class="text-base font-normal text-gray-600">{{ __(' à vie') }}</span>
                            @php
                                $lifetimeSavings = $package->getLifetimeSavings();
                            @endphp
                            @if($lifetimeSavings['percent'] > 0)
                                <div class="text-sm font-normal text-green-600 mt-1">
                                    {{ __('Économisez') }} {{ $lifetimeSavings['percent'] }}% {{ __('sur 5 ans') }}
                                </div>
                            @endif
                        @endif
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-500">{{ $package->getDescription() }}</div>
                </div>
                
                <div class="space-y-4 flex-grow">
                    @foreach($features as $featureIndex => $feature)
                        @if(!empty(trim($feature)))
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="flex-grow">{{ $feature }}</span>
                            </div>
                        @endif
                    @endforeach
                    
                    <!-- Affichage dynamique des types d'outils -->
                    @foreach($package->toolTypes as $toolType)
                        @php
                            $toolsCount = $package->getToolsCountByType($toolType->slug);
                            $toolsLimit = $package->getToolsLimitByType($toolType->slug);
                            $displayCount = $toolsLimit && $toolsLimit > 0 ? min($toolsCount, $toolsLimit) : $toolsCount;
                        @endphp
                        <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $toolType->color }}20;">
                                <span class="text-sm font-semibold" style="color: {{ $toolType->color }}">{{ $displayCount }}</span>
                            </div>
                            <span class="flex-grow">{{ $toolType->getName() }}</span>
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <!-- Bouton pour le plan mensuel -->
                    <a 
                        x-show="billingPeriod === 'monthly'"
                        href="@auth
                                {{ $isFreePlan 
                                    ? route('register', ['locale' => app()->getLocale(), 'package' => $package->slug]) 
                                    : ($package->has_trial 
                                        ? route('trial.start', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'monthly']) 
                                        : route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'monthly'])) 
                                }}
                            @else
                                {{ route('login', ['locale' => app()->getLocale(), 'redirect_to' => $isFreePlan 
                                    ? route('register', ['locale' => app()->getLocale(), 'package' => $package->slug]) 
                                    : ($package->has_trial 
                                        ? route('trial.start', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'monthly']) 
                                        : route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'monthly'])), 'intent' => 'subscribe_'.$package->slug.'_monthly']) 
                                }}
                            @endauth"
                        class="w-full py-3 px-4 text-white rounded-lg transform hover:scale-105 transition-all duration-200 inline-block text-center" 
                        style="background-color: {{ $buttonColor }};">
                        @if($isFreePlan)
                            {{ __('Commencer gratuitement') }}
                        @elseif($package->has_trial)
                            {{ __('Commencer l\'essai') }} ({{ $package->getTrialText() }})
                        @else
                            {{ __('Souscrire maintenant') }}
                        @endif
                    </a>
                    
                    <!-- Bouton pour le plan annuel -->
                    <a 
                        x-show="billingPeriod === 'annual'"
                        href="@auth
                                {{ $isFreePlan 
                                    ? route('register', ['locale' => app()->getLocale(), 'package' => $package->slug]) 
                                    : (!$hasAnnualPlan 
                                        ? '#' 
                                        : ($package->has_trial 
                                            ? route('trial.start', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'annual']) 
                                            : route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'annual']))) 
                                }}
                            @else
                                {{ !$hasAnnualPlan ? '#' : route('login', ['locale' => app()->getLocale(), 'redirect_to' => $isFreePlan 
                                    ? route('register', ['locale' => app()->getLocale(), 'package' => $package->slug]) 
                                    : ($package->has_trial 
                                        ? route('trial.start', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'annual']) 
                                        : route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'annual'])), 'intent' => 'subscribe_'.$package->slug.'_annual']) 
                                }}
                            @endauth"
                        class="w-full py-3 px-4 rounded-lg transform hover:scale-105 transition-all duration-200 inline-block text-center"
                        :class="{ 'bg-gray-300 text-gray-600 cursor-not-allowed': {{ !$hasAnnualPlan && !$isFreePlan ? 'true' : 'false' }}, 'text-white': {{ ($hasAnnualPlan || $isFreePlan) ? 'true' : 'false' }} }"
                        style="{{ ($hasAnnualPlan || $isFreePlan) ? 'background-color: '.$buttonColor.';' : '' }}"
                        {{ !$hasAnnualPlan && !$isFreePlan ? 'onclick="event.preventDefault()"' : '' }}>
                        @if($isFreePlan)
                            {{ __('Commencer gratuitement') }}
                        @elseif(!$hasAnnualPlan)
                            {{ __('Non disponible') }}
                        @elseif($package->has_trial)
                            {{ __('Commencer l\'essai') }} ({{ $package->getTrialText() }})
                        @else
                            {{ __('Souscrire à l\'année') }}
                        @endif
                    </a>
                    
                    <!-- Bouton pour le plan à vie -->
                    <a 
                        x-show="billingPeriod === 'lifetime'"
                        href="@auth
                                {{ $isFreePlan 
                                    ? route('register', ['locale' => app()->getLocale(), 'package' => $package->slug]) 
                                    : (!$hasLifetimePlan 
                                        ? '#' 
                                        : route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'lifetime'])) 
                                }}
                            @else
                                {{ !$hasLifetimePlan ? '#' : route('login', ['locale' => app()->getLocale(), 'redirect_to' => $isFreePlan 
                                    ? route('register', ['locale' => app()->getLocale(), 'package' => $package->slug]) 
                                    : route('checkout', ['locale' => app()->getLocale(), 'slug' => $package->slug, 'cycle' => 'lifetime']), 'intent' => 'subscribe_'.$package->slug.'_lifetime']) 
                                }}
                            @endauth"
                        class="w-full py-3 px-4 rounded-lg transform hover:scale-105 transition-all duration-200 inline-block text-center"
                        :class="{ 'bg-gray-300 text-gray-600 cursor-not-allowed': {{ !$hasLifetimePlan && !$isFreePlan ? 'true' : 'false' }}, 'text-white': {{ ($hasLifetimePlan || $isFreePlan) ? 'true' : 'false' }} }"
                        style="{{ ($hasLifetimePlan || $isFreePlan) ? 'background-color: '.$buttonColor.';' : '' }}"
                        {{ !$hasLifetimePlan && !$isFreePlan ? 'onclick="event.preventDefault()"' : '' }}>
                        @if($isFreePlan)
                            {{ __('Commencer gratuitement') }}
                        @elseif(!$hasLifetimePlan)
                            {{ __('Non disponible') }}
                        @else
                            {{ __('Accès illimité à vie') }}
                        @endif
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('packagePeriod', {
            period: 'monthly'
        });
    });
</script>
@endpush 