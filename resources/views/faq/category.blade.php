<x-app-layout>
    <div class="container mx-auto px-4 py-12">
        <div class="mb-8">
            <a href="{{ route('faq.index', ['locale' => app()->getLocale()]) }}" class="text-primary hover:text-primary-dark transition-colors duration-300 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>{{ __('Retour à toutes les FAQs') }}</span>
            </a>
        </div>

        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="{{ $category->icon ?? 'fas fa-question-circle' }} text-3xl text-primary"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $category->name }}</h1>
            @if($category->description)
            <p class="text-lg text-gray-600">{{ $category->description }}</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-md p-8">            
            <div class="space-y-6" x-data="{ selected: null }">
                @forelse($faqs as $index => $faq)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button @click="selected !== {{ $index }} ? selected = {{ $index }} : selected = null" 
                            class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors duration-300">
                        <span class="font-medium text-gray-900">{{ $faq->question }}</span>
                        <i class="fas transition-transform duration-300" :class="selected === {{ $index }} ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="selected === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 transform -translate-y-2" 
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="p-4 bg-gray-50 border-t border-gray-200">
                        <p class="text-gray-600">{!! $faq->answer !!}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-500">{{ __('Aucune FAQ disponible dans cette catégorie pour le moment.') }}</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="mt-12 text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('Vous n\'avez pas trouvé ce que vous cherchiez ?') }}</h3>
            <p class="text-gray-600 mb-6">{{ __('Contactez-nous pour toute autre question.') }}</p>
            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg inline-block transition-colors duration-300">
                <i class="fas fa-envelope mr-2"></i>{{ __('Nous contacter') }}
            </a>
        </div>
    </div>
</x-app-layout> 