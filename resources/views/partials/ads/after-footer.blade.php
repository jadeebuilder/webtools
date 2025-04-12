@if(isset($adSettings['after_footer']) && $adSettings['after_footer']['active'])
    <div class="ad-container py-2 text-center">
        @if($adSettings['after_footer']['type'] == 'image')
            <a href="{{ $adSettings['after_footer']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['after_footer']['image']) }}" 
                     alt="{{ $adSettings['after_footer']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['after_footer']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['after_footer']['code'] !!}
            </div>
        @endif
    </div>
@endif 