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