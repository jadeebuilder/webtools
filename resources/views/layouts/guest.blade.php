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
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-purple-50 to-indigo-100">
            <div class="mb-6">
                <a href="{{ URL::localizedRoute('home') }}">
                    <x-application-logo class="w-40 h-auto" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl overflow-hidden sm:rounded-lg border border-gray-200">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} WebTools. {{ __('All rights reserved.') }}</p>
                <div class="mt-2 flex justify-center space-x-4">
                    <a href="#" class="text-gray-500 hover:text-purple-500 transition duration-150">
                        {{ __('Privacy Policy') }}
                    </a>
                    <a href="#" class="text-gray-500 hover:text-purple-500 transition duration-150">
                        {{ __('Terms of Service') }}
                    </a>
                    <a href="#" class="text-gray-500 hover:text-purple-500 transition duration-150">
                        {{ __('Contact Us') }}
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
