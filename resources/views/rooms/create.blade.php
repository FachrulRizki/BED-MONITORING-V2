@extends('layouts.app')

@section('title', 'Tambah Ruangan')
@section('page-title', 'Tambah Ruangan')
@section('page-description', 'Buat data ruangan baru dengan format yang sama seperti referensi dashboard.')

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
    'action' => route('rooms.store'),
    'method' => 'POST',
    'submitLabel' => 'Simpan Ruangan',
])
@endsection
