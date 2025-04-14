<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 relative">
                <span class="relative z-10">Gestion des témoignages</span>
                <span class="absolute -bottom-1 left-0 w-1/3 h-1 bg-gradient-to-r from-purple-600 to-purple-400 rounded"></span>
            </h1>
            <a href="{{ route('admin.testimonials.create', ['locale' => app()->getLocale()]) }}" 
               class="bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 transform hover:scale-105 transition-all duration-300 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                <i class="fas fa-plus-circle mr-2 text-purple-200"></i>
                <span class="font-medium">Ajouter un témoignage</span>
            </a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-fadeIn">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2 text-xl"></i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm animate-fadeIn">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2 text-xl"></i>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif
        
        <!-- Grid view for testimonials -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @if(isset($testimonials) && $testimonials->count() > 0)
                @foreach($testimonials as $item)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden group animate-fadeIn">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-shrink-0 mr-4">
                                    @if(isset($item->avatar) && !empty($item->avatar))
                                        <img src="{{ Storage::url($item->avatar) }}" alt="{{ $item->name }}" 
                                            class="w-24 h-24 rounded-full object-cover border-4 border-purple-100 shadow-md">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center border-4 border-purple-100 shadow-md">
                                            <i class="fas fa-user text-gray-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <span class="{{ $item->is_active ? 'bg-green-500' : 'bg-gray-400' }} text-white text-xs px-2 py-1 rounded-full">
                                        {{ $item->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="font-bold text-xl text-gray-800 mb-1">{{ $item->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $item->position }}</p>
                            
                            <div class="mb-4 flex">
                                @for($i = 0; $i < $item->rating; $i++)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @endfor
                                @for($i = $item->rating; $i < 5; $i++)
                                    <i class="far fa-star text-yellow-400"></i>
                                @endfor
                            </div>
                            
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $item->content }}</p>
                            
                            <div class="border-t pt-4 flex justify-between">
                                <a href="{{ route('admin.testimonials.edit', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center group">
                                    <i class="fas fa-edit mr-1 transform group-hover:scale-110 transition-transform duration-200"></i>
                                    <span>Modifier</span>
                                </a>
                                
                                <form method="POST" action="{{ route('admin.testimonials.toggle-status', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $item->is_active ? 'text-green-600 hover:text-green-800' : 'text-gray-500 hover:text-gray-700' }} transition-colors duration-200 flex items-center group">
                                        <i class="fas {{ $item->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-1 transform group-hover:scale-110 transition-transform duration-200"></i>
                                        <span>{{ $item->is_active ? 'Activer' : 'Désactiver' }}</span>
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('admin.testimonials.destroy', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" 
                                      class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-200 flex items-center group">
                                        <i class="fas fa-trash-alt mr-1 transform group-hover:scale-110 transition-transform duration-200"></i>
                                        <span>Supprimer</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-3 bg-white rounded-xl p-12 text-center shadow-sm">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <i class="fas fa-comment-slash text-5xl mb-4 text-purple-200"></i>
                        <h3 class="text-xl font-medium mb-2">Aucun témoignage trouvé</h3>
                        <p class="text-gray-400 mb-6">Commencez par ajouter un nouveau témoignage</p>
                        <a href="{{ route('admin.testimonials.create', ['locale' => app()->getLocale()]) }}" 
                           class="bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-700 hover:to-purple-600 transform hover:scale-105 transition-all duration-300 text-white px-5 py-2.5 rounded-lg shadow-md">
                            <i class="fas fa-plus-circle mr-2"></i>
                            <span>Ajouter un témoignage</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Alternative table view (for admins who prefer the classic view) - temporairement masqué -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hidden">
            <div class="p-4 bg-gray-50 border-b">
                <h2 class="font-semibold text-gray-700">Vue tableau</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Témoignage</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($testimonials) && $testimonials->count() > 0)
                            @foreach($testimonials as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if(isset($item->avatar) && !empty($item->avatar))
                                                <img src="{{ Storage::url($item->avatar) }}" alt="{{ $item->name }}" 
                                                    class="w-10 h-10 rounded-full object-cover mr-3">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                                <div class="text-gray-500 text-sm">{{ $item->position }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            <span class="w-2 h-2 mr-1 rounded-full {{ $item->is_active ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                                            {{ $item->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex items-center space-x-4">
                                        <a href="{{ route('admin.testimonials.edit', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" 
                                        class="text-blue-600 hover:text-blue-900 transition-colors duration-200 flex items-center">
                                            <span class="bg-blue-100 p-1.5 rounded-lg text-blue-600 mr-1.5">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.testimonials.toggle-status', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $item->is_active ? 'text-green-600' : 'text-gray-500' }} hover:opacity-75 transition-opacity duration-200">
                                                <span class="{{ $item->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }} p-1.5 rounded-lg inline-block">
                                                    <i class="fas {{ $item->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </span>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('admin.testimonials.destroy', ['locale' => app()->getLocale(), 'testimonial' => $item->id]) }}" 
                                            class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                <span class="bg-red-100 p-1.5 rounded-lg text-red-600 inline-block">
                                                    <i class="fas fa-trash-alt"></i>
                                                </span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                    <i class="fas fa-comment-slash text-3xl mb-2 text-gray-300"></i>
                                    <p>Aucun témoignage trouvé</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Style pour les animations -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-admin-layout> 