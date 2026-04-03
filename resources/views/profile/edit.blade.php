@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-description', 'Kelola identitas akun dan kata sandi dengan pola form yang sama di seluruh aplikasi.')

@section('content')
<div class="mb-6 flex justify-start">
    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button :href="route('dashboard')" variant="secondary">
            <i data-lucide="arrow-left" class="size-4"></i>
            Dashboard
        </x-ui.button>
    </div>
</div>

@if (session('status') === 'profile-updated')
    <x-ui.alert variant="success">Profil berhasil diperbarui.</x-ui.alert>
@endif

@if (session('status') === 'password-updated')
    <x-ui.alert variant="success">Password berhasil diperbarui.</x-ui.alert>
@endif

<div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
    <x-ui.card title="Informasi Profil" description="Data identitas utama yang digunakan di dalam sistem.">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <x-ui.form-field label="Nama" for="name" :error="$errors->first('name')" required>
                <x-ui.input id="name" name="name" :value="old('name', $user->name)" required autofocus />
            </x-ui.form-field>

            <x-ui.form-field label="Username" for="username" :error="$errors->first('username')" required>
                <x-ui.input id="username" name="username" :value="old('username', $user->username)" required />
            </x-ui.form-field>

            <x-ui.form-field label="Email" for="email" :error="$errors->first('email')" required>
                <x-ui.input type="email" id="email" name="email" :value="old('email', $user->email)" required />
            </x-ui.form-field>

            <div class="flex justify-end">
                <x-ui.button variant="primary" type="submit">
                    <i data-lucide="save" class="size-4"></i>
                    Simpan Perubahan
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card title="Ubah Password" description="Gunakan kata sandi yang kuat agar akses akun tetap aman.">
        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <x-ui.form-field label="Password Saat Ini" for="current_password" :error="$errors->first('current_password')" required>
                <x-ui.input type="password" id="current_password" name="current_password" autocomplete="current-password" required />
            </x-ui.form-field>

            <x-ui.form-field label="Password Baru" for="password" :error="$errors->first('password')" required>
                <x-ui.input type="password" id="password" name="password" autocomplete="new-password" required />
            </x-ui.form-field>

            <x-ui.form-field label="Konfirmasi Password Baru" for="password_confirmation" required>
                <x-ui.input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required />
            </x-ui.form-field>

            <div class="flex justify-end">
                <x-ui.button variant="primary" type="submit">
                    <i data-lucide="lock-keyhole" class="size-4"></i>
                    Perbarui Password
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
