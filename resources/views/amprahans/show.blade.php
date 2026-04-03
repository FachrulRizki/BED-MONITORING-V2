@extends('layouts.app')

@section('title', 'Detail Laporan Amprahan')
@section('page-title', 'Detail Laporan Amprahan')
@section('page-description', 'Tinjau detail laporan, metadata pengirim, dan dokumentasi gambar dalam satu tampilan.')

@section('content')
@php
    $shiftVariant = match ($amprahan->shift) {
        'pagi' => 'warning',
        'sore' => 'primary',
        'malam' => 'dark',
        default => 'neutral',
    };

    $nextShiftVariant = match ($amprahan->next_shift) {
        'pagi' => 'warning',
        'sore' => 'primary',
        'malam' => 'dark',
        default => 'neutral',
    };
@endphp

<div class="mb-6 flex justify-start">
    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button :href="route('amprahans.index')" variant="secondary">
            <i data-lucide="arrow-left" class="size-4"></i>
            Kembali
        </x-ui.button>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_340px]">
    <x-ui.card title="Informasi Laporan" description="Data utama laporan amprahan yang dikirim petugas.">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl bg-muted p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Ruangan</p>
                <p class="mt-2 text-lg font-bold text-foreground">{{ $amprahan->room->name ?? '-' }}</p>
            </div>
            <div class="rounded-2xl bg-muted p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Jam Amprahan</p>
                <p class="mt-2 text-lg font-bold text-foreground">{{ $amprahan->report_time }}</p>
            </div>
            <div class="rounded-2xl bg-muted p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Shift Saat Ini</p>
                <div class="mt-2">
                    <x-ui.badge :variant="$shiftVariant">{{ ucfirst($amprahan->shift) }}</x-ui.badge>
                </div>
            </div>
            <div class="rounded-2xl bg-muted p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Shift Berikutnya</p>
                <div class="mt-2">
                    <x-ui.badge :variant="$nextShiftVariant">{{ ucfirst($amprahan->next_shift) }}</x-ui.badge>
                </div>
            </div>
            <div class="rounded-2xl bg-muted p-4 col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Tanggal Dibuat</p>
                <p class="mt-2 text-lg font-bold text-foreground">{{ $amprahan->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-border p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Pasien Pria</p>
                <p class="mt-2 text-3xl font-bold text-primary">{{ $amprahan->male_patient_count }}</p>
            </div>
            <div class="rounded-2xl border border-border p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Pasien Wanita</p>
                <p class="mt-2 text-3xl font-bold text-primary">{{ $amprahan->female_patient_count }}</p>
            </div>
        </div>

        <div class="mt-6 rounded-2xl bg-muted p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Rencana Tindakan</p>
            <p class="mt-2 whitespace-pre-line text-sm text-foreground">{{ $amprahan->action_plan ?: '-' }}</p>
        </div>

        @if ($amprahan->image_path)
            <div class="mt-6">
                <p class="mb-3 text-sm font-semibold text-foreground">Dokumentasi Gambar</p>
                <img src="{{ Storage::url($amprahan->image_path) }}" alt="Gambar laporan amprahan" class="w-full rounded-3xl border border-border object-cover shadow-sm">
            </div>
        @endif
    </x-ui.card>

    <x-ui.card title="Metadata" description="Informasi petugas dan pengirim laporan.">
        <div class="space-y-4">
            <div class="rounded-2xl bg-muted p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Petugas Shift Saat Ini</p>
                <p class="mt-2 text-sm font-semibold text-foreground">{{ $amprahan->officer_name ?: '-' }}</p>
            </div>
            <div class="rounded-2xl bg-muted p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Petugas Shift Berikutnya</p>
                <p class="mt-2 text-sm font-semibold text-foreground">{{ $amprahan->next_officer_name ?: '-' }}</p>
            </div>
            <div class="rounded-2xl bg-muted p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Disubmit Oleh</p>
                <p class="mt-2 text-sm font-semibold text-foreground">{{ $amprahan->submittedBy->name ?? '-' }}</p>
            </div>
        </div>
    </x-ui.card>
</div>
@endsection
