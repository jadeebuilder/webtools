<!-- Testimonials Section -->
<div class="mb-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">{{ __('What People Say') }}</h2>
    <p class="text-gray-600 text-center mb-8">{{ __('Trusted by thousands of developers worldwide') }}</p>

    <div class="grid md:grid-cols-3 gap-8">
        @php
            try {
                // Utiliser DB::table pour récupérer les témoignages actifs
                $testimonials = DB::table('testimonials')
                    ->where('is_active', 1)
                    ->orderBy('order')
                    ->limit(3)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Erreur lors du chargement des témoignages: ' . $e->getMessage());
                $testimonials = collect();
            }
        @endphp
        
        @if($testimonials && $testimonials->count() > 0)
            @foreach($testimonials as $testimonial)
                <!-- Testimonial -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center mb-4">
                        @if($testimonial->avatar)
                            <img src="{{ Storage::url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        @endif
                        <div class="ml-4">
                            <h4 class="font-medium text-gray-900">{{ $testimonial->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $testimonial->position }}</p>
                        </div>
                    </div>
                    <p class="text-gray-600">{{ __('"') }}{{ $testimonial->content }}{{ __('"') }}</p>
                    <div class="mt-4 flex text-yellow-400">
                        @for($i = 0; $i < $testimonial->rating; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                        @for($i = $testimonial->rating; $i < 5; $i++)
                            <i class="far fa-star"></i>
                        @endfor
                    </div>
                </div>
            @endforeach
        @else
            <!-- Aucun témoignage trouvé, afficher un message -->
            <div class="col-span-3 text-center py-10">
                <p class="text-gray-500">{{ __('No testimonials available at the moment.') }}</p>
            </div>
        @endif
    </div>
</div> 