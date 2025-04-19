<x-admin-layout>
    <div class="p-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ __('Modifier le package') }}</h1>
                <a href="{{ route('admin.packages.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Retour à la liste') }}
                </a>
            </div>

            <!-- Formulaire d'édition -->
            <form method="POST" action="{{ route('admin.packages.update', ['locale' => app()->getLocale(), 'id' => $package->id]) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('Informations générales') }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Slug') }} <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <input type="text" name="slug" id="slug" value="{{ old('slug', $package->slug) }}" required
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
                                    <input type="color" name="color" id="color" value="{{ old('color', $package->color) }}" required
                                        class="h-9 w-14 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <input type="text" id="color-hex" value="{{ old('color', $package->color) }}" 
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
                                        <option value="{{ $value }}" {{ old('cycle_type', $package->cycle_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('cycle_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Nombre de cycles -->
                            <div id="cycle-count-container">
                                <label for="cycle_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Nombre de cycles') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="cycle_count" id="cycle_count" value="{{ old('cycle_count', $package->cycle_count) }}" min="1" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('cycle_count')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Nombre de jours, mois ou années de validité du package') }}</p>
                            </div>
                            
                            <!-- Prix mensuel -->
                            <div>
                                <label for="monthly_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Prix mensuel') }} ({{ \App\Models\Currency::getDefault()->symbol }}) <span class="text-red-500">*</span></label>
                                <input type="number" name="monthly_price" id="monthly_price" value="{{ old('monthly_price', $package->monthly_price) }}" min="0" step="0.01" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('monthly_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Prix pour un abonnement mensuel (0 pour gratuit)') }}</p>
                            </div>
                            
                            <!-- Prix annuel -->
                            <div>
                                <label for="annual_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Prix annuel') }} ({{ \App\Models\Currency::getDefault()->symbol }}) <span class="text-red-500">*</span></label>
                                <input type="number" name="annual_price" id="annual_price" value="{{ old('annual_price', $package->annual_price) }}" min="0" step="0.01" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('annual_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Prix pour un abonnement annuel (0 pour gratuit)') }}</p>
                            </div>
                            
                            <!-- Prix à vie -->
                            <div>
                                <label for="lifetime_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Prix à vie') }} ({{ \App\Models\Currency::getDefault()->symbol }}) <span class="text-red-500">*</span></label>
                                <input type="number" name="lifetime_price" id="lifetime_price" value="{{ old('lifetime_price', $package->lifetime_price) }}" min="0" step="0.01" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('lifetime_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Prix pour un abonnement à vie (0 pour non disponible)') }}</p>
                            </div>
                            
                            <!-- Période d'essai -->
                            <div class="col-span-1 md:col-span-3 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2">
                                <h3 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Période d\'essai') }}</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Activer la période d'essai -->
                                    <div class="flex items-center">
                                        <input type="checkbox" name="has_trial" id="has_trial" value="1" {{ old('has_trial', $package->has_trial) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        <label for="has_trial" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                            {{ __('Activer la période d\'essai') }}
                                        </label>
                                        @error('has_trial')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Durée de la période d'essai -->
                                    <div>
                                        <label for="trial_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Durée (jours)') }}</label>
                                        <input type="number" name="trial_days" id="trial_days" value="{{ old('trial_days', $package->trial_days) }}" min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        @error('trial_days')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Nombre de jours d\'essai gratuit') }}</p>
                                    </div>
                                    
                                    <!-- Restrictions de la période d'essai -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Restrictions') }}</label>
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" name="trial_restrictions[]" id="trial_restriction_downloads" value="downloads" {{ in_array('downloads', old('trial_restrictions', $package->getTrialRestrictions())) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                <label for="trial_restriction_downloads" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ __('Limiter les téléchargements') }}
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="trial_restrictions[]" id="trial_restriction_exports" value="exports" {{ in_array('exports', old('trial_restrictions', $package->getTrialRestrictions())) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                <label for="trial_restriction_exports" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ __('Limiter les exports') }}
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" name="trial_restrictions[]" id="trial_restriction_ai" value="ai" {{ in_array('ai', old('trial_restrictions', $package->getTrialRestrictions())) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                <label for="trial_restriction_ai" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ __('Limiter les outils AI') }}
                                                </label>
                                            </div>
                                        </div>
                                        @error('trial_restrictions')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ordre d'affichage -->
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Ordre d\'affichage') }}</label>
                                <input type="number" name="order" id="order" value="{{ old('order', $package->order) }}" min="0"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Ordre d\'affichage (les valeurs plus basses apparaissent en premier)') }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <!-- Types d'outils et catégories -->
                            <div class="col-span-1 md:col-span-3 border-t border-gray-200 dark:border-gray-700 pt-4 mb-4">
                                <h3 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Catégories et types d\'outils') }}</h3>
                                
                                <!-- Catégories d'outils -->
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">{{ __('Catégories d\'outils disponibles') }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($toolCategories as $category)
                                            <div class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                                <input type="checkbox" name="tool_categories[]" id="category_{{ $category->id }}" value="{{ $category->id }}"
                                                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                                    {{ in_array($category->id, $selectedCategories ?? []) ? 'checked' : '' }}>
                                                <label for="category_{{ $category->id }}" class="flex items-center cursor-pointer">
                                                    @if($category->icon)
                                                        <i class="{{ $category->icon }} mr-2 text-gray-500 dark:text-gray-400"></i>
                                                    @endif
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $category->getName() }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('Sélectionnez les catégories d\'outils que les utilisateurs pourront utiliser avec ce package') }}</p>
                                </div>
                                
                                <!-- Types d'outils avec limites -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">{{ __('Types d\'outils et limitations') }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($toolTypes as $type)
                                            @php
                                                $isSelected = in_array($type->id, $selectedToolTypes ?? []);
                                                $toolsLimit = $toolTypeLimits[$type->id] ?? 0;
                                            @endphp
                                            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750" style="border-left: 4px solid {{ $type->color }}">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center">
                                                        @if($type->icon)
                                                            <i class="{{ $type->icon }} mr-2" style="color: {{ $type->color }}"></i>
                                                        @endif
                                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $type->getName() }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <label class="relative inline-flex items-center cursor-pointer">
                                                            <input type="checkbox" name="tool_types[]" id="type_{{ $type->id }}" value="{{ $type->id }}" class="sr-only peer" {{ $isSelected ? 'checked' : '' }}>
                                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-3">
                                                    <label for="tool_type_{{ $type->id }}_limit" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Limite d\'outils') }}</label>
                                                    <div class="flex items-center">
                                                        <input type="number" name="tool_type_limits[{{ $type->id }}]" id="tool_type_{{ $type->id }}_limit" min="0" value="{{ $toolsLimit }}"
                                                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('0 = illimité') }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('Configurez les types d\'outils disponibles et leurs limitations pour ce package') }}</p>
                                </div>
                            </div>
                            
                            <!-- Afficher les publicités -->
                            <div class="flex items-center">
                                <input type="checkbox" name="show_ads" id="show_ads" value="1" {{ old('show_ads', $package->show_ads) ? 'checked' : '' }}
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
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}
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
                                <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $package->is_default) ? 'checked' : '' }}
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
                                               value="{{ old("translations.{$language->code}.name", isset($translations[$language->code]) ? $translations[$language->code]['name'] : '') }}" 
                                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                               @if($language->is_default) required @endif
                                               placeholder="{{ __('Nom du package') }}">
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
                                                  placeholder="{{ __('Description courte du package') }}">{{ old("translations.{$language->code}.description", isset($translations[$language->code]) ? $translations[$language->code]['description'] : '') }}</textarea>
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
                                                  placeholder="{{ __('Liste des fonctionnalités (une par ligne)') }}">{{ old("translations.{$language->code}.features", isset($translations[$language->code]) ? $translations[$language->code]['features'] : '') }}</textarea>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du slug
            const generateSlugButton = document.getElementById('generate-slug');
            const slugInput = document.getElementById('slug');
            const nameInputs = document.querySelectorAll('input[name^="translations"][name$="[name]"]');

            if (generateSlugButton && nameInputs.length > 0) {
                generateSlugButton.addEventListener('click', function() {
                    const defaultNameInput = nameInputs[0];
                    const name = defaultNameInput.value.trim();
                    
                    if (name) {
                        const slug = name.toLowerCase()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-');
                        
                        slugInput.value = slug;
                    }
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

            // Gestion du type de cycle
            const cycleTypeSelect = document.getElementById('cycle_type');
            const cycleCountContainer = document.getElementById('cycle-count-container');
            
            if (cycleTypeSelect && cycleCountContainer) {
                const toggleCycleCount = function() {
                    if (cycleTypeSelect.value === 'lifetime') {
                        cycleCountContainer.style.display = 'none';
                    } else {
                        cycleCountContainer.style.display = 'block';
                    }
                };
                
                cycleTypeSelect.addEventListener('change', toggleCycleCount);
                toggleCycleCount(); // Vérifier l'état initial
            }
        });
    </script>
</x-admin-layout> 