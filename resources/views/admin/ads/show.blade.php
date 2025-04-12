<x-app-layout :pageTitle="__('Détails de la publicité')" :metaDescription="__('Détails d\'une publicité')">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails de la publicité') }}
            </h1>
            <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Position') }}: 
                                <span class="font-normal">
                                    @switch($ad->position)
                                        @case('before_nav')
                                            {{ __('Avant la navigation') }}
                                            @break
                                        @case('after_nav')
                                            {{ __('Après la navigation') }}
                                            @break
                                        @case('before_tool_title')
                                            {{ __('Avant le titre de l\'outil') }}
                                            @break
                                        @case('after_tool_description')
                                            {{ __('Après la description de l\'outil') }}
                                            @break
                                        @case('before_tool')
                                            {{ __('Avant l\'outil') }}
                                            @break
                                        @case('after_tool')
                                            {{ __('Après l\'outil') }}
                                            @break
                                        @case('left_sidebar')
                                            {{ __('Barre latérale gauche') }}
                                            @break
                                        @case('right_sidebar')
                                            {{ __('Barre latérale droite') }}
                                            @break
                                        @case('bottom_tool')
                                            {{ __('En bas de l\'outil') }}
                                            @break
                                        @case('before_footer')
                                            {{ __('Avant le pied de page') }}
                                            @break
                                        @case('after_footer')
                                            {{ __('Après le pied de page') }}
                                            @break
                                        @default
                                            {{ $ad->position }}
                                    @endswitch
                                </span>
                            </h2>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ __('Type') }}: {{ $ad->type === 'image' ? __('Image') : __('Code AdSense') }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __('Status') }}: 
                                <span class="px-2 py-1 text-xs rounded-full {{ $ad->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $ad->active ? __('Actif') : __('Inactif') }}
                                </span>
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __('Priorité') }}: {{ $ad->priority }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __('Affichage sur') }}: 
                                @foreach(json_decode($ad->display_on) as $displayOn)
                                    <span class="inline-block px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full mr-1">
                                        @switch($displayOn)
                                            @case('home')
                                                {{ __('Accueil') }}
                                                @break
                                            @case('tool')
                                                {{ __('Outils') }}
                                                @break
                                            @case('category')
                                                {{ __('Catégories') }}
                                                @break
                                            @case('admin')
                                                {{ __('Admin') }}
                                                @break
                                            @default
                                                {{ $displayOn }}
                                        @endswitch
                                    </span>
                                @endforeach
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.ads.edit', ['ad' => $ad, 'locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-edit mr-2"></i> {{ __('Modifier') }}
                            </a>
                            <form action="{{ route('admin.ads.destroy', ['ad' => $ad, 'locale' => app()->getLocale()]) }}" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette publicité ?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <i class="fas fa-trash mr-2"></i> {{ __('Supprimer') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Aperçu de la publicité') }}</h3>
                        
                        @if($ad->type === 'image')
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-700">{{ __('Image') }}:</p>
                                    <p class="text-sm text-gray-600">{{ $ad->image }}</p>
                                    
                                    <p class="mt-2 text-sm font-medium text-gray-700">{{ __('Lien') }}:</p>
                                    <p class="text-sm text-gray-600">{{ $ad->link }}</p>
                                    
                                    <p class="mt-2 text-sm font-medium text-gray-700">{{ __('Texte alternatif') }}:</p>
                                    <p class="text-sm text-gray-600">{{ $ad->alt }}</p>
                                </div>
                                
                                <div class="mt-4 border rounded-lg overflow-hidden bg-white">
                                    @if(file_exists(public_path($ad->image)))
                                        <a href="{{ $ad->link }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ asset($ad->image) }}" alt="{{ $ad->alt }}" class="max-w-full h-auto">
                                        </a>
                                    @else
                                        <div class="p-4 bg-red-50 text-red-600">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            {{ __('L\'image') }} <strong>{{ $ad->image }}</strong> {{ __('n\'existe pas ou n\'est pas accessible.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Code AdSense') }}:</p>
                                <div class="bg-gray-100 p-4 rounded overflow-x-auto">
                                    <pre class="text-sm text-gray-800">{{ $ad->code }}</pre>
                                </div>
                                
                                <div class="mt-6 border rounded-lg p-4 bg-white">
                                    <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Rendu') }}:</p>
                                    <div class="adsense-preview">
                                        {!! $ad->code !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 