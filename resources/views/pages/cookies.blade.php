<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-6">{{ __('Cookie Policy') }}</h1>

                    <div class="prose max-w-none">
                        <p class="mb-4">{{ __('Last updated: ') }} {{ date('F d, Y') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">1. {{ __('What Are Cookies') }}</h2>
                        <p class="mb-4">{{ __('Cookies are small text files that are stored on your computer or mobile device when you visit our website.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">2. {{ __('How We Use Cookies') }}</h2>
                        <p class="mb-4">{{ __('We use cookies for the following purposes:') }}</p>
                        <ul class="list-disc pl-6 mb-4">
                            <li class="mb-2">{{ __('Essential cookies for site functionality') }}</li>
                            <li class="mb-2">{{ __('Analytics cookies to understand usage') }}</li>
                            <li class="mb-2">{{ __('Preference cookies to remember your settings') }}</li>
                        </ul>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">3. {{ __('Types of Cookies We Use') }}</h2>
                        <div class="space-y-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">{{ __('Essential Cookies') }}</h3>
                                <p>{{ __('Required for basic site functionality and security.') }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">{{ __('Preference Cookies') }}</h3>
                                <p>{{ __('Remember your settings and preferences.') }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">{{ __('Analytics Cookies') }}</h3>
                                <p>{{ __('Help us understand how visitors interact with our website.') }}</p>
                            </div>
                        </div>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">4. {{ __('Managing Cookies') }}</h2>
                        <p class="mb-4">{{ __('You can control and/or delete cookies as you wish. You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">5. {{ __('Contact Us') }}</h2>
                        <p class="mb-4">{{ __('If you have questions about our use of cookies, please contact us.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
