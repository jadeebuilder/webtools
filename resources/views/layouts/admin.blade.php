<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle ?? config('app.name', 'Laravel') . ' - Administration' }}</title>
        <meta name="description" content="{{ $metaDescription ?? 'Panneau d\'administration WebTools' }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Script pour gérer la transition du sidebar -->
        <script>
            document.addEventListener('alpine:init', function() {
                Alpine.store('sidebar', {
                    open: true,
                    toggle() {
                        this.open = !this.open;
                        // Force une redimensionnement du contenu principal
                        setTimeout(() => {
                            window.dispatchEvent(new Event('resize'));
                        }, 300);
                    }
                });
            });
        </script>

        <!-- Styles -->
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            
            .sidebar {
                width: 280px;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 40;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                background: linear-gradient(to bottom, #fff, #fcfcfc);
                border-right: 1px solid rgba(102, 11, 171, 0.1);
            }
            
            .sidebar-collapsed {
                width: 70px;
            }
            
            .sidebar-item {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border-radius: 0.5rem;
                margin-bottom: 0.25rem;
                position: relative;
                overflow: hidden;
            }
            
            .sidebar-item::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 0;
                background-color: rgba(102, 11, 171, 0.05);
                transition: width 0.3s ease;
                z-index: -1;
            }
            
            .sidebar-item:hover::before {
                width: 100%;
            }
            
            .sidebar-item:hover {
                background-color: rgba(102, 11, 171, 0.05);
                transform: translateX(3px);
            }
            
            .sidebar-item.active {
                background-color: rgba(102, 11, 171, 0.08);
                border-left: 4px solid #660bab;
                font-weight: 500;
            }
            
            .sidebar-item.active .sidebar-icon {
                color: #660bab;
            }
            
            .sidebar-content {
                height: calc(100vh - 64px);
                scrollbar-width: thin;
                scrollbar-color: rgba(102, 11, 171, 0.2) transparent;
            }
            
            .sidebar-content::-webkit-scrollbar {
                width: 4px;
            }
            
            .sidebar-content::-webkit-scrollbar-track {
                background: transparent;
            }
            
            .sidebar-content::-webkit-scrollbar-thumb {
                background-color: rgba(102, 11, 171, 0.2);
                border-radius: 20px;
            }
            
            /* Animations pour les sous-menus */
            .submenu-enter-active {
                max-height: 1000px;
                opacity: 1;
                transition: max-height 0.5s ease, opacity 0.4s ease;
            }
            
            .submenu-leave-active, 
            .submenu-enter-from {
                max-height: 0;
                opacity: 0;
                overflow: hidden;
                transition: max-height 0.5s ease, opacity 0.2s ease;
            }
            
            /* Style pour les libellés de menu */
            .menu-label {
                transition: all 0.3s ease;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            /* Styles pour les flèches de sous-menus */
            .submenu-icon {
                transition: transform 0.3s ease;
            }
            
            .submenu-icon.open {
                transform: rotate(90deg);
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

            /* Classes pour gérer la transition du contenu principal */
            .main-content {
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                width: calc(100% - 280px);
                margin-left: 280px;
                position: relative;
                min-height: 100vh;
                flex: 1 1 auto;
                display: flex;
                flex-direction: column;
            }
            
            .main-content-expanded {
                width: calc(100% - 70px);
                margin-left: 70px;
            }
            
            /* Style pour s'assurer que le contenu prend toute la hauteur */
            .main-content > main {
                flex: 1;
            }

            /* Assurer que le contenu est toujours visible même lorsque le sidebar est ouvert */
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                    box-shadow: none;
                }
                
                .sidebar.sidebar-visible {
                    transform: translateX(0);
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }
                
                .main-content {
                    width: 100%;
                    margin-left: 0;
                }
            }
            
            /* Effet de transition pour le sidebar */
            .sidebar {
                transform: translateX(0);
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), width 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Animation des icônes lors de la transition */
            .sidebar-icon {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
            }
            
            .sidebar-item:hover .sidebar-icon {
                transform: translateX(4px);
                color: #660bab;
            }
            
            /* Effet de pulse sur les icônes */
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            
            .sidebar-item.active .sidebar-icon {
                animation: pulse 2s infinite;
            }
            
            /* Effet au survol des liens */
            .sidebar-item {
                position: relative;
                overflow: hidden;
            }
            
            .sidebar-item::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0;
                height: 2px;
                background-color: rgba(102, 11, 171, 0.5);
                transition: width 0.3s ease;
            }
            
            .sidebar-item:hover::after {
                width: 100%;
            }
            
            /* Style pour le header du sidebar */
            .sidebar-header {
                background: linear-gradient(to right, rgba(102, 11, 171, 0.05), transparent);
                border-bottom: 1px solid rgba(102, 11, 171, 0.1);
            }
            
            /* Style pour le bouton de toggle du sidebar */
            .sidebar-toggle {
                transition: all 0.3s ease;
                border-radius: 50%;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar-toggle:hover {
                background-color: rgba(102, 11, 171, 0.1);
                transform: scale(1.1);
            }

            /* Mode rabattu du sidebar */
            .sidebar-collapsed .sidebar-icon {
                margin: 0 auto;
                font-size: 1.25rem;
            }
            
            .sidebar-collapsed .sidebar-item {
                padding: 0.75rem;
                display: flex;
                justify-content: center;
            }
            
            /* Effet de tooltip pour les menus en mode rabattu */
            .sidebar-collapsed .sidebar-item {
                position: relative;
            }
            
            .sidebar-collapsed .sidebar-item:hover::before {
                content: attr(data-tooltip);
                position: absolute;
                left: 100%;
                top: 50%;
                transform: translateY(-50%);
                background-color: #660bab;
                color: white;
                padding: 0.5rem 0.75rem;
                border-radius: 0.25rem;
                font-size: 0.875rem;
                white-space: nowrap;
                z-index: 50;
                opacity: 0;
                animation: fadeIn 0.3s forwards;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-50%) translateX(-10px); }
                to { opacity: 1; transform: translateY(-50%) translateX(10px); }
            }

            /* Effet de soulignement progressif */
            .sidebar-item:not(.active)::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0;
                height: 2px;
                background: linear-gradient(to right, #660bab, #9333ea);
                transition: width 0.3s ease;
                transform-origin: left;
            }
            
            .sidebar-item:not(.active):hover::after {
                width: 100%;
            }
            
            /* Effet glow pour les éléments actifs */
            .sidebar-item.active {
                box-shadow: 0 0 10px rgba(102, 11, 171, 0.2);
            }
            
            .sidebar-item.active .sidebar-icon {
                text-shadow: 0 0 10px rgba(102, 11, 171, 0.3);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div x-data class="min-h-screen flex flex-col">
            <!-- Sidebar et Main Content -->
            <div class="flex flex-grow">
                @include('layouts.partials.admin-sidebar')

                <!-- Main Content -->
                <div class="main-content" 
                     x-data="{ sidebarOpen: $store.sidebar.open }"
                     x-init="$watch('$store.sidebar.open', value => { sidebarOpen = value; })"
                     :class="{ 'main-content-expanded': !sidebarOpen }">
                    <!-- Top Navbar -->
                    <nav class="bg-white shadow-sm h-16 flex items-center px-4 justify-between">
                        <div class="flex items-center">
                            <button class="md:hidden mr-4 p-2 rounded-md text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none" @click="$dispatch('toggle-sidebar')">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <h1 class="text-xl font-semibold text-gray-700">{{ $title ?? 'Administration' }}</h1>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Sélecteur de langue -->
                            <select onchange="window.location.href=this.value" class="bg-gray-100 text-gray-700 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-purple-500">
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
                            
                            <!-- Profil utilisateur -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-400 to-indigo-500 flex items-center justify-center text-white overflow-hidden">
                                        <span class="font-bold">{{ substr(Auth::user()->firstname ?? 'A', 0, 1) }}</span>
                                    </div>
                                    <span class="text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('profile.edit', ['locale' => app()->getLocale()]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('Profil') }}
                                    </a>
                                    <form method="POST" action="{{ route('logout', ['locale' => app()->getLocale()]) }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            {{ __('Déconnexion') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Page Content -->
                    <main class="py-6 px-4 sm:px-6 lg:px-8">
                        <!-- Page Heading -->
                        @if (isset($header))
                            <header class="mb-6">
                                {{ $header }}
                            </header>
                        @endif
                        
                        <!-- Flash Messages -->
                        @if (session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif
                        
                        <!-- Main Content -->
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html> 