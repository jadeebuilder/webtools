<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Gestion des outils') }}</h1>
                <a href="{{ route('admin.tools.create', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                    <i class="fas fa-plus mr-2"></i>{{ __('Nouvel outil') }}
                </a>
            </div>
            
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.tools.index', ['locale' => app()->getLocale()]) }}" method="GET" class="flex flex-wrap gap-4">
                        <div class="w-full md:w-64">
                            <input type="text" name="search" placeholder="{{ __('Rechercher...') }}" value="{{ request('search') }}" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        </div>
                        
                        <div class="w-full md:w-48">
                            <select name="category" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="">{{ __('Toutes les catégories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="w-full md:w-40">
                            <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="">{{ __('Tous les statuts') }}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Actif') }}</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactif') }}</option>
                            </select>
                        </div>
                        
                        <div class="w-full md:w-40">
                            <select name="type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                <option value="">{{ __('Tous les types') }}</option>
                                <option value="free" {{ request('type') == 'free' ? 'selected' : '' }}>{{ __('Gratuit') }}</option>
                                <option value="premium" {{ request('type') == 'premium' ? 'selected' : '' }}>{{ __('Premium') }}</option>
                            </select>
                        </div>
                        
                        <div class="w-full md:w-auto flex items-center">
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                <i class="fas fa-search mr-2"></i>{{ __('Filtrer') }}
                            </button>
                            
                            @if(request('search') || request('category') || request('status') || request('type'))
                                <a href="{{ route('admin.tools.index', ['locale' => app()->getLocale()]) }}" class="ml-2 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <i class="fas fa-times mr-2"></i>{{ __('Réinitialiser') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Outil') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Slug') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Catégorie') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Statut') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Type') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Ordre') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tools as $tool)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                                <i class="{{ $tool->icon }} text-xl"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $tool->getName() }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $tool->slug }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $tool->category ? $tool->category->getName() : __('Non catégorisé') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tool->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $tool->is_active ? __('Actif') : __('Inactif') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tool->is_premium ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                            {{ $tool->is_premium ? __('Premium') : __('Gratuit') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $tool->order }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.templates.edit', ['tool' => $tool, 'locale' => app()->getLocale()]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="{{ __('Gérer le template') }}">
                                                <i class="fas fa-puzzle-piece"></i>
                                            </a>
                                            <a href="{{ route('admin.tools.edit', ['tool' => $tool, 'locale' => app()->getLocale()]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="{{ __('Modifier') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="toggle-status text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" 
                                                data-url="{{ route('admin.tools.toggle-status', ['tool' => $tool, 'locale' => app()->getLocale()]) }}"
                                                data-status="{{ $tool->is_active }}"
                                                title="{{ $tool->is_active ? __('Désactiver') : __('Activer') }}">
                                                <i class="fas {{ $tool->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                            </button>
                                            <form action="{{ route('admin.tools.destroy', ['tool' => $tool, 'locale' => app()->getLocale()]) }}" method="POST" class="inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="{{ __('Supprimer') }}" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cet outil ?') }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('Aucun outil trouvé') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($tools->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $tools->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-status');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const url = this.dataset.url;
                    
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
                            // Mettre à jour l'icône et le statut
                            const icon = this.querySelector('i');
                            icon.classList.toggle('fa-toggle-on');
                            icon.classList.toggle('fa-toggle-off');
                            
                            // Mettre à jour le titre
                            this.title = data.is_active ? '{{ __("Désactiver") }}' : '{{ __("Activer") }}';
                            
                            // Mettre à jour le statut dans la colonne
                            const statusCell = this.closest('tr').querySelector('td:nth-child(4) span');
                            statusCell.textContent = data.is_active ? '{{ __("Actif") }}' : '{{ __("Inactif") }}';
                            statusCell.classList.toggle('bg-green-100');
                            statusCell.classList.toggle('text-green-800');
                            statusCell.classList.toggle('bg-red-100');
                            statusCell.classList.toggle('text-red-800');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
                });
            });
        });
    </script>
    @endpush
</x-admin-layout> 