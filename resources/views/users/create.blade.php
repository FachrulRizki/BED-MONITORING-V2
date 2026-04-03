@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')
@section('page-description', 'Buat akun admin atau petugas baru untuk sistem.')

@section('content')
<div class="mb-6 flex justify-start">
    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button :href="route('users.index')" variant="secondary">
            <i data-lucide="arrow-left" class="size-4"></i>
            Kembali
        </x-ui.button>
    </div>
</div>

@include('users.partials.form', [
    'action' => route('users.store'),
    'method' => 'POST',
    'submitLabel' => 'Simpan Pengguna',
])
@endsection
