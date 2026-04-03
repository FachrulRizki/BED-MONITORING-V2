@props([
    'title' => null,
    'description' => null,
    'bodyClass' => 'p-6',
])

<div {{ $attributes->class(['overflow-hidden rounded-2xl border border-border bg-white shadow-sm']) }}>
    @if ($title || $description || isset($header) || isset($actions))
        <div class="flex flex-col gap-4 border-b border-border p-6 md:flex-row md:items-start md:justify-between">
            <div>
                @if (isset($header))
                    {{ $header }}
                @else
                    @if ($title)
                        <h3 class="text-lg font-bold text-foreground">{{ $title }}</h3>
                    @endif
                    @if ($description)
                        <p class="mt-1 text-sm text-secondary">{{ $description }}</p>
                    @endif
                @endif
            </div>

            @if (isset($actions))
                <div class="flex flex-wrap items-center gap-3">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>
