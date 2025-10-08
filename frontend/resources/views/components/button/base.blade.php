@props([
    'label' => null,
    'type' => 'button',
])
<button
    type="{{ $type }}"
    class="block rounded-lg border border-gray-300 bg-gray-100 px-2 py-1.5"
>
    {{ $label ?? $slot }}
</button>
