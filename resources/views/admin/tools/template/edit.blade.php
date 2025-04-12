<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Édition du template') }}: {{ $tool->getName() }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-5">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Sections actives') }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ __('Glissez-déposez pour réorganiser les sections. Cliquez sur supprimer pour retirer une section.') }}</p>

                        <div id="active-sections" class="min-h-[200px] border border-dashed border-gray-300 p-4 rounded-md bg-gray-50">
                            @if ($toolSections->isEmpty())
                                <p id="empty-sections-msg" class="text-center text-gray-500 py-10">{{ __('Aucune section n\'a été ajoutée à cet outil.') }}</p>
                            @else
                                <ul id="sortable-sections" class="space-y-3">
                                    @foreach ($toolSections as $template)
                                        <li class="section-item bg-white p-4 rounded-md shadow-sm border border-gray-200 cursor-move" data-id="{{ $template->id }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $template->section->icon }}"></path>
                                                    </svg>
                                                    <div>
                                                        <h4 class="font-medium">{{ $template->section->name }}</h4>
                                                        <p class="text-sm text-gray-500">{{ $template->section->description }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <button
                                                        class="toggle-section-btn px-3 py-1 rounded-md text-sm {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}"
                                                        data-id="{{ $template->id }}"
                                                        data-active="{{ $template->is_active ? 'true' : 'false' }}"
                                                    >
                                                        {{ $template->is_active ? __('Activé') : __('Désactivé') }}
                                                    </button>
                                                    <button
                                                        class="remove-section-btn px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm"
                                                        data-id="{{ $template->id }}"
                                                    >
                                                        {{ __('Supprimer') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Sections disponibles') }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ __('Sélectionnez une section à ajouter au template de l\'outil.') }}</p>

                        @if ($availableSections->isEmpty())
                            <p class="text-center text-gray-500 py-5">{{ __('Toutes les sections disponibles ont déjà été ajoutées.') }}</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($availableSections as $section)
                                    <div class="bg-white p-4 rounded-md shadow-sm border border-gray-200">
                                        <div class="flex items-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $section->icon }}"></path>
                                            </svg>
                                            <h4 class="font-medium">{{ $section->name }}</h4>
                                        </div>
                                        <p class="text-sm text-gray-500 mb-3">{{ $section->description }}</p>
                                        <button
                                            class="add-section-btn w-full px-3 py-1.5 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700 transition-colors"
                                            data-id="{{ $section->id }}"
                                        >
                                            {{ __('Ajouter cette section') }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser le drag and drop avec SortableJS
            const sortableList = document.getElementById('sortable-sections');
            if (sortableList) {
                const sortable = new Sortable(sortableList, {
                    animation: 150,
                    ghostClass: 'bg-purple-100',
                    onEnd: function(evt) {
                        updateSectionsOrder();
                    }
                });
            }

            // Mettre à jour l'ordre des sections
            function updateSectionsOrder() {
                const sections = Array.from(document.querySelectorAll('.section-item')).map(item => item.dataset.id);
                
                fetch('{{ route('admin.tools.template.update-order', ['locale' => app()->getLocale()]) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ sections })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Une erreur est survenue', 'error');
                });
            }

            // Ajouter une section
            document.querySelectorAll('.add-section-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const sectionId = this.dataset.id;
                    
                    fetch('{{ route('admin.tools.template.add-section', ['tool' => $tool->id, 'locale' => app()->getLocale()]) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ section_id: sectionId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Recharger la page pour afficher la nouvelle section
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Une erreur est survenue', 'error');
                    });
                });
            });

            // Supprimer une section
            document.querySelectorAll('.remove-section-btn').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette section ?')) {
                        const templateId = this.dataset.id;
                        
                        fetch(`{{ url(app()->getLocale() . '/admin/tools/template') }}/${templateId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const item = this.closest('.section-item');
                                item.remove();
                                
                                // Si toutes les sections sont supprimées, afficher le message "vide"
                                if (document.querySelectorAll('.section-item').length === 0) {
                                    document.getElementById('empty-sections-msg')?.classList.remove('hidden');
                                    const sortable = document.getElementById('sortable-sections');
                                    if (sortable) {
                                        sortable.innerHTML = '<p id="empty-sections-msg" class="text-center text-gray-500 py-10">Aucune section n\'a été ajoutée à cet outil.</p>';
                                    }
                                }
                                
                                showNotification(data.message, 'success');
                            } else {
                                showNotification(data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Une erreur est survenue', 'error');
                        });
                    }
                });
            });

            // Activer/désactiver une section
            document.querySelectorAll('.toggle-section-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const templateId = this.dataset.id;
                    const isActive = this.dataset.active === 'true';
                    const newStatus = !isActive;
                    
                    fetch(`{{ url(app()->getLocale() . '/admin/tools/template') }}/${templateId}/toggle`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ active: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour le bouton
                            this.dataset.active = newStatus ? 'true' : 'false';
                            this.textContent = newStatus ? 'Activé' : 'Désactivé';
                            this.className = `toggle-section-btn px-3 py-1 rounded-md text-sm ${newStatus ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
                            
                            showNotification(data.message, 'success');
                        } else {
                            showNotification(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Une erreur est survenue', 'error');
                    });
                });
            });

            // Afficher une notification
            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed bottom-4 right-4 p-4 rounded-md ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white shadow-lg z-50`;
                notification.textContent = message;
                document.body.appendChild(notification);
                
                // Disparaître après 3 secondes
                setTimeout(() => {
                    notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>
    @endpush
</x-app-layout> 