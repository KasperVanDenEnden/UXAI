<x-layouts.base>
    {{-- todo for demo turn this off --}}
    @isset($apiHealth)
        <div class="m-4">
            <x-typography.h1 title="Api health" />
            <pre class="mt-4 border bg-gray-100 px-2 py-1.5">{{ json_encode($apiHealth, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endisset
</x-layouts.base>
