<x-admin-layout>
    <div class="p-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ __('Créer un nouveau package') }}</h1>
                <a href="{{ route('admin.packages.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Retour à la liste') }}
                </a>
            </div>

            <!-- Formulaire de création -->
            <form method="POST" action="{{ route('admin.packages.store', ['locale' => app()->getLocale()]) }}" class="space-y-6">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('Informations générales') }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Slug') }} <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                        class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                        placeholder="monpackage">
                                    <button type="button" id="generate-slug" class="ml-2 px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Identifiant unique utilisé dans les URL (minuscules, chiffres et tirets uniquement)') }}</p>
                            </div>
                            
                            <!-- Couleur -->
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Couleur') }} <span class="text-red-500">*</span></label>
                                <div class="flex items-center">
                                    <input type="color" name="color" id="color" value="{{ old('color', '#660bab') }}" required
                                        class="h-9 w-14 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <input type="text" id="color-hex" value="{{ old('color', '#660bab') }}" 
                                        class="ml-2 flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                        readonly>
                                </div>
                                @error('color')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Couleur principale du package (utilisée pour les visuels)') }}</p>
                            </div>
                            
                            <!-- Type de cycle -->
                            <div>
                                <label for="cycle_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Type de cycle') }} <span class="text-red-500">*</span></label>
                                <select name="cycle_type" id="cycle_type" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    @foreach($cycleTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('cycle_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('cycle_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Nombre de cycles -->
                            <div id="cycle-count-container">
                                <label for="cycle_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nombre de cycles') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="cycle_count" id="cycle_count" value="{{ old('cycle_count', 1) }}" min="1" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('cycle_count')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Nombre de jours, mois ou années de validité du package') }}</p>
                            </div>
                            
                            <!-- Prix mensuel -->
                            <div>
                                <label for="monthly_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Prix mensuel (€)') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="monthly_price" id="monthly_price" value="{{ old('monthly_price', 0) }}" min="0" step="0.01" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('monthly_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Prix pour un abonnement mensuel (0 pour gratuit)') }}</p>
                            </div>
                            
                            <!-- Prix annuel -->
                            <div>
                                <label for="annual_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Prix annuel (€)') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="annual_price" id="annual_price" value="{{ old('annual_price', 0) }}" min="0" step="0.01" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('annual_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Prix pour un abonnement annuel (0 pour gratuit)') }}</p>
                            </div>
                            
                            <!-- Prix à vie -->
                            <div>
                                <label for="lifetime_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Prix à vie (€)') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="lifetime_price" id="lifetime_price" value="{{ old('lifetime_price', 0) }}" min="0" step="0.01" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('lifetime_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Prix pour un abonnement à vie (0 pour non disponible)') }}</p>
                            </div>
                            
                            <!-- Ordre d'affichage -->
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
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <!-- Afficher les publicités -->
                            <div class="flex items-center">
                                <input type="checkbox" name="show_ads" id="show_ads" value="1" {{ old('show_ads', true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <label for="show_ads" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Afficher les publicités') }}
                                </label>
                                @error('show_ads')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Package actif -->
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Package actif') }}
                                </label>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Package par défaut -->
                            <div class="flex items-center">
                                <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <label for="is_default" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('Package par défaut') }}
                                </label>
                                @error('is_default')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Traductions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
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
                                               placeholder="{{ __('Nom du package') }}"
                                               @if($language->code === $languages->first()->code) x-on:input="generateSlugFromName($event.target.value)" @endif>
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
                                                  placeholder="{{ __('Description courte du package') }}">{{ old("translations.{$language->code}.description") }}</textarea>
                                        @error("translations.{$language->code}.description")
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Fonctionnalités -->
                                    <div>
                                        <label for="translations_{{ $language->code }}_features" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            {{ __('Fonctionnalités') }}
                                        </label>
                                        <textarea name="translations[{{ $language->code }}][features]" 
                                                  id="translations_{{ $language->code }}_features" 
                                                  rows="5"
                                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                                  placeholder="{{ __('Liste des fonctionnalités (une par ligne)') }}">{{ old("translations.{$language->code}.features") }}</textarea>
                                        @error("translations.{$language->code}.features")
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Saisissez une fonctionnalité par ligne pour l\'affichage en liste') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Outils -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('Outils') }}</h2>
                        
                        <!-- Sélection des outils par catégorie -->
                        <div x-data="{ activeCategory: 'all' }">
                            <!-- Onglets des catégories -->
                            <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
                                <nav class="flex flex-wrap -mb-px overflow-x-auto" aria-label="Catégories">
                                    <button type="button" 
                                            class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium leading-5 focus:outline-none transition"
                                            :class="activeCategory === 'all' ? 'border-purple-500 text-purple-600 dark:text-purple-400 dark:border-purple-400 focus:border-purple-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600 focus:text-gray-700 focus:border-gray-300'"
                                            @click="activeCategory = 'all'">
                                        <i class="fas fa-layer-group mr-2"></i>
                                        {{ __('Toutes les catégories') }}
                                    </button>
                                    
                                    @foreach($toolCategories as $category)
                                        <button type="button" 
                                                class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium leading-5 focus:outline-none transition"
                                                :class="activeCategory === '{{ $category->id }}' ? 'border-purple-500 text-purple-600 dark:text-purple-400 dark:border-purple-400 focus:border-purple-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600 focus:text-gray-700 focus:border-gray-300'"
                                                @click="activeCategory = '{{ $category->id }}'">
                                            @if($category->icon)
                                                <i class="{{ $category->icon }} mr-2"></i>
                                            @endif
                                            {{ $category->getName() }}
                                            <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">({{ $category->tools->count() }})</span>
                                        </button>
                                    @endforeach
                                </nav>
                            </div>
                            
                            <!-- Liste des outils -->
                            <div class="space-y-4">
                                @foreach($toolCategories as $category)
                                    <div x-show="activeCategory === 'all' || activeCategory === '{{ $category->id }}'">
                                        <h3 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $category->getName() }}</h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($category->tools as $tool)
                                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <i class="{{ $tool->icon ?? 'fas fa-tools' }} text-primary mr-2"></i>
                                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $tool->getName() }}</span>
                                                            
                                                            @if($tool->is_premium)
                                                                <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                                    {{ __('Premium') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="flex items-center space-x-2">
                                                            <div class="flex items-center">
                                                                <input type="checkbox" name="tools[]" id="tool_{{ $tool->id }}" value="{{ $tool->id }}" {{ in_array($tool->id, old('tools', [])) ? 'checked' : '' }}
                                                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                                <label for="tool_{{ $tool->id }}" class="ml-1 text-xs text-gray-500">
                                                                    {{ __('Inclus') }}
                                                                </label>
                                                            </div>
                                                            
                                                            <div class="flex items-center">
                                                                <input type="checkbox" name="vip_tools[]" id="vip_tool_{{ $tool->id }}" value="{{ $tool->id }}" {{ in_array($tool->id, old('vip_tools', [])) ? 'checked' : '' }}
                                                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                                <label for="vip_tool_{{ $tool->id }}" class="ml-1 text-xs text-gray-500">
                                                                    {{ __('VIP') }}
                                                                </label>
                                                            </div>
                                                            
                                                            <div class="flex items-center">
                                                                <input type="checkbox" name="ai_tools[]" id="ai_tool_{{ $tool->id }}" value="{{ $tool->id }}" {{ in_array($tool->id, old('ai_tools', [])) ? 'checked' : '' }}
                                                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                                <label for="ai_tool_{{ $tool->id }}" class="ml-1 text-xs text-gray-500">
                                                                    {{ __('AI') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.packages.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                        {{ __('Annuler') }}
                    </a>
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i>{{ __('Enregistrer') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mise à jour du champ "color-hex" lors du changement de couleur
            const colorInput = document.getElementById('color');
            const colorHexInput = document.getElementById('color-hex');
            
            colorInput.addEventListener('input', function() {
                colorHexInput.value = this.value;
            });
            
            // Gestion du champ cycle_count en fonction du type de cycle
            const cycleTypeSelect = document.getElementById('cycle_type');
            const cycleCountContainer = document.getElementById('cycle-count-container');
            
            function updateCycleCountVisibility() {
                if (cycleTypeSelect.value === 'lifetime') {
                    cycleCountContainer.style.display = 'none';
                } else {
                    cycleCountContainer.style.display = 'block';
                }
            }
            
            cycleTypeSelect.addEventListener('change', updateCycleCountVisibility);
            updateCycleCountVisibility();
            
            // Génération du slug à partir du nom
            const generateSlugButton = document.getElementById('generate-slug');
            const slugInput = document.getElementById('slug');
            const defaultLangNameInput = document.getElementById('translations_{{ $languages->first()->code }}_name');
            
            generateSlugButton.addEventListener('click', function() {
                if (defaultLangNameInput.value) {
                    generateSlug(defaultLangNameInput.value);
                }
            });
            
            window.generateSlugFromName = function(name) {
                // Auto-generate slug when typing in the name field
                if (!slugInput.value) {
                    generateSlug(name);
                }
            };
            
            function generateSlug(name) {
                fetch('{{ route('admin.packages.generate-slug', ['locale' => app()->getLocale()]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: name })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.slug) {
                        slugInput.value = data.slug;
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
    @endpush
</x-admin-layout> 