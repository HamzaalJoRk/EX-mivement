@props(['active'])

@php
$classes = ($active ?? false)
            ? 'dropdown-item bold'
            : 'dropdown-item bold';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
