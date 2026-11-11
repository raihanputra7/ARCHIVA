@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-on-surface']) }}>
    {{ $value ?? $slot }}
</label>
