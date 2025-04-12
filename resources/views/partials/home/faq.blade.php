<!-- FAQ Section -->
<div class="mb-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{ __('Frequently Asked Questions') }}</h2>
    <p class="text-gray-600 text-center mb-8">{{ __('Got questions? We\'ve got answers.') }}</p>

    <div class="space-y-6 w-full" x-data="{ selected: null }">
        <!-- FAQ Item 1 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
            <button @click="selected !== 1 ? selected = 1 : selected = null"
                    class="flex justify-between items-center w-full p-6 text-left transition-colors duration-300 hover:bg-gray-50">
                <span class="font-medium text-gray-900 text-lg">{{ __('Are all tools free to use?') }}</span>
                <div class="ml-4 flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 group-hover:bg-gray-200 transition-colors duration-300">
                    <i class="fas transition-transform duration-300" :class="selected === 1 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </div>
            </button>
            <div x-show="selected === 1"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="p-6 pt-0 text-gray-600">
                {{ __('Yes, most of our tools are free to use. However, some advanced features require a Pro subscription.') }}
            </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
            <button @click="selected !== 2 ? selected = 2 : selected = null"
                    class="flex justify-between items-center w-full p-6 text-left transition-colors duration-300 hover:bg-gray-50">
                <span class="font-medium text-gray-900 text-lg">{{ __('Do you offer API access?') }}</span>
                <div class="ml-4 flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 group-hover:bg-gray-200 transition-colors duration-300">
                    <i class="fas transition-transform duration-300" :class="selected === 2 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </div>
            </button>
            <div x-show="selected === 2"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="p-6 pt-0 text-gray-600">
                {{ __('Yes, API access is available with our Pro subscription. You can integrate our tools directly into your applications.') }}
            </div>
        </div>

        <!-- Autres questions... -->
    </div>
</div> 