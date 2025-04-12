<x-admin-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-purple-800">{{ __('Configuration globale des emplacements publicitaires') }}</h1>
            <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="px-4 py-2 bg-gray-500 text-white font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                {{ __('Retour') }}
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <p class="text-gray-600 mb-4">
                {{ __('Cette page vous permet d\'activer ou de désactiver rapidement les emplacements publicitaires par type de page. Cochez les cases pour activer les emplacements correspondants.') }}
            </p>
            <p class="text-gray-600 mb-4">
                {{ __('Note: Ces paramètres affecteront toutes les publicités configurées pour ces emplacements. Pour une gestion plus détaillée, utilisez la page de gestion des publicités individuelle.') }}
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        <form action="{{ route('admin.ads.global-settings.update', ['locale' => app()->getLocale()]) }}" method="POST">
            @csrf
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">{{ __('Position') }}</th>
                            @foreach($pageTypes as $pageKey => $pageName)
                                <th class="px-6 py-3 text-center text-xs font-medium text-purple-700 uppercase tracking-wider">{{ $pageName }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($positions as $key => $name)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $name }}</td>
                                @foreach($pageTypes as $pageKey => $pageName)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <label class="inline-flex items-center">
                                            <input type="hidden" name="settings[{{ $key }}][{{ $pageKey }}]" value="0">
                                            <input type="checkbox" name="settings[{{ $key }}][{{ $pageKey }}]" value="1" 
                                                  class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                                  {{ isset($settings[$key][$pageKey]) && $settings[$key][$pageKey] ? 'checked' : '' }}>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-purple-700 text-white font-medium rounded-md hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    {{ __('Enregistrer les paramètres') }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout> 