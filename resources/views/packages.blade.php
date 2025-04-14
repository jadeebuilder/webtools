<x-app-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- En-tête de la page -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl sm:tracking-tight">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">{{ __('Nos Packages') }}</span>
                </h1>
                <p class="mt-5 max-w-3xl mx-auto text-xl text-gray-500 dark:text-gray-300">
                    {{ __('Choisissez le forfait qui correspond à vos besoins et accédez à notre gamme d\'outils.') }}
                </p>
            </div>

            <!-- Sélecteur de période -->
            <div class="mb-12" x-data="{ activeTab: 'monthly' }">
                <div class="flex justify-center mb-8">
                    <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <button 
                            @click="activeTab = 'monthly'" 
                            :class="{ 'bg-white dark:bg-gray-700 text-purple-600 dark:text-purple-400 shadow': activeTab === 'monthly', 'text-gray-500 dark:text-gray-400': activeTab !== 'monthly' }"
                            class="py-2 px-4 rounded-md font-medium text-sm focus:outline-none transition-all duration-300 ease-in-out"
                        >
                            {{ __('Mensuel') }}
                        </button>
                        <button 
                            @click="activeTab = 'annual'" 
                            :class="{ 'bg-white dark:bg-gray-700 text-purple-600 dark:text-purple-400 shadow': activeTab === 'annual', 'text-gray-500 dark:text-gray-400': activeTab !== 'annual' }"
                            class="py-2 px-4 rounded-md font-medium text-sm focus:outline-none transition-all duration-300 ease-in-out"
                        >
                            {{ __('Annuel') }}
                            <span class="ml-1 text-xs text-green-500 dark:text-green-400 font-normal">{{ __('Économisez plus') }}</span>
                        </button>
                        <button 
                            @click="activeTab = 'lifetime'" 
                            :class="{ 'bg-white dark:bg-gray-700 text-purple-600 dark:text-purple-400 shadow': activeTab === 'lifetime', 'text-gray-500 dark:text-gray-400': activeTab !== 'lifetime' }"
                            class="py-2 px-4 rounded-md font-medium text-sm focus:outline-none transition-all duration-300 ease-in-out"
                        >
                            {{ __('À vie') }}
                        </button>
                    </div>
                </div>

                <!-- Conteneur des packages -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Packages gratuits -->
                    @if(isset($packagesByPrice['free']) && count($packagesByPrice['free']) > 0)
                        @foreach($packagesByPrice['free'] as $package)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group flex flex-col h-full">
                                <div class="absolute top-0 left-0 w-full h-2" style="background-color: {{ $package->color }}"></div>
                                
                                @if($package->is_default)
                                    <div class="absolute -right-12 -top-12 w-24 h-24 bg-green-500 transform rotate-45"></div>
                                    <div class="absolute right-4 top-4 text-xs text-white font-bold tracking-wider">
                                        {{ __('RECOMMANDÉ') }}
                                    </div>
                                @endif
                                
                                <div class="text-center mb-8">
                                    <h3 class="text-xl uppercase mb-2 tracking-wider" style="color: {{ $package->color }}">{{ $package->getName() }}</h3>
                                    <div class="text-4xl font-bold text-gray-800 dark:text-gray-100" x-show="activeTab === 'monthly'">
                                        @if($package->monthly_price > 0)
                                            {{ number_format($package->monthly_price, 2) }}€<span class="text-base font-normal text-gray-500 dark:text-gray-400">/{{ __('mois') }}</span>
                                        @else
                                            {{ __('Gratuit') }}
                                        @endif
                                    </div>
                                    <div class="text-4xl font-bold text-gray-800 dark:text-gray-100" x-show="activeTab === 'annual'">
                                        @if($package->annual_price > 0)
                                            {{ number_format($package->annual_price / 12, 2) }}€<span class="text-base font-normal text-gray-500 dark:text-gray-400">/{{ __('mois') }}</span>
                                            <div class="text-sm text-green-500 dark:text-green-400">
                                                {{ number_format($package->annual_price, 2) }}€ {{ __('facturé annuellement') }}
                                            </div>
                                        @else
                                            {{ __('Gratuit') }}
                                        @endif
                                    </div>
                                    <div class="text-4xl font-bold text-gray-800 dark:text-gray-100" x-show="activeTab === 'lifetime'">
                                        @if($package->lifetime_price > 0)
                                            {{ number_format($package->lifetime_price, 2) }}€<span class="text-base font-normal text-gray-500 dark:text-gray-400">{{ __('à vie') }}</span>
                                        @else
                                            {{ __('Non disponible') }}
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ $package->getDescription() }}</div>
                                </div>
                                
                                <div class="space-y-4 flex-grow">
                                    @if($package->tools->count() > 0)
                                        <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $package->color }}20">
                                                <span class="text-sm font-semibold" style="color: {{ $package->color }}">{{ $package->tools->count() }}</span>
                                            </div>
                                            <span class="flex-grow text-gray-600 dark:text-gray-300">{{ __('Outils disponibles') }}</span>
                                            <i class="fas fa-check text-green-500"></i>
                                        </div>
                                    @endif
                                    
                                    @if($package->vipTools()->count() > 0)
                                        <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-purple-100 dark:bg-purple-900">
                                                <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">{{ $package->vipTools()->count() }}</span>
                                            </div>
                                            <span class="flex-grow text-gray-600 dark:text-gray-300">{{ __('Outils VIP') }}</span>
                                            <i class="fas fa-check text-green-500"></i>
                                        </div>
                                    @endif
                                    
                                    @if($package->aiTools()->count() > 0)
                                        <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-blue-100 dark:bg-blue-900">
                                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $package->aiTools()->count() }}</span>
                                            </div>
                                            <span class="flex-grow text-gray-600 dark:text-gray-300">{{ __('Outils IA') }}</span>
                                            <i class="fas fa-check text-green-500"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-100 dark:bg-gray-700">
                                            <i class="fas {{ $package->show_ads ? 'fa-ad text-gray-500 dark:text-gray-400' : 'fa-ban text-red-500' }}"></i>
                                        </div>
                                        <span class="flex-grow text-gray-600 dark:text-gray-300">
                                            {{ $package->show_ads ? __('Publicités') : __('Sans publicités') }}
                                        </span>
                                        <i class="fas {{ $package->show_ads ? 'fa-times text-red-500' : 'fa-check text-green-500' }}"></i>
                                    </div>
                                    
                                    <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-100 dark:bg-gray-700">
                                            <i class="fas fa-clock text-gray-500 dark:text-gray-400"></i>
                                        </div>
                                        <span class="flex-grow text-gray-600 dark:text-gray-300">{{ __('Validité') }}</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $package->getCyclePeriodText() }}</span>
                                    </div>
                                    
                                    @if(!empty($package->getFeatures()))
                                        <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Caractéristiques') }}</h4>
                                            <ul class="space-y-2 text-sm">
                                                @foreach(explode("\n", $package->getFeatures()) as $feature)
                                                    @if(!empty(trim($feature)))
                                                        <li class="flex items-start">
                                                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                                            <span class="text-gray-600 dark:text-gray-300">{{ trim($feature) }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <button class="w-full py-3 px-4 rounded-lg transform hover:scale-105 transition-all duration-200"
                                            style="background-color: {{ $package->color }}; color: white;">
                                        {{ __('Obtenir') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    <!-- Packages payants -->
                    @if(isset($packagesByPrice['paid']) && count($packagesByPrice['paid']) > 0)
                        @foreach($packagesByPrice['paid'] as $package)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group flex flex-col h-full {{ $package->is_default ? 'scale-105 border-2' : '' }}" style="{{ $package->is_default ? 'border-color: '.$package->color : '' }}">
                                <div class="absolute top-0 left-0 w-full h-2" style="background-color: {{ $package->color }}"></div>
                                
                                @if($package->is_default)
                                    <div class="absolute -right-12 -top-12 w-24 h-24 transform rotate-45" style="background-color: {{ $package->color }}"></div>
                                    <div class="absolute right-4 top-4 text-xs text-white font-bold tracking-wider">
                                        {{ __('RECOMMANDÉ') }}
                                    </div>
                                @endif
                                
                                <div class="text-center mb-8">
                                    <h3 class="text-xl uppercase mb-2 tracking-wider" style="color: {{ $package->color }}">{{ $package->getName() }}</h3>
                                    <div class="text-4xl font-bold text-gray-800 dark:text-gray-100" x-show="activeTab === 'monthly'">
                                        @if($package->monthly_price > 0)
                                            {{ number_format($package->monthly_price, 2) }}€<span class="text-base font-normal text-gray-500 dark:text-gray-400">/{{ __('mois') }}</span>
                                        @else
                                            {{ __('Gratuit') }}
                                        @endif
                                    </div>
                                    <div class="text-4xl font-bold text-gray-800 dark:text-gray-100" x-show="activeTab === 'annual'">
                                        @if($package->annual_price > 0)
                                            {{ number_format($package->annual_price / 12, 2) }}€<span class="text-base font-normal text-gray-500 dark:text-gray-400">/{{ __('mois') }}</span>
                                            <div class="text-sm text-green-500 dark:text-green-400">
                                                {{ number_format($package->annual_price, 2) }}€ {{ __('facturé annuellement') }}
                                            </div>
                                        @else
                                            {{ __('Gratuit') }}
                                        @endif
                                    </div>
                                    <div class="text-4xl font-bold text-gray-800 dark:text-gray-100" x-show="activeTab === 'lifetime'">
                                        @if($package->lifetime_price > 0)
                                            {{ number_format($package->lifetime_price, 2) }}€<span class="text-base font-normal text-gray-500 dark:text-gray-400">{{ __('à vie') }}</span>
                                        @else
                                            {{ __('Non disponible') }}
                                        @endif
                                    </div>
                                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ $package->getDescription() }}</div>
                                </div>
                                
                                <div class="space-y-4 flex-grow">
                                    @if($package->tools->count() > 0)
                                        <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $package->color }}20">
                                                <span class="text-sm font-semibold" style="color: {{ $package->color }}">{{ $package->tools->count() }}</span>
                                            </div>
                                            <span class="flex-grow text-gray-600 dark:text-gray-300">{{ __('Outils disponibles') }}</span>
                                            <i class="fas fa-check text-green-500"></i>
                                        </div>
                                    @endif
                                    
                                    @if($package->vipTools()->count() > 0)
                                        <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-purple-100 dark:bg-purple-900">
                                                <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">{{ $package->vipTools()->count() }}</span>
                                            </div>
                                            <span class="flex-grow text-gray-600 dark:text-gray-300">{{ __('Outils VIP') }}</span>
                                            <i class="fas fa-check text-green-500"></i>
                                        </div>
                                    @endif
                                    
                                    @if($package->aiTools()->count() > 0)
                                        <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-blue-100 dark:bg-blue-900">
                                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $package->aiTools()->count() }}</span>
                                            </div>
                                            <span class="flex-grow text-gray-600 dark:text-gray-300">{{ __('Outils IA') }}</span>
                                            <i class="fas fa-check text-green-500"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-100 dark:bg-gray-700">
                                            <i class="fas {{ $package->show_ads ? 'fa-ad text-gray-500 dark:text-gray-400' : 'fa-ban text-red-500' }}"></i>
                                        </div>
                                        <span class="flex-grow text-gray-600 dark:text-gray-300">
                                            {{ $package->show_ads ? __('Publicités') : __('Sans publicités') }}
                                        </span>
                                        <i class="fas {{ $package->show_ads ? 'fa-times text-red-500' : 'fa-check text-green-500' }}"></i>
                                    </div>
                                    
                                    <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-gray-100 dark:bg-gray-700">
                                            <i class="fas fa-clock text-gray-500 dark:text-gray-400"></i>
                                        </div>
                                        <span class="flex-grow text-gray-600 dark:text-gray-300">{{ __('Validité') }}</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $package->getCyclePeriodText() }}</span>
                                    </div>
                                    
                                    @if(!empty($package->getFeatures()))
                                        <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Caractéristiques') }}</h4>
                                            <ul class="space-y-2 text-sm">
                                                @foreach(explode("\n", $package->getFeatures()) as $feature)
                                                    @if(!empty(trim($feature)))
                                                        <li class="flex items-start">
                                                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                                            <span class="text-gray-600 dark:text-gray-300">{{ trim($feature) }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                                    <button class="w-full py-3 px-4 rounded-lg transform hover:scale-105 transition-all duration-200"
                                            x-show="(activeTab === 'monthly' && {{ $package->monthly_price }} > 0) || (activeTab === 'annual' && {{ $package->annual_price }} > 0) || (activeTab === 'lifetime' && {{ $package->lifetime_price }} > 0)"
                                            style="background-color: {{ $package->color }}; color: white;">
                                        {{ __('Acheter maintenant') }}
                                    </button>
                                    <button class="w-full py-3 px-4 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed"
                                            x-show="(activeTab === 'monthly' && {{ $package->monthly_price }} == 0) || (activeTab === 'annual' && {{ $package->annual_price }} == 0) || (activeTab === 'lifetime' && {{ $package->lifetime_price }} == 0)">
                                        {{ __('Non disponible') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 