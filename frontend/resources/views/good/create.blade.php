<x-layouts.base>
    <x-forms.post :route="route('good.store')">
        <div class="mx-2 my-1.5">
            <x-typography.h2 title="Questions" />
            @include('partials.form.good-inputs')
            <x-button.submit />
        </div>
    </x-forms.post>
</x-layouts.base>
