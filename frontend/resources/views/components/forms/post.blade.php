@props([
    'route',
])
<form
    method="POST"
    action="{{ $route }}"
>
    @csrf

    {{ $slot }}
</form>
