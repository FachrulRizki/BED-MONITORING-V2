@props([
    'label' => null,
    'for' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
])

<div {{ $attributes->class(['space-y-2']) }}>
    @if ($label)
        <label for="{{ $for }}" class="block text-sm font-medium text-secondary">
            {{ $label }}
            @if ($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($hint)
        <p class="text-xs text-secondary">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="text-xs font-medium text-error">{{ $error }}</p>
    @endif
</div>
