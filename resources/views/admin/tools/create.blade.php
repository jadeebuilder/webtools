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
                                    <div class="relative flex-grow">
                                        <input type="text" name="icon" id="icon" value="{{ old('icon', 'fas fa-wrench') }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i id="icon-preview" class="{{ old('icon', 'fas fa-wrench') }} text-gray-400"></i>
                                        </div>
                                    </div>
                                    <button type="button" onclick="openIconModal()"
                                        class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                        {{ __('Choisir une icône') }}
                                    </button>
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
                            
                            <!-- Types d'outils disponibles -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Types d\'outils') }}
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($toolTypes as $type)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="tool_types[]" id="tool_type_{{ $type->id }}" value="{{ $type->id }}"
                                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                                                {{ in_array($type->id, old('tool_types', [])) ? 'checked' : '' }}>
                                            <label for="tool_type_{{ $type->id }}" class="ml-2 flex items-center">
                                                @if($type->icon)
                                                    <i class="{{ $type->icon }} mr-2" style="color: {{ $type->color }};"></i>
                                                @endif
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $type->getName() }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('tool_types')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                            <textarea 
                                                name="translations[{{ $language->code }}][description]" 
                                                id="translations_{{ $language->code }}_description" 
                                                rows="6"
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                                required
                                                placeholder="{{ __('Description de l\'outil') }}">{{ old("translations.{$language->code}.description") }}</textarea>
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
    
    <!-- Modal de sélection d'icône -->
    <div id="iconModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-[400px] max-h-[90vh] flex flex-col">
            <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Sélectionner une icône') }}</h3>
                    <button onclick="closeIconModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="relative mt-2">
                    <input type="text" id="iconSearch" 
                           class="w-full px-3 py-1 pl-8 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="{{ __('Rechercher une icône...') }}">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <div class="overflow-y-auto p-3" style="max-height: 320px;">
                <div class="grid grid-cols-5 gap-2" id="iconGrid">
                    <!-- Les icônes seront ajoutées ici dynamiquement -->
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Liste des icônes Font Awesome à afficher
        const icons = [
            'fa-solid fa-cube', 'fa-solid fa-puzzle-piece', 'fa-solid fa-tools', 'fa-solid fa-wrench', 'fa-solid fa-cog', 'fa-solid fa-sliders-h',
            'fa-solid fa-code', 'fa-solid fa-terminal', 'fa-solid fa-file-code', 'fa-solid fa-laptop-code', 'fa-solid fa-keyboard',
            'fa-solid fa-mouse-pointer', 'fa-solid fa-magic', 'fa-solid fa-wand-magic', 'fa-solid fa-bolt', 'fa-solid fa-lightbulb',
            'fa-solid fa-paint-brush', 'fa-solid fa-palette', 'fa-solid fa-image', 'fa-solid fa-photo-video', 'fa-solid fa-camera',
            'fa-solid fa-chart-bar', 'fa-solid fa-chart-line', 'fa-solid fa-chart-pie', 'fa-solid fa-chart-area', 'fa-solid fa-table',
            'fa-solid fa-database', 'fa-solid fa-server', 'fa-solid fa-network-wired', 'fa-solid fa-globe', 'fa-solid fa-cloud',
            'fa-solid fa-shield-alt', 'fa-solid fa-lock', 'fa-solid fa-key', 'fa-solid fa-user-shield', 'fa-solid fa-fingerprint',
            'fa-solid fa-search', 'fa-solid fa-filter', 'fa-solid fa-sort', 'fa-solid fa-sort-alpha-down', 'fa-solid fa-sort-numeric-down',
            'fa-solid fa-calculator', 'fa-solid fa-percentage', 'fa-solid fa-divide', 'fa-solid fa-equals', 'fa-solid fa-infinity',
            'fa-solid fa-clock', 'fa-solid fa-calendar', 'fa-solid fa-calendar-alt', 'fa-solid fa-calendar-check', 'fa-solid fa-calendar-times',
            'fa-solid fa-map-marker-alt', 'fa-solid fa-compass', 'fa-solid fa-location-arrow', 'fa-solid fa-street-view', 'fa-solid fa-map',
            'fa-solid fa-envelope', 'fa-solid fa-paper-plane', 'fa-solid fa-inbox', 'fa-solid fa-comment', 'fa-solid fa-comments',
            'fa-solid fa-share-alt', 'fa-solid fa-retweet', 'fa-solid fa-sync', 'fa-solid fa-redo', 'fa-solid fa-undo',
            'fa-solid fa-trash', 'fa-solid fa-trash-alt', 'fa-solid fa-archive', 'fa-solid fa-folder', 'fa-solid fa-folder-open',
            'fa-solid fa-download', 'fa-solid fa-upload', 'fa-solid fa-file-import', 'fa-solid fa-file-export', 'fa-solid fa-file-download',
            'fa-solid fa-print', 'fa-solid fa-copy', 'fa-solid fa-paste', 'fa-solid fa-cut', 'fa-solid fa-clipboard',
            'fa-solid fa-link', 'fa-solid fa-unlink', 'fa-solid fa-external-link-alt', 'fa-solid fa-external-link-square-alt', 'fa-solid fa-share',
            'fa-solid fa-plus', 'fa-solid fa-minus', 'fa-solid fa-times', 'fa-solid fa-check', 'fa-solid fa-ban',
            'fa-solid fa-info-circle', 'fa-solid fa-question-circle', 'fa-solid fa-exclamation-circle', 'fa-solid fa-exclamation-triangle', 'fa-solid fa-bell',
            'fa-solid fa-star', 'fa-solid fa-heart', 'fa-solid fa-thumbs-up', 'fa-solid fa-thumbs-down', 'fa-solid fa-flag',
            'fa-solid fa-bookmark', 'fa-solid fa-tag', 'fa-solid fa-tags', 'fa-solid fa-hashtag', 'fa-solid fa-at',
            'fa-solid fa-user', 'fa-solid fa-users', 'fa-solid fa-user-plus', 'fa-solid fa-user-minus', 'fa-solid fa-user-cog',
            'fa-solid fa-cog', 'fa-solid fa-sliders-h', 'fa-solid fa-tasks', 'fa-solid fa-list', 'fa-solid fa-th-list',
            'fa-solid fa-bars', 'fa-solid fa-ellipsis-h', 'fa-solid fa-ellipsis-v', 'fa-solid fa-align-left', 'fa-solid fa-align-center',
            'fa-solid fa-align-right', 'fa-solid fa-align-justify', 'fa-solid fa-indent', 'fa-solid fa-outdent', 'fa-solid fa-list-ol',
            'fa-solid fa-list-ul', 'fa-solid fa-quote-left', 'fa-solid fa-quote-right', 'fa-solid fa-paragraph', 'fa-solid fa-text-height',
            'fa-solid fa-bold', 'fa-solid fa-italic', 'fa-solid fa-underline', 'fa-solid fa-strikethrough', 'fa-solid fa-subscript',
            'fa-solid fa-superscript', 'fa-solid fa-eraser', 'fa-solid fa-font', 'fa-solid fa-heading', 'fa-solid fa-highlighter'
        ];

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
            
            // Initialiser le modal d'icônes
            populateIconGrid();
        });

        // Fonctions pour le modal d'icônes
        function openIconModal() {
            const modal = document.getElementById('iconModal');
            modal.classList.remove('hidden');
            populateIconGrid();
        }

        function closeIconModal() {
            const modal = document.getElementById('iconModal');
            modal.classList.add('hidden');
        }

        function populateIconGrid() {
            const iconGrid = document.getElementById('iconGrid');
            const iconSearch = document.getElementById('iconSearch');
            
            if (!iconGrid) return;
            
            const searchTerm = iconSearch ? iconSearch.value.toLowerCase() : '';
            
            // Vider la grille
            iconGrid.innerHTML = '';
            
            // Filtrer les icônes en fonction de la recherche
            const filteredIcons = icons.filter(icon => 
                icon.toLowerCase().includes(searchTerm)
            );
            
            // Ajouter les icônes filtrées à la grille
            filteredIcons.forEach(icon => {
                const iconDiv = document.createElement('div');
                iconDiv.className = 'flex items-center justify-center h-14 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200';
                iconDiv.innerHTML = `<i class="${icon} text-xl"></i>`;
                iconDiv.onclick = () => selectIcon(icon);
                iconGrid.appendChild(iconDiv);
            });
        }

        function selectIcon(icon) {
            const iconInput = document.getElementById('icon');
            const iconPreview = document.getElementById('icon-preview');
            
            if (iconInput && iconPreview) {
                iconInput.value = icon;
                iconPreview.className = `${icon} text-gray-400`;
                closeIconModal();
            }
        }

        // Écouter les changements dans la recherche d'icônes
        const iconSearchInput = document.getElementById('iconSearch');
        if (iconSearchInput) {
            iconSearchInput.addEventListener('input', populateIconGrid);
        }

        // Fermer la modal en cliquant en dehors
        const iconModal = document.getElementById('iconModal');
        if (iconModal) {
            iconModal.addEventListener('click', function(e) {
                if (e.target === iconModal) {
                    closeIconModal();
                }
            });
        }
    </script>
    @endpush
</x-admin-layout> 