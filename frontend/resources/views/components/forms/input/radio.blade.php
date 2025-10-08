@props([
    'name',
    'label',
    'required' => false,
])
<div class="mb-4">
    <x-forms.label
        :label="$label"
        required
    />

    <div class="mt-1 flex gap-4">
        @foreach (\App\YesNoEnum::cases() as $case)
            <label>
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $case->value }}"
                />
                {{ $case->label() }}
            </label>
        @endforeach
    </div>
    <x-forms.input-error :messages="$errors->get($errorName ?? $name)" />
</div>
