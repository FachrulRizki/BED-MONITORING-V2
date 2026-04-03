@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan operasional bed, laporan shift, dan notifikasi terbaru.')

@section('content')
@php
    $totalCapacity = $stats['total_available'] + $stats['total_occupied'];
    $occupancyPercentage = $totalCapacity > 0 ? round(($stats['total_occupied'] / $totalCapacity) * 100) : 0;
    $occupancyLabel = $occupancyPercentage >= 85 ? 'Tinggi' : ($occupancyPercentage >= 60 ? 'Normal' : 'Longgar');
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
    <x-ui.stat-card title="Total Ruangan" :value="$stats['total_rooms']" icon="building-2" accent="primary" badge="Aktif" badge-variant="primary" />
    <x-ui.stat-card title="Bed Tersedia" :value="$stats['total_available']" icon="bed-single" accent="success" :meta="'Sisa kapasitas siap pakai'" />
    <x-ui.stat-card title="Bed Terisi" :value="$stats['total_occupied']" icon="users" accent="danger" :meta="'Sudah ditempati pasien'" />
    <x-ui.stat-card title="Laporan Hari Ini" :value="$stats['reports_today']" icon="clipboard-list" accent="warning" :badge="now()->translatedFormat('d M')" badge-variant="warning" />
    <x-ui.stat-card title="Belum Dibaca" :value="$stats['unread_notifications']" icon="bell-ring" accent="primary" :badge="$stats['unread_notifications'] > 0 ? 'Perlu aksi' : 'Terkendali'" :badge-variant="$stats['unread_notifications'] > 0 ? 'danger' : 'success'" />
</div>

