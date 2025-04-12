@if(isset($adSettings['before_nav']) && $adSettings['before_nav']['active'])
    <div class="ad-container py-2 text-center">
        @if($adSettings['before_nav']['type'] == 'image')
            <a href="{{ $adSettings['before_nav']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['before_nav']['image']) }}" 
                     alt="{{ $adSettings['before_nav']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['before_nav']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['before_nav']['code'] !!}
            </div>
        @endif
    </div>
@endif 