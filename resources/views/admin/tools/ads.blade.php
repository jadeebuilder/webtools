<x-admin-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-purple-800">{{ __('Configuration des publicités pour') }}: {{ $tool->getName() }}</h1>
            <a href="{{ route('admin.tools.edit', ['locale' => app()->getLocale(), 'tool' => $tool->id]) }}" class="px-4 py-2 bg-gray-500 text-white font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                {{ __('Retour') }}
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <p class="text-gray-600 mb-4">
                {{ __('Cette page vous permet de configurer les emplacements publicitaires spécifiquement pour cet outil. Décochez les cases pour désactiver les emplacements correspondants pour cet outil uniquement.') }}
            </p>
            <p class="text-gray-600 mb-4">
                {{ __('Note: Ces paramètres sont spécifiques à cet outil et remplaceront les configurations globales.') }}
            </p>
        </div>
        
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        <form action="{{ route('admin.tools.ads.update', ['locale' => app()->getLocale(), 'tool' => $tool->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">{{ __('Position') }}</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-purple-700 uppercase tracking-wider">{{ __('Activé') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($positions as $key => $name)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="positions[{{ $key }}]" value="1" 
                                              class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                              {{ isset($toolAdSettings[$key]) && $toolAdSettings[$key] ? 'checked' : '' }}>
                                    </label>
                                </td>
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