<x-app-layout :pageTitle="__('Administration des publicités')" :metaDescription="__('Gérer les publicités du site')">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des publicités') }}
            </h1>
            <a href="{{ route('admin.ads.create', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-800 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> {{ __('Nouvelle publicité') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Positions des publicités -->
            @foreach($adSettingsByPosition as $position => $ads)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-purple-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-purple-800">
                            @switch($position)
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
                                    {{ $position }}
                            @endswitch
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($ads as $ad)
                            <div class="p-6 flex items-center justify-between">
                                <div class="flex items-center w-2/3">
                                    <div class="w-16 h-16 flex-shrink-0 mr-4">
                                        @if($ad->type === 'image' && $ad->image)
                                            <img src="{{ asset($ad->image) }}" alt="{{ $ad->alt }}" class="w-full h-full object-cover rounded-md">
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-md">
                                                <i class="fas fa-code text-gray-500"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $ad->type === 'image' ? 'Image publicitaire' : 'Code AdSense' }}
                                            <span class="ml-2 px-2 py-1 text-xs {{ $ad->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                                                {{ $ad->active ? __('Actif') : __('Inactif') }}
                                            </span>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ __('Priorité') }}: {{ $ad->priority }} | 
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
                                </div>
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.ads.toggle', ['ad' => $ad, 'locale' => app()->getLocale()]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 {{ $ad->active ? 'text-red-600 hover:bg-red-100' : 'text-green-600 hover:bg-green-100' }} rounded hover:shadow-sm" title="{{ $ad->active ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas {{ $ad->active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.ads.edit', ['ad' => $ad, 'locale' => app()->getLocale()]) }}" class="p-2 text-indigo-600 hover:bg-indigo-100 rounded hover:shadow-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.ads.destroy', ['ad' => $ad, 'locale' => app()->getLocale()]) }}" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette publicité ?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded hover:shadow-sm" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-gray-500 text-center">
                                {{ __('Aucune publicité configurée pour cette position.') }}
                                <a href="{{ route('admin.ads.create', ['locale' => app()->getLocale()]) }}" class="text-purple-600 hover:underline">
                                    {{ __('Ajouter une publicité ici') }}
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout> 