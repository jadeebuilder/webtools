<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold text-purple-700 mb-6">{{ __('Activity History') }}</h1>
                    
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 mb-6">
                        <div class="flex items-center">
                            <span class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white mr-3">
                                <i class="fas fa-history"></i>
                            </span>
                            <p class="text-gray-700">{{ __('View your recent tool usage and account activity.') }}</p>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <label for="filter-type" class="block text-sm font-medium text-gray-700">{{ __('Activity Type') }}</label>
                                    <select id="filter-type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                        <option value="all">{{ __('All Activities') }}</option>
                                        <option value="tool">{{ __('Tool Usage') }}</option>
                                        <option value="account">{{ __('Account Changes') }}</option>
                                        <option value="login">{{ __('Login Activity') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="filter-date" class="block text-sm font-medium text-gray-700">{{ __('Date Range') }}</label>
                                    <select id="filter-date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                        <option value="7">{{ __('Last 7 days') }}</option>
                                        <option value="30" selected>{{ __('Last 30 days') }}</option>
                                        <option value="90">{{ __('Last 90 days') }}</option>
                                        <option value="all">{{ __('All time') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    <i class="fas fa-download mr-2"></i>
                                    {{ __('Export History') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau d'activité -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date & Time') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Activity') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Details') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('IP Address') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- Activité 1 -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ now()->subHours(2)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-3">
                                                    <i class="fas fa-tools"></i>
                                                </span>
                                                <span class="text-sm font-medium text-gray-900">{{ __('Tool Usage') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __('Used') }} <strong>{{ __('JSON Formatter') }}</strong>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            192.168.1.1
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Success') }}
                                            </span>
                                        </td>
                                    </tr>
                                    
                                    <!-- Activité 2 -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ now()->subHours(5)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 mr-3">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </span>
                                                <span class="text-sm font-medium text-gray-900">{{ __('Login') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __('Successful login from') }} Windows PC
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            192.168.1.1
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Success') }}
                                            </span>
                                        </td>
                                    </tr>
                                    
                                    <!-- Activité 3 -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ now()->subDays(1)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-500 mr-3">
                                                    <i class="fas fa-user-edit"></i>
                                                </span>
                                                <span class="text-sm font-medium text-gray-900">{{ __('Profile Update') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __('Updated profile information') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            192.168.1.1
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Success') }}
                                            </span>
                                        </td>
                                    </tr>
                                    
                                    <!-- Activité 4 -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ now()->subDays(2)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 mr-3">
                                                    <i class="fas fa-tools"></i>
                                                </span>
                                                <span class="text-sm font-medium text-gray-900">{{ __('Tool Usage') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __('Used') }} <strong>{{ __('Image Compressor') }}</strong>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            192.168.1.1
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('Success') }}
                                            </span>
                                        </td>
                                    </tr>
                                    
                                    <!-- Activité 5 -->
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ now()->subDays(3)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-500 mr-3">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </span>
                                                <span class="text-sm font-medium text-gray-900">{{ __('Failed Login') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ __('Failed login attempt') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            195.24.65.120
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ __('Failed') }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    {{ __('Showing') }} <span class="font-medium">1</span> {{ __('to') }} <span class="font-medium">5</span> {{ __('of') }} <span class="font-medium">24</span> {{ __('results') }}
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        {{ __('Previous') }}
                                    </button>
                                    <button type="button" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        {{ __('Next') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 