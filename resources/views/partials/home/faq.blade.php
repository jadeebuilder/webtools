<!-- FAQ Section -->
<div class="mb-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{ __('Frequently Asked Questions') }}</h2>
    <p class="text-gray-600 text-center mb-8">{{ __('Got questions? We\'ve got answers.') }}</p>

    <div class="space-y-6 w-full" x-data="{ selected: null }">
        @forelse($faqs as $index => $faq)
        <!-- FAQ Item {{ $index + 1 }} -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
            <button @click="selected !== {{ $index + 1 }} ? selected = {{ $index + 1 }} : selected = null"
                    class="flex justify-between items-center w-full p-6 text-left transition-colors duration-300 hover:bg-gray-50">
                <span class="font-medium text-gray-900 text-lg">{{ $faq->question }}</span>
                <div class="ml-4 flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 group-hover:bg-gray-200 transition-colors duration-300">
                    <i class="fas transition-transform duration-300" :class="selected === {{ $index + 1 }} ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </div>
            </button>
            <div x-show="selected === {{ $index + 1 }}"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="p-6 pt-0 text-gray-600">
                {!! $faq->answer !!}
            </div>
        </div>
        @empty
        <!-- Aucune FAQ trouvée -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden p-6">
            <p class="text-gray-600 text-center">{{ __('No FAQs available at the moment.') }}</p>
        </div>
        @endforelse

        <div class="text-center mt-6">
            <a href="{{ route('faq.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors duration-300">
                <span>{{ __('View all FAQs') }}</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div> 