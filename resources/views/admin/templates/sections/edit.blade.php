<x-app-layout :pageTitle="__('Modifier une section')" :metaDescription="__('Modifier une section de template existante')">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modifier une section') }}
            </h1>
            <a href="{{ route('admin.templates.sections.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.templates.sections.update', $section) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nom -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nom') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $section->name) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Chemin du partial -->
                        <div>
                            <label for="partial_path" class="block text-sm font-medium text-gray-700">{{ __('Chemin du partial') }} <span class="text-red-500">*</span></label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">partials.</span>
                                <input type="text" name="partial_path" id="partial_path" value="{{ old('partial_path', str_replace('partials.', '', $section->partial_path)) }}" class="flex-1 block w-full border border-gray-300 rounded-none rounded-r-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="home.testimonials" required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Format : home.testimonials (correspond à /resources/views/partials/home/testimonials.blade.php)') }}</p>
                            @error('partial_path')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Icône -->
                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700">{{ __('Icône FontAwesome') }}</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">fas fa-</span>
                                <input type="text" name="icon" id="icon" value="{{ old('icon', $section->icon) }}" class="flex-1 block w-full border border-gray-300 rounded-none rounded-r-md py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="users">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Nom de l\'icône FontAwesome (sans le préfixe fa-)') }}</p>
                            @error('icon')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $section->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ordre -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700">{{ __('Ordre d\'affichage') }}</label>
                            <input type="number" name="order" id="order" value="{{ old('order', $section->order) }}" min="0" class="mt-1 block w-40 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">{{ __('Position de la section dans la liste (0 = premier)') }}</p>
                            @error('order')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actif -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">{{ __('Section active') }}</label>
                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <a href="{{ route('admin.templates.sections.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Annuler') }}
                                </a>
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    {{ __('Mettre à jour') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ __('Aperçu de la section') }}</h2>
                    
                    <div class="border rounded-lg p-4 bg-gray-50">
                        @if(view()->exists($section->partial_path))
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-md bg-purple-100 text-purple-600">
                                        <i class="fas fa-{{ $section->icon }}"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $section->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $section->description }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 border rounded-lg overflow-hidden bg-white">
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-sm font-medium text-gray-700">{{ __('Rendu de la vue') }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ __('Vue trouvée') }}</span>
                                    </div>
                                    <div class="preview-container overflow-auto max-h-96 border p-4 rounded-lg">
                                        <div class="text-xs text-gray-500 mb-2">{{ __('Aperçu limité dans le panneau d\'administration') }}</div>
                                        @includeIf($section->partial_path)
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-red-50 text-red-600">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ __('La vue') }} <strong>{{ $section->partial_path }}</strong> {{ __('n\'existe pas ou n\'est pas accessible.') }}
                                <p class="mt-2 text-sm">{{ __('Chemin attendu:') }} <code>{{ resource_path('views/' . str_replace('.', '/', $section->partial_path) . '.blade.php') }}</code></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 