@if(isset($adSettings['after_tool_description']) && $adSettings['after_tool_description']['active'])
    <div class="ad-container py-2 text-center my-4">
        @if($adSettings['after_tool_description']['type'] == 'image')
            <a href="{{ $adSettings['after_tool_description']['link'] }}" target="_blank" rel="nofollow noopener">
                <img src="{{ asset($adSettings['after_tool_description']['image']) }}" 
                     alt="{{ $adSettings['after_tool_description']['alt'] ?? 'Advertisement' }}" 
                     class="max-w-full mx-auto h-auto">
            </a>
        @elseif($adSettings['after_tool_description']['type'] == 'adsense')
            <div class="adsense-container">
                {!! $adSettings['after_tool_description']['code'] !!}
            </div>
        @endif
    </div>
@endif 