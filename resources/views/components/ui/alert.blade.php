@props([
    'variant' => 'success',
])

@php
    $variantClasses = [
        'success' => 'border-success/20 bg-success/10 text-success',
        'danger' => 'border-error/20 bg-error/10 text-error',
        'warning' => 'border-warning/30 bg-warning/10 text-amber-700',
        'info' => 'border-primary/20 bg-primary/10 text-primary',
    ][$variant] ?? 'border-success/20 bg-success/10 text-success';
@endphp

<div {{ $attributes->class(["mb-6 rounded-2xl border px-4 py-3 text-sm font-medium {$variantClasses}"]) }}>
    {{ $slot }}
</div>
