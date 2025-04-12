<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des sections de template') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Ajouter une nouvelle section') }}</h3>
                        
                        <form method="POST" action="{{ route('admin.tools.template.store-section', ['locale' => app()->getLocale()]) }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nom') }}</label>
                                    <input type="text" name="name" id="name" required 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50"
                                        value="{{ old('name') }}">
                                </div>
                                
                                <div>
                                    <label for="partial_path" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Chemin du partial') }}</label>
                                    <input type="text" name="partial_path" id="partial_path" required 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50"
                                        value="{{ old('partial_path') }}" placeholder="partials.home.section-name">
                                    <p class="text-xs text-gray-500 mt-1">{{ __('Format: partials.home.nom-section') }}</p>
                                </div>
                                
                                <div>
                                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Icône SVG Path') }}</label>
                                    <input type="text" name="icon" id="icon" required 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50"
                                        value="{{ old('icon') }}" placeholder="M12 ...">
                                    <p class="text-xs text-gray-500 mt-1">{{ __('Format SVG path data (M12...)') }}</p>
                                </div>
                                
                                <div>
                                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ordre d\'affichage') }}</label>
                                    <input type="number" name="order" id="order" min="0" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50"
                                        value="{{ old('order', 0) }}">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                                <textarea name="description" id="description" rows="3" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            </div>
                            
                            <div>
                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                                    {{ __('Ajouter la section') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <hr class="my-8">
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Sections disponibles') }}</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Nom') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Chemin du partial') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Ordre') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Statut') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($sections as $section)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section->icon }}"></path>
                                                    </svg>
                                                    <div class="text-sm font-medium text-gray-900">{{ $section->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $section->partial_path }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $section->order }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $section->is_active ? __('Actif') : __('Inactif') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button class="edit-section text-purple-600 hover:text-purple-900" data-id="{{ $section->id }}">
                                                    {{ __('Modifier') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'édition -->
    <div id="edit-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Modifier la section') }}</h3>
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="edit-form" method="POST" action="#">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nom') }}</label>
                        <input type="text" name="name" id="edit-name" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-partial_path" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Chemin du partial') }}</label>
                        <input type="text" name="partial_path" id="edit-partial_path" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-icon" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Icône SVG Path') }}</label>
                        <input type="text" name="icon" id="edit-icon" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                        <textarea name="description" id="edit-description" rows="3" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-order" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ordre d\'affichage') }}</label>
                        <input type="number" name="order" id="edit-order" min="0" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" id="edit-is_active" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Actif') }}</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="close-modal px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition-colors mr-2">
                            {{ __('Annuler') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            {{ __('Enregistrer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Édition d'une section
            const editModal = document.getElementById('edit-modal');
            const editForm = document.getElementById('edit-form');
            
            document.querySelectorAll('.edit-section').forEach(button => {
                button.addEventListener('click', function() {
                    const sectionId = this.dataset.id;
                    
                    // Récupérer les données de la section
                    fetch(`{{ url(app()->getLocale() . '/admin/tools/template/sections') }}/${sectionId}`, {
                        headers: {
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.section) {
                            const section = data.section;
                            
                            // Remplir le formulaire
                            document.getElementById('edit-name').value = section.name;
                            document.getElementById('edit-partial_path').value = section.partial_path;
                            document.getElementById('edit-icon').value = section.icon;
                            document.getElementById('edit-description').value = section.description;
                            document.getElementById('edit-order').value = section.order;
                            document.getElementById('edit-is_active').checked = section.is_active;
                            
                            // Mettre à jour l'action du formulaire
                            editForm.action = `{{ url(app()->getLocale() . '/admin/tools/template/sections') }}/${sectionId}`;
                            
                            // Afficher la modal
                            editModal.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Une erreur est survenue lors de la récupération des données.');
                    });
                });
            });
            
            // Fermer la modal
            document.querySelectorAll('.close-modal').forEach(button => {
                button.addEventListener('click', function() {
                    editModal.classList.add('hidden');
                });
            });
            
            // Fermer la modal en cliquant à l'extérieur
            editModal.addEventListener('click', function(event) {
                if (event.target === editModal) {
                    editModal.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 