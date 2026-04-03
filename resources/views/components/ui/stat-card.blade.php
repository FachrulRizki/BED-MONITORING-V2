@props([
    'title',
    'value',
    'icon' => 'activity',
    'accent' => 'primary',
    'meta' => null,
    'badge' => null,
    'badgeVariant' => 'success',
])

@php
    $accentClasses = [
        'primary' => 'bg-primary/10 text-primary',
        'success' => 'bg-success/10 text-success',
        'warning' => 'bg-warning/10 text-warning',
        'danger' => 'bg-error/10 text-error',
    ][$accent] ?? 'bg-primary/10 text-primary';
@endphp

<div {{ $attributes->class(['rounded-2xl border border-border bg-white p-5 shadow-sm transition-shadow hover:shadow-md']) }}>
    <div class="mb-4 flex items-center justify-between gap-3">
        <div class="flex size-10 items-center justify-center rounded-xl {{ $accentClasses }}">
            <i data-lucide="{{ $icon }}" class="size-5"></i>
        </div>

        @if ($badge)
            <x-ui.badge :variant="$badgeVariant">{{ $badge }}</x-ui.badge>
        @endif
    </div>

    <p class="text-sm font-medium text-secondary">{{ $title }}</p>
    <h3 class="mt-1 text-3xl font-bold text-foreground">{{ $value }}</h3>

    @if ($meta)
        <p class="mt-2 text-xs text-secondary">{{ $meta }}</p>
    @endif
</div>
