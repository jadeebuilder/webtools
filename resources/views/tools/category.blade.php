<x-app-layout :pageTitle="$pageTitle" :metaDescription="$metaDescription">
    <!-- Publicité avant la navigation -->
    @include('partials.ads.before-nav')
    
    <!-- Publicité après la navigation -->
    @include('partials.ads.after-nav')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row">
                <!-- Publicité barre latérale gauche -->
                @if(isset($adSettings['left_sidebar']) && $adSettings['left_sidebar']['active'])
                    <div class="lg:w-1/6 mb-4 lg:mb-0 lg:pr-4">
                        @include('partials.ads.left-sidebar')
                    </div>
                @endif
                
                <div class="flex-grow">
                    <!-- En-tête de la catégorie -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 text-purple-600 mr-4">
                                    <i class="fas fa-{{ $category->icon ?? 'th-large' }} text-xl"></i>
                                </span>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-800">{{ $category->getName() }}</h1>
                                    <p class="text-gray-600">{{ $category->getDescription() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Liste des outils de la catégorie -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Outils disponibles') }}</h2>
                            
                            @if($tools->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($tools as $tool)
                                        <a href="{{ route($tool->getRouteName(), $tool->getRouteParameters()) }}" 
                                           class="block bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden transform hover:-translate-y-1">
                                            <div class="p-5">
                                                <div class="flex items-center mb-3">
                                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-md bg-purple-100 text-purple-600">
                                                        <i class="fas fa-{{ $tool->icon ?? 'tools' }} text-lg"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h3 class="text-lg font-medium text-gray-900">{{ $tool->getName() }}</h3>
                                                    </div>
                                                    @if($tool->is_premium)
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-crown mr-1"></i> {{ __('Premium') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-500">{{ $tool->getTranslation(app()->getLocale())?->short_description }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">{{ __('Aucun outil disponible dans cette catégorie pour le moment.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Autres catégories -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Autres catégories') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <a href="{{ route('tools.category', ['locale' => app()->getLocale(), 'slug' => 'checker']) }}" class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-purple-100 text-purple-600 mr-3">
                                        <i class="fas fa-check-circle text-sm"></i>
                                    </span>
                                    <span class="text-gray-700">{{ __('Checker tools') }}</span>
                                </a>
                                <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'text']) }}" class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-gray-100 text-gray-600 mr-3">
                                        <i class="fas fa-font text-sm"></i>
                                    </span>
                                    <span class="text-gray-700">{{ __('Text tools') }}</span>
                                </a>
                                <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'converter']) }}" class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-green-100 text-green-600 mr-3">
                                        <i class="fas fa-exchange-alt text-sm"></i>
                                    </span>
                                    <span class="text-gray-700">{{ __('Converter tools') }}</span>
                                </a>
                                <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'generator']) }}" class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-blue-100 text-blue-600 mr-3">
                                        <i class="fas fa-magic text-sm"></i>
                                    </span>
                                    <span class="text-gray-700">{{ __('Generator tools') }}</span>
                                </a>
                                <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'developer']) }}" class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-indigo-100 text-indigo-600 mr-3">
                                        <i class="fas fa-code text-sm"></i>
                                    </span>
                                    <span class="text-gray-700">{{ __('Developer tools') }}</span>
                                </a>
                                <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'image']) }}" class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-orange-100 text-orange-600 mr-3">
                                        <i class="fas fa-image text-sm"></i>
                                    </span>
                                    <span class="text-gray-700">{{ __('Image tools') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sections additionnelles -->
                    @include('partials.home.testimonials')
                </div>
                
                <!-- Publicité barre latérale droite -->
                @if(isset($adSettings['right_sidebar']) && $adSettings['right_sidebar']['active'])
                    <div class="lg:w-1/6 mt-4 lg:mt-0 lg:pl-4">
                        @include('partials.ads.right-sidebar')
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Publicité avant le footer -->
    @include('partials.ads.before-footer')
</x-app-layout>

<!-- Publicité après le footer -->
@include('partials.ads.after-footer') 