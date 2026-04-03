@props([
    'name',
    'title' => null,
    'description' => null,
    'maxWidth' => 'max-w-md',
])

<div id="{{ $name }}" data-ui-modal class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="w-full {{ $maxWidth }} overflow-hidden rounded-[28px] border border-border bg-white shadow-2xl">
        <div class="flex items-start justify-between gap-4 border-b border-border p-6">
            <div>
                @if ($title)
                    <h3 class="text-xl font-bold text-foreground">{{ $title }}</h3>
                @endif
                @if ($description)
                    <p class="mt-1 text-sm text-secondary">{{ $description }}</p>
                @endif
            </div>

            <button type="button" class="rounded-xl p-2 text-secondary transition-colors hover:bg-muted hover:text-foreground" data-modal-close>
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>

        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
