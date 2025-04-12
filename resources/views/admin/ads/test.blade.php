<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Test des publicités') }}
                </h1>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Section de débogage détaillé -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Débogage des publicités') }}
                    </h2>

                    @php
                        $pageType = 'home'; 
                        $adsCount = AdSetting::where('active', true)->count();
                        $homeAdsCount = AdSetting::where('active', true)->whereJsonContains('display_on', $pageType)->count();
                        $allAdsWithHome = AdSetting::whereJsonContains('display_on', $pageType)->get();
                    @endphp

                    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="font-medium text-purple-800 dark:text-purple-300 mb-2">
                            {{ __('Page type:') }} {{ $pageType }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            {{ __('Nombre total de publicités actives:') }} {{ $adsCount }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ __('Nombre de publicités actives pour la page d\'accueil:') }} {{ $homeAdsCount }}
                        </p>

                        <h4 class="font-medium text-purple-700 dark:text-purple-400 mb-2">
                            {{ __('Publicités disponibles:') }}
                        </h4>
                        
                        @if($allAdsWithHome->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-800">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Position</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actif</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Display On (brut)</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Display On (JSON)</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Display On (Tableau)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($allAdsWithHome as $ad)
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900 dark:text-gray-200">{{ $ad->id }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900 dark:text-gray-200">{{ $ad->position }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-xs">
                                                    @if($ad->active)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                                            {{ __('Actif') }}
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">
                                                            {{ __('Inactif') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-2 text-xs text-gray-900 dark:text-gray-200">
                                                    <code>{{ var_export($ad->display_on, true) }}</code>
                                                </td>
                                                <td class="px-3 py-2 text-xs text-gray-900 dark:text-gray-200">
                                                    <code>{{ json_encode($ad->display_on) }}</code>
                                                </td>
                                                <td class="px-3 py-2 text-xs text-gray-900 dark:text-gray-200">
                                                    @php
                                                        $displayOnArray = is_array($ad->display_on) ? $ad->display_on : json_decode($ad->display_on, true);
                                                        if (is_array($displayOnArray)) {
                                                            echo implode(', ', $displayOnArray);
                                                        } else {
                                                            echo 'Non tableau: ' . gettype($displayOnArray);
                                                        }
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-red-500">
                                {{ __('Aucune publicité trouvée!') }}
                            </p>
                        @endif
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-medium text-purple-700 dark:text-purple-400 mb-2">
                            {{ __('Test accès aux fichiers image:') }}
                        </h4>
                        <div class="text-xs">
                            @php
                                $testPaths = [
                                    'storage/images/ads',
                                    'images/ads'
                                ];
                                foreach ($testPaths as $path) {
                                    $fullPath = public_path($path);
                                    $exists = file_exists($fullPath);
                                    $isDir = is_dir($fullPath);
                                    $readable = is_readable($fullPath);
                                    echo "<div class='mb-1'>";
                                    echo "<strong>Chemin:</strong> {$path} | ";
                                    echo "<strong>Chemin complet:</strong> {$fullPath} | ";
                                    echo "<strong>Existe:</strong> " . ($exists ? 'Oui' : 'Non') . " | ";
                                    echo "<strong>Est dossier:</strong> " . ($isDir ? 'Oui' : 'Non') . " | ";
                                    echo "<strong>Lisible:</strong> " . ($readable ? 'Oui' : 'Non');
                                    echo "</div>";
                                    
                                    if ($exists && $isDir && $readable) {
                                        $files = scandir($fullPath);
                                        echo "<div class='ml-4'><strong>Fichiers:</strong> " . implode(', ', array_slice($files, 2)) . "</div>";
                                    }
                                }
                            @endphp
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour à la liste des publicités') }}
                        </a>
                        <a href="{{ route('admin.ads.test.repair', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-purple-700 text-white rounded-md hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                            <i class="fas fa-wrench mr-2"></i> {{ __('Réparer les publicités') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Vérification des publicités actives') }}
                    </h2>

                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        {{ __('Cette page vous permet de tester le service de publicités et de vérifier si les publicités sont correctement configurées.') }}
                    </p>

                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="font-medium text-gray-900 dark:text-gray-200 mb-2">
                            {{ __('Publicités configurées dans la base de données') }}
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Position</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actif</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Display On</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Image/Code</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($allAds as $ad)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $ad->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $ad->position }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $ad->type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($ad->active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                                        {{ __('Actif') }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">
                                                        {{ __('Inactif') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                                @php
                                                    $displayOn = json_decode($ad->display_on, true);
                                                @endphp
                                                @if(is_array($displayOn))
                                                    @foreach($displayOn as $type)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-700 dark:text-purple-100 mr-1">
                                                            {{ $type }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-red-500">{{ __('Format invalide') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                                @if($ad->type === 'image')
                                                    <div>
                                                        <span class="text-xs">{{ $ad->image }}</span>
                                                        @if($ad->image)
                                                            @php
                                                                $imageFile = public_path(ltrim($ad->image, '/'));
                                                                $imageExists = file_exists($imageFile);
                                                            @endphp
                                                            <div class="mt-1">
                                                                @if($imageExists)
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                        {{ __('Fichier existe') }}
                                                                    </span>
                                                                @else
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                        {{ __('Fichier introuvable') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-500">{{ __('Code AdSense') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="font-medium text-gray-900 dark:text-gray-200 mb-2">
                            {{ __('Test du service de publicités') }}
                        </h3>
                        <div class="space-y-4">
                            @foreach(['home', 'tool', 'category'] as $pageType)
                                <div class="p-4 border rounded-md dark:border-gray-600">
                                    <h4 class="font-medium text-purple-600 dark:text-purple-400 mb-2">
                                        {{ __('Publicités pour le type: ') }} {{ $pageType }}
                                    </h4>
                                    @php
                                        $serviceAds = $adService->getAdsForPage($pageType, false);
                                    @endphp
                                    @if(count($serviceAds) > 0)
                                        <ul class="list-disc pl-5 space-y-2">
                                            @foreach($serviceAds as $position => $ad)
                                                <li class="text-sm text-gray-700 dark:text-gray-300">
                                                    <strong>{{ $position }}:</strong>
                                                    {{ $ad['type'] }} 
                                                    ({{ $ad['active'] ? 'Actif' : 'Inactif' }}) 
                                                    @if($ad['type'] === 'image')
                                                        - {{ $ad['image'] }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-red-500">
                                            {{ __('Aucune publicité trouvée pour ce type de page.') }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('admin.ads.index', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-arrow-left mr-2"></i> {{ __('Retour à la liste des publicités') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 