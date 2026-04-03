@extends('layouts.public')

@section('title', 'Monitor Ketersediaan Bed')
@section('header-class', 'flex w-full flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8 lg:py-5')
@section('main-class', 'w-full min-w-0 px-4 py-6 sm:px-6 lg:px-8')

@push('meta')
    <meta http-equiv="refresh" content="30">
@endpush

@section('content')
@php
    $totalRooms = $rooms->count();
    $totalCapacity = $rooms->sum(fn ($room) => $room['male_capacity'] + $room['female_capacity']);
    $totalOccupied = $rooms->sum(fn ($room) => $room['male_occupied'] + $room['female_occupied']);
    $totalAvailable = $totalCapacity - $totalOccupied;
@endphp

<div class="mb-6 grid grid-cols-1 gap-4">
    <div class="rounded-[28px] border border-border bg-white p-6 shadow-sm sm:p-8">
        <p class="text-sm font-semibold text-secondary">Pembaruan Sistem</p>
        <div class="mt-4 flex items-end justify-between gap-6">
            <div>
                <p id="clock" class="text-5xl font-bold tracking-tight text-foreground">--:--:--</p>
                <p class="mt-2 text-sm text-secondary">Waktu lokal monitor</p>
            </div>
            <div class="rounded-2xl bg-success/10 px-4 py-3 text-success">
                <p class="text-xs font-semibold uppercase tracking-wide">Auto Refresh</p>
                <p class="mt-1 text-sm font-bold">30 detik</p>
            </div>
        </div>
    </div>
</div>

@if ($rooms->isEmpty())
    <x-ui.empty-state icon="bed-double" title="Belum ada data ruangan" description="Tambahkan ruangan dari dashboard internal agar monitor publik menampilkan status bed." />
@else
    <div id="rooms-grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5">
        @foreach ($rooms as $room)
            @php
                $isFull = $room['is_full'];
                $pct = $room['usage_percentage'];
                $maleAvail = $room['male_capacity'] - $room['male_occupied'];
                $femaleAvail = $room['female_capacity'] - $room['female_occupied'];
            @endphp
            <div data-room-id="{{ $room['id'] }}" class="overflow-hidden rounded-[28px] border {{ $isFull ? 'border-error/20 bg-white' : 'border-success/20 bg-white' }} p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <div class="mb-3 flex items-center gap-2">
                            <span class="size-3 rounded-full {{ $isFull ? 'bg-error' : 'bg-success' }}"></span>
                            <x-ui.badge :variant="$isFull ? 'danger' : 'success'">{{ $isFull ? 'Penuh' : 'Tersedia' }}</x-ui.badge>
                        </div>
                        <h3 class="text-xl font-bold text-foreground">{{ $room['name'] }}</h3>
                    </div>

                    <div class="rounded-2xl {{ $isFull ? 'bg-error/10 text-error' : 'bg-success/10 text-success' }} px-3 py-2 text-right">
                        <p class="text-xs font-semibold uppercase tracking-wide">Okupansi</p>
                        <p class="mt-1 text-lg font-bold">{{ $pct }}%</p>
                    </div>
                </div>

                <div class="mb-5 h-3 overflow-hidden rounded-full bg-muted">
                    <div class="h-full rounded-full {{ $isFull ? 'bg-error' : 'bg-primary' }}" style="width: {{ $pct }}%"></div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-muted p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Laki-laki</p>
                        <p class="mt-2 text-2xl font-bold text-foreground">{{ $maleAvail }}</p>
                        <p class="mt-1 text-xs text-secondary">tersedia dari {{ $room['male_capacity'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-muted p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-secondary">Perempuan</p>
                        <p class="mt-2 text-2xl font-bold text-foreground">{{ $femaleAvail }}</p>
                        <p class="mt-1 text-xs text-secondary">tersedia dari {{ $room['female_capacity'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${h}:${m}:${s}`;
    }

    updateClock();
    setInterval(updateClock, 1000);
</script>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const PUSHER_KEY = '{{ config("broadcasting.connections.pusher.key") }}';
        const PUSHER_CLUSTER = '{{ config("broadcasting.connections.pusher.options.cluster", "mt1") }}';
        const PUSHER_HOST = '{{ config("broadcasting.connections.pusher.host", "") }}';
        const PUSHER_PORT = {{ config("broadcasting.connections.pusher.port", 443) }};
        const PUSHER_SCHEME = '{{ config("broadcasting.connections.pusher.scheme", "https") }}';

        if (!PUSHER_KEY || PUSHER_KEY === 'your-pusher-app-key') {
            return;
        }

        const options = { cluster: PUSHER_CLUSTER || 'mt1' };

        if (PUSHER_HOST) {
            options.wsHost = PUSHER_HOST;
            options.wsPort = PUSHER_PORT;
            options.wssPort = PUSHER_PORT;
            options.forceTLS = PUSHER_SCHEME === 'https';
            options.enabledTransports = ['ws', 'wss'];
            options.disableStats = true;
        }

        const pusher = new Pusher(PUSHER_KEY, options);
        const channel = pusher.subscribe('bed-availability');

        channel.bind('BedAvailabilityUpdated', () => {
            window.location.reload();
        });
    });
</script>
@endpush
