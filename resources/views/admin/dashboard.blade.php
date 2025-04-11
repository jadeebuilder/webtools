<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6 text-purple-800">{{ __('Administration Dashboard') }}</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Carte Statistiques -->
                        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-semibold">{{ __('Statistics') }}</h2>
                                <i class="fas fa-chart-bar text-2xl opacity-75"></i>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-indigo-200">{{ __('Total Users') }}</p>
                                    <p class="text-2xl font-bold">{{ App\Models\User::count() }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-200">{{ __('New Today') }}</p>
                                    <p class="text-2xl font-bold">{{ App\Models\User::whereDate('created_at', today())->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-200">{{ __('Newsletter Subscribers') }}</p>
                                    <p class="text-2xl font-bold">{{ App\Models\User::where('newsletter_subscribed', true)->count() }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-200">{{ __('Administrators') }}</p>
                                    <p class="text-2xl font-bold">{{ App\Models\User::where('is_admin', true)->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carte Actions Rapides -->
                        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-semibold text-gray-800">{{ __('Quick Actions') }}</h2>
                                <i class="fas fa-bolt text-2xl text-yellow-500"></i>
                            </div>
                            <div class="space-y-3">
                                <a href="#" class="block w-full py-2 px-4 bg-purple-100 text-purple-700 rounded-md hover:bg-purple-200 transition-colors flex items-center">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    {{ __('Add New User') }}
                                </a>
                                <a href="#" class="block w-full py-2 px-4 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors flex items-center">
                                    <i class="fas fa-tools mr-2"></i>
                                    {{ __('Manage Tools') }}
                                </a>
                                <a href="#" class="block w-full py-2 px-4 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    {{ __('Send Newsletter') }}
                                </a>
                                <a href="#" class="block w-full py-2 px-4 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors flex items-center">
                                    <i class="fas fa-cog mr-2"></i>
                                    {{ __('Site Settings') }}
                                </a>
                            </div>
                        </div>
                        
                        <!-- Carte Activité Récente -->
                        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-semibold text-gray-800">{{ __('Recent Activity') }}</h2>
                                <i class="fas fa-history text-2xl text-gray-500"></i>
                            </div>
                            <div class="divide-y divide-gray-200">
                                <div class="py-3">
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-user-check mr-2 text-green-500"></i>
                                        {{ __('New user registration') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ __('5 minutes ago') }}</p>
                                </div>
                                <div class="py-3">
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-tools mr-2 text-blue-500"></i>
                                        {{ __('Tool usage: Password Generator') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ __('15 minutes ago') }}</p>
                                </div>
                                <div class="py-3">
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-envelope mr-2 text-purple-500"></i>
                                        {{ __('Newsletter sent') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ __('1 hour ago') }}</p>
                                </div>
                            </div>
                            <a href="#" class="mt-4 text-sm text-purple-600 hover:text-purple-800 flex items-center">
                                {{ __('View all activity') }}
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Section Derniers Utilisateurs -->
                    <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">{{ __('Recent Users') }}</h2>
                            <a href="#" class="text-sm text-purple-600 hover:text-purple-800 flex items-center">
                                {{ __('View all users') }}
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Name') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Email') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Registered') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach (App\Models\User::latest()->take(5)->get() as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-400 to-indigo-500 flex items-center justify-center text-white overflow-hidden">
                                                        <span class="font-bold">{{ substr($user->firstname, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($user->is_admin)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                {{ __('Admin') }}
                                            </span>
                                            @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ __('User') }}
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Edit') }}</a>
                                            <a href="#" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 