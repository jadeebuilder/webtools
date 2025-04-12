@if(isset($adSettings['after_nav']) && $adSettings['after_nav']['active'])
    <div class="ad-container py-2 text-center">
        @if($adSettings['after_nav']['type'] == 'image')
            <a href="{{ $adSettings['after_nav']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['after_nav']['image']) }}" 
                     alt="{{ $adSettings['after_nav']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['after_nav']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['after_nav']['code'] !!}
            </div>
        @endif
    </div>
@endif 