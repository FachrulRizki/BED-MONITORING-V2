@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('page-description', 'Perbarui identitas, role, dan password akun pengguna.')

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
    'user' => $user,
    'action' => route('users.update', $user),
    'method' => 'PUT',
    'submitLabel' => 'Simpan Perubahan',
])
@endsection
