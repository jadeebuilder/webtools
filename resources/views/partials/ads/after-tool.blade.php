@if(isset($adSettings['after_tool']) && $adSettings['after_tool']['active'])
    <div class="ad-container py-2 text-center mt-6">
        @if($adSettings['after_tool']['type'] == 'image')
            <a href="{{ $adSettings['after_tool']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['after_tool']['image']) }}" 
                     alt="{{ $adSettings['after_tool']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['after_tool']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['after_tool']['code'] !!}
            </div>
        @endif
    </div>
@endif 