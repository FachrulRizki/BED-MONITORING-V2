@props([
    'variant' => 'neutral',
    'size' => 'md',
])

@php
    $variantClasses = [
        'primary' => 'bg-primary/10 text-primary',
        'success' => 'bg-success/10 text-success',
        'warning' => 'bg-warning/10 text-warning',
        'danger' => 'bg-error/10 text-error',
        'neutral' => 'bg-muted text-secondary',
        'dark' => 'bg-foreground text-white',
    ][$variant] ?? 'bg-muted text-secondary';
    $sizeClasses = [
        'sm' => 'px-2.5 py-1 text-[11px]',
        'md' => 'px-3 py-1 text-xs',
    ][$size] ?? 'px-3 py-1 text-xs';
@endphp

<span {{ $attributes->class(["inline-flex items-center rounded-full font-semibold {$variantClasses} {$sizeClasses}"]) }}>
    {{ $slot }}
</span>
