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