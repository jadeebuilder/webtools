<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Catégories de FAQ') }}</h1>
            <a href="{{ route('admin.faq_categories.create', ['locale' => app()->getLocale()]) }}" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors duration-300">
                <i class="fas fa-plus mr-2"></i>{{ __('Ajouter une catégorie') }}
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nom') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Slug') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Statut') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Ordre') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Traductions') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" x-data="{ dragOver: -1 }" @drop.prevent="$event.preventDefault()" @dragover.prevent="$event.preventDefault()">
                        @forelse($categories as $category)
                        <tr draggable="true" 
                            @dragstart="event.dataTransfer.setData('text/plain', {{ $category->id }})"
                            @dragenter="dragOver = {{ $loop->index }}"
                            @dragleave="dragOver = -1"
                            @drop="
                                dragOver = -1;
                                const id = event.dataTransfer.getData('text/plain');
                                if(id != {{ $category->id }}) {
                                    let form = document.getElementById('reorderForm');
                                    let src = document.createElement('input');
                                    src.type = 'hidden';
                                    src.name = 'source';
                                    src.value = id;
                                    let dest = document.createElement('input');
                                    dest.type = 'hidden';
                                    dest.name = 'destination';
                                    dest.value = {{ $category->id }};
                                    form.appendChild(src);
                                    form.appendChild(dest);
                                    form.submit();
                                }
                            "
                            :class="{ 'bg-gray-100': dragOver == {{ $loop->index }} }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? __('Actif') : __('Inactif') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $category->order }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-1">
                                    @foreach($category->translations as $translation)
                                        <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $translation->language->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.faq_categories.edit', ['locale' => app()->getLocale(), 'faq_category' => $category->id]) }}" class="text-primary hover:text-primary-dark mr-3">
                                    <i class="fas fa-edit"></i> {{ __('Éditer') }}
                                </a>
                                <form action="{{ route('admin.faq_categories.destroy', ['locale' => app()->getLocale(), 'faq_category' => $category->id]) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette catégorie ?') }}')">
                                        <i class="fas fa-trash"></i> {{ __('Supprimer') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ __('Aucune catégorie de FAQ trouvée.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4">
                {{ $categories->links() }}
            </div>
        </div>
        
        <form id="reorderForm" action="{{ route('admin.faq_categories.update-order', ['locale' => app()->getLocale()]) }}" method="POST" class="hidden">
            @csrf
            @method('PUT')
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Aucun JavaScript supplémentaire requis pour l'instant
            // Le glisser-déposer est géré par Alpine.js
        });
    </script>
</x-admin-layout> 