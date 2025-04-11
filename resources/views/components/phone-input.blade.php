@props(['disabled' => false, 'value' => ''])

<div class="phone-input-container">
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1']) !!} value="{{ $value }}">
    <div class="phone-error text-sm text-red-600 mt-1 hidden"></div>
</div> 