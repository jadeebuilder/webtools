<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Styles -->
        <style>
            .tool-item {
                transition: all 0.2s ease-in-out;
            }

            .tool-item:hover {
                transform: translateY(-2px);
            }

            .tool-icon {
                transition: all 0.2s ease-in-out;
            }

            .tool-item:hover .tool-icon {
                transform: scale(1.1);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            
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
                            <form class="space-y-3">
                                <div class="flex">
                                    <input type="email"
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
                                        // Construire la nouvelle URL en remplaçant simplement la partie langue
                                        $currentPath = Request::path();
                                        $segments = explode('/', $currentPath);

                                        // Remplacer le premier segment (la langue) par la nouvelle langue
                                        if (count($segments) > 0) {
                                            $segments[0] = $langKey;
                                        }

                                        $newPath = implode('/', $segments);
                                        $newUrl = url($newPath);
                                    @endphp
                                    <option value="{{ $newUrl }}" {{ app()->getLocale() == $langKey ? 'selected' : '' }}>{{ __($langName) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
