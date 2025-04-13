<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle ?? \App\Models\Setting::get('meta_title', config('app.name', 'Laravel')) }}</title>
        <meta name="description" content="{{ $metaDescription ?? \App\Models\Setting::get('meta_description', '') }}">
        <meta name="keywords" content="{{ \App\Models\Setting::get('meta_keywords', '') }}">
        <meta name="author" content="{{ \App\Models\Setting::get('meta_author', '') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $pageTitle ?? \App\Models\Setting::get('meta_title', config('app.name', 'Laravel')) }}">
        <meta property="og:description" content="{{ $metaDescription ?? \App\Models\Setting::get('meta_description', '') }}">
        <meta property="og:image" content="{{ $ogImage ?? asset(\App\Models\Setting::get('site_logo_light', 'images/og-default.jpg')) }}">

        <!-- Twitter -->
        <meta name="twitter:card" content="{{ $twitterCardType ?? 'summary_large_image' }}">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="{{ $pageTitle ?? \App\Models\Setting::get('meta_title', config('app.name', 'Laravel')) }}">
        <meta name="twitter:description" content="{{ $metaDescription ?? \App\Models\Setting::get('meta_description', '') }}">
        <meta name="twitter:image" content="{{ $ogImage ?? asset(\App\Models\Setting::get('site_logo_light', 'images/og-default.jpg')) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Styles -->
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            
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
            
            .btn-primary {
                background-color: #660bab;
            }
            
            .btn-primary:hover {
                background-color: #4e0883;
            }
            
            .text-primary {
                color: #660bab;
            }
            
            .bg-primary {
                background-color: #660bab;
            }
            
            .border-primary {
                border-color: #660bab;
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
            @include('partials.footer')
        </div>
    </body>
</html>
