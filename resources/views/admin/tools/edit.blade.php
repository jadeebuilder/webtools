<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Modifier l\'outil') }}: {{ $tool->getName() }}</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.tools.ads.edit', ['locale' => app()->getLocale(), 'tool' => $tool->id]) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        <i class="fas fa-ad mr-1"></i> {{ __('Configuration des publicités') }}
                    </a>
                    <a href="{{ route('admin.tools.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-arrow-left mr-1"></i> {{ __('Retour à la liste') }}
                    </a>
                </div>
            </div>

            <!-- Formulaire d'édition de l'outil -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <!-- Contenu du formulaire d'édition -->
                <form action="{{ route('admin.tools.update', ['locale' => app()->getLocale(), 'tool' => $tool->id]) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Le reste du formulaire d'édition ici -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Informations de base') }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Icône') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-sm">
                                        <i class="fas fa-icons"></i>
                                    </span>
                                    <input type="text" name="icon" id="icon" value="{{ old('icon', $tool->icon) }}" required
                                        class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('Classe d\'icône FontAwesome, ex: fas fa-wrench') }}
                                </p>
                                @error('icon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Slug') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="slug" id="slug" value="{{ old('slug', $tool->slug) }}" required
                                        class="flex-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('URL de l\'outil (minuscules, tirets)') }}
                                </p>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="tool_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Catégorie') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="tool_category_id" name="tool_category_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <option value="">{{ __('Sélectionner une catégorie') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('tool_category_id', $tool->tool_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->getName() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tool_category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Ordre') }}
                                </label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="order" id="order" value="{{ old('order', $tool->order) }}" min="0"
                                        class="flex-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('Ordre d\'affichage (0 = premier)') }}
                                </p>
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" {{ old('is_active', $tool->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Outil actif') }}
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="is_premium" name="is_premium" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" {{ old('is_premium', $tool->is_premium) ? 'checked' : '' }}>
                                <label for="is_premium" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Outil premium') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-purple-700 text-white font-medium rounded-md hover:bg-purple-800">
                            {{ __('Enregistrer les modifications') }}
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Autres sections -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Sections du template') }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            {{ __('Gérez les sections qui apparaissent sur la page de cet outil.') }}
                        </p>
                        <a href="{{ route('admin.templates.edit', ['locale' => app()->getLocale(), 'tool' => $tool->id]) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            <i class="fas fa-puzzle-piece mr-2"></i> {{ __('Gérer les sections') }}
                        </a>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Publicités') }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            {{ __('Configurez les emplacements publicitaires spécifiques pour cet outil.') }}
                        </p>
                        <a href="{{ route('admin.tools.ads.edit', ['locale' => app()->getLocale(), 'tool' => $tool->id]) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            <i class="fas fa-ad mr-2"></i> {{ __('Configurer les publicités') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 