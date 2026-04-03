@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-xl border font-semibold transition-all cursor-pointer';
    $variantClasses = [
        'primary' => 'border-primary bg-primary text-white shadow-lg shadow-primary/20 hover:border-primary-hover hover:bg-primary-hover',
        'secondary' => 'border-border bg-white text-foreground hover:bg-muted',
        'ghost' => 'border-transparent bg-transparent text-secondary hover:bg-muted hover:text-foreground',
        'danger' => 'border-error bg-error text-white shadow-lg shadow-error/20 hover:bg-error/90 hover:border-error/90',
        'success' => 'border-success bg-success text-white shadow-lg shadow-success/20 hover:bg-success/90 hover:border-success/90',
    ][$variant] ?? 'border-primary bg-primary text-white shadow-lg shadow-primary/20 hover:border-primary-hover hover:bg-primary-hover';
    $sizeClasses = [
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-3 text-sm',
        'lg' => 'px-5 py-3 text-sm',
        'icon' => 'size-10 p-0 text-sm',
    ][$size] ?? 'px-4 py-3 text-sm';
    $classes = trim("{$baseClasses} {$variantClasses} {$sizeClasses}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->class([$classes])->merge(['type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif
