<x-admin-layout>
    <div class="p-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ __('Modifier le type d\'outil') }}</h1>
                <a href="{{ route('admin.tool-types.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Retour à la liste') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.tool-types.update', ['locale' => app()->getLocale(), 'id' => $toolType->id]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Slug') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $toolType->slug) }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Identifiant unique (minuscules, chiffres et tirets uniquement)') }}</p>
                            </div>
                            
                            <!-- Icône -->
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Icône') }}</label>
                                <div class="flex">
                                    <div class="relative flex-grow">
                                        <input type="text" name="icon" id="icon" value="{{ old('icon', $toolType->icon) }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                            placeholder="fas fa-star">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i id="icon-preview" class="{{ old('icon', $toolType->icon ?: 'fas fa-icons') }} text-gray-400"></i>
                                        </div>
                                    </div>
                                    <button type="button" onclick="openIconModal()"
                                        class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                        {{ __('Choisir une icône') }}
                                    </button>
                                </div>
                                @error('icon')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Classe Font Awesome (optionnel)') }}</p>
                            </div>
                            
                            <!-- Couleur -->
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Couleur') }} <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <input type="color" name="color" id="color" value="{{ old('color', $toolType->color) }}" required
                                        class="h-10 w-16 rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <input type="text" id="color-hex" value="{{ old('color', $toolType->color) }}" 
                                        class="flex-1 rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                        readonly>
                                </div>
                                @error('color')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Couleur associée à ce type d\'outil') }}</p>
                            </div>
                            
                            <!-- Ordre -->
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Ordre d\'affichage') }}</label>
                                <input type="number" name="order" id="order" value="{{ old('order', $toolType->order) }}" min="0"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Ordre d\'affichage (les valeurs plus basses apparaissent en premier)') }}</p>
                            </div>
                        </div>
                        
                        <!-- Actif -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $toolType->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Type d\'outil actif') }}
                            </label>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Traductions -->
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('Traductions') }}</h2>
                            
                            <div x-data="{ activeTab: '{{ $languages->first()->code }}' }">
                                <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                                    <nav class="flex flex-wrap -mb-px" aria-label="Tabs">
                                        @foreach($languages as $language)
                                            <button type="button" 
                                                    class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium leading-5 focus:outline-none transition"
                                                    :class="activeTab === '{{ $language->code }}' ? 'border-purple-500 text-purple-600 dark:text-purple-400 dark:border-purple-400 focus:border-purple-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600 focus:text-gray-700 focus:border-gray-300'"
                                                    @click="activeTab = '{{ $language->code }}'">
                                                @if($language->flag)
                                                    <span class="fi fi-{{ $language->flag }} mr-2"></span>
                                                @endif
                                                {{ $language->name }}
                                                @if($language->is_default)
                                                    <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">({{ __('Par défaut') }})</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </nav>
                                </div>

                                @foreach($languages as $language)
                                    <div x-show="activeTab === '{{ $language->code }}'" class="space-y-4">
                                        <input type="hidden" name="translations[{{ $language->code }}][locale]" value="{{ $language->code }}">
                                        
                                        <!-- Nom -->
                                        <div>
                                            <label for="translations_{{ $language->code }}_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                {{ __('Nom') }} 
                                                @if($language->is_default)<span class="text-red-500">*</span>@endif
                                            </label>
                                            <input type="text" 
                                                   name="translations[{{ $language->code }}][name]" 
                                                   id="translations_{{ $language->code }}_name" 
                                                   value="{{ old("translations.{$language->code}.name", isset($translations[$language->code]) ? $translations[$language->code]['name'] : '') }}" 
                                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                                   @if($language->is_default) required @endif
                                                   placeholder="{{ __('Nom du type d\'outil') }}">
                                            @error("translations.{$language->code}.name")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Description -->
                                        <div>
                                            <label for="translations_{{ $language->code }}_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                {{ __('Description') }}
                                            </label>
                                            <input id="translations_{{ $language->code }}_description_hidden" 
                                                  type="hidden" 
                                                  name="translations[{{ $language->code }}][description]" 
                                                  value="{{ old("translations.{$language->code}.description", isset($translations[$language->code]) ? $translations[$language->code]['description'] : '') }}">
                                            <div class="rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-900 overflow-hidden">
                                                <trix-editor input="translations_{{ $language->code }}_description_hidden" 
                                                            class="trix-editor w-full h-48 text-gray-800 dark:text-gray-200"
                                                            placeholder="{{ __('Description du type d\'outil') }}"></trix-editor>
                                            </div>
                                            @error("translations.{$language->code}.description")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex justify-end space-x-4 mt-6">
                            <a href="{{ route('admin.tool-types.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                                <i class="fas fa-save mr-2"></i>{{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <style>
        .trix-editor {
            min-height: 12rem;
            padding: 0.5rem;
        }
        .trix-button-group {
            border-color: rgba(156, 163, 175, 0.5) !important;
        }
        .trix-button {
            border-bottom: none !important;
        }
        .dark .trix-editor {
            color: white;
            background-color: rgb(17, 24, 39);
        }
        .dark .trix-toolbar {
            background-color: rgb(31, 41, 55);
        }
        .dark .trix-button {
            background-color: rgb(31, 41, 55);
            color: white;
        }
        .dark .trix-button-group {
            border-color: rgba(75, 85, 99, 0.5) !important;
        }
        .dark .trix-button--icon {
            filter: invert(1);
        }
        .dark .trix-button.trix-active {
            background-color: rgb(55, 65, 81);
        }
    </style>
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

        // Formatage automatique du slug
        const slugInput = document.getElementById('slug');
        if (slugInput) {
            slugInput.addEventListener('input', function(e) {
                let value = e.target.value.toLowerCase().replace(/\s+/g, '-');
                value = value.replace(/[^a-z0-9-]/g, '');
                value = value.replace(/-+/g, '-');
                e.target.value = value;
            });
        }

        // Gestion de la couleur
        const colorInput = document.getElementById('color');
        const colorHexInput = document.getElementById('color-hex');
        if (colorInput && colorHexInput) {
            colorInput.addEventListener('input', function() {
                colorHexInput.value = colorInput.value;
            });
        }

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

        // Écouter les changements dans la recherche
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

        // Initialiser la grille d'icônes au chargement
        document.addEventListener('DOMContentLoaded', function() {
            populateIconGrid();
        });
    </script>
</x-admin-layout> 