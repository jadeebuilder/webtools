<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-6">{{ __('Terms of Service') }}</h1>

                    <div class="prose max-w-none">
                        <p class="mb-4">{{ __('Last updated: ') }} {{ date('F d, Y') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">1. {{ __('Acceptance of Terms') }}</h2>
                        <p class="mb-4">{{ __('By accessing and using WebTools, you accept and agree to be bound by the terms and provision of this agreement.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">2. {{ __('Use License') }}</h2>
                        <ul class="list-disc pl-6 mb-4">
                            <li class="mb-2">{{ __('Personal and non-commercial use only for free accounts') }}</li>
                            <li class="mb-2">{{ __('No modification or copying of the service') }}</li>
                            <li class="mb-2">{{ __('No unauthorized use of the API') }}</li>
                        </ul>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">3. {{ __('Disclaimer') }}</h2>
                        <p class="mb-4">{{ __('The tools are provided "as is". We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties, including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">4. {{ __('Limitations') }}</h2>
                        <p class="mb-4">{{ __('In no event shall WebTools or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the tools.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">5. {{ __('Privacy') }}</h2>
                        <p class="mb-4">{{ __('Please review our Privacy Policy, which also governs your visit to our website, to understand our practices.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
