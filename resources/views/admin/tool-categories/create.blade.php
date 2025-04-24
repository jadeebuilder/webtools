<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nouvelle catégorie d\'outils') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.tool-categories.store', ['locale' => app()->getLocale()]) }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Slug') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    required>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Icon -->
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Icône') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative">
                                    <div class="flex items-center">
                                        <div class="relative flex-grow">
                                            <input type="text" name="icon" id="icon" value="{{ old('icon') }}"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                                required>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i id="icon-preview" class="fas {{ old('icon', 'fa-cube') }} text-gray-400"></i>
                                            </div>
                                        </div>
                                        <button type="button" onclick="openIconModal()"
                                            class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                            {{ __('Choisir une icône') }}
                                        </button>
                                    </div>
                                </div>
                                @error('icon')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Order -->
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Ordre') }}
                                </label>
                                <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Statut') }}
                                </label>
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Actif') }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Translations -->
                            @foreach($languages as $language)
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                        {{ __('Traduction') }} ({{ $language->name }})
                                    </h3>

                                    <input type="hidden" name="translations[{{ $language->code }}][locale]" value="{{ $language->code }}">

                                    <div class="mb-4">
                                        <label for="translations[{{ $language->code }}][name]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('Nom') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="translations[{{ $language->code }}][name]" 
                                            id="translations[{{ $language->code }}][name]" 
                                            value="{{ old("translations.{$language->code}.name") }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                            required>
                                        @error("translations.{$language->code}.name")
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="translations[{{ $language->code }}][description]" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('Description') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input id="translations_{{ $language->code }}_description_hidden" 
                                              type="hidden" 
                                              name="translations[{{ $language->code }}][description]" 
                                              value="{{ old("translations.{$language->code}.description") }}" 
                                              required>
                                        <div class="rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-900 overflow-hidden">
                                            <trix-editor input="translations_{{ $language->code }}_description_hidden" 
                                                        class="trix-editor w-full h-48 text-gray-800 dark:text-gray-200"
                                                        placeholder="{{ __('Description de la catégorie d\'outil') }}"></trix-editor>
                                        </div>
                                        @error("translations.{$language->code}.description")
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.tool-categories.index', ['locale' => app()->getLocale()]) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit"
                                class="ml-3 inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Créer') }}
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
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

        // Formatage automatique du slug en minuscules et remplacement des espaces par des tirets
        document.getElementById('slug').addEventListener('input', function(e) {
            // Convertir en minuscules et remplacer les espaces par des tirets
            let value = e.target.value.toLowerCase().replace(/\s+/g, '-');
            
            // Supprimer les caractères spéciaux indésirables
            value = value.replace(/[^a-z0-9-]/g, '');
            
            // Éviter les tirets multiples consécutifs
            value = value.replace(/-+/g, '-');
            
            // Mettre à jour la valeur du champ
            e.target.value = value;
        });

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
            const searchTerm = document.getElementById('iconSearch').value.toLowerCase();
            
            // Vider la grille
            iconGrid.innerHTML = '';
            
            // Liste des icônes couramment utilisées pour les outils
            const commonIcons = [
                'fa-solid fa-tools',
                'fa-solid fa-calculator',
                'fa-solid fa-chart-bar',
                'fa-solid fa-code',
                'fa-solid fa-image',
                'fa-solid fa-file-alt',
                'fa-solid fa-link',
                'fa-solid fa-search',
                'fa-solid fa-crop',
                'fa-solid fa-paint-brush',
                'fa-solid fa-font',
                'fa-solid fa-palette',
                'fa-solid fa-camera',
                'fa-solid fa-video',
                'fa-solid fa-music',
                'fa-solid fa-file-audio',
                'fa-solid fa-file-video',
                'fa-solid fa-file-image',
                'fa-solid fa-file-pdf',
                'fa-solid fa-file-word'
            ];
            
            // Filtrer les icônes en fonction de la recherche
            const filteredIcons = commonIcons.filter(icon => 
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
            document.getElementById('icon').value = icon;
            document.getElementById('icon-preview').className = `${icon} text-gray-400`;
            closeIconModal();
        }

        // Écouter les changements dans la recherche
        document.getElementById('iconSearch').addEventListener('input', populateIconGrid);

        // Fermer la modal en cliquant en dehors
        document.getElementById('iconModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('iconModal')) {
                closeIconModal();
            }
        });
    </script>
</x-admin-layout> 
</x-admin-layout> 
</x-admin-layout> 
</x-admin-layout> 
</x-admin-layout> 