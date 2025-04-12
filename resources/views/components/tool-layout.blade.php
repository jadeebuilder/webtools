@props(['pageTitle' => null, 'metaDescription' => null, 'customH1' => null, 'customDescription' => null])

<x-layouts.tool 
    :pageTitle="$pageTitle"
    :metaDescription="$metaDescription"
    :customH1="$customH1"
    :customDescription="$customDescription">
    {{ $slot }}
    
    @if(isset($additionalSections))
        <x-slot name="additionalSections">
            {{ $additionalSections }}
        </x-slot>
    @endif
</x-layouts.tool> 