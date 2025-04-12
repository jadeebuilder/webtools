@if(isset($adSettings['after_nav']) && $adSettings['after_nav']['active'])
    <div class="ad-container py-2 text-center">
        @if($adSettings['after_nav']['type'] == 'image')
            @if(!empty($adSettings['after_nav']['image']))
                @php
                    $imageFile = $adSettings['after_nav']['image'];
                    $imageUrl = Str::startsWith($imageFile, ['http://', 'https://']) 
                        ? $imageFile 
                        : asset($imageFile);
                    $altText = $adSettings['after_nav']['alt'] ?? 'Advertisement';
                @endphp
                
                <a href="{{ $adSettings['after_nav']['link'] }}" target="_blank" rel="nofollow noopener">
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $altText }}" 
                         class="max-w-full mx-auto h-auto">
                </a>
            @elseif(config('app.debug'))
                <div class="border border-red-300 bg-red-100 text-red-800 p-2 rounded">
                    Image manquante pour la publicité 'after_nav'
                </div>
            @endif
        @elseif($adSettings['after_nav']['type'] == 'adsense')
            @if(!empty($adSettings['after_nav']['code']))
                <div class="adsense-container">
                    {!! $adSettings['after_nav']['code'] !!}
                </div>
            @elseif(config('app.debug'))
                <div class="border border-red-300 bg-red-100 text-red-800 p-2 rounded">
                    Code AdSense manquant pour la publicité 'after_nav'
                </div>
            @endif
        @endif
    </div>
@endif 