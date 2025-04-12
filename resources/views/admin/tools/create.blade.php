<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Nouvel outil') }}</h1>
                <a href="{{ route('admin.tools.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left mr-1"></i> {{ __('Retour à la liste') }}
                </a>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <form action="{{ route('admin.tools.store', ['locale' => app()->getLocale()]) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Informations de base -->
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
                                    <input type="text" name="icon" id="icon" value="{{ old('icon', 'fas fa-wrench') }}" required
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
                                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
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
                                        <option value="{{ $category->id }}" {{ old('tool_category_id') == $category->id ? 'selected' : '' }}>
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
                                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
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
                                <input id="is_active" name="is_active" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" {{ old('is_active') ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Outil actif') }}
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="is_premium" name="is_premium" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" {{ old('is_premium') ? 'checked' : '' }}>
                                <label for="is_premium" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Outil premium') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Traductions -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Traductions') }}</h2>
                        
                        <div x-data="{ activeTab: '{{ $languages->first()->code }}' }">
                            <div class="border-b border-gray-200 dark:border-gray-700">
                                <nav class="-mb-px flex space-x-4">
                                    @foreach($languages as $language)
                                        <button type="button"
                                            @click="activeTab = '{{ $language->code }}'"
                                            :class="activeTab === '{{ $language->code }}' ? 'border-purple-500 text-purple-600 dark:text-purple-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-300'"
                                            class="py-2 px-1 font-medium text-sm border-b-2 focus:outline-none">
                                            <span class="flex items-center">
                                                <span class="mr-2">{{ $language->name }}</span>
                                                @if($language->is_default)
                                                    <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-xs px-1.5 py-0.5 rounded-full">
                                                        {{ __('Par défaut') }}
                                                    </span>
                                                @endif
                                            </span>
                                        </button>
                                    @endforeach
                                </nav>
                            </div>
                            
                            @foreach($languages as $language)
                                <div x-show="activeTab === '{{ $language->code }}'" class="mt-4 space-y-4">
                                    <input type="hidden" name="translations[{{ $language->code }}][locale]" value="{{ $language->code }}">
                                    
                                    <div>
                                        <label for="translations_{{ $language->code }}_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('Nom') }} <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mt-1">
                                            <input type="text" name="translations[{{ $language->code }}][name]" id="translations_{{ $language->code }}_name" 
                                                value="{{ old("translations.{$language->code}.name") }}" required
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        </div>
                                        @error("translations.{$language->code}.name")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="translations_{{ $language->code }}_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('Description') }} <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mt-1">
                                            <textarea name="translations[{{ $language->code }}][description]" id="translations_{{ $language->code }}_description" rows="3" required
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">{{ old("translations.{$language->code}.description") }}</textarea>
                                        </div>
                                        @error("translations.{$language->code}.description")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="translations_{{ $language->code }}_meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ __('Titre SEO') }}
                                            </label>
                                            <div class="mt-1">
                                                <input type="text" name="translations[{{ $language->code }}][meta_title]" id="translations_{{ $language->code }}_meta_title" 
                                                    value="{{ old("translations.{$language->code}.meta_title") }}" maxlength="70"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('Laissez vide pour utiliser le nom de l\'outil') }}
                                            </p>
                                            @error("translations.{$language->code}.meta_title")
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="translations_{{ $language->code }}_meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ __('Description SEO') }}
                                            </label>
                                            <div class="mt-1">
                                                <textarea name="translations[{{ $language->code }}][meta_description]" id="translations_{{ $language->code }}_meta_description" rows="2" maxlength="160"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">{{ old("translations.{$language->code}.meta_description") }}</textarea>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('Laissez vide pour utiliser la description de l\'outil') }}
                                            </p>
                                            @error("translations.{$language->code}.meta_description")
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="translations_{{ $language->code }}_custom_h1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ __('Titre H1 personnalisé') }}
                                            </label>
                                            <div class="mt-1">
                                                <input type="text" name="translations[{{ $language->code }}][custom_h1]" id="translations_{{ $language->code }}_custom_h1" 
                                                    value="{{ old("translations.{$language->code}.custom_h1") }}"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('Laissez vide pour utiliser le nom de l\'outil') }}
                                            </p>
                                            @error("translations.{$language->code}.custom_h1")
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="translations_{{ $language->code }}_custom_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ __('Description personnalisée') }}
                                            </label>
                                            <div class="mt-1">
                                                <textarea name="translations[{{ $language->code }}][custom_description]" id="translations_{{ $language->code }}_custom_description" rows="2"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">{{ old("translations.{$language->code}.custom_description") }}</textarea>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('Laissez vide pour utiliser la description de l\'outil') }}
                                            </p>
                                            @error("translations.{$language->code}.custom_description")
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.tools.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            {{ __('Annuler') }}
                        </a>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            {{ __('Créer l\'outil') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Génération du slug à partir du nom de l'outil dans la langue par défaut
            const defaultLocale = document.querySelector('button[class*="border-purple-500"]').getAttribute('onclick').match(/'([^']+)'/)[1];
            const nameInput = document.getElementById(`translations_${defaultLocale}_name`);
            const slugInput = document.getElementById('slug');
            
            if (nameInput && slugInput) {
                nameInput.addEventListener('blur', function() {
                    if (slugInput.value === '' && this.value !== '') {
                        // Faire une requête AJAX pour générer le slug
                        fetch('{{ route('admin.tools.generate-slug', ['locale' => app()->getLocale()]) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                name: this.value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.slug) {
                                slugInput.value = data.slug;
                            }
                        })
                        .catch(error => console.error('Erreur:', error));
                    }
                });
            }
        });
    </script>
    @endpush
</x-admin-layout> 