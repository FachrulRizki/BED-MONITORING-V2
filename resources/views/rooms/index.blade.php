@extends('layouts.app')

@section('title', 'Manajemen Ruangan')
@section('page-title', 'Manajemen Ruangan')
@section('page-description', 'Pantau kapasitas, okupansi, dan pembaruan setiap ruangan dengan tampilan tabel yang konsisten.')

@section('content')
<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div class="relative flex-1 max-w-md">
        <i data-lucide="search" class="absolute left-3 top-1/2 size-5 -translate-y-1/2 text-secondary"></i>
        <input id="room-search" type="text" placeholder="Cari nama ruangan..." class="w-full rounded-xl border border-border bg-white py-3 pl-10 pr-4 text-sm text-foreground outline-none transition-all placeholder:text-secondary/70 focus:border-primary focus:ring-2 focus:ring-primary/20">
    </div>

    <div class="flex gap-3">
        <select id="room-filter" class="rounded-xl border border-border bg-white px-4 py-3 text-sm font-medium text-foreground outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            <option value="all">Semua Status</option>
            <option value="tersedia">Tersedia</option>
            <option value="kosong">Kosong</option>
            <option value="penuh">Penuh</option>
        </select>
        <x-ui.button href="{{ route('rooms.create') }}" variant="primary">
            <i data-lucide="plus" class="size-4"></i>
            Tambah Ruangan
        </x-ui.button>
    </div>
</div>

@if ($rooms->isEmpty())
    <x-ui.empty-state icon="bed-double" title="Belum ada ruangan" description="Tambahkan ruangan pertama agar dashboard okupansi dan laporan amprahan dapat mulai digunakan.">
        <x-ui.button href="{{ route('rooms.create') }}" variant="primary">
            <i data-lucide="plus" class="size-4"></i>
            Tambah Ruangan
        </x-ui.button>
    </x-ui.empty-state>
@else
    <x-ui.card body-class="p-0">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-border bg-muted/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-secondary">Ruangan</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Kapasitas</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Terisi</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Tersedia</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Status</th>
                        <th class="p-4 text-right text-sm font-semibold text-secondary">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                        @php
                            $totalCapacity = $room->male_capacity + $room->female_capacity;
                            $totalOccupied = $room->male_occupied + $room->female_occupied;
                            $totalAvailable = $totalCapacity - $totalOccupied;
                            $status = $totalOccupied >= $totalCapacity && $totalCapacity > 0
                                ? 'penuh'
                                : ($totalOccupied === 0 ? 'kosong' : 'tersedia');
                            $badgeVariant = match ($status) {
                                'penuh' => 'danger',
                                'kosong' => 'neutral',
                                default => 'success',
                            };
                            $deleteFormId = 'delete-room-'.$room->id;
                            $modalId = 'confirm-delete-room-'.$room->id;
                        @endphp
                        <tr data-room-row data-status="{{ $status }}" data-search="{{ strtolower($room->name) }}" class="border-b border-border last:border-b-0 hover:bg-card-grey/60">
                            <td class="p-4">
                                <p class="font-semibold text-foreground">{{ $room->name }}</p>
                                <p class="mt-1 text-xs text-secondary">L: {{ $room->male_capacity }} · P: {{ $room->female_capacity }}</p>
                            </td>
                            <td class="p-4 text-sm text-secondary">{{ $totalCapacity }} bed</td>
                            <td class="p-4 text-sm text-secondary">{{ $totalOccupied }} bed</td>
                            <td class="p-4 text-sm font-semibold {{ $totalAvailable > 0 ? 'text-success' : 'text-error' }}">{{ $totalAvailable }} bed</td>
                            <td class="p-4">
                                <x-ui.badge :variant="$badgeVariant">{{ ucfirst($status) }}</x-ui.badge>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-end gap-2">
                                    <x-ui.button :href="route('rooms.edit', $room)" variant="ghost" size="icon" aria-label="Edit {{ $room->name }}">
                                        <i data-lucide="edit-3" class="size-4"></i>
                                    </x-ui.button>

                                    <x-ui.button variant="ghost" size="icon" onclick="openModal('{{ $modalId }}')" aria-label="Hapus {{ $room->name }}">
                                        <i data-lucide="trash-2" class="size-4 text-error"></i>
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>

                        @push('modals')
                            <form id="{{ $deleteFormId }}" action="{{ route('rooms.destroy', $room) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <x-ui.confirm-modal :name="$modalId" title="Hapus ruangan?" :message="'Data '.$room->name.' akan dihapus permanen dari daftar ruangan.'" :form-id="$deleteFormId" confirm-label="Ya, Hapus" />
                        @endpush
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('room-search');
        const filterInput = document.getElementById('room-filter');
        const rows = Array.from(document.querySelectorAll('[data-room-row]'));

        function applyRoomFilter() {
            const keyword = (searchInput?.value || '').trim().toLowerCase();
            const status = filterInput?.value || 'all';

            rows.forEach((row) => {
                const matchesKeyword = row.dataset.search.includes(keyword);
                const matchesStatus = status === 'all' || row.dataset.status === status;
                row.classList.toggle('hidden', !(matchesKeyword && matchesStatus));
            });
        }

        searchInput?.addEventListener('input', applyRoomFilter);
        filterInput?.addEventListener('change', applyRoomFilter);
    });
</script>
@endpush
