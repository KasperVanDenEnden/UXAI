<!-- resources/views/components/switch/app.blade.php -->
@if (Route::is('bad.index', 'good.index'))
    @php
        $currentMode = request()->cookie('app_mode', 'good');
        $isGood = $currentMode === 'good';
    @endphp

    <form
        method="POST"
        action="{{ route('app-mode') }}"
    >
        @csrf
        <button
            type="submit"
            class="{{ $isGood ? 'bg-green-500' : 'bg-red-500' }} {{ $isGood ? 'focus:ring-green-500' : 'focus:ring-red-500' }} relative h-10 w-20 rounded-full transition-colors hover:opacity-90 focus:ring-2 focus:ring-offset-2 focus:outline-none"
        >
            <span class="{{ $isGood ? '' : 'translate-x-10' }} absolute top-1 left-1 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm transition-transform">
                {{ $isGood ? '😊' : '😈' }}
            </span>
        </button>
    </form>
@endif
