<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold text-purple-700 mb-6">{{ __('Account Settings') }}</h1>
                    
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 mb-6">
                        <div class="flex items-center">
                            <span class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-cog"></i>
                            </span>
                            <p class="text-gray-700">{{ __('Manage your account settings, preferences, notifications and security options.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Menu de navigation latéral -->
                        <div class="col-span-1">
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <h3 class="text-md font-semibold text-gray-800 mb-3 pb-2 border-b">{{ __('Settings Menu') }}</h3>
                                <nav class="space-y-1">
                                    <a href="#profile" class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-purple-50 text-purple-700">
                                        <i class="fas fa-user-circle mr-3 text-purple-500"></i>
                                        {{ __('Profile Settings') }}
                                    </a>
                                    <a href="#notification" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                        <i class="fas fa-bell mr-3 text-gray-400"></i>
                                        {{ __('Notification Settings') }}
                                    </a>
                                    <a href="#security" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                        <i class="fas fa-shield-alt mr-3 text-gray-400"></i>
                                        {{ __('Security Settings') }}
                                    </a>
                                    <a href="#appearance" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                                        <i class="fas fa-paint-brush mr-3 text-gray-400"></i>
                                        {{ __('Appearance') }}
                                    </a>
                                </nav>
                            </div>
                        </div>

                        <!-- Contenu principal des paramètres -->
                        <div class="col-span-1 md:col-span-2">
                            <!-- Paramètres de profil -->
                            <div id="profile" class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Profile Settings') }}</h3>
                                
                                <form action="#" method="POST" class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                        <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    </div>
                                    <div>
                                        <label for="language" class="block text-sm font-medium text-gray-700">{{ __('Preferred Language') }}</label>
                                        <select id="language" name="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                            <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>{{ __('French') }}</option>
                                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                                            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>{{ __('Spanish') }}</option>
                                        </select>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                            <i class="fas fa-save mr-2"></i>
                                            {{ __('Save Changes') }}
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Paramètres des notifications -->
                            <div id="notification" class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Notification Settings') }}</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ __('Email Notifications') }}</h4>
                                            <p class="text-sm text-gray-500">{{ __('Receive email notifications about account activity') }}</p>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <button type="button" role="switch" aria-checked="true" class="relative inline-flex h-6 w-11 items-center rounded-full bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                                <span class="sr-only">{{ __('Enable email notifications') }}</span>
                                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition translate-x-6"></span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ __('Security Alerts') }}</h4>
                                            <p class="text-sm text-gray-500">{{ __('Get notified about suspicious activity on your account') }}</p>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <button type="button" role="switch" aria-checked="true" class="relative inline-flex h-6 w-11 items-center rounded-full bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                                <span class="sr-only">{{ __('Enable security alerts') }}</span>
                                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition translate-x-6"></span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ __('Marketing Emails') }}</h4>
                                            <p class="text-sm text-gray-500">{{ __('Receive promotional offers and updates about our services') }}</p>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <button type="button" role="switch" aria-checked="false" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                                <span class="sr-only">{{ __('Enable marketing emails') }}</span>
                                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition translate-x-1"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 