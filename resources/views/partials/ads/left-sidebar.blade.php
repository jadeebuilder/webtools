@if(isset($adSettings['left_sidebar']) && $adSettings['left_sidebar']['active'])
    <div class="ad-container sticky top-4 py-2">
        @if($adSettings['left_sidebar']['type'] == 'image')
            <a href="{{ $adSettings['left_sidebar']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['left_sidebar']['image']) }}" 
                     alt="{{ $adSettings['left_sidebar']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['left_sidebar']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['left_sidebar']['code'] !!}
            </div>
        @endif
    </div>
@endif 