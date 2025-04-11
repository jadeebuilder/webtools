@php
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WebTools') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col bg-gray-50">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Content -->
        <div class="py-12 flex-grow flex items-center justify-center bg-gradient-to-br from-purple-50 to-indigo-100">
            <div class="max-w-xl w-full mx-auto px-6 lg:px-8">
                <div class="mb-8 text-center">
                    <a href="{{ URL::localizedRoute('home') }}">
                        <img src="{{ Vite::asset('resources/images/webtools_logo.png') }}" alt="WebTools" class="h-12 mx-auto mb-4">
                    </a>
                </div>
                
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="bg-white shadow-xl rounded-lg overflow-hidden p-8 border border-gray-200">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Logo et Description -->
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <img src="{{ Vite::asset('resources/images/webtools_logo_gris.png') }}" alt="WebTools Logo" class="h-8 w-auto">
                            <span class="ml-3 text-xl font-bold">WebTools</span>
                        </div>
                        <p class="text-gray-400 text-sm">
                            {{ __('Your one-stop platform for all web development tools. Simple, fast, and reliable solutions for developers worldwide.') }}
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Menu Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">{{ __('Quick Links') }}</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ URL::localizedRoute('about') }}" class="text-gray-400 hover:text-white transition-colors duration-300">
                                    {{ __('About Us') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::localizedRoute('terms') }}" class="text-gray-400 hover:text-white transition-colors duration-300">
                                    {{ __('Terms of Service') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::localizedRoute('privacy') }}" class="text-gray-400 hover:text-white transition-colors duration-300">
                                    {{ __('Privacy Policy') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::localizedRoute('cookies') }}" class="text-gray-400 hover:text-white transition-colors duration-300">
                                    {{ __('Cookie Policy') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">{{ __('Contact Us') }}</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-map-marker-alt mt-1.5 mr-3 text-gray-400"></i>
                                <span class="text-gray-400">
                                    {{ __('123 Developer Street') }}<br>
                                    {{ __('Tech City, TC 12345') }}
                                </span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone mr-3 text-gray-400"></i>
                                <a href="tel:+1234567890" class="text-gray-400 hover:text-white transition-colors duration-300">
                                    +1 (234) 567-890
                                </a>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-envelope mr-3 text-gray-400"></i>
                                <a href="mailto:contact@webtools.com" class="text-gray-400 hover:text-white transition-colors duration-300">
                                    contact@webtools.com
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">{{ __('Stay Updated') }}</h3>
                        <p class="text-gray-400 text-sm mb-4">
                            {{ __('Subscribe to our newsletter for the latest updates and tools.') }}
                        </p>
                        <form class="space-y-3" action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="flex">
                                <input type="email"
                                       name="email"
                                       placeholder="{{ __('Enter your email') }}"
                                       class="flex-grow px-4 py-2 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-800 text-white">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 transition-colors duration-300">
                                    {{ __('Subscribe') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © {{ date('Y') }} WebTools. {{ __('All rights reserved.') }}
                    </p>
                    <div class="mt-4 md:mt-0">
                        <select onchange="window.location.href=this.value" class="bg-gray-800 text-gray-400 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(config('app.available_locales') as $langKey => $langName)
                                @php
                                    // Récupérer le chemin actuel
                                    $currentPath = request()->path();
                                    
                                    // Déterminer si nous sommes sur une route d'authentification
                                    $isAuthRoute = in_array($currentPath, ['login', 'register', 'password/reset', 'password/confirm']) 
                                        || Str::startsWith($currentPath, 'password/reset/');
                                    
                                    if ($isAuthRoute) {
                                        // Pour les routes d'authentification, conserver le même chemin
                                        $newUrl = url('/' . $currentPath);
                                    } else {
                                        // Pour les autres routes, extraire et manipuler le préfixe de langue
                                        $pathWithoutLocale = $currentPath;
                                        foreach (config('app.available_locales') as $locale => $name) {
                                            if (Str::startsWith($currentPath, $locale.'/')) {
                                                $pathWithoutLocale = Str::after($currentPath, $locale.'/');
                                                break;
                                            }
                                        }
                                        
                                        if ($langKey === config('app.fallback_locale')) {
                                            $newUrl = url('/' . $pathWithoutLocale);
                                        } else {
                                            $newUrl = url('/' . $langKey . '/' . $pathWithoutLocale);
                                        }
                                    }
                                    
                                    // Enregistrer la langue sélectionnée dans la session
                                    // Cela sera utilisé par le middleware LocalizeAuthRoutes
                                    $sessionUrl = $newUrl . '?locale=' . $langKey;
                                @endphp
                                <option value="{{ $sessionUrl }}" {{ app()->getLocale() == $langKey ? 'selected' : '' }}>{{ __($langName) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html> 