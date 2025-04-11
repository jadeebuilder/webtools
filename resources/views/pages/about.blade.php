<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-6">{{ __('About Us') }}</h1>

                    <div class="prose max-w-none">
                        <p class="mb-4">{{ __('Welcome to WebTools, your comprehensive platform for web development tools and utilities.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">{{ __('Our Mission') }}</h2>
                        <p class="mb-4">{{ __('Our mission is to provide developers with reliable, efficient, and easy-to-use tools that streamline their workflow and enhance productivity.') }}</p>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">{{ __('What We Offer') }}</h2>
                        <ul class="list-disc pl-6 mb-4">
                            <li class="mb-2">{{ __('Over 1,800 development tools') }}</li>
                            <li class="mb-2">{{ __('Regular updates and new features') }}</li>
                            <li class="mb-2">{{ __('Secure and reliable service') }}</li>
                            <li class="mb-2">{{ __('User-friendly interface') }}</li>
                            <li class="mb-2">{{ __('Professional support') }}</li>
                        </ul>

                        <h2 class="text-2xl font-semibold mt-8 mb-4">{{ __('Our Values') }}</h2>
                        <div class="grid md:grid-cols-3 gap-6 mt-6">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">{{ __('Quality') }}</h3>
                                <p>{{ __('We maintain high standards in all our tools and services.') }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">{{ __('Innovation') }}</h3>
                                <p>{{ __('We constantly evolve and adapt to new technologies.') }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">{{ __('User Focus') }}</h3>
                                <p>{{ __('We prioritize user experience and satisfaction.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
