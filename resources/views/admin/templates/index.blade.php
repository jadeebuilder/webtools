<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Templates d\'outils') }}
            </h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.templates.sections.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-puzzle-piece mr-2"></i> {{ __('Gérer les sections') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filtres -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.templates.index', ['locale' => app()->getLocale()]) }}" method="GET" class="space-y-4 sm:flex sm:items-end sm:space-y-0 sm:space-x-4">
                        <div class="flex-grow">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Rechercher un outil') }}</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="{{ __('Nom de l\'outil') }}">
                        </div>
                        
                        <div class="sm:w-40">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Catégorie') }}</label>
                            <select name="category" id="category" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Toutes') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->getName() }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-gray-800 hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                                <i class="fas fa-search mr-2"></i> {{ __('Filtrer') }}
                            </button>
                            
                            <a href="{{ route('admin.templates.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                                <i class="fas fa-times mr-2"></i> {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tools as $tool)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition duration-200">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-md bg-purple-100 text-purple-600">
                                        <i class="fas fa-{{ $tool->icon ?? 'tools' }} text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h2 class="text-lg font-medium text-gray-900">{{ $tool->getName() }}</h2>
                                        <p class="text-sm text-gray-500">{{ __('Catégorie') }}: {{ $tool->category ? $tool->category->getName() : __('Non catégorisé') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-4">
                                <div class="flex items-center text-sm">
                                    <span class="mr-2 text-gray-500">{{ __('Sections') }}:</span>
                                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">{{ $tool->templates->count() }}</span>
                                </div>
                                <a href="{{ route('admin.templates.edit', ['tool' => $tool->id, 'locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-800 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <i class="fas fa-edit mr-2"></i> {{ __('Configurer') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-3">
                        <div class="p-6 bg-white border-b border-gray-200 text-center text-gray-500">
                            {{ __('Aucun outil trouvé.') }}
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $tools->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-admin-layout> 