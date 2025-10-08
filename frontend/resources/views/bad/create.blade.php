<x-layouts.base>
    <x-forms.post :route="route('bad.store')">
        <div class="mx-2 my-1.5">
            <x-typography.h1 title="Questions" />
            @include('partials.form.bad-inputs')
            @include('partials.form.good-inputs')
            <x-button.submit />
        </div>
    </x-forms.post>
</x-layouts.base>
