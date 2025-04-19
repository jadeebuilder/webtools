<x-admin-layout>
    <div class="p-6">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ __('Gestion des packages') }}</h1>
                <a href="{{ route('admin.packages.create', ['locale' => app()->getLocale()]) }}" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>{{ __('Ajouter un package') }}
                </a>
            </div>

            <!-- Filtres -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
                <form action="{{ route('admin.packages.index', ['locale' => app()->getLocale()]) }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[180px]">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Rechercher') }}</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ __('Nom ou slug...') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                    </div>
                    <div class="w-48">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Statut') }}</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <option value="">{{ __('Tous') }}</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Actif') }}</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('Inactif') }}</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-search mr-2"></i>{{ __('Filtrer') }}
                        </button>
                    </div>
                    @if(request('search') || request('status'))
                        <div>
                            <a href="{{ route('admin.packages.index', ['locale' => app()->getLocale()]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                                <i class="fas fa-times mr-2"></i>{{ __('Réinitialiser') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Tableau des packages -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Nom') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Prix') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Cycle') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Outils') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Statut') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($packages as $package)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded" style="background-color: {{ $package->color }}"></div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $package->getName() }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $package->slug }}</div>
                                                @if($package->is_default)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                        {{ __('Par défaut') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            @if($package->monthly_price > 0)
                                                <div><span class="font-medium">{{ __('Mensuel') }}:</span> {!! $package->getFormattedMonthlyPrice() !!}</div>
                                            @endif
                                            
                                            @if($package->annual_price > 0)
                                                <div><span class="font-medium">{{ __('Annuel') }}:</span> {!! $package->getFormattedAnnualPrice() !!}</div>
                                            @endif
                                            
                                            @if($package->lifetime_price > 0)
                                                <div><span class="font-medium">{{ __('À vie') }}:</span> {!! $package->getFormattedLifetimePrice() !!}</div>
                                            @endif
                                            
                                            @if($package->monthly_price == 0 && $package->annual_price == 0 && $package->lifetime_price == 0)
                                                <div class="text-green-600 dark:text-green-400 font-medium">{{ __('Gratuit') }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $package->getCyclePeriodText() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $package->tools->count() }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($package->vipTools()->count() > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100 mr-1">
                                                    {{ $package->vipTools()->count() }} VIP
                                                </span>
                                            @endif
                                            
                                            @if($package->aiTools()->count() > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                    {{ $package->aiTools()->count() }} AI
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                class="sr-only peer package-status-toggle" 
                                                data-url="{{ route('admin.packages.toggle-status', ['locale' => app()->getLocale(), 'id' => $package->id]) }}"
                                                {{ $package->is_active ? 'checked' : '' }}
                                            >
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white status-text">
                                                {{ $package->is_active ? __('Actif') : __('Inactif') }}
                                            </span>
                                        </label>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.packages.edit', ['locale' => app()->getLocale(), 'id' => $package->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.packages.destroy', ['locale' => app()->getLocale(), 'id' => $package->id]) }}" class="inline-block delete-form" data-name="{{ $package->getName() }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-box-open text-4xl mb-3"></i>
                                            <span class="text-lg font-medium">{{ __('Aucun package trouvé') }}</span>
                                            <p class="text-sm mt-1">{{ __('Commencez par créer votre premier package') }}</p>
                                            <a href="{{ route('admin.packages.create', ['locale' => app()->getLocale()]) }}" class="mt-4 bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg">
                                                <i class="fas fa-plus mr-2"></i>{{ __('Ajouter un package') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $packages->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts pour gestion des actions -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle status via AJAX
            document.querySelectorAll('.package-status-toggle').forEach(function(toggle) {
                toggle.addEventListener('change', function() {
                    const url = this.dataset.url;
                    const statusText = this.parentNode.querySelector('.status-text');
                    
                    fetch(url, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            statusText.textContent = data.is_active ? '{{ __('Actif') }}' : '{{ __('Inactif') }}';
                            
                            // Afficher une notification
                            showNotification(data.message, 'success');
                        } else {
                            // Remettre l'état précédent en cas d'erreur
                            this.checked = !this.checked;
                            showNotification('{{ __('Une erreur est survenue') }}', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.checked = !this.checked;
                        showNotification('{{ __('Une erreur est survenue') }}', 'error');
                    });
                });
            });
            
            // Confirmation de suppression
            document.querySelectorAll('.delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const packageName = this.dataset.name;
                    
                    if (confirm(`{{ __('Êtes-vous sûr de vouloir supprimer le package') }} "${packageName}" ? {{ __('Cette action est irréversible.') }}`)) {
                        this.submit();
                    }
                });
            });
            
            // Fonction pour afficher une notification
            function showNotification(message, type) {
                // Si vous avez une bibliothèque de notifications, utilisez-la ici
                alert(message);
            }
        });
    </script>
    @endpush
</x-admin-layout> 