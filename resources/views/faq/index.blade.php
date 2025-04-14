<x-app-layout>
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Questions fréquemment posées') }}</h1>
            <p class="text-lg text-gray-600">{{ __('Trouvez rapidement des réponses à vos questions.') }}</p>
        </div>

        @if($categories->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($categories as $category)
            <a href="{{ route('faq.category', ['locale' => app()->getLocale(), 'slug' => $category->slug]) }}" class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 p-6">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-4">
                        <i class="{{ $category->icon ?? 'fas fa-question-circle' }} text-2xl text-primary"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-2">{{ $category->name }}</h3>
                    <p class="text-gray-600 text-center">{{ $category->description }}</p>
                    <div class="mt-4 text-primary font-medium flex items-center">
                        <span>{{ __('Voir les :count questions', ['count' => $category->faqs->count()]) }}</span>
                        <i class="fas fa-chevron-right ml-2"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">{{ __('Questions populaires') }}</h2>
            
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
                        @if($faq->category)
                        <div class="mt-4 text-sm">
                            <span class="text-gray-500">{{ __('Catégorie') }}:</span> 
                            <a href="{{ route('faq.category', ['locale' => app()->getLocale(), 'slug' => $faq->category->slug]) }}" class="text-primary hover:text-primary-dark transition-colors duration-300">
                                {{ $faq->category->name }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-500">{{ __('Aucune FAQ disponible pour le moment.') }}</p>
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