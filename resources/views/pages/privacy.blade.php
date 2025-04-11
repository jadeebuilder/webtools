<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-6">{{ __('Privacy Policy') }}</h1>

                    <div class="prose max-w-none">
                        <p class="mb-4">{{ __('Last updated: ') }} {{ date('F d, Y') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">1. {{ __('Information We Collect') }}</h2>
                        <p class="mb-4">{{ __('We collect information that you provide directly to us, including:') }}</p>
                        <ul class="list-disc pl-6 mb-4">
                            <li class="mb-2">{{ __('Account information (email, name)') }}</li>
                            <li class="mb-2">{{ __('Usage data') }}</li>
                            <li class="mb-2">{{ __('Tool preferences') }}</li>
                        </ul>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">2. {{ __('How We Use Your Information') }}</h2>
                        <p class="mb-4">{{ __('We use the information we collect to:') }}</p>
                        <ul class="list-disc pl-6 mb-4">
                            <li class="mb-2">{{ __('Provide and maintain our services') }}</li>
                            <li class="mb-2">{{ __('Improve user experience') }}</li>
                            <li class="mb-2">{{ __('Send important notifications') }}</li>
                        </ul>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">3. {{ __('Data Security') }}</h2>
                        <p class="mb-4">{{ __('We implement appropriate security measures to protect your personal information. All operations are performed client-side when possible.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">4. {{ __('Third-Party Services') }}</h2>
                        <p class="mb-4">{{ __('We may use third-party services that collect, monitor and analyze this type of information in order to increase our Service's functionality.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">5. {{ __('Contact Us') }}</h2>
                        <p class="mb-4">{{ __('If you have any questions about this Privacy Policy, please contact us.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
