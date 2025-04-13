<!-- En-tête -->
<div class="text-center mb-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ \App\Models\Setting::get('site_name', 'Online Web Tools') }}</h1>
    <p class="text-xl text-gray-600">
        {{ \App\Models\Setting::get('site_description', 'Instantly boost productivity with our free web tools. Fast, easy & straight to the point.') }}
    </p>
    <div class="mt-8">
        <a href="#tools" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-500 hover:bg-green-600 transition duration-150 ease-in-out">
            <i class="fas fa-tools mr-2"></i>
            {{ __('Use tools') }}
        </a>
    </div>
</div> 