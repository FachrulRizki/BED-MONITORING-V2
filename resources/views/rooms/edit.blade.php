@extends('layouts.app')

@section('title', 'Edit Ruangan')
@section('page-title', 'Edit Ruangan')
@section('page-description', 'Perbarui kapasitas dan angka okupansi ruangan secara langsung.')

@section('content')
<div class="mb-6 flex justify-start">
    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button :href="route('rooms.index')" variant="secondary">
            <i data-lucide="arrow-left" class="size-4"></i>
            Kembali
        </x-ui.button>
    </div>
</div>

@include('rooms.partials.form', [
    'room' => $room,
    'action' => route('rooms.update', $room),
    'method' => 'PUT',
    'submitLabel' => 'Simpan Perubahan',
])
@endsection
