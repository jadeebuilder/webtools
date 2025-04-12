<x-app-layout :pageTitle="$pageTitle" :metaDescription="$metaDescription">
    <!-- Publicité avant le titre de l'outil -->
    @include('partials.ads.before-tool-title')

    <x-slot name="header">
        <div class="flex flex-col items-center">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">{{ $customH1 }}</h1>
            <p class="text-center text-gray-600 max-w-3xl">{{ $customDescription }}</p>
        </div>
    </x-slot>

    <!-- Publicité après la description de l'outil -->
    @include('partials.ads.after-tool-description')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row">
                <!-- Publicité à gauche -->
                @if(isset($adSettings['left_sidebar']) && $adSettings['left_sidebar']['active'])
                    <div class="lg:w-1/6 mb-4 lg:mb-0 lg:pr-4">
                        @include('partials.ads.left-sidebar')
                    </div>
                @endif

                <div class="flex-grow">
                    <!-- Publicité avant l'outil -->
                    @include('partials.ads.before-tool')

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            {{ $slot }}
                        </div>
                    </div>

                    <!-- Publicité après l'outil -->
                    @include('partials.ads.after-tool')

                    <!-- Publicité en bas de l'outil -->
                    @include('partials.ads.bottom-tool')
                </div>

                <!-- Publicité à droite -->
                @if(isset($adSettings['right_sidebar']) && $adSettings['right_sidebar']['active'])
                    <div class="lg:w-1/6 mt-4 lg:mt-0 lg:pl-4">
                        @include('partials.ads.right-sidebar')
                    </div>
                @endif
            </div>

            <!-- Sections supplémentaires configurées pour cet outil -->
            @if(isset($additionalSections) && !empty($additionalSections))
                <div class="mt-8">
                    {!! $additionalSections !!}
                </div>
            @endif
        </div>
    </div>

    <!-- Publicité avant le footer -->
    @include('partials.ads.before-footer')
</x-app-layout>

<!-- Publicité après le footer -->
@include('partials.ads.after-footer')