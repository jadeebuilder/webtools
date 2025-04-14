<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Gestion des témoignages</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Liste des témoignages</h2>
                <a href="{{ route('admin.testimonials.create', ['locale' => app()->getLocale()]) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    Ajouter un témoignage
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
            
            <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($testimonials) && $testimonials->count() > 0)
                            @foreach($testimonials as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $item->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap flex items-center">
                                        <a href="{{ route('admin.testimonials.edit', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.testimonials.toggle-status', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" class="inline mr-3">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $item->is_active ? 'text-green-600 hover:text-green-900' : 'text-gray-500 hover:text-gray-700' }}">
                                                <i class="fas {{ $item->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-1"></i>
                                                {{ $item->is_active ? 'Actif' : 'Inactif' }}
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.testimonials.destroy', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                    Aucun témoignage trouvé
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout> 