@props([
    'type' => 'text',
])

<input
    type="{{ $type }}"
    {{ $attributes->class(['w-full rounded-xl border border-border bg-white px-4 py-3 text-sm text-foreground outline-none transition-all placeholder:text-secondary/70 focus:border-primary focus:ring-2 focus:ring-primary/20']) }}
>
