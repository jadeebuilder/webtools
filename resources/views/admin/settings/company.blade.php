<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Détails de l\'entreprise') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('Configurez les informations relatives à votre entreprise.') }}</p>
    </x-slot>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.settings.company.update', ['locale' => app()->getLocale()]) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations générales -->
                    <div class="col-span-2 mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">{{ __('Informations légales') }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nom de l\'entreprise') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="company_name" id="company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                    value="{{ $settings['company_name'] ?? '' }}" required>
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="company_registration" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Numéro d\'immatriculation') }}</label>
                                <input type="text" name="company_registration" id="company_registration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                    value="{{ $settings['company_registration'] ?? '' }}">
                                <p class="mt-1 text-xs text-gray-500">{{ __('Exemple: RCS Paris B 123 456 789') }}</p>
                                @error('company_registration')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="company_vat" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Numéro de TVA') }}</label>
                                <input type="text" name="company_vat" id="company_vat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                    value="{{ $settings['company_vat'] ?? '' }}">
                                <p class="mt-1 text-xs text-gray-500">{{ __('Exemple: FR12345678901') }}</p>
                                @error('company_vat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Adresse et contact -->
                    <div class="col-span-2 md:col-span-1 mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">{{ __('Adresse et contact') }}</h3>
                        
                        <div class="mb-4">
                            <label for="company_address" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Adresse complète') }} <span class="text-red-500">*</span></label>
                            <textarea name="company_address" id="company_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" required>{{ $settings['company_address'] ?? '' }}</textarea>
                            @error('company_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Téléphone') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="company_phone" id="company_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['company_phone'] ?? '' }}" required>
                            @error('company_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="company_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email de contact') }} <span class="text-red-500">*</span></label>
                            <input type="email" name="company_email" id="company_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50" 
                                value="{{ $settings['company_email'] ?? '' }}" required>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Cet email sera affiché sur le site.') }}</p>
                            @error('company_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="company_opening_hours" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Horaires d\'ouverture') }}</label>
                            <textarea name="company_opening_hours" id="company_opening_hours" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ $settings['company_opening_hours'] ?? '' }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Exemple: Lundi-Vendredi: 9h-18h, Samedi: 10h-16h') }}</p>
                            @error('company_opening_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Réseaux sociaux -->
                    <div class="col-span-2 md:col-span-1 mb-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">{{ __('Réseaux sociaux') }}</h3>
                        
                        <div class="mb-4">
                            <label for="company_social_facebook" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Facebook') }}</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fab fa-facebook"></i>
                                </span>
                                <input type="url" name="company_social_facebook" id="company_social_facebook" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 sm:text-sm" 
                                    value="{{ $settings['company_social_facebook'] ?? '' }}" placeholder="https://facebook.com/votrepage">
                            </div>
                            @error('company_social_facebook')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="company_social_twitter" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Twitter') }}</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fab fa-twitter"></i>
                                </span>
                                <input type="url" name="company_social_twitter" id="company_social_twitter" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 sm:text-sm" 
                                    value="{{ $settings['company_social_twitter'] ?? '' }}" placeholder="https://twitter.com/votrecompte">
                            </div>
                            @error('company_social_twitter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="company_social_instagram" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Instagram') }}</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fab fa-instagram"></i>
                                </span>
                                <input type="url" name="company_social_instagram" id="company_social_instagram" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 sm:text-sm" 
                                    value="{{ $settings['company_social_instagram'] ?? '' }}" placeholder="https://instagram.com/votrecompte">
                            </div>
                            @error('company_social_instagram')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="company_social_linkedin" class="block text-sm font-medium text-gray-700 mb-1">{{ __('LinkedIn') }}</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fab fa-linkedin"></i>
                                </span>
                                <input type="url" name="company_social_linkedin" id="company_social_linkedin" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 sm:text-sm" 
                                    value="{{ $settings['company_social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/votreentreprise">
                            </div>
                            @error('company_social_linkedin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="company_social_youtube" class="block text-sm font-medium text-gray-700 mb-1">{{ __('YouTube') }}</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fab fa-youtube"></i>
                                </span>
                                <input type="url" name="company_social_youtube" id="company_social_youtube" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 sm:text-sm" 
                                    value="{{ $settings['company_social_youtube'] ?? '' }}" placeholder="https://youtube.com/c/votrechaine">
                            </div>
                            @error('company_social_youtube')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                {{ __('Ces informations seront affichées sur les pages légales du site et dans le pied de page. Elles sont importantes pour la conformité légale de votre site.') }}
                            </p>
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
</x-admin-layout> 