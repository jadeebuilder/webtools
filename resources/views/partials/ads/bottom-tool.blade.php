@if(isset($adSettings['bottom_tool']) && $adSettings['bottom_tool']['active'])
    <div class="ad-container py-2 text-center my-6">
        @if($adSettings['bottom_tool']['type'] == 'image')
            <a href="{{ $adSettings['bottom_tool']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['bottom_tool']['image']) }}" 
                     alt="{{ $adSettings['bottom_tool']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['bottom_tool']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['bottom_tool']['code'] !!}
            </div>
        @endif
    </div>
@endif 