<div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-[1.25fr_0.75fr]">
    <x-ui.card title="Ringkasan Okupansi" description="Proporsi penggunaan bed rumah sakit saat ini.">
        <div class="grid gap-6 lg:grid-cols-[1fr_220px] lg:items-center">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <x-ui.badge :variant="$occupancyPercentage >= 85 ? 'danger' : ($occupancyPercentage >= 60 ? 'warning' : 'success')">
                        {{ $occupancyLabel }}
                    </x-ui.badge>
                    <p class="text-sm text-secondary">Tingkat keterisian keseluruhan</p>
                </div>

                <div class="h-4 overflow-hidden rounded-full bg-muted">
                    <div class="h-full rounded-full bg-primary transition-all" style="width: {{ $occupancyPercentage }}%"></div>
                </div>

                <div class="mt-3 flex items-center justify-between text-sm">
                    <span class="font-medium text-foreground">{{ $occupancyPercentage }}% terisi</span>
                    <span class="text-secondary">{{ $stats['total_occupied'] }} dari {{ $totalCapacity }} bed</span>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-muted p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Kapasitas</p>
                        <p class="mt-2 text-2xl font-bold text-foreground">{{ $totalCapacity }}</p>
                    </div>
                    <div class="rounded-2xl bg-muted p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Tersisa</p>
                        <p class="mt-2 text-2xl font-bold text-success">{{ $stats['total_available'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-muted p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Notifikasi</p>
                        <p class="mt-2 text-2xl font-bold text-primary">{{ $stats['unread_notifications'] }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[28px] bg-gradient-to-br from-primary to-emerald-900 p-6 text-white shadow-lg shadow-primary/20">
                <p class="text-sm font-medium text-white/75">Status sistem</p>
                <p class="mt-3 text-5xl font-bold">{{ $occupancyPercentage }}%</p>
                <p class="mt-2 text-sm text-white/80">Okupansi total rumah sakit</p>

                <div class="mt-8 space-y-3">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-3">
                        <p class="text-xs uppercase tracking-wide text-white/60">Monitoring</p>
                        <p class="mt-1 text-sm font-semibold">Live dan konsisten</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-3">
                        <p class="text-xs uppercase tracking-wide text-white/60">Shift aktif</p>
                        <p class="mt-1 text-sm font-semibold">{{ now()->translatedFormat('l, d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>

    <x-ui.card title="Aksi Cepat" description="Akses alur kerja utama tanpa berpindah-pindah menu.">
        <div class="space-y-3">
            <a href="{{ route('amprahans.create') }}" class="flex items-center gap-4 rounded-2xl border border-border bg-white p-4 transition-all hover:border-primary/30 hover:bg-primary/5">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                    <i data-lucide="clipboard-plus" class="size-5"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-foreground">Buat Laporan Amprahan</p>
                    <p class="mt-1 text-xs text-secondary">Catat kondisi bed untuk shift berjalan.</p>
                </div>
                <i data-lucide="arrow-right" class="size-4 text-secondary"></i>
            </a>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('rooms.index') }}" class="flex items-center gap-4 rounded-2xl border border-border bg-white p-4 transition-all hover:border-primary/30 hover:bg-primary/5">
                    <div class="flex size-12 items-center justify-center rounded-2xl bg-success/10 text-success">
                        <i data-lucide="bed-double" class="size-5"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-foreground">Kelola Ruangan</p>
                        <p class="mt-1 text-xs text-secondary">Perbarui kapasitas dan okupansi ruangan.</p>
                    </div>
                    <i data-lucide="arrow-right" class="size-4 text-secondary"></i>
                </a>
            @endif

            <a href="{{ route('notifications.index') }}" class="flex items-center gap-4 rounded-2xl border border-border bg-white p-4 transition-all hover:border-primary/30 hover:bg-primary/5">
                <div class="flex size-12 items-center justify-center rounded-2xl bg-warning/10 text-warning">
                    <i data-lucide="bell-ring" class="size-5"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-foreground">Tinjau Notifikasi</p>
                        @if ($stats['unread_notifications'] > 0)
                            <x-ui.badge variant="danger" size="sm">{{ $stats['unread_notifications'] }} baru</x-ui.badge>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-secondary">Lacak laporan yang baru masuk dan tandai dibaca.</p>
                </div>
                <i data-lucide="arrow-right" class="size-4 text-secondary"></i>
            </a>
        </div>
    </x-ui.card>
</div>

<div class="mt-6">
    <x-ui.card title="Laporan Amprahan Terbaru" description="Lima entri terbaru dari petugas shift.">
        @if ($recentReports->isEmpty())
            <x-ui.empty-state icon="clipboard-x" title="Belum ada laporan amprahan" description="Mulai dengan membuat laporan pertama untuk mendokumentasikan kondisi bed ruangan.">
                <x-ui.button href="{{ route('amprahans.create') }}" variant="primary">
                    <i data-lucide="plus" class="size-4"></i>
                    Buat Laporan
                </x-ui.button>
            </x-ui.empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead class="border-b border-border bg-muted/50">
                        <tr>
                            <th class="p-4 text-sm font-semibold text-secondary">Ruangan</th>
                            <th class="p-4 text-sm font-semibold text-secondary">Shift</th>
                            <th class="p-4 text-sm font-semibold text-secondary">Petugas</th>
                            <th class="p-4 text-sm font-semibold text-secondary">Jam Amprahan</th>
                            <th class="p-4 text-sm font-semibold text-secondary">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentReports as $report)
                            @php
                                $shiftVariant = match ($report->shift) {
                                    'pagi' => 'warning',
                                    'sore' => 'primary',
                                    'malam' => 'dark',
                                    default => 'neutral',
                                };
                            @endphp
                            <tr class="border-b border-border last:border-b-0 hover:bg-card-grey/60">
                                <td class="p-4">
                                    <p class="font-semibold text-foreground">{{ $report->room?->name ?? '—' }}</p>
                                </td>
                                <td class="p-4">
                                    <x-ui.badge :variant="$shiftVariant">{{ ucfirst($report->shift) }}</x-ui.badge>
                                </td>
                                <td class="p-4 text-sm text-secondary">{{ $report->officer_name ?: ($report->submittedBy->name ?? 'Petugas') }}</td>
                                <td class="p-4 text-sm text-secondary">{{ $report->report_time }}</td>
                                <td class="p-4 text-sm text-secondary">{{ $report->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-ui.card>
</div>
@endsection
