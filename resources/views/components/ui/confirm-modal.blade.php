@props([
    'name',
    'title',
    'message',
    'confirmLabel' => 'Konfirmasi',
    'cancelLabel' => 'Batal',
    'formId' => null,
    'variant' => 'danger',
])

<x-ui.modal :name="$name" :title="$title" :description="$message" max-width="max-w-lg">
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <x-ui.button variant="secondary" size="md" type="button" data-modal-close>
            {{ $cancelLabel }}
        </x-ui.button>

        @if ($formId)
            <x-ui.button :variant="$variant" size="md" type="submit" :form="$formId">
                {{ $confirmLabel }}
            </x-ui.button>
        @else
            <x-ui.button :variant="$variant" size="md" type="button">
                {{ $confirmLabel }}
            </x-ui.button>
        @endif
    </div>
</x-ui.modal>
