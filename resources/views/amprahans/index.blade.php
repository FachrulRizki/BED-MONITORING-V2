@extends('layouts.app')

@section('title', 'Laporan Amprahan')
@section('page-title', 'Laporan Amprahan')
@section('page-description', 'Daftar laporan shift dengan tampilan tabel seragam dan mudah dipindai.')

@section('content')
<div class="mb-6 flex justify-start">
    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button :href="route('amprahans.create')" variant="primary">
            <i data-lucide="plus" class="size-4"></i>
            Buat Laporan
        </x-ui.button>
    </div>
</div>

<x-ui.card title="Filter Laporan" description="Saring daftar dan hasil cetak berdasarkan rentang tanggal pembuatan laporan.">
    <form method="GET" action="{{ route('amprahans.index') }}" class="grid gap-4 md:grid-cols-[1fr_1fr_auto] lg:grid-cols-[220px_220px_auto_auto] lg:items-end">
        <x-ui.form-field label="Dari Tanggal" for="date_from">
            <x-ui.input type="date" id="date_from" name="date_from" :value="$filters['date_from'] ?? ''" />
        </x-ui.form-field>

        <x-ui.form-field label="Sampai Tanggal" for="date_to">
            <x-ui.input type="date" id="date_to" name="date_to" :value="$filters['date_to'] ?? ''" />
        </x-ui.form-field>

        <div class="flex flex-wrap gap-3">
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="filter" class="size-4"></i>
                Terapkan
            </x-ui.button>

            <x-ui.button :href="route('amprahans.index')" variant="secondary">
                Reset
            </x-ui.button>
        </div>

        <div class="flex flex-wrap gap-3 lg:justify-end">
            <x-ui.button :href="route('amprahans.print', request()->only(['date_from', 'date_to']))" variant="secondary" target="_blank" rel="noopener">
                <i data-lucide="printer" class="size-4"></i>
                Cetak Laporan
            </x-ui.button>
        </div>
    </form>
</x-ui.card>

<div class="my-6">
    @if(($filters['date_from'] ?? null) || ($filters['date_to'] ?? null))
        <x-ui.alert variant="info" class="mb-0">
            Menampilkan laporan
            dari <strong>{{ $filters['date_from'] ?? 'awal data' }}</strong>
            sampai <strong>{{ $filters['date_to'] ?? 'hari ini' }}</strong>.
        </x-ui.alert>
    @endif
</div>

@if ($reports->isEmpty())
    <x-ui.empty-state icon="clipboard-x" title="Belum ada laporan amprahan" description="Belum ada laporan yang masuk. Mulai dari membuat laporan pertama untuk shift berjalan.">
        <x-ui.button :href="route('amprahans.create')" variant="primary">
            <i data-lucide="plus" class="size-4"></i>
            Buat Laporan
        </x-ui.button>
    </x-ui.empty-state>
@else
    <x-ui.card body-class="p-0">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-border bg-muted/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-secondary">Ruangan</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Shift</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Jam Amprahan</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Pasien</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Petugas</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Dibuat</th>
                        <th class="p-4 text-right text-sm font-semibold text-secondary">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        @php
                            $shiftVariant = match ($report->shift) {
                                'pagi' => 'warning',
                                'sore' => 'primary',
                                'malam' => 'dark',
                                default => 'neutral',
                            };
                        @endphp
                        <tr class="border-b border-border last:border-b-0 hover:bg-card-grey/60">
                            <td class="p-4 font-semibold text-foreground">{{ $report->room->name ?? '-' }}</td>
                            <td class="p-4">
                                <div><x-ui.badge :variant="$shiftVariant">{{ ucfirst($report->shift) }}</x-ui.badge></div>
                                <div class="mt-1 text-xs text-secondary/80">Next: {{ ucfirst($report->next_shift ?: $report->shift) }}</div>
                            </td>
                            <td class="p-4 text-sm text-secondary">{{ $report->report_time }}</td>
                            <td class="p-4 text-sm text-secondary">Pria {{ $report->male_patient_count }} · Wanita {{ $report->female_patient_count }}</td>
                            <td class="p-4 text-sm text-secondary">
                                <div>{{ $report->officer_name ?: '-' }}</div>
                                <div class="mt-1 text-xs text-secondary/80">Next: {{ $report->next_officer_name ?: '-' }}</div>
                            </td>
                            <td class="p-4 text-sm text-secondary">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-right">
                                <x-ui.button :href="route('amprahans.show', $report)" variant="ghost" size="icon" aria-label="Lihat detail laporan">
                                    <i data-lucide="eye" class="size-4"></i>
                                </x-ui.button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-border px-4 py-4">
            {{ $reports->links() }}
        </div>
    </x-ui.card>
@endif
@endsection
