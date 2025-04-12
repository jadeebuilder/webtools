<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Paramètres généraux') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('Configurez les paramètres généraux de votre site web.') }}</p>
    </x-slot>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.settings.general.update', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations générales -->
                    <div class="col-span-2 border-b pb-4 mb-4">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Informations du site') }}</h3>
                        
                        <div class="mb-4">
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nom du site') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="site_name" id="site_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['site_name'] ?? config('app.name') }}" required>
                            @error('site_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description du site') }} <span class="text-red-500">*</span></label>
                            <textarea name="site_description" id="site_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" required>{{ $settings['site_description'] ?? '' }}</textarea>
                            @error('site_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">{{ __('Cette description sera utilisée dans les balises méta et pour le référencement.') }}</p>
                        </div>

                        <div class="mb-4">
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email de contact') }} <span class="text-red-500">*</span></label>
                            <input type="email" name="contact_email" id="contact_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['contact_email'] ?? '' }}" required>
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="default_timezone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Fuseau horaire par défaut') }} <span class="text-red-500">*</span></label>
                                <select name="default_timezone" id="default_timezone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone }}" {{ (isset($settings['default_timezone']) && $settings['default_timezone'] == $timezone) ? 'selected' : ($timezone == 'Europe/Paris' ? 'selected' : '') }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('default_timezone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="default_locale" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Langue par défaut') }} <span class="text-red-500">*</span></label>
                                <select name="default_locale" id="default_locale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                    @foreach($available_locales as $locale => $name)
                                        <option value="{{ $locale }}" {{ (isset($settings['default_locale']) && $settings['default_locale'] == $locale) ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('default_locale')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tools_per_page" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Outils par page') }} <span class="text-red-500">*</span></label>
                                <input type="number" name="tools_per_page" id="tools_per_page" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                    value="{{ $settings['tools_per_page'] ?? 12 }}" min="6" max="100" required>
                                @error('tools_per_page')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="tools_order" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ordre d\'affichage des outils') }} <span class="text-red-500">*</span></label>
                                <select name="tools_order" id="tools_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                    <option value="desc" {{ (isset($settings['tools_order']) && $settings['tools_order'] == 'desc') ? 'selected' : '' }}>
                                        {{ __('Plus récents d\'abord (DESC)') }}
                                    </option>
                                    <option value="asc" {{ (isset($settings['tools_order']) && $settings['tools_order'] == 'asc') ? 'selected' : '' }}>
                                        {{ __('Plus anciens d\'abord (ASC)') }}
                                    </option>
                                </select>
                                @error('tools_order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO & Métadonnées -->
                    <div class="col-span-2 border-b pb-4 mb-4">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('SEO & Métadonnées') }}</h3>
                        
                        <div class="mb-4">
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Titre de la page d\'accueil (meta title)') }}</label>
                            <input type="text" name="meta_title" id="meta_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['meta_title'] ?? '' }}" maxlength="70">
                            <div class="flex justify-between mt-1">
                                <p class="text-xs text-gray-500">{{ __('Titre principal utilisé dans les moteurs de recherche.') }}</p>
                                <p class="text-xs text-gray-500"><span id="meta_title_count">0</span>/70</p>
                            </div>
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description pour les moteurs de recherche') }}</label>
                            <textarea name="meta_description" id="meta_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" maxlength="160">{{ $settings['meta_description'] ?? '' }}</textarea>
                            <div class="flex justify-between mt-1">
                                <p class="text-xs text-gray-500">{{ __('Description affichée dans les résultats de recherche.') }}</p>
                                <p class="text-xs text-gray-500"><span id="meta_description_count">0</span>/160</p>
                            </div>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Mots-clés (keywords)') }}</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['meta_keywords'] ?? '' }}">
                            <p class="mt-1 text-xs text-gray-500">{{ __('Mots-clés séparés par des virgules. Exemple: outils web, conversion, générateur') }}</p>
                            @error('meta_keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="meta_author" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Auteur (meta author)') }}</label>
                            <input type="text" name="meta_author" id="meta_author" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['meta_author'] ?? '' }}">
                            @error('meta_author')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="site_opengraph_image" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Image OpenGraph (pour les réseaux sociaux)') }}</label>
                            <div class="flex items-center space-x-4">
                                @if(!empty($settings['site_opengraph_image']))
                                    <div class="w-32 h-32 overflow-hidden rounded-md border border-gray-200">
                                        <img src="{{ $settings['site_opengraph_image'] }}" alt="OpenGraph" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="site_opengraph_image" id="site_opengraph_image" class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-purple-50 file:text-purple-700
                                        hover:file:bg-purple-100">
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Format 1200x630px recommandé. Cette image apparaîtra lors du partage sur les réseaux sociaux.') }}</p>
                                </div>
                            </div>
                            @error('site_opengraph_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Logo et Favicon -->
                    <div class="col-span-2 md:col-span-1 border-b pb-4 mb-4 md:mb-0 md:border-r md:pr-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Logos et Images') }}</h3>
                        
                        <div class="mb-4">
                            <label for="site_logo_light" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Logo pour fond clair (300x60px)') }}</label>
                            <div class="flex items-center space-x-4">
                                @if(!empty($settings['site_logo_light']))
                                    <div class="w-32 h-10 overflow-hidden rounded-md border border-gray-200 bg-gray-100">
                                        <img src="{{ $settings['site_logo_light'] }}" alt="Logo fond clair" class="w-full h-full object-contain">
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="site_logo_light" id="site_logo_light" class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-purple-50 file:text-purple-700
                                        hover:file:bg-purple-100">
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Logo utilisé sur les fonds clairs. Taille recommandée: 300x60px.') }}</p>
                                </div>
                            </div>
                            @error('site_logo_light')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="site_logo_dark" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Logo pour fond sombre (300x60px)') }}</label>
                            <div class="flex items-center space-x-4">
                                @if(!empty($settings['site_logo_dark']))
                                    <div class="w-32 h-10 overflow-hidden rounded-md border border-gray-200 bg-gray-800">
                                        <img src="{{ $settings['site_logo_dark'] }}" alt="Logo fond sombre" class="w-full h-full object-contain">
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="site_logo_dark" id="site_logo_dark" class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-purple-50 file:text-purple-700
                                        hover:file:bg-purple-100">
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Logo utilisé sur les fonds sombres. Taille recommandée: 300x60px.') }}</p>
                                </div>
                            </div>
                            @error('site_logo_dark')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="site_logo_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Logo pour emails (300x60px)') }}</label>
                            <div class="flex items-center space-x-4">
                                @if(!empty($settings['site_logo_email']))
                                    <div class="w-32 h-10 overflow-hidden rounded-md border border-gray-200 bg-white">
                                        <img src="{{ $settings['site_logo_email'] }}" alt="Logo email" class="w-full h-full object-contain">
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="site_logo_email" id="site_logo_email" class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-purple-50 file:text-purple-700
                                        hover:file:bg-purple-100">
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Logo utilisé dans les emails. Taille recommandée: 300x60px.') }}</p>
                                </div>
                            </div>
                            @error('site_logo_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="site_favicon" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Favicon') }}</label>
                            <div class="flex items-center space-x-4">
                                @if(!empty($settings['site_favicon']))
                                    <div class="w-12 h-12 overflow-hidden rounded-md border border-gray-200">
                                        <img src="{{ $settings['site_favicon'] }}" alt="Favicon actuel" class="w-full h-full object-contain">
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="site_favicon" id="site_favicon" class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-purple-50 file:text-purple-700
                                        hover:file:bg-purple-100">
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Formats acceptés : ICO, PNG. Taille recommandée: 32x32px.') }}</p>
                                </div>
                            </div>
                            @error('site_favicon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="site_home_cover" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Image de couverture (page d\'accueil)') }}</label>
                            <div class="flex items-center space-x-4">
                                @if(!empty($settings['site_home_cover']))
                                    <div class="w-32 h-20 overflow-hidden rounded-md border border-gray-200">
                                        <img src="{{ $settings['site_home_cover'] }}" alt="Image de couverture" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="site_home_cover" id="site_home_cover" class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-purple-50 file:text-purple-700
                                        hover:file:bg-purple-100">
                                    <p class="mt-1 text-xs text-gray-500">{{ __('Image de fond pour la page d\'accueil. Format 16:9 recommandé.') }}</p>
                                </div>
                            </div>
                            @error('site_home_cover')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tracking et paramètres additionnels -->
                    <div class="col-span-2 md:col-span-1 pb-4 md:pl-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700">{{ __('Tracking et fonctionnalités') }}</h3>
                        
                        <div class="mb-4">
                            <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('ID Google Analytics') }}</label>
                            <input type="text" name="google_analytics_id" id="google_analytics_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['google_analytics_id'] ?? '' }}" placeholder="G-XXXXXXXXXX">
                            <p class="mt-1 text-xs text-gray-500">{{ __('Format : G-XXXXXXXXXX') }}</p>
                            @error('google_analytics_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="facebook_pixel_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('ID Facebook Pixel') }}</label>
                            <input type="text" name="facebook_pixel_id" id="facebook_pixel_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['facebook_pixel_id'] ?? '' }}" placeholder="XXXXXXXXXXXXXXXX">
                            @error('facebook_pixel_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="enable_cookie_banner" id="enable_cookie_banner" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" 
                                    {{ !empty($settings['enable_cookie_banner']) && $settings['enable_cookie_banner'] == 1 ? 'checked' : '' }}>
                                <label for="enable_cookie_banner" class="ml-2 block text-sm text-gray-700">{{ __('Activer la bannière de cookies') }}</label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 ml-6">{{ __('Affiche une bannière pour informer les utilisateurs de l\'utilisation des cookies.') }}</p>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="enable_dark_mode" id="enable_dark_mode" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" 
                                    {{ !empty($settings['enable_dark_mode']) && $settings['enable_dark_mode'] == 1 ? 'checked' : '' }}>
                                <label for="enable_dark_mode" class="ml-2 block text-sm text-gray-700">{{ __('Activer le mode sombre') }}</label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 ml-6">{{ __('Permet aux utilisateurs de basculer entre le mode clair et sombre.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:border-purple-800 focus:ring focus:ring-purple-200 disabled:opacity-25 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('Enregistrer les modifications') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // {{ __('Compteur de caractères pour les champs méta') }}
        document.addEventListener('DOMContentLoaded', function() {
            const metaTitle = document.getElementById('meta_title');
            const metaTitleCount = document.getElementById('meta_title_count');
            const metaDescription = document.getElementById('meta_description');
            const metaDescriptionCount = document.getElementById('meta_description_count');
            
            if (metaTitle && metaTitleCount) {
                metaTitleCount.textContent = metaTitle.value.length;
                metaTitle.addEventListener('input', function() {
                    metaTitleCount.textContent = this.value.length;
                });
            }
            
            if (metaDescription && metaDescriptionCount) {
                metaDescriptionCount.textContent = metaDescription.value.length;
                metaDescription.addEventListener('input', function() {
                    metaDescriptionCount.textContent = this.value.length;
                });
            }
        });
    </script>
</x-admin-layout> 