@if(isset($adSettings['right_sidebar']) && $adSettings['right_sidebar']['active'])
    <div class="ad-container sticky top-4 py-2">
        @if($adSettings['right_sidebar']['type'] == 'image')
            <a href="{{ $adSettings['right_sidebar']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['right_sidebar']['image']) }}" 
                     alt="{{ $adSettings['right_sidebar']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['right_sidebar']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['right_sidebar']['code'] !!}
            </div>
        @endif
    </div>
@endif 