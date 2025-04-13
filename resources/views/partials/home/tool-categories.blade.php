<!-- Catégories d'outils -->
<div id="tools" class="space-y-4 mb-16">
    <!-- Checker Tools -->
    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'checker']) }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
        <div class="p-6 flex items-center">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 text-purple-600">
                <i class="fas fa-check-circle text-xl"></i>
            </span>
            <div class="ml-4 flex-grow">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Checker tools') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('A collection of great checker-type tools to help you check & verify different types of things.') }}</p>
            </div>
            <div class="ml-4">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </a>

    <!-- Text Tools -->
    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'text']) }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
        <div class="p-6 flex items-center">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-gray-100 text-gray-600">
                <i class="fas fa-font text-xl"></i>
            </span>
            <div class="ml-4 flex-grow">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Text tools') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('A collection of text content related tools to help you create, modify & improve text type of content.') }}</p>
            </div>
            <div class="ml-4">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </a>

    <!-- Converter Tools -->
    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'converter']) }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
        <div class="p-6 flex items-center">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                <i class="fas fa-exchange-alt text-xl"></i>
            </span>
            <div class="ml-4 flex-grow">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Converter tools') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('A collection of tools that help you easily convert data.') }}</p>
            </div>
            <div class="ml-4">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </a>

    <!-- Generator Tools -->
    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'generator']) }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
        <div class="p-6 flex items-center">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                <i class="fas fa-magic text-xl"></i>
            </span>
            <div class="ml-4 flex-grow">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Generator tools') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('A collection of the most useful generator tools that you can generate data with.') }}</p>
            </div>
            <div class="ml-4">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </a>

    <!-- Developer Tools -->
    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'developer']) }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
        <div class="p-6 flex items-center">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-indigo-100 text-indigo-600">
                <i class="fas fa-code text-xl"></i>
            </span>
            <div class="ml-4 flex-grow">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Developer tools') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('A collection of highly useful tools mainly for developers and not only.') }}</p>
            </div>
            <div class="ml-4">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </a>

    <!-- Image Tools -->
    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'image']) }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
        <div class="p-6 flex items-center">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-orange-100 text-orange-600">
                <i class="fas fa-image text-xl"></i>
            </span>
            <div class="ml-4 flex-grow">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Image manipulation tools') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('A collection of tools that help modify & convert image files.') }}</p>
            </div>
            <div class="ml-4">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </a>

    <!-- Unit Converter -->
    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'unit']) }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
        <div class="p-6 flex items-center">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-pink-100 text-pink-600">
                <i class="fas fa-ruler text-xl"></i>
            </span>
            <div class="ml-4 flex-grow">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Unit converter tools') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('A collection of the most popular and useful tools that help you easily convert day-to-day data.') }}</p>
            </div>
            <div class="ml-4">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </a>

    <!-- Time Converter -->
    <a href="{{ URL::localizedRoute('tools.category', ['slug' => 'time']) }}" class="block bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-all duration-200 transform hover:-translate-y-1">
        <div class="p-6 flex items-center">
            <span class="inline-flex items-center justify-center h-12 w-12 rounded-md bg-teal-100 text-teal-600">
                <i class="fas fa-clock text-xl"></i>
            </span>
            <div class="ml-4 flex-grow">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Time converter tools') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('A collection of date & time conversion related tools.') }}</p>
            </div>
            <div class="ml-4">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        </div>
    </a>

    <!-- Et les autres catégories... -->
</div> 