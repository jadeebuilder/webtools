<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Détails de la publicité') }}
                </h1>
                <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <!-- Détails de la publicité -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Informations') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Position -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Position') }}</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                                @switch($ad->position)
                                    @case('before_nav')
                                        {{ __('Avant la barre de navigation') }}
                                        @break
                                    @case('after_nav')
                                        {{ __('Après la barre de navigation') }}
                                        @break
                                    @case('header')
                                        {{ __('En-tête (header)') }}
                                        @break
                                    @case('before_content')
                                        {{ __('Avant le contenu principal') }}
                                        @break
                                    @case('after_content')
                                        {{ __('Après le contenu principal') }}
                                        @break
                                    @case('sidebar_top')
                                        {{ __('Haut de la barre latérale') }}
                                        @break
                                    @case('sidebar_middle')
                                        {{ __('Milieu de la barre latérale') }}
                                        @break
                                    @case('sidebar_bottom')
                                        {{ __('Bas de la barre latérale') }}
                                        @break
                                    @case('footer')
                                        {{ __('Pied de page (footer)') }}
                                        @break
                                    @default
                                        {{ $ad->position }}
                                @endswitch
                            </p>
                        </div>

                        <!-- Type -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Type') }}</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                                {{ $ad->type == 'image' ? __('Image') : __('Code AdSense') }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Statut') }}</h3>
                            <p class="mt-1">
                                @if($ad->active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ __('Actif') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ __('Inactif') }}
                                    </span>
                                @endif
                            </p>
                        </div>

                        <!-- Priority -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Priorité') }}</h3>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $ad->priority }}</p>
                        </div>

                        <!-- Display Locations -->
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Affiché sur') }}</h3>
                            <div class="mt-1 flex flex-wrap gap-2">
                                @if(is_string($ad->display_on))
                                    @foreach(json_decode($ad->display_on) as $location)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100">
                                            @switch($location)
                                                @case('home')
                                                    {{ __('Page d\'accueil') }}
                                                    @break
                                                @case('category')
                                                    {{ __('Pages de catégorie') }}
                                                    @break
                                                @case('tool')
                                                    {{ __('Pages d\'outil') }}
                                                    @break
                                                @case('blog')
                                                    {{ __('Pages de blog') }}
                                                    @break
                                                @default
                                                    {{ $location }}
                                            @endswitch
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <a href="{{ route('admin.ads.edit', ['locale' => app()->getLocale(), 'ad_id' => $ad->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                            <i class="fas fa-edit mr-2"></i> {{ __('Modifier') }}
                        </a>
                        <form action="{{ route('admin.ads.destroy', ['locale' => app()->getLocale(), 'ad_id' => $ad->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette publicité ?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition">
                                <i class="fas fa-trash-alt mr-2"></i> {{ __('Supprimer') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Prévisualisation -->
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Prévisualisation') }}</h2>
                    
                    @if($ad->type == 'image')
                        <!-- Détails de l'image -->
                        <div class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Chemin de l\'image') }}</h3>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $ad->image ?: __('Non défini') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Lien') }}</h3>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $ad->link ?: __('Non défini') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Texte alternatif') }}</h3>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $ad->alt ?: __('Non défini') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Aperçu de l'image -->
                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Aperçu') }}</h3>
                            @if($ad->image && file_exists(public_path($ad->image)))
                                <a href="{{ $ad->link }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset($ad->image) }}" alt="{{ $ad->alt }}" class="max-w-full h-auto rounded border border-gray-200 dark:border-gray-700">
                                </a>
                            @else
                                <div class="bg-gray-100 dark:bg-gray-700 rounded p-4 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <p>{{ __('L\'image n\'existe pas ou le chemin est incorrect') }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Code AdSense -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Code AdSense') }}</h3>
                            <div class="mt-2 mb-4">
                                <pre class="p-4 bg-gray-100 dark:bg-gray-900 rounded text-xs text-gray-600 dark:text-gray-300 overflow-x-auto">{{ $ad->code ?: __('Non défini') }}</pre>
                            </div>
                            
                            <!-- Aperçu AdSense -->
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('Aperçu') }}</h3>
                            <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                                @if($ad->code)
                                    {!! $ad->code !!}
                                @else
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-code text-3xl mb-2"></i>
                                        <p>{{ __('Aucun code AdSense n\'a été défini') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 