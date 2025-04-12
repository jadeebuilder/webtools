<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Gestion des publicités') }}
                </h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.ads.test', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-vial mr-2"></i> {{ __('Tester les publicités') }}
                    </a>
                    <a href="{{ route('admin.ads.create', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 bg-purple-700 text-white rounded-md hover:bg-purple-800 transition-colors">
                        <i class="fas fa-plus mr-2"></i> {{ __('Nouvelle publicité') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Positions des publicités -->
            @foreach($adSettingsByPosition as $position => $ads)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-purple-50 dark:bg-purple-900 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-purple-800 dark:text-purple-300">
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
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($ads as $ad)
                            <div class="p-6 flex items-center justify-between">
                                <div class="flex items-center w-2/3">
                                    <div class="w-16 h-16 flex-shrink-0 mr-4">
                                        @if($ad->type === 'image' && $ad->image)
                                            <img src="{{ asset($ad->image) }}" alt="{{ $ad->alt }}" class="w-full h-full object-cover rounded-md">
                                        @else
                                            <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-md">
                                                <i class="fas fa-code text-gray-500 dark:text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $ad->type === 'image' ? 'Image publicitaire' : 'Code AdSense' }}
                                            <span class="ml-2 px-2 py-1 text-xs {{ $ad->active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }} rounded-full">
                                                {{ $ad->active ? __('Actif') : __('Inactif') }}
                                            </span>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Priorité') }}: {{ $ad->priority }} | 
                                            {{ __('Affichage sur') }}: 
                                            @php
                                                $displayOnArray = is_array($ad->display_on) ? $ad->display_on : json_decode($ad->display_on, true);
                                            @endphp
                                            @if(is_array($displayOnArray))
                                                @foreach($displayOnArray as $displayOn)
                                                    <span class="inline-block px-2 py-1 text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full mr-1">
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
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.ads.toggle', ['locale' => app()->getLocale(), 'ad_id' => $ad->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                                        <button type="submit" class="p-2 {{ $ad->active ? 'text-red-600 hover:bg-red-100 dark:text-red-500 dark:hover:bg-red-900' : 'text-green-600 hover:bg-green-100 dark:text-green-500 dark:hover:bg-green-900' }} rounded hover:shadow-sm" title="{{ $ad->active ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas {{ $ad->active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.ads.edit', ['locale' => app()->getLocale(), 'ad_id' => $ad->id]) }}" class="p-2 text-indigo-600 hover:bg-indigo-100 dark:text-indigo-500 dark:hover:bg-indigo-900 rounded hover:shadow-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.ads.destroy', ['locale' => app()->getLocale(), 'ad_id' => $ad->id]) }}" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette publicité ?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-100 dark:text-red-500 dark:hover:bg-red-900 rounded hover:shadow-sm" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-gray-500 dark:text-gray-400 text-center">
                                {{ __('Aucune publicité configurée pour cette position.') }}
                                <a href="{{ route('admin.ads.create', ['locale' => app()->getLocale()]) }}" class="text-purple-600 dark:text-purple-400 hover:underline">
                                    {{ __('Ajouter une publicité ici') }}
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-admin-layout> 