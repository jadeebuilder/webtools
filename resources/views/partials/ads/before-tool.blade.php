@if(isset($adSettings['before_tool']) && $adSettings['before_tool']['active'])
    <div class="ad-container py-2 text-center mb-6">
        @if($adSettings['before_tool']['type'] == 'image')
            <a href="{{ $adSettings['before_tool']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['before_tool']['image']) }}" 
                     alt="{{ $adSettings['before_tool']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['before_tool']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['before_tool']['code'] !!}
            </div>
        @endif
    </div>
@endif 