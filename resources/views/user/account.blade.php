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
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 