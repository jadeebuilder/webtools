@if(config('app.debug'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 my-4" style="max-height: 300px; overflow-y: auto;">
        <h3 class="font-bold">Débogage des publicités</h3>
        
        <div class="mt-2">
            <p><strong>Page type:</strong> {{ request()->route() ? request()->route()->getName() : 'No route' }}</p>
            
            <p><strong>Publicités disponibles:</strong></p>
            @if(isset($adSettings) && count($adSettings) > 0)
                <ul class="ml-4 list-disc">
                    @foreach($adSettings as $position => $ad)
                        <li>
                            <strong>{{ $position }}:</strong> 
                            {{ $ad['type'] }} 
                            @if($ad['type'] == 'image')
                                - <a href="{{ asset($ad['image']) }}" target="_blank">{{ $ad['image'] }}</a>
                            @endif
                            (Active: {{ $ad['active'] ? 'Oui' : 'Non' }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-red-600">Aucune publicité trouvée!</p>
            @endif
            
            <p class="mt-2"><strong>Test accès aux fichiers image:</strong></p>
            @if(isset($adSettings))
                @foreach($adSettings as $position => $ad)
                    @if($ad['type'] == 'image' && $ad['image'])
                        @php
                            $imageFile = public_path(ltrim($ad['image'], '/'));
                            $imageExists = file_exists($imageFile);
                        @endphp
                        <p>
                            {{ $ad['image'] }}: 
                            @if($imageExists)
                                <span class="text-green-600">Fichier existe</span>
                            @else
                                <span class="text-red-600">Fichier n'existe pas! ({{ $imageFile }})</span>
                            @endif
                        </p>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endif 