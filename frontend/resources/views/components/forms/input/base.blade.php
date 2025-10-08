@props([
    'id' => null,
    'name',
    'label',
    'type' => 'text',
])
<div class="flex flex-col space-y-1">
    <x-forms.label
        :label="$label"
        required
    />

    <input
        type="{{ $type }}"
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        class="w-1/2 rounded-lg border bg-gray-100"
    />

    <x-forms.input-error :messages="$errors->get($errorName ?? $name)" />
</div>
