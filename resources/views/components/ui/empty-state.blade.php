@props([
    'icon' => 'inbox',
    'title' => 'Belum ada data',
    'description' => null,
])

<div {{ $attributes->class(['flex flex-col items-center justify-center rounded-2xl border border-dashed border-border bg-white px-6 py-14 text-center shadow-sm']) }}>
    <div class="mb-4 flex size-14 items-center justify-center rounded-2xl bg-muted text-secondary">
        <i data-lucide="{{ $icon }}" class="size-6"></i>
    </div>
    <h3 class="text-lg font-bold text-foreground">{{ $title }}</h3>
    @if ($description)
        <p class="mt-2 max-w-md text-sm text-secondary">{{ $description }}</p>
    @endif
    @if (trim($slot) !== '')
        <div class="mt-5">
            {{ $slot }}
        </div>
    @endif
</div>
