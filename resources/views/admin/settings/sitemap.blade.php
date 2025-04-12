<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Gestion du Sitemap') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('Configurez et générez le sitemap XML de votre site.') }}</p>
    </x-slot>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.settings.sitemap.update', ['locale' => app()->getLocale()]) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Paramètres du Sitemap') }}</h3>
                    
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="sitemap_auto_generation" id="sitemap_auto_generation" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" 
                               {{ !empty($settings['sitemap_auto_generation']) && $settings['sitemap_auto_generation'] == 1 ? 'checked' : '' }}>
                        <label for="sitemap_auto_generation" class="ml-2 block text-sm text-gray-700">{{ __('Génération automatique du sitemap') }}</label>
                    </div>
                    <p class="ml-6 text-xs text-gray-500 mb-4">{{ __('Lorsque cette option est activée, le sitemap sera automatiquement régénéré lors des modifications importantes du site.') }}</p>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="sitemap_include_tools" id="sitemap_include_tools" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" 
                                   {{ !empty($settings['sitemap_include_tools']) && $settings['sitemap_include_tools'] == 1 ? 'checked' : '' }}>
                            <label for="sitemap_include_tools" class="ml-2 block text-sm text-gray-700">{{ __('Inclure les pages d\'outils') }}</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="sitemap_include_blog" id="sitemap_include_blog" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" 
                                   {{ !empty($settings['sitemap_include_blog']) && $settings['sitemap_include_blog'] == 1 ? 'checked' : '' }}>
                            <label for="sitemap_include_blog" class="ml-2 block text-sm text-gray-700">{{ __('Inclure les articles de blog') }}</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="sitemap_frequency" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Fréquence de mise à jour') }} <span class="text-red-500">*</span></label>
                        <select name="sitemap_frequency" id="sitemap_frequency" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                            @foreach(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'] as $frequency)
                                <option value="{{ $frequency }}" {{ (!empty($settings['sitemap_frequency']) && $settings['sitemap_frequency'] == $frequency) ? 'selected' : '' }}>
                                    {{ __(ucfirst($frequency)) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">{{ __('Indication pour les moteurs de recherche sur la fréquence de changement des pages.') }}</p>
                    </div>
                    
                    <div>
                        <label for="sitemap_priority" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Priorité par défaut') }} <span class="text-red-500">*</span></label>
                        <select name="sitemap_priority" id="sitemap_priority" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md">
                            @foreach([0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0] as $priority)
                                <option value="{{ $priority }}" {{ (!empty($settings['sitemap_priority']) && $settings['sitemap_priority'] == $priority) ? 'selected' : '' }}>
                                    {{ $priority }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">{{ __('Priorité par défaut des pages pour les moteurs de recherche.') }}</p>
                    </div>
                </div>
                
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('État du sitemap') }}</h3>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-700">{{ __('Dernière génération:') }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    @if(!empty($settings['sitemap_last_generated']))
                                        {{ \Carbon\Carbon::parse($settings['sitemap_last_generated'])->diffForHumans() }}
                                        ({{ \Carbon\Carbon::parse($settings['sitemap_last_generated'])->format('d/m/Y H:i') }})
                                    @else
                                        {{ __('Jamais généré') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                @if(file_exists(public_path('sitemap.xml')))
                                    <a href="{{ url('sitemap.xml') }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        {{ __('Voir l\'index des sitemaps') }}
                                    </a>
                                    
                                    @foreach(array_keys(config('app.available_locales', ['fr' => 'Français'])) as $locale)
                                        @if(file_exists(public_path("sitemap-{$locale}.xml")))
                                            <a href="{{ url("sitemap-{$locale}.xml") }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                {{ strtoupper($locale) }}
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="inline-flex items-center px-3 py-2 border border-red-300 text-sm leading-4 font-medium rounded-md text-red-700 bg-red-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        {{ __('Sitemap non généré') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 border-l-4 border-purple-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-purple-700">
                                    {{ __('Un sitemap séparé sera généré pour chaque langue du site (') }}
                                    @foreach(array_keys(config('app.available_locales', ['fr' => 'Français'])) as $key => $locale)
                                        {{ strtoupper($locale) }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    {{ __(') et un fichier d\'index principal sera créé à l\'emplacement') }} <code class="px-1 py-0.5 bg-purple-100 rounded text-xs font-mono">public/sitemap.xml</code>.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    {{ __('N\'oubliez pas de soumettre uniquement le fichier d\'index de sitemap') }} <code class="px-1 py-0.5 bg-blue-100 rounded text-xs font-mono">sitemap.xml</code> {{ __('aux moteurs de recherche comme Google Search Console.') }}
                                    {{ __('Ce fichier indexera automatiquement les sitemaps de chaque langue.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:border-purple-800 focus:ring focus:ring-purple-200 disabled:opacity-25 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('Générer le sitemap et enregistrer') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout> 