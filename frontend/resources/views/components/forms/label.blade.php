@props([
    'label',
    'required' => false,
])
<label class="font-semibold">
    {{ $label }}
    @if ($required)
        <x-forms.label-required />
    @endif
</label>
