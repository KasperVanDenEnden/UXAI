@props([
    'route' => '#',
    'label',
])
<a
    href="{{ $route }}"
    class="block rounded-lg border px-2 py-1.5 font-medium shadow-sm transition-all duration-200 hover:shadow-md"
>
    {{ $label }}
</a>
