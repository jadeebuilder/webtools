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
                <li x-data="{ settingsOpen: {{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }" x-init="settingsOpen = {{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }}">
                    <button @click="settingsOpen = !settingsOpen" 
                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-cog text-xl sidebar-icon {{ request()->routeIs('admin.settings.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                            <span class="ml-3 menu-label" x-show="open">{{ __('Configuration') }}</span>
                        </div>
                        <i class="fas" :class="settingsOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
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
                <li x-data="{ toolsOpen: {{ request()->routeIs('admin.tools.*') || request()->routeIs('admin.tool-categories.*') || request()->routeIs('admin.tool-types.*') || request()->routeIs('admin.templates.*') ? 'true' : 'false' }} }" x-init="toolsOpen = {{ request()->routeIs('admin.tools.*') || request()->routeIs('admin.tool-categories.*') || request()->routeIs('admin.tool-types.*') || request()->routeIs('admin.templates.*') ? 'true' : 'false' }}">
                    <button @click="toolsOpen = !toolsOpen" 
                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700 {{ request()->routeIs('admin.tools.*') || request()->routeIs('admin.tool-categories.*') || request()->routeIs('admin.tool-types.*') || request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-tools text-xl sidebar-icon {{ request()->routeIs('admin.tools.*') || request()->routeIs('admin.tool-categories.*') || request()->routeIs('admin.tool-types.*') || request()->routeIs('admin.templates.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                            <span class="ml-3" x-show="open">{{ __('Gestion des Outils') }}</span>
                        </div>
                        <i class="fas" :class="toolsOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                    </button>
                    <ul x-show="toolsOpen" class="mt-1 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('admin.tools.index', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.tools.index') ? 'active' : '' }}">
                                <i class="fas fa-list text-lg sidebar-icon {{ request()->routeIs('admin.tools.index') ? 'text-primary' : 'text-gray-500' }}"></i>
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
                            <a href="{{ route('admin.tool-types.index', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.tool-types.*') ? 'active' : '' }}">
                                <i class="fas fa-layer-group text-lg sidebar-icon {{ request()->routeIs('admin.tool-types.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Types d\'outils') }}</span>
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
                
                <!-- Publicités -->
                <li x-data="{ adsOpen: {{ request()->routeIs('admin.ads.*') ? 'true' : 'false' }} }" x-init="adsOpen = {{ request()->routeIs('admin.ads.*') ? 'true' : 'false' }}">
                    <button @click="adsOpen = !adsOpen" 
                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700 {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-ad text-xl sidebar-icon {{ request()->routeIs('admin.ads.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                            <span class="ml-3" x-show="open">{{ __('Publicités') }}</span>
                        </div>
                        <i class="fas" :class="adsOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                    </button>
                    <ul x-show="adsOpen" class="mt-1 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.ads.index') ? 'active' : '' }}">
                                <i class="fas fa-list text-lg sidebar-icon {{ request()->routeIs('admin.ads.index') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Gérer les publicités') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ads.global-settings', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.ads.global-settings') ? 'active' : '' }}">
                                <i class="fas fa-sliders-h text-lg sidebar-icon {{ request()->routeIs('admin.ads.global-settings') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Configuration globale') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.adblock.index', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.adblock.*') ? 'active' : '' }}">
                                <i class="fas fa-ban text-lg sidebar-icon {{ request()->routeIs('admin.adblock.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Détection AdBlock') }}</span>
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
                    <ul x-show="usersOpen" class="mt-1 ml-6 space-y-1">
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
                    <ul x-show="paymentsOpen" class="mt-1 ml-6 space-y-1">
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
                    <ul x-show="langOpen" class="mt-1 ml-6 space-y-1">
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
                    <ul x-show="seoOpen" class="mt-1 ml-6 space-y-1">
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
                <li x-data="{ faqOpen: {{ request()->routeIs('admin.faq.*') || request()->routeIs('admin.faq_categories.*') ? 'true' : 'false' }} }" x-init="faqOpen = {{ request()->routeIs('admin.faq.*') || request()->routeIs('admin.faq_categories.*') ? 'true' : 'false' }}">
                    <button @click="faqOpen = !faqOpen" 
                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700 {{ request()->routeIs('admin.faq.*') || request()->routeIs('admin.faq_categories.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-question-circle text-xl sidebar-icon {{ request()->routeIs('admin.faq.*') || request()->routeIs('admin.faq_categories.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                            <span class="ml-3" x-show="open">{{ __('FAQ') }}</span>
                        </div>
                        <i class="fas" :class="faqOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                    </button>
                    <ul x-show="faqOpen" class="mt-1 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('admin.faq.index', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.faq.index') ? 'active' : '' }}">
                                <i class="fas fa-list text-lg sidebar-icon {{ request()->routeIs('admin.faq.index') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liste des FAQ') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.faq.create', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.faq.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle text-lg sidebar-icon {{ request()->routeIs('admin.faq.create') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Ajouter une FAQ') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.faq_categories.index', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.faq_categories.*') ? 'active' : '' }}">
                                <i class="fas fa-th-large text-lg sidebar-icon {{ request()->routeIs('admin.faq_categories.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Catégories de FAQ') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- PACKAGES -->
                <li x-data="{ packagesOpen: {{ request()->routeIs('admin.packages.*') ? 'true' : 'false' }} }" x-init="packagesOpen = {{ request()->routeIs('admin.packages.*') ? 'true' : 'false' }}">
                    <button @click="packagesOpen = !packagesOpen" 
                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700 {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-box text-xl sidebar-icon {{ request()->routeIs('admin.packages.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                            <span class="ml-3" x-show="open">{{ __('PACKAGES') }}</span>
                        </div>
                        <i class="fas" :class="packagesOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                    </button>
                    <ul x-show="packagesOpen" class="mt-1 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('admin.packages.index', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.packages.index') ? 'active' : '' }}">
                                <i class="fas fa-list text-lg sidebar-icon {{ request()->routeIs('admin.packages.index') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liste des packages') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.packages.create', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.packages.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle text-lg sidebar-icon {{ request()->routeIs('admin.packages.create') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Ajouter un package') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- TESTIMONIALS -->
                <li x-data="{ testimonialsOpen: {{ request()->routeIs('admin.testimonials.*') ? 'true' : 'false' }} }" x-init="testimonialsOpen = {{ request()->routeIs('admin.testimonials.*') ? 'true' : 'false' }}">
                    <button @click="testimonialsOpen = !testimonialsOpen" 
                            class="sidebar-item w-full flex items-center justify-between p-3 rounded-lg text-gray-700 {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-comment-dots text-xl sidebar-icon {{ request()->routeIs('admin.testimonials.*') ? 'text-primary' : 'text-gray-500' }}"></i>
                            <span class="ml-3" x-show="open">{{ __('TESTIMONIALS') }}</span>
                        </div>
                        <i class="fas" :class="testimonialsOpen ? 'fa-chevron-down' : 'fa-chevron-right'" x-show="open"></i>
                    </button>
                    <ul x-show="testimonialsOpen" class="mt-1 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('admin.testimonials.index', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.testimonials.index') ? 'active' : '' }}">
                                <i class="fas fa-list text-lg sidebar-icon {{ request()->routeIs('admin.testimonials.index') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Liste des témoignages') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.testimonials.create', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.testimonials.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle text-lg sidebar-icon {{ request()->routeIs('admin.testimonials.create') ? 'text-primary' : 'text-gray-500' }}"></i>
                                <span class="ml-3 text-gray-700 text-sm" x-show="open">{{ __('Ajouter un témoignage') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.testimonials.moderation', ['locale' => app()->getLocale()]) }}" 
                               class="sidebar-item flex items-center p-2 rounded-lg {{ request()->routeIs('admin.testimonials.moderation') ? 'active' : '' }}">
                                <i class="fas fa-star text-lg sidebar-icon {{ request()->routeIs('admin.testimonials.moderation') ? 'text-primary' : 'text-gray-500' }}"></i>
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
                    <ul x-show="advancedOpen" class="mt-1 ml-6 space-y-1">
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