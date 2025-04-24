<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Catégories d\'outils') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('admin.tool-categories.create', ['locale' => app()->getLocale()]) }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Nouvelle catégorie') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Nom') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Slug') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Statut') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Ordre') }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                                    <i class="{{ $category->icon }} text-xl"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $category->getName() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full status-badge {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $category->is_active ? __('Actif') : __('Inactif') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $category->order }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <button onclick="toggleStatus('{{ route('admin.tool-categories.toggle', ['locale' => app()->getLocale(), 'category' => $category->id]) }}', this)"
                                                    class="p-2 rounded-full {{ $category->is_active ? 'bg-green-100 text-green-600 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <i class="fas {{ $category->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                                <a href="{{ route('admin.tool-categories.edit', ['locale' => app()->getLocale(), 'tool_category' => $category->id]) }}"
                                                    class="p-2 rounded-full bg-purple-100 text-purple-600 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.tool-categories.destroy', ['locale' => app()->getLocale(), 'tool_category' => $category->id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="p-2 rounded-full bg-red-100 text-red-600 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                        onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette catégorie ?') }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Aucune catégorie trouvée') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

<script>
    function toggleStatus(url, button) {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'icône et la couleur du bouton
                const icon = button.querySelector('i');
                
                if (data.is_active) {
                    button.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                    button.classList.add('bg-green-100', 'text-green-600', 'hover:bg-green-200');
                    icon.classList.remove('fa-toggle-off');
                    icon.classList.add('fa-toggle-on');
                } else {
                    button.classList.remove('bg-green-100', 'text-green-600', 'hover:bg-green-200');
                    button.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                    icon.classList.remove('fa-toggle-on');
                    icon.classList.add('fa-toggle-off');
                }
                
                // Mettre à jour le badge de statut
                const statusBadge = button.closest('tr').querySelector('.status-badge');
                if (statusBadge) {
                    if (data.is_active) {
                        statusBadge.classList.remove('bg-red-100', 'text-red-800');
                        statusBadge.classList.add('bg-green-100', 'text-green-800');
                        statusBadge.textContent = '{{ __("Actif") }}';
                    } else {
                        statusBadge.classList.remove('bg-green-100', 'text-green-800');
                        statusBadge.classList.add('bg-red-100', 'text-red-800');
                        statusBadge.textContent = '{{ __("Inactif") }}';
                    }
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('{{ __("Une erreur est survenue lors de la mise à jour du statut") }}');
        });
    }
</script> 