@if(isset($adSettings['before_footer']) && $adSettings['before_footer']['active'])
    <div class="ad-container py-2 text-center my-6">
        @if($adSettings['before_footer']['type'] == 'image')
            <a href="{{ $adSettings['before_footer']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['before_footer']['image']) }}" 
                     alt="{{ $adSettings['before_footer']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['before_footer']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['before_footer']['code'] !!}
            </div>
        @endif
    </div>
@endif 