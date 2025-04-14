<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Modération des témoignages</h1>
        
        <div class="mb-4">
            <a href="{{ route('admin.testimonials.index', ['locale' => app()->getLocale()]) }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">
                Retour à la liste
            </a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @if(isset($testimonials))
                @forelse($testimonials as $testimonial)
                    <div class="bg-white p-4 rounded shadow">
                        <h3 class="font-bold">{{ $testimonial->name ?? 'Sans nom' }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $testimonial->position ?? '' }}</p>
                        <div class="mb-4">
                            <p>{{ $testimonial->content ?? '' }}</p>
                        </div>
                        <div class="flex justify-between mt-4">
                            <a href="{{ route('admin.testimonials.edit', ['locale' => app()->getLocale(), 'testimonial' => $testimonial->id]) }}" class="text-blue-600 hover:text-blue-800">Modifier</a>
                            <a href="#" onclick="if(confirm('Êtes-vous sûr?')){document.getElementById('delete-form-{{ $testimonial->id }}').submit();}" class="text-red-600 hover:text-red-800">Supprimer</a>
                            <form id="delete-form-{{ $testimonial->id }}" action="{{ route('admin.testimonials.destroy', ['locale' => app()->getLocale(), 'testimonial' => $testimonial->id]) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white p-6 rounded shadow text-center">
                        Aucun témoignage trouvé
                    </div>
                @endforelse
            @else
                <div class="col-span-3 bg-white p-6 rounded shadow text-center">
                    Données non disponibles
                </div>
            @endif
        </div>
    </div>
</x-admin-layout> 