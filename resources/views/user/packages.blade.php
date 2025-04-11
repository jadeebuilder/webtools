<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold text-purple-700 mb-6">{{ __('My Packages') }}</h1>
                    
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 mb-6">
                        <div class="flex items-center">
                            <span class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-box"></i>
                            </span>
                            <p class="text-gray-700">{{ __('Manage your tool packages and subscriptions.') }}</p>
                        </div>
                    </div>

                    <!-- Statut d'abonnement actuel -->
                    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Current Subscription') }}</h3>
                        
                        <div class="flex items-center justify-between border-b pb-4 mb-4">
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 mb-2">
                                    <i class="fas fa-star mr-1 text-yellow-500"></i>
                                    {{ __('Free Plan') }}
                                </span>
                                <h4 class="text-md font-medium">{{ __('Basic access to all tools') }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ __('Renewed on') }}: {{ now()->addMonths(1)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <a href="#" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-md hover:from-purple-600 hover:to-indigo-700 transition">
                                    <i class="fas fa-arrow-up mr-2"></i>
                                    {{ __('Upgrade Now') }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-3 border rounded-lg">
                                <p class="text-sm text-gray-500">{{ __('Tools Used') }}</p>
                                <p class="text-2xl font-semibold text-purple-600">12</p>
                            </div>
                            <div class="text-center p-3 border rounded-lg">
                                <p class="text-sm text-gray-500">{{ __('Usage Limit') }}</p>
                                <p class="text-2xl font-semibold text-purple-600">100 <span class="text-sm font-normal">/ {{ __('month') }}</span></p>
                            </div>
                            <div class="text-center p-3 border rounded-lg">
                                <p class="text-sm text-gray-500">{{ __('Current Usage') }}</p>
                                <p class="text-2xl font-semibold text-purple-600">23%</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Plans disponibles -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Available Plans') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Plan Gratuit -->
                        <div class="bg-white p-6 rounded-lg border-2 border-purple-200 shadow-sm relative">
                            <div class="absolute top-0 right-0 bg-purple-100 text-purple-800 rounded-bl-lg px-3 py-1 text-xs font-medium">{{ __('Current') }}</div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ __('Free Plan') }}</h4>
                            <p class="text-3xl font-bold text-purple-600 mb-4">{{ __('€0') }} <span class="text-sm font-normal text-gray-500">/ {{ __('month') }}</span></p>
                            
                            <ul class="space-y-2 mb-6">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('Access to all basic tools') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('100 uses per month') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('Standard support') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-times-circle text-red-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('No download options') }}</span>
                                </li>
                            </ul>
                            
                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">
                                {{ __('Current Plan') }}
                            </button>
                        </div>
                        
                        <!-- Plan Pro -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:border-purple-300 hover:shadow-md transition duration-300">
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ __('Pro Plan') }}</h4>
                            <p class="text-3xl font-bold text-purple-600 mb-4">{{ __('€9.99') }} <span class="text-sm font-normal text-gray-500">/ {{ __('month') }}</span></p>
                            
                            <ul class="space-y-2 mb-6">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('All Free Plan features') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('Unlimited tool usage') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('Priority email support') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('Download results') }}</span>
                                </li>
                            </ul>
                            
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                {{ __('Upgrade to Pro') }}
                            </button>
                        </div>
                        
                        <!-- Plan Business -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:border-purple-300 hover:shadow-md transition duration-300">
                            <div class="absolute top-0 right-0"></div>
                            <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ __('Business Plan') }}</h4>
                            <p class="text-3xl font-bold text-purple-600 mb-4">{{ __('€29.99') }} <span class="text-sm font-normal text-gray-500">/ {{ __('month') }}</span></p>
                            
                            <ul class="space-y-2 mb-6">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('All Pro Plan features') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('API access') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('Dedicated support') }}</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span class="text-sm text-gray-600">{{ __('White-label options') }}</span>
                                </li>
                            </ul>
                            
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-md hover:from-purple-600 hover:to-indigo-700 transition">
                                {{ __('Upgrade to Business') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 