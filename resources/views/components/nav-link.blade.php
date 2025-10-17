@props(['active' => false])

@php
$classes = $active
            ? 'flex cursor-pointer items-center p-2 text-[#1779FF] rounded-lg bg-gray-100 group text-sm'
            : 'flex cursor-pointer items-center p-2 text-gray-600 rounded-lg hover:bg-gray-100 group text-sm';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
