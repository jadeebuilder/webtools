<!-- Footer -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Logo et Description -->
            <div class="space-y-6">
                <div class="flex items-center">
                    <img src="{{ setting('site_logo_dark') ? asset(setting('site_logo_dark')) : Vite::asset('resources/images/webtools_logo_gris.png') }}" alt="{{ setting('site_name', 'WebTools') }} Logo" class="h-8 w-auto">
                </div>
                <p class="text-gray-400 text-sm">
                    {{ setting('site_description', 'Your one-stop platform for all web development tools. Simple, fast, and reliable solutions for developers worldwide.') }}
                </p>
                <div class="flex space-x-4">
                    @if(setting('company_social_facebook'))
                        <a href="{{ setting('company_social_facebook') }}" class="text-gray-400 hover:text-white transition-colors duration-300" target="_blank">
                            <i class="fab fa-facebook"></i>
                        </a>
                    @endif
                    
                    @if(setting('company_social_twitter'))
                        <a href="{{ setting('company_social_twitter') }}" class="text-gray-400 hover:text-white transition-colors duration-300" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    
                    @if(setting('company_social_instagram'))
                        <a href="{{ setting('company_social_instagram') }}" class="text-gray-400 hover:text-white transition-colors duration-300" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    
                    @if(setting('company_social_linkedin'))
                        <a href="{{ setting('company_social_linkedin') }}" class="text-gray-400 hover:text-white transition-colors duration-300" target="_blank">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    @endif
                    
                    @if(setting('company_social_youtube'))
                        <a href="{{ setting('company_social_youtube') }}" class="text-gray-400 hover:text-white transition-colors duration-300" target="_blank">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
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
                            {!! nl2br(e(setting('company_address', '123 Developer Street<br>Tech City, TC 12345'))) !!}
                        </span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone mr-3 text-gray-400"></i>
                        <a href="tel:{{ setting('company_phone', '+1234567890') }}" class="text-gray-400 hover:text-white transition-colors duration-300">
                            {{ setting('company_phone', '+1 (234) 567-890') }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-gray-400"></i>
                        <a href="mailto:{{ setting('company_email', 'contact@webtools.com') }}" class="text-gray-400 hover:text-white transition-colors duration-300">
                            {{ setting('company_email', 'contact@webtools.com') }}
                        </a>
                    </li>
                    @if(setting('company_opening_hours'))
                        <li class="flex items-start">
                            <i class="fas fa-clock mt-1.5 mr-3 text-gray-400"></i>
                            <span class="text-gray-400">
                                {!! nl2br(e(setting('company_opening_hours'))) !!}
                            </span>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ __('Stay Updated') }}</h3>
                <p class="text-gray-400 text-sm mb-4">
                    {{ __('Subscribe to our newsletter for the latest updates and tools.') }}
                </p>
                <form class="space-y-3" action="{{ URL::localizedRoute('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <div class="flex">
                        <input type="email"
                               name="email"
                               placeholder="{{ __('Enter your email') }}"
                               class="flex-grow px-4 py-2 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-800 text-white">
                        <button type="submit"
                                class="px-4 py-2 bg-purple-700 text-white rounded-r-lg hover:bg-purple-800 transition-colors duration-300">
                            {{ __('Subscribe') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm">
                © {{ date('Y') }} {{ setting('company_name', 'WebTools') }}. {{ __('All rights reserved.') }}
            </p>
            <div class="mt-4 md:mt-0">
                <select onchange="window.location.href=this.value" class="bg-gray-800 text-gray-400 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-purple-500">
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