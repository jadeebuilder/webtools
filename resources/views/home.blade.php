<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- En-tête -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('Online Web Tools') }}</h1>
                <p class="text-xl text-gray-600">
                    {{ __('Instantly boost productivity with our') }} <span class="text-green-500">1,889</span> {{ __('free web tools.') }}
                    {{ __('Fast, easy & straight to the point.') }}
                </p>
                <div class="mt-8">
                    <a href="#tools" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-500 hover:bg-green-600 transition duration-150 ease-in-out">
                        <i class="fas fa-tools mr-2"></i>
                        {{ __('Use tools') }}
                    </a>
                </div>
            </div>

            <!-- Popular Tools Section -->
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">{{ __('Popular Tools') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- IP Lookup -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-search text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-900">{{ __('IP Lookup') }}</h3>
                                    <span class="text-sm text-gray-500">{{ __('3 uses today') }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">{{ __('Get approximate IP details and location information.') }}</p>
                        </div>
                    </div>

                    <!-- DNS Lookup -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-network-wired text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-900">{{ __('DNS Lookup') }}</h3>
                                    <span class="text-sm text-gray-500">{{ __('5 uses today') }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">{{ __('Find A, AAAA, CNAME, MX, NS, TXT, SOA DNS records.') }}</p>
                        </div>
                    </div>

                    <!-- HTML Minifier -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-code text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-900">{{ __('HTML Minifier') }}</h3>
                                    <span class="text-sm text-gray-500">{{ __('8 uses today') }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">{{ __('Minify your HTML by removing all unnecessary characters.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catégories d'outils -->
            <div id="tools" class="space-y-4 mb-16">
                <!-- Checker Tools -->
                <a href="{{ URL::localizedRoute('tools.checker') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 text-purple-600">
                            <i class="fas fa-check-circle text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Checker tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of great checker-type tools to help you check & verify different types of things.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Text Tools -->
                <a href="{{ URL::localizedRoute('tools.text') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-gray-100 text-gray-600">
                            <i class="fas fa-font text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Text tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of text content related tools to help you create, modify & improve text type of content.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Converter Tools -->
                <a href="{{ URL::localizedRoute('tools.converter') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                            <i class="fas fa-exchange-alt text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Converter tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of tools that help you easily convert data.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Generator Tools -->
                <a href="{{ URL::localizedRoute('tools.generator') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                            <i class="fas fa-magic text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Generator tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of the most useful generator tools that you can generate data with.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Developer Tools -->
                <a href="{{ URL::localizedRoute('tools.developer') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-indigo-100 text-indigo-600">
                            <i class="fas fa-code text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Developer tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of highly useful tools mainly for developers and not only.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Image Tools -->
                <a href="{{ URL::localizedRoute('tools.image') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-orange-100 text-orange-600">
                            <i class="fas fa-image text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Image manipulation tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of tools that help modify & convert image files.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Unit Converter -->
                <a href="{{ URL::localizedRoute('tools.unit') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-pink-100 text-pink-600">
                            <i class="fas fa-ruler text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Unit converter tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of the most popular and useful tools that help you easily convert day-to-day data.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Time Converter -->
                <a href="{{ URL::localizedRoute('tools.time') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-teal-100 text-teal-600">
                            <i class="fas fa-clock text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Time converter tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of date & time conversion related tools.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Data Converter -->
                <a href="{{ URL::localizedRoute('tools.data') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-green-200 text-green-800">
                            <i class="fas fa-database text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Data converter tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of computer data & sizing converter tools.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Color Converter -->
                <a href="{{ URL::localizedRoute('tools.color') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                            <i class="fas fa-palette text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Color converter tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of tools that help convert colors between HEX, RGBA, RGB, HSLA, HSL, etc., and HSLA formats.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>

                <!-- Misc Tools -->
                <a href="{{ URL::localizedRoute('tools.misc') }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
                    <div class="p-6 flex items-center">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 text-purple-600">
                            <i class="fas fa-tools text-xl"></i>
                        </span>
                        <div class="ml-4 flex-grow">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Misc tools') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('A collection of other random, but great & useful tools.') }}</p>
                        </div>
                        <div class="ml-4">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Testimonials Section -->
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{ __('What People Say') }}</h2>
                <p class="text-gray-600 text-center mb-8">{{ __('Trusted by thousands of developers worldwide') }}</p>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center mb-4">
                            <img src="https://api.uifaces.co/our-content/donated/xZ4wg2Xj.jpg" alt="User" class="w-12 h-12 rounded-full">
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">{{ __('Sarah Johnson') }}</h4>
                                <p class="text-sm text-gray-500">{{ __('Frontend Developer') }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600">{{ __('"These tools have become an essential part of my development workflow. They\'re intuitive and save me hours of work."') }}</p>
                        <div class="mt-4 flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center mb-4">
                            <img src="https://api.uifaces.co/our-content/donated/FJkauyEa.jpg" alt="User" class="w-12 h-12 rounded-full">
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">{{ __('Michael Chen') }}</h4>
                                <p class="text-sm text-gray-500">{{ __('Full Stack Developer') }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600">{{ __('"The variety and quality of tools available is impressive. Everything I need is just a click away."') }}</p>
                        <div class="mt-4 flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center mb-4">
                            <img src="https://api.uifaces.co/our-content/donated/n4Ngwvi7.jpg" alt="User" class="w-12 h-12 rounded-full">
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900">{{ __('Emily Rodriguez') }}</h4>
                                <p class="text-sm text-gray-500">{{ __('UX Designer') }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600">{{ __('"Clean interface, powerful features, and regular updates. It\'s exactly what I needed for my projects."') }}</p>
                        <div class="mt-4 flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Section -->
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{ __('Nos Forfaits') }}</h2>
                <p class="text-gray-600 text-center mb-8">{{ __('Choisissez le forfait qui correspond à vos besoins') }}</p>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Guest Plan -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group flex flex-col h-full">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gray-400"></div>
                        <div class="text-center mb-8">
                            <h3 class="text-xl text-gray-500 uppercase mb-2 tracking-wider">{{ __('GUEST') }}</h3>
                            <div class="text-4xl font-bold text-gray-800">{{ __('Gratuit') }}</div>
                            <div class="mt-4 text-sm text-gray-500">{{ __('Pour démarrer') }}</div>
                        </div>
                        <div class="space-y-4 flex-grow">
                            <!-- Checker Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <span class="text-sm font-semibold text-purple-600">17</span>
                                </div>
                                <span class="flex-grow">{{ __('Checker tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Text Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                    <span class="text-sm font-semibold text-gray-600">19</span>
                                </div>
                                <span class="flex-grow">{{ __('Text tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Converter Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <span class="text-sm font-semibold text-green-600">14</span>
                                </div>
                                <span class="flex-grow">{{ __('Converter tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Generator Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="text-sm font-semibold text-blue-600">31</span>
                                </div>
                                <span class="flex-grow">{{ __('Generator tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Developer Tools -->
                            <div class="flex items-center opacity-50">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-sm text-indigo-400"></i>
                                </div>
                                <span class="flex-grow">{{ __('Developer tools') }}</span>
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                            <!-- Image Tools -->
                            <div class="flex items-center opacity-50">
                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-sm text-orange-400"></i>
                                </div>
                                <span class="flex-grow">{{ __('Image tools') }}</span>
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                            <!-- Unit Converter -->
                            <div class="flex items-center opacity-50">
                                <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-sm text-pink-400"></i>
                                </div>
                                <span class="flex-grow">{{ __('Unit converter') }}</span>
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                            <!-- Time Converter -->
                            <div class="flex items-center opacity-50">
                                <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-sm text-teal-400"></i>
                                </div>
                                <span class="flex-grow">{{ __('Time converter') }}</span>
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                            <!-- Data Converter -->
                            <div class="flex items-center opacity-50">
                                <div class="w-8 h-8 rounded-full bg-green-200 flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-sm text-green-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Data converter') }}</span>
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                            <!-- Color Converter -->
                            <div class="flex items-center opacity-50">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-sm text-green-400"></i>
                                </div>
                                <span class="flex-grow">{{ __('Color converter') }}</span>
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                            <!-- Misc Tools -->
                            <div class="flex items-center opacity-50">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-sm text-purple-400"></i>
                                </div>
                                <span class="flex-grow">{{ __('Misc tools') }}</span>
                                <i class="fas fa-times text-red-500"></i>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-center opacity-50">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-lock text-sm text-gray-400"></i>
                                    </div>
                                    <span class="flex-grow">{{ __('API access') }}</span>
                                    <i class="fas fa-times text-red-500"></i>
                                </div>
                                <div class="flex items-center mt-4 opacity-50">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-lock text-sm text-gray-400"></i>
                                    </div>
                                    <span class="flex-grow">{{ __('No ads') }}</span>
                                    <i class="fas fa-times text-red-500"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button class="w-full py-3 px-4 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transform hover:scale-105 transition-all duration-200">
                                {{ __('Commencer gratuitement') }}
                            </button>
                        </div>
                    </div>

                    <!-- Basic Plan -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group scale-105 flex flex-col h-full">
                        <div class="absolute top-0 left-0 w-full h-2 bg-blue-500"></div>
                        <div class="absolute -right-12 -top-12 w-24 h-24 bg-blue-500 transform rotate-45"></div>
                        <div class="absolute right-4 top-4 text-xs text-white font-bold tracking-wider">
                            POPULAIRE
                        </div>
                        <div class="text-center mb-8">
                            <h3 class="text-xl text-blue-500 uppercase mb-2 tracking-wider">{{ __('BASIC') }}</h3>
                            <div class="text-4xl font-bold text-gray-800">9.99€<span class="text-base font-normal text-gray-600">{{ __('/mois') }}</span></div>
                            <div class="mt-4 text-sm text-gray-500">{{ __('Pour les professionnels') }}</div>
                        </div>
                        <div class="space-y-4 flex-grow">
                            <!-- Checker Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-purple-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Checker tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Text Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-gray-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Text tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Converter Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-green-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Converter tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Generator Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-blue-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Generator tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Developer Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-indigo-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Developer tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Image Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-orange-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Image tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Unit Converter -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-pink-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Unit converter') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Time Converter -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-teal-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Time converter') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Data Converter -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-green-200 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-green-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Data converter') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Color Converter -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-green-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Color converter') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <!-- Misc Tools -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-purple-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Misc tools') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-sm text-blue-600"></i>
                                    </div>
                                    <span class="flex-grow">{{ __('API access') }}</span>
                                    <i class="fas fa-check text-green-500"></i>
                                </div>
                                <div class="flex items-center mt-4 group-hover:scale-105 transition-transform duration-200">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-sm text-blue-600"></i>
                                    </div>
                                    <span class="flex-grow">{{ __('No ads') }}</span>
                                    <i class="fas fa-check text-green-500"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button class="w-full py-3 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transform hover:scale-105 transition-all duration-200">
                                {{ __('Commencer l\'essai') }}
                            </button>
                        </div>
                    </div>

                    <!-- VIP Plan -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 transform hover:-translate-y-1 transition-all duration-300 hover:shadow-xl relative overflow-hidden group flex flex-col h-full">
                        <div class="absolute top-0 left-0 w-full h-2 bg-purple-500"></div>
                        <div class="text-center mb-8">
                            <h3 class="text-xl text-purple-500 uppercase mb-2 tracking-wider">{{ __('VIP') }}</h3>
                            <div class="text-4xl font-bold text-gray-800">29.99€<span class="text-base font-normal text-gray-600">{{ __('/mois') }}</span></div>
                            <div class="mt-4 text-sm text-gray-500">{{ __('Pour les entreprises') }}</div>
                        </div>
                        <div class="space-y-4 flex-grow">
                            <!-- Toutes les catégories avec accès illimité -->
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-purple-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Tous les outils') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-star text-sm text-purple-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Support prioritaire') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-infinity text-sm text-purple-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('API illimité') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-sm text-purple-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Sans publicité') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <div class="flex items-center group-hover:scale-105 transition-transform duration-200">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-sm text-purple-600"></i>
                                </div>
                                <span class="flex-grow">{{ __('Accès multi-utilisateurs') }}</span>
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <button class="w-full py-3 px-4 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transform hover:scale-105 transition-all duration-200">
                                {{ __('Contacter les ventes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{ __('Frequently Asked Questions') }}</h2>
                <p class="text-gray-600 text-center mb-8">{{ __('Got questions? We\'ve got answers.') }}</p>

                <div class="space-y-6 w-full" x-data="{ selected: null }">
                    <!-- FAQ Item 1 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <button @click="selected !== 1 ? selected = 1 : selected = null"
                                class="flex justify-between items-center w-full p-6 text-left transition-colors duration-300 hover:bg-gray-50">
                            <span class="font-medium text-gray-900 text-lg">{{ __('Are all tools free to use?') }}</span>
                            <div class="ml-4 flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 group-hover:bg-gray-200 transition-colors duration-300">
                                <i class="fas transition-transform duration-300" :class="selected === 1 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </div>
                        </button>
                        <div x-show="selected === 1"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="p-6 pt-0 text-gray-600">
                            {{ __('Yes, most of our tools are free to use. However, some advanced features require a Pro subscription.') }}
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <button @click="selected !== 2 ? selected = 2 : selected = null"
                                class="flex justify-between items-center w-full p-6 text-left transition-colors duration-300 hover:bg-gray-50">
                            <span class="font-medium text-gray-900 text-lg">{{ __('Do you offer API access?') }}</span>
                            <div class="ml-4 flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 group-hover:bg-gray-200 transition-colors duration-300">
                                <i class="fas transition-transform duration-300" :class="selected === 2 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </div>
                        </button>
                        <div x-show="selected === 2"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="p-6 pt-0 text-gray-600">
                            {{ __('Yes, API access is available with our Pro subscription. You can integrate our tools directly into your applications.') }}
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <button @click="selected !== 3 ? selected = 3 : selected = null"
                                class="flex justify-between items-center w-full p-6 text-left transition-colors duration-300 hover:bg-gray-50">
                            <span class="font-medium text-gray-900 text-lg">{{ __('How often are new tools added?') }}</span>
                            <div class="ml-4 flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 group-hover:bg-gray-200 transition-colors duration-300">
                                <i class="fas transition-transform duration-300" :class="selected === 3 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </div>
                        </button>
                        <div x-show="selected === 3"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="p-6 pt-0 text-gray-600">
                            {{ __('We regularly add new tools and update existing ones. On average, we add 2-3 new tools every month.') }}
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <button @click="selected !== 4 ? selected = 4 : selected = null"
                                class="flex justify-between items-center w-full p-6 text-left transition-colors duration-300 hover:bg-gray-50">
                            <span class="font-medium text-gray-900 text-lg">{{ __('Is my data secure?') }}</span>
                            <div class="ml-4 flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 group-hover:bg-gray-200 transition-colors duration-300">
                                <i class="fas transition-transform duration-300" :class="selected === 4 ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </div>
                        </button>
                        <div x-show="selected === 4"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="p-6 pt-0 text-gray-600">
                            {{ __('Yes, we take data security seriously. We don\'t store any of your processed data, and all operations are performed client-side when possible.') }}
                        </div>
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
                                <img src="/images/logo.svg" alt="WebTools Logo" class="h-8 w-auto">
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
    </div>
</x-app-layout>
