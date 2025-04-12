<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.home.header')
            @include('partials.home.popular-tools')
            @include('partials.home.tool-categories')
            @include('partials.home.testimonials')
            @include('partials.home.packages')
            @include('partials.home.faq')
        </div>
    </div>
</x-app-layout>
