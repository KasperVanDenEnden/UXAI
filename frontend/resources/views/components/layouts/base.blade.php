@props([
    'title' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, viewport-fit=cover"
        />
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        />
        <title>{{ $title ? $title : '' }} - {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body
        class="flex min-h-screen flex-col bg-cover bg-center"
        style="background-image: url('{{ asset('img/background.jpg') }}')"
    >
        <x-layouts.navbar />

        <div class="my-4 flex flex-grow justify-center">
            <div class="mx-auto w-1/2 rounded-2xl border border-b-cyan-300 bg-white">
                {{ $slot }}
            </div>
        </div>

        <x-layouts.footer />
        @stack('scripts')
    </body>
</html>
