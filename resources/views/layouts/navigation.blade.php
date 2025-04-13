<nav x-data="{ open: false, userMenuOpen: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ URL::localizedRoute('home') }}" class="flex items-center">
                        <img src="{{ Vite::asset('resources/images/webtools_logo.png') }}" alt="WebTools" class="h-10 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <div x-data="{
                            toolsMenu: false,
                            closeTimer: null,
                            showMenu() {
                                if (this.closeTimer) clearTimeout(this.closeTimer);
                                this.toolsMenu = true;
                                setTimeout(() => {
                                    document.querySelectorAll('.tool-category').forEach((card, index) => {
                                        setTimeout(() => {
                                            card.classList.add('show');
                                        }, index * 50);
                                    });
                                }, 100);
                            },
                            hideMenu() {
                                document.querySelectorAll('.tool-category').forEach(card => {
                                    card.classList.remove('show');
                                });
                                this.closeTimer = setTimeout(() => {
                                    this.toolsMenu = false;
                                }, 300);
                            }
                        }"
                         class="static"
                         @keydown.escape.window="hideMenu()">

                        <button @mouseenter="showMenu()"
                                @click="toolsMenu = !toolsMenu"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <i class="fas fa-tools mr-2"></i>
                                {{ __('Tools') }}
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        <!-- Mega Menu -->
                        <div x-show="toolsMenu"
                             x-cloak
                             @mouseenter="showMenu()"
                             @mouseleave="hideMenu()"
                             class="mega-menu"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            <div class="mega-menu-content py-6 relative">
                                <!-- Close button -->
                                <button @click="hideMenu()"
                                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 focus:outline-none"
                                        aria-label="{{ __('Close menu') }}">
                                    <span class="sr-only">{{ __('Close menu') }}</span>
                                    <i class="fas fa-times text-xl"></i>
                                </button>

                                <!-- Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                    <!-- Checker Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'checker']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-check-circle text-3xl text-purple-500 group-hover:text-purple-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Checker Tools') }}</h3>
                                        <p class="tool-description">{{ __('Validate and verify different types of data') }}</p>
                                    </a>

                                    <!-- Text Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'text']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-font text-3xl text-purple-500 group-hover:text-purple-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Text Tools') }}</h3>
                                        <p class="tool-description">{{ __('Manipulate and transform your text') }}</p>
                                    </a>

                                    <!-- Converter Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'converter']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-exchange-alt text-3xl text-green-500 group-hover:text-green-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Converter Tools') }}</h3>
                                        <p class="tool-description">{{ __('Convert different data formats') }}</p>
                                    </a>

                                    <!-- Generator Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'generator']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-magic text-3xl text-blue-500 group-hover:text-blue-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Generator Tools') }}</h3>
                                        <p class="tool-description">{{ __('Generate different types of content') }}</p>
                                    </a>

                                    <!-- Developer Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'developer']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-code text-3xl text-indigo-500 group-hover:text-indigo-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Developer Tools') }}</h3>
                                        <p class="tool-description">{{ __('Essential tools for developers') }}</p>
                                    </a>

                                    <!-- Image Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'image']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-image text-3xl text-orange-500 group-hover:text-orange-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Image Tools') }}</h3>
                                        <p class="tool-description">{{ __('Manipulate and optimize your images') }}</p>
                                    </a>

                                    <!-- Unit Converter -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'unit']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-ruler text-3xl text-pink-500 group-hover:text-pink-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Unit Converter') }}</h3>
                                        <p class="tool-description">{{ __('Convert different units of measurement') }}</p>
                                    </a>

                                    <!-- Time Converter -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'time']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-clock text-3xl text-teal-500 group-hover:text-teal-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Time Converter') }}</h3>
                                        <p class="tool-description">{{ __('Convert and calculate time') }}</p>
                                    </a>

                                    <!-- Data Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'data']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-database text-3xl text-yellow-500 group-hover:text-yellow-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Data Tools') }}</h3>
                                        <p class="tool-description">{{ __('Manipulate and analyze your data') }}</p>
                                    </a>

                                    <!-- Color Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'color']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-palette text-3xl text-red-500 group-hover:text-red-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Color Tools') }}</h3>
                                        <p class="tool-description">{{ __('Manage and convert colors') }}</p>
                                    </a>

                                    <!-- Misc Tools -->
                                    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'misc']) }}" class="tool-category">
                                        <div class="tool-icon-wrapper">
                                            <i class="fas fa-ellipsis-h text-3xl text-gray-500 group-hover:text-gray-600"></i>
                                        </div>
                                        <h3 class="tool-title">{{ __('Misc Tools') }}</h3>
                                        <p class="tool-description">{{ __('Other useful tools') }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auth Navigation Links -->
            <div class="hidden sm:flex sm:items-center">
                @guest
                    <div class="auth-buttons flex items-center space-x-3">
                        <a href="{{ route('login', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-purple-700 transition duration-300 ease-in-out transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2 text-purple-500"></i>
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-indigo-600 rounded-md hover:from-purple-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-300 ease-in-out transform hover:scale-105 shadow-md">
                            <i class="fas fa-user-plus mr-2"></i>
                            {{ __('Register') }}
                        </a>
                    </div>
                @else
                    <!-- User Profile Dropdown -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <div>
                            <button 
                                @click="open = !open" 
                                @click.away="open = false" 
                                class="flex items-center text-sm font-medium text-gray-700 bg-white rounded-full hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 transition duration-150 ease-in-out group"
                                id="user-menu-button"
                                aria-expanded="false"
                                aria-haspopup="true">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-400 to-indigo-500 flex items-center justify-center text-white overflow-hidden border-2 border-white shadow-md transition duration-300 ease-in-out transform group-hover:scale-110">
                                    @if (Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="ml-2 hidden md:block">
                                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[120px]">{{ Auth::user()->email }}</div>
                                </div>
                                <svg class="ml-2 h-5 w-5 text-gray-400 group-hover:text-purple-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        
                        <div 
                            x-show="open" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="origin-top-right absolute right-0 mt-2 w-60 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
                            role="menu" 
                            aria-orientation="vertical" 
                            aria-labelledby="user-menu-button" 
                            tabindex="-1">
                            
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="text-sm text-gray-500">{{ __('Logged in as') }}</div>
                                <div class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            
                            <div class="py-1 space-y-1">
                                @if(Auth::user()->isAdmin())
                                <div>
                                    <a href="{{ URL::localizedRoute('admin.dashboard') }}" class="user-menu-item bg-gradient-to-r from-purple-500 to-indigo-600 text-white hover:from-purple-600 hover:to-indigo-700">
                                        <i class="fas fa-crown mr-3 text-yellow-300"></i>
                                        <span class="font-bold text-lg">{{ __('Admin Panel') }}</span>
                                    </a>
                                </div>
                                
                                <div class="border-t border-gray-100 my-1"></div>
                                @endif

                                <div>
                                    <a href="{{ URL::localizedRoute('user.account') }}" class="user-menu-item">
                                        <i class="fas fa-user-circle mr-3 text-purple-500"></i>
                                        <span>{{ __('My Account') }}</span>
                                    </a>
                                </div>
                                
                                <div>
                                    <a href="{{ URL::localizedRoute('user.settings') }}" class="user-menu-item">
                                        <i class="fas fa-cog mr-3 text-blue-500"></i>
                                        <span>{{ __('Account Settings') }}</span>
                                    </a>
                                </div>
                                
                                <div>
                                    <a href="{{ URL::localizedRoute('user.packages') }}" class="user-menu-item">
                                        <i class="fas fa-box mr-3 text-indigo-500"></i>
                                        <span>{{ __('My Packages') }}</span>
                                    </a>
                                </div>
                                
                                <div>
                                    <a href="{{ URL::localizedRoute('user.history') }}" class="user-menu-item">
                                        <i class="fas fa-history mr-3 text-green-500"></i>
                                        <span>{{ __('Activity History') }}</span>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-100 mt-1"></div>
                            
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout', ['locale' => app()->getLocale()]) }}">
                                    @csrf
                                    <button type="submit" class="user-menu-item w-full text-red-600 hover:text-red-700 hover:bg-red-50 group">
                                        <i class="fas fa-sign-out-alt mr-3 text-red-500 group-hover:text-red-700"></i>
                                        <span>{{ __('Logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="URL::localizedRoute('tools.index')" :active="request()->routeIs('tools.index')" class="flex items-center">
                <i class="fas fa-tools mr-2"></i>
                {{ __('All Tools') }}
            </x-responsive-nav-link>
        </div>
        
        <!-- Responsive Auth Links -->
        @guest
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4 py-2">
                    <a href="{{ route('login', ['locale' => app()->getLocale()]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-purple-700">
                        <i class="fas fa-sign-in-alt mr-2 text-purple-500"></i>
                        {{ __('Login') }}
                    </a>
                </div>
                <div class="flex items-center px-4 py-2">
                    <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-indigo-600 rounded-md hover:from-purple-600 hover:to-indigo-700">
                        <i class="fas fa-user-plus mr-2"></i>
                        {{ __('Register') }}
                    </a>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-400 to-indigo-500 flex items-center justify-center text-white overflow-hidden border-2 border-white">
                            @if (Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="ml-3">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    @if(Auth::user()->isAdmin())
                    <x-responsive-nav-link href="{{ URL::localizedRoute('admin.dashboard') }}" class="flex items-center bg-gradient-to-r from-purple-500 to-indigo-600 text-white">
                        <i class="fas fa-crown mr-2 text-yellow-300"></i>
                        <span class="font-bold text-lg">{{ __('Admin Panel') }}</span>
                    </x-responsive-nav-link>
                    @endif
                    
                    <x-responsive-nav-link href="{{ URL::localizedRoute('user.account') }}" class="flex items-center">
                        <i class="fas fa-user-circle mr-2 text-purple-500"></i>
                        <span>{{ __('My Account') }}</span>
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ URL::localizedRoute('user.settings') }}" class="flex items-center">
                        <i class="fas fa-cog mr-2 text-blue-500"></i>
                        <span>{{ __('Account Settings') }}</span>
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ URL::localizedRoute('user.packages') }}" class="flex items-center">
                        <i class="fas fa-box mr-2 text-indigo-500"></i>
                        <span>{{ __('My Packages') }}</span>
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link href="{{ URL::localizedRoute('user.history') }}" class="flex items-center">
                        <i class="fas fa-history mr-2 text-green-500"></i>
                        <span>{{ __('Activity History') }}</span>
                    </x-responsive-nav-link>
                    
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout', ['locale' => app()->getLocale()]) }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout', ['locale' => app()->getLocale()])"
                                onclick="event.preventDefault();
                                this.closest('form').submit();" class="flex items-center text-red-600">
                            <i class="fas fa-sign-out-alt mr-2 text-red-500"></i>
                            <span>{{ __('Logout') }}</span>
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endguest
    </div>
</nav>

<style>
/* Animations et couleurs */
.auth-buttons a {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

/* Style pour les items du menu utilisateur */
.user-menu-item {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    width: 100% !important;
    padding: 0.5rem 1rem !important;
    font-size: 0.875rem !important;
    color: #4b5563 !important;
    text-align: left !important;
    background-color: transparent !important;
    border: 0 !important;
    border-radius: 0 !important;
    transition: background-color 0.15s ease-in-out !important;
}

.user-menu-item:hover {
    background-color: #f9fafb !important;
    color: #374151 !important;
}

/* Fix pour le conteneur du menu */
.origin-top-right a, 
.origin-top-right button {
    display: block !important;
    width: 100% !important;
    margin-bottom: 2px !important;
}

/* Animation pour le menu déroulant utilisateur */
@keyframes pulse-ring {
    0% {
        box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(124, 58, 237, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(124, 58, 237, 0);
    }
}

[x-cloak] {
    display: none !important;
}

/* Animation pour le hover sur les boutons d'authentification */
.auth-buttons a:hover {
    transform: translateY(-2px);
}
</style>
