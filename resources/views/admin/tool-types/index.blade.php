<x-admin-layout>
    <div class="p-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ __('Types d\'outils') }}</h1>
                <a href="{{ route('admin.tool-types.create', ['locale' => app()->getLocale()]) }}" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>{{ __('Ajouter un type') }}
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    @if($toolTypes->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-layer-group text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Aucun type d\'outil n\'a été créé pour le moment.') }}</p>
                            <a href="{{ route('admin.tool-types.create', ['locale' => app()->getLocale()]) }}" class="inline-block mt-4 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md">
                                <i class="fas fa-plus mr-2"></i>{{ __('Créer un type d\'outil') }}
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Couleur') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Nom') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Slug') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Ordre') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Statut') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($toolTypes as $type)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-6 h-6 rounded-full" style="background-color: {{ $type->color }}"></div>
                                                    <span class="ml-2 text-xs text-gray-500">{{ $type->color }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($type->icon)
                                                        <i class="{{ $type->icon }} mr-2 text-gray-500"></i>
                                                    @endif
                                                    <span class="text-gray-900 dark:text-white">{{ $type->getName() }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                {{ $type->slug }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                {{ $type->order }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center justify-center">
                                                    <button type="button" 
                                                            class="toggle-status relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 {{ $type->is_active ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700' }}"
                                                            data-id="{{ $type->id }}"
                                                            data-active="{{ $type->is_active ? 'true' : 'false' }}"
                                                            aria-pressed="{{ $type->is_active ? 'true' : 'false' }}"
                                                            role="switch">
                                                        <span class="sr-only">{{ __('Changer le statut') }}</span>
                                                        <span class="toggle-dot pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $type->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('admin.tool-types.edit', ['locale' => app()->getLocale(), 'id' => $type->id]) }}" class="text-primary hover:text-primary-dark">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.tool-types.destroy', ['locale' => app()->getLocale(), 'id' => $type->id]) }}" method="POST" class="inline-block delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de la confirmation de suppression
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (confirm("{{ __('Êtes-vous sûr de vouloir supprimer ce type d\'outil?') }}")) {
                        this.submit();
                    }
                });
            });
            
            // Gestion du toggle de statut
            document.querySelectorAll('.toggle-status').forEach(button => {
                button.addEventListener('click', function() {
                    const typeId = this.dataset.id;
                    const isActive = this.dataset.active === 'true';
                    const button = this;
                    const dot = button.querySelector('.toggle-dot');
                    
                    // Envoyer la requête AJAX pour changer le statut
                    fetch(`{{ url('/') }}/${app.locale}/admin/tool-types/${typeId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour l'apparence du bouton
                            button.classList.toggle('bg-primary');
                            button.classList.toggle('bg-gray-200');
                            button.classList.toggle('dark:bg-gray-700');
                            
                            dot.classList.toggle('translate-x-5');
                            dot.classList.toggle('translate-x-0');
                            
                            // Mettre à jour les attributs
                            button.dataset.active = isActive ? 'false' : 'true';
                            button.setAttribute('aria-pressed', isActive ? 'false' : 'true');
                            
                            // Afficher un message de succès
                            alert(data.message);
                        } else {
                            alert("{{ __('Une erreur est survenue lors du changement de statut.') }}");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert("{{ __('Une erreur est survenue lors du changement de statut.') }}");
                    });
                });
            });
        });
    </script>
    @endpush
</x-admin-layout> 