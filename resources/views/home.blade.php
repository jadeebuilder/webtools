<x-app-layout>
    <!-- Publicité avant la navigation -->
    @include('partials.ads.before-nav')
    
    <!-- Publicité après la navigation -->
    @include('partials.ads.after-nav')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row">
                <!-- Publicité barre latérale gauche -->
                @if(isset($adSettings['left_sidebar']) && $adSettings['left_sidebar']['active'])
                    <div class="lg:w-1/6 mb-4 lg:mb-0 lg:pr-4">
                        @include('partials.ads.left-sidebar')
                    </div>
                @endif
                
                <div class="flex-grow">
                    @include('partials.home.header')
                    @include('partials.home.popular-tools')
                    @include('partials.home.tool-categories')
                    @include('partials.home.testimonials')
                    @include('partials.home.packages')
                    @include('partials.home.faq')
                </div>
                
                <!-- Publicité barre latérale droite -->
                @if(isset($adSettings['right_sidebar']) && $adSettings['right_sidebar']['active'])
                    <div class="lg:w-1/6 mt-4 lg:mt-0 lg:pl-4">
                        @include('partials.ads.right-sidebar')
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Publicité avant le footer -->
    @include('partials.ads.before-footer')
</x-app-layout>

<!-- Publicité après le footer -->
@include('partials.ads.after-footer')
