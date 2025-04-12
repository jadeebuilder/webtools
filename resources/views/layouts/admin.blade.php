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
                <!-- Sidebar -->
                <aside class="sidebar bg-white shadow-lg fixed inset-y-0 left-0 z-10" 
                       x-data="{ open: $store.sidebar.open, mobileOpen: false }" 
                       :class="{ 'sidebar-collapsed': !open, 'sidebar-visible': mobileOpen }"
                       @toggle-sidebar.window="mobileOpen = !mobileOpen"
                       x-init="$watch('open', value => $store.sidebar.open = value)">
                    <div class="flex flex-col h-full">
                        <!-- Header -->
                        <div class="sidebar-header flex items-center justify-between h-16 px-4">
                            <div class="flex items-center">
                                <img src="{{ Vite::asset('resources/images/webtools_logo.png') }}" alt="WebTools Logo" class="h-8 w-auto" :class="{ 'mx-auto': !open }">
                            </div>
                            <button @click="$store.sidebar.toggle(); open = $store.sidebar.open" 
                                   class="sidebar-toggle p-2 rounded-md text-gray-500 hover:text-gray-900 focus:outline-none">
                                <svg x-show="open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                </svg>
                                <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Sidebar Content -->
                        <div class="sidebar-content flex-1 overflow-y-auto py-4 px-3">
                            <ul class="space-y-2">
                                <!-- Dashboard -->
                                <li>
                                    <a href="{{ route('admin.dashboard', ['locale' => app()->getLocale()]) }}" 
                                       class="sidebar-item flex items-center p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                       data-tooltip="{{ __('Dashboard') }}">
                                        <i class="fas fa-tachometer-alt text-xl sidebar-icon {{ request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-gray-500' }}"></i>
                                        <span class="ml-3 text-gray-700 menu-label" x-show="open">{{ __('Dashboard') }}</span>
                                    </a>
                                </li>
                                
                                <!-- Configuration générale -->
                                <li x-data="{ settingsOpen: false }">
                                    <button @click="settingsOpen = !settingsOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-cog text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3 menu-label" x-show="open">{{ __('Configuration') }}</span>
                                        </div>
                                        <i class="fas fa-chevron-right text-xs text-gray-500 submenu-icon" 
                                           :class="{ 'open': settingsOpen }" 
                                           x-show="open"></i>
                                    </button>
                                    <div x-show="settingsOpen" 
                                         x-transition:enter="submenu-enter-active"
                                         x-transition:leave="submenu-leave-active">
                                        <ul class="mt-1 ml-6 space-y-1">
                                            <li>
                                                <a href="{{ route('admin.settings.general', ['locale' => app()->getLocale()]) }}" 
                                                   class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                                                    <i class="fas fa-sliders-h text-lg sidebar-icon {{ request()->routeIs('admin.settings.general') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                    <span class="ml-3 text-gray-700 text-sm menu-label" x-show="open">{{ __('Paramètres généraux') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.settings.maintenance', ['locale' => app()->getLocale()]) }}" 
                                                   class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.settings.maintenance') ? 'active' : '' }}">
                                                    <i class="fas fa-tools text-lg sidebar-icon {{ request()->routeIs('admin.settings.maintenance') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                    <span class="ml-3 text-gray-700 text-sm menu-label" x-show="open">{{ __('Mode maintenance') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.settings.sitemap', ['locale' => app()->getLocale()]) }}" 
                                                   class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.settings.sitemap') ? 'active' : '' }}">
                                                    <i class="fas fa-sitemap text-lg sidebar-icon {{ request()->routeIs('admin.settings.sitemap') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                    <span class="ml-3 text-gray-700 text-sm menu-label" x-show="open">{{ __('Sitemap') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.settings.company', ['locale' => app()->getLocale()]) }}" 
                                                   class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.settings.company') ? 'active' : '' }}">
                                                    <i class="fas fa-building text-lg sidebar-icon {{ request()->routeIs('admin.settings.company') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                    <span class="ml-3 text-gray-700 text-sm menu-label" x-show="open">{{ __('Détails entreprise') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                
                                <!-- Gestion des Outils -->
                                <li x-data="{ toolsOpen: false }">
                                    <button @click="toolsOpen = !toolsOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-tools text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('Gestion des Outils') }}</span>
                                        </div>
                                        <i class="fas" :class="toolsOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="toolsOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="{{ route('admin.tools.index', ['locale' => app()->getLocale()]) }}" 
                                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.tools.*') ? 'active' : '' }}">
                                                <i class="fas fa-list text-lg sidebar-icon {{ request()->routeIs('admin.tools.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liste des outils') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.tools.create', ['locale' => app()->getLocale()]) }}" 
                                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.tools.create') ? 'active' : '' }}">
                                                <i class="fas fa-plus-circle text-lg sidebar-icon {{ request()->routeIs('admin.tools.create') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Ajouter un outil') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.tool-categories.index', ['locale' => app()->getLocale()]) }}" 
                                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.tool-categories.*') ? 'active' : '' }}">
                                                <i class="fas fa-th-large text-lg sidebar-icon {{ request()->routeIs('admin.tool-categories.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Catégories d\'outils') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.templates.index', ['locale' => app()->getLocale()]) }}" 
                                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.templates.index') ? 'active' : '' }}">
                                                <i class="fas fa-file-alt text-lg sidebar-icon {{ request()->routeIs('admin.templates.index') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Templates d\'outils') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.templates.sections.index', ['locale' => app()->getLocale()]) }}" 
                                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.templates.sections.index') ? 'active' : '' }}">
                                                <i class="fas fa-puzzle-piece text-lg sidebar-icon {{ request()->routeIs('admin.templates.sections.index') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Sections de templates') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- Gestion des utilisateurs -->
                                <li x-data="{ usersOpen: false }">
                                    <button @click="usersOpen = !usersOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-users text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('Utilisateurs') }}</span>
                                        </div>
                                        <i class="fas" :class="usersOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="usersOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-user-friends text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liste des utilisateurs') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-user-plus text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Ajouter un utilisateur') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-user-shield text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Rôles et permissions') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- Paiements et abonnements -->
                                <li x-data="{ paymentsOpen: false }">
                                    <button @click="paymentsOpen = !paymentsOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-credit-card text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('Paiements') }}</span>
                                        </div>
                                        <i class="fas" :class="paymentsOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="paymentsOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-cogs text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Paramètres paiements') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-money-check-alt text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Processeurs de paiement') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-crown text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Plans & packages') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-history text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Historique transactions') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-handshake text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Système d\'affiliation') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- Publicités -->
                                <li x-data="{ adsOpen: false }">
                                    <button @click="adsOpen = !adsOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-ad text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('Publicités') }}</span>
                                        </div>
                                        <i class="fas" :class="adsOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="adsOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" 
                                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.ads.index') ? 'active' : '' }}">
                                                <i class="fas fa-list text-lg sidebar-icon {{ request()->routeIs('admin.ads.index') ? 'text-primary' : 'text-gray-500' }}"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Gérer les publicités') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-ban text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Détection AdBlock') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- Gestion des langues -->
                                <li x-data="{ langOpen: false }">
                                    <button @click="langOpen = !langOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-language text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('Langues') }}</span>
                                        </div>
                                        <i class="fas" :class="langOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="langOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-globe text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Langues disponibles') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-edit text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Éditer traductions') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- SEO -->
                                <li x-data="{ seoOpen: false }">
                                    <button @click="seoOpen = !seoOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-search text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('SEO') }}</span>
                                        </div>
                                        <i class="fas" :class="seoOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="seoOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-home text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Page d\'accueil') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-key text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Pages Auth') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-gavel text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Pages légales') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-envelope text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Page Contact') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- FAQ -->
                                <li x-data="{ faqOpen: false }">
                                    <button @click="faqOpen = !faqOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-question-circle text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('FAQ') }}</span>
                                        </div>
                                        <i class="fas" :class="faqOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="faqOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-list text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liste des FAQ') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-plus-circle text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Ajouter une FAQ') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-th-large text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Catégories de FAQ') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- PACKAGES -->
                                <li x-data="{ packagesOpen: false }">
                                    <button @click="packagesOpen = !packagesOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-box text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('PACKAGES') }}</span>
                                        </div>
                                        <i class="fas" :class="packagesOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="packagesOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-list text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liste des packages') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-plus-circle text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Ajouter un package') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-cogs text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Configuration packages') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- TESTIMONIALS -->
                                <li x-data="{ testimonialsOpen: false }">
                                    <button @click="testimonialsOpen = !testimonialsOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-comment-dots text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('TESTIMONIALS') }}</span>
                                        </div>
                                        <i class="fas" :class="testimonialsOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="testimonialsOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-list text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liste des témoignages') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-plus-circle text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Ajouter un témoignage') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-star text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Modération témoignages') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- Options avancées -->
                                <li x-data="{ advancedOpen: false }">
                                    <button @click="advancedOpen = !advancedOpen" 
                                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700">
                                        <div class="flex items-center">
                                            <i class="fas fa-cogs text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3" x-show="open">{{ __('Options avancées') }}</span>
                                        </div>
                                        <i class="fas" :class="advancedOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                                    </button>
                                    <ul x-show="advancedOpen" class="mt-1 ml-6 space-y-1" x-show="open">
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-shield-alt text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Configuration Captcha') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-users-cog text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Social Login') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-share-alt text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liens sociaux') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-envelope-open-text text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Paramètres SMTP') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" 
                                               class="sidebar-item flex items-center p-2 rounded-lg">
                                                <i class="fas fa-file-code text-lg sidebar-icon text-gray-500"></i>
                                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Code personnalisé') }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            
                            <div class="border-t border-gray-200 my-4"></div>
                            
                            <!-- Liens utiles -->
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" 
                                       class="sidebar-item flex items-center p-3 rounded-lg">
                                        <i class="fas fa-home text-xl sidebar-icon text-gray-500"></i>
                                        <span class="ml-3 text-gray-700" x-show="open">{{ __('Retour au site') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout', ['locale' => app()->getLocale()]) }}">
                                        @csrf
                                        <button type="submit" class="w-full sidebar-item flex items-center p-3 rounded-lg text-left">
                                            <i class="fas fa-sign-out-alt text-xl sidebar-icon text-gray-500"></i>
                                            <span class="ml-3 text-gray-700" x-show="open">{{ __('Déconnexion') }}</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </aside>

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