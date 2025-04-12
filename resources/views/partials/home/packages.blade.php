<!-- Packages Section -->
<div class="mb-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{ __('Nos Forfaits') }}</h2>
    <p class="text-gray-600 text-center mb-8">{{ __('Choisissez le forfait qui correspond à vos besoins') }}</p>

    <div class="grid md:grid-cols-3 gap-8">
        <!-- Guest Plan -->
        <div class="bg-white rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group flex flex-col h-full">
            <div class="absolute top-0 left-0 w-full h-2 bg-gray-400"></div>
            <div class="text-center mb-8">
                <h3 class="text-xl text-gray-500 uppercase mb-2 tracking-wider">{{ __('GUEST') }}</h3>
                <div class="text-4xl font-bold text-gray-800">{{ __('Gratuit') }}</div>
                <div class="mt-4 text-sm text-gray-500">{{ __('Pour démarrer') }}</div>
            </div>
            <div class="space-y-4 flex-grow">
                <!-- Checker Tools -->
                <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                        <span class="text-sm font-semibold text-purple-600">17</span>
                    </div>
                    <span class="flex-grow">{{ __('Checker tools') }}</span>
                    <i class="fas fa-check text-green-500"></i>
                </div>
                <!-- Text Tools -->
                <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                        <span class="text-sm font-semibold text-gray-600">19</span>
                    </div>
                    <span class="flex-grow">{{ __('Text tools') }}</span>
                    <i class="fas fa-check text-green-500"></i>
                </div>
                <!-- Plus d'outils... -->
            </div>
            <div class="mt-8 pt-6 border-t border-gray-100">
                <button class="w-full py-3 px-4 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transform hover:scale-105 transition-all duration-200">
                    {{ __('Commencer gratuitement') }}
                </button>
            </div>
        </div>

        <!-- Basic Plan -->
        <div class="bg-white rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group scale-105 flex flex-col h-full">
            <div class="absolute top-0 left-0 w-full h-2 bg-blue-500"></div>
            <div class="absolute -right-12 -top-12 w-24 h-24 bg-blue-500 transform rotate-45"></div>
            <div class="absolute right-4 top-4 text-xs text-white font-bold tracking-wider">
                POPULAIRE
            </div>
            <div class="text-center mb-8">
                <h3 class="text-xl text-blue-500 uppercase mb-2 tracking-wider">{{ __('BASIC') }}</h3>
                <div class="text-4xl font-bold text-gray-800">9.99€<span class="text-base font-normal text-gray-600">{{ __('/mois') }}</span></div>
                <div class="mt-4 text-sm text-gray-500">{{ __('Pour les professionnels') }}</div>
            </div>
            <div class="space-y-4 flex-grow">
                <!-- Plus d'options... -->
            </div>
            <div class="mt-8 pt-6 border-t border-gray-100">
                <button class="w-full py-3 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transform hover:scale-105 transition-all duration-200">
                    {{ __('Commencer l\'essai') }}
                </button>
            </div>
        </div>

        <!-- VIP Plan -->
        <div class="bg-white rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group flex flex-col h-full">
            <div class="absolute top-0 left-0 w-full h-2 bg-purple-500"></div>
            <div class="text-center mb-8">
                <h3 class="text-xl text-purple-500 uppercase mb-2 tracking-wider">{{ __('VIP') }}</h3>
                <div class="text-4xl font-bold text-gray-800">29.99€<span class="text-base font-normal text-gray-600">{{ __('/mois') }}</span></div>
                <div class="mt-4 text-sm text-gray-500">{{ __('Pour les entreprises') }}</div>
            </div>
            <div class="space-y-4 flex-grow">
                <!-- Plus d'options... -->
            </div>
            <div class="mt-8 pt-6 border-t border-gray-100">
                <button class="w-full py-3 px-4 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transform hover:scale-105 transition-all duration-200">
                    {{ __('Contacter les ventes') }}
                </button>
            </div>
        </div>
    </div>
</div> 