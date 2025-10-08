@php
    $currentMode = currentMode();
    $isGood = isGoodMode();
@endphp

<div class="flex w-full justify-between border-b border-gray-400 bg-white p-4">
    <div class="flex justify-start gap-x-4">
        <a href="{{ route($currentMode . '.index') }}">
            <img
                src="{{ asset('img/logo.jpg') }}"
                alt="Logo"
                class="h-12 w-auto"
            />
        </a>
        <span class="self-center text-2xl font-bold">Hairlytics</span>
    </div>
    <div class="flex items-center justify-end gap-x-4">
        <x-button.a
            label="Test now"
            :route="route($currentMode.'.create')"
        />
        <x-button.a
            label="Info"
            :route="route('info')"
        />

        <x-switch.app />
    </div>
</div>
