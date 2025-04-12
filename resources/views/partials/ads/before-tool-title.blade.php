@if(isset($adSettings['before_tool_title']) && $adSettings['before_tool_title']['active'])
    <div class="ad-container py-2 text-center mb-4">
        @if($adSettings['before_tool_title']['type'] == 'image')
            <a href="{{ $adSettings['before_tool_title']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['before_tool_title']['image']) }}" 
                     alt="{{ $adSettings['before_tool_title']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['before_tool_title']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['before_tool_title']['code'] !!}
            </div>
        @endif
    </div>
@endif 