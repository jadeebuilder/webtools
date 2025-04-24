<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold text-purple-700 mb-6">{{ __('My Account') }}</h1>
                    
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 mb-6">
                        <div class="flex items-center">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-400 to-indigo-500 flex items-center justify-center text-white overflow-hidden border-2 border-white shadow-md mr-4">
                                @if (Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-bold text-2xl">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
                                <p class="text-gray-600">{{ Auth::user()->email }}</p>
                                <p class="text-sm text-gray-500">{{ __('Member since') }}: {{ Auth::user()->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Personal Information') }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded-md">{{ Auth::user()->name }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded-md">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ URL::localizedRoute('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                    <i class="fas fa-edit mr-2"></i>
                                    {{ __('Edit Profile') }}
                                </a>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Account Status') }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ __('Active') }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Account Type') }}</label>
                                    <div class="mt-1">
                                        @if(Auth::user()->isAdmin())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-crown mr-1 text-yellow-500"></i>
                                                {{ __('Administrator') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ __('Standard User') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur la souscription active -->
                    @if($subscription)
                    <div class="mt-6 bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Current Subscription') }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Package') }}</label>
                                    <div class="mt-1 p-2 flex items-center bg-purple-50 rounded-md">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-600 text-white mr-3">
                                            <i class="fas fa-box"></i>
                                        </span>
                                        <span class="font-medium">{{ $package ? $package->getName() : __('N/A') }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                                    <div class="mt-1">
                                        @if($subscription->onTrial())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-flask mr-1"></i>
                                                {{ __('Trial Period') }}
                                            </span>
                                        @elseif($subscription->isActive())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                {{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                {{ $subscription->getStatus() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Cycle') }}</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded-md">
                                        @if($subscription->cycle == 'monthly')
                                            <i class="far fa-calendar-alt mr-2 text-purple-600"></i> {{ __('Monthly') }}
                                        @elseif($subscription->cycle == 'annual')
                                            <i class="far fa-calendar-alt mr-2 text-purple-600"></i> {{ __('Annual') }}
                                        @elseif($subscription->cycle == 'lifetime')
                                            <i class="fas fa-infinity mr-2 text-purple-600"></i> {{ __('Lifetime') }}
                                        @else
                                            {{ $subscription->cycle }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded-md">
                                        <i class="far fa-calendar-plus mr-2 text-purple-600"></i>
                                        {{ $subscription->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                
                                @if($subscription->onTrial())
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Trial End Date') }}</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded-md">
                                        <i class="far fa-calendar-times mr-2 text-purple-600"></i>
                                        {{ $subscription->trial_ends_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                @endif
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Expiration Date') }}</label>
                                    <div class="mt-1 p-2 bg-gray-50 rounded-md">
                                        <i class="far fa-calendar-times mr-2 text-purple-600"></i>
                                        {{ $subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : __('N/A') }}
                                    </div>
                                </div>
                                
                                @if($subscription->onTrial() || ($subscription->ends_at && $subscription->ends_at->isFuture()))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Time Remaining') }}</label>
                                    <div class="mt-1">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                            @php
                                                $dateToUse = $subscription->onTrial() ? $subscription->trial_ends_at : $subscription->ends_at;
                                                $totalDays = $subscription->onTrial() ? $package->trial_days : 30; // Remplacez 30 par la durée réelle du cycle
                                                $daysRemaining = max(0, Carbon\Carbon::now()->diffInDays($dateToUse, false));
                                                $percentage = min(100, round(($daysRemaining / $totalDays) * 100));
                                            @endphp
                                            <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">
                                            @if($daysRemaining > 0)
                                                {{ $daysRemaining }} {{ __('days remaining') }}
                                            @else
                                                {{ __('Expires today') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <a href="{{ URL::localizedRoute('user.packages') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                <i class="fas fa-box-open mr-2"></i>
                                {{ __('Manage Subscription') }}
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="mt-6 bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Subscription') }}</h3>
                        <div class="text-center py-6">
                            <div class="text-purple-500 mb-4">
                                <i class="fas fa-box-open text-5xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">{{ __('No active subscription') }}</h4>
                            <p class="text-gray-500 mb-4">{{ __('Explore our packages to access premium features.') }}</p>
                            <a href="{{ URL::localizedRoute('packages') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                <i class="fas fa-boxes mr-2"></i>
                                {{ __('View Packages') }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 