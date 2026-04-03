@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-description', 'Seluruh notifikasi laporan baru tersusun rapi dengan penanda status baca yang jelas.')

@section('content')
@php
    $unreadCount = $notifications->whereNull('read_at')->count();
@endphp

@if ($unreadCount > 0)
    <div class="flex justify-start mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                @csrf
                <x-ui.button variant="secondary" type="submit">
                    <i data-lucide="check-check" class="size-4"></i>
                    Tandai Semua Dibaca
                </x-ui.button>
            </form>
        </div>
    </div>
@endif
    
@if ($notifications->isEmpty())
    <x-ui.empty-state icon="bell-off" title="Tidak ada notifikasi" description="Notifikasi laporan baru akan muncul di halaman ini saat ada aktivitas masuk." />
@else
    <div class="space-y-4">
        @foreach ($notifications as $notification)
            @php $isUnread = is_null($notification->read_at); @endphp
            <div class="rounded-2xl border {{ $isUnread ? 'border-primary/30 bg-primary/5' : 'border-border bg-white' }} p-5 shadow-sm transition-all">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div class="flex gap-4">
                        <div class="mt-0.5 flex size-11 items-center justify-center rounded-2xl {{ $isUnread ? 'bg-primary/10 text-primary' : 'bg-muted text-secondary' }}">
                            <i data-lucide="bell-ring" class="size-5"></i>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold text-foreground">Laporan Amprahan Baru - {{ $notification->data['room_name'] ?? '-' }}</p>
                                @if ($isUnread)
                                    <x-ui.badge variant="primary" size="sm">Baru</x-ui.badge>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-secondary">
                                Jam amprahan: {{ $notification->data['report_time'] ?? '-' }}
                                <span class="mx-2">·</span>
                                {{ $notification->created_at->diffForHumans() }}
                            </p>

                            @if (!empty($notification->data['link']))
                                <a href="{{ $notification->data['link'] }}" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
                                    Lihat Detail Laporan
                                    <i data-lucide="arrow-right" class="size-4"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($isUnread)
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                            @csrf
                            <x-ui.button variant="primary" size="sm" type="submit">
                                <i data-lucide="check" class="size-4"></i>
                                Tandai Dibaca
                            </x-ui.button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
