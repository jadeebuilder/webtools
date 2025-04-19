<x-admin-layout>
    <div class="p-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ __('Ajouter un type d\'outil') }}</h1>
                <a href="{{ route('admin.tool-types.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Retour à la liste') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.tool-types.store', ['locale' => app()->getLocale()]) }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Slug') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
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
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                        <i class="fas fa-icons"></i>
                                    </span>
                                    <input type="text" name="icon" id="icon" value="{{ old('icon') }}"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                        placeholder="fas fa-star">
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
                                    <input type="color" name="color" id="color" value="{{ old('color', '#660bab') }}" required
                                        class="h-10 w-16 rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <input type="text" id="color-hex" value="{{ old('color', '#660bab') }}" 
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
                                <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Ordre d\'affichage (les valeurs plus basses apparaissent en premier)') }}</p>
                            </div>
                        </div>
                        
                        <!-- Actif -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
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
                                                   value="{{ old("translations.{$language->code}.name") }}" 
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
                                            <textarea name="translations[{{ $language->code }}][description]" 
                                                      id="translations_{{ $language->code }}_description" 
                                                      rows="3"
                                                      class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                                      placeholder="{{ __('Description du type d\'outil') }}">{{ old("translations.{$language->code}.description") }}</textarea>
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de la couleur
            const colorInput = document.getElementById('color');
            const colorHexInput = document.getElementById('color-hex');

            if (colorInput && colorHexInput) {
                colorInput.addEventListener('input', function() {
                    colorHexInput.value = colorInput.value;
                });
            }
        });
    </script>
    @endpush
</x-admin-layout> 