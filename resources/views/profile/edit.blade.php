@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    .profile-page { max-width: 680px; margin: 0 auto; }
    .profile-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,.08);
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .profile-card h2 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e1b4b;
        margin-bottom: .25rem;
    }
    .profile-card p.subtitle {
        font-size: .85rem;
        color: #6b7280;
        margin-bottom: 1.5rem;
    }
    .form-group { margin-bottom: 1.1rem; }
    .form-group label {
        display: block;
        font-size: .875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .35rem;
    }
    .form-control {
        width: 100%;
        padding: .55rem .85rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: .9rem;
        color: #111827;
        transition: border-color .15s, box-shadow .15s;
        outline: none;
    }
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    }
    .form-control.is-invalid { border-color: #ef4444; }
    .invalid-feedback {
        color: #ef4444;
        font-size: .8rem;
        margin-top: .3rem;
    }
    .btn-save {
        background: #4f46e5;
        color: #fff;
        border: none;
        padding: .55rem 1.4rem;
        border-radius: 6px;
        font-size: .9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-save:hover { background: #4338ca; }
    .alert-success {
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        color: #065f46;
        border-radius: 6px;
        padding: .65rem 1rem;
        font-size: .875rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .divider {
        border: none;
        border-top: 1px solid #f3f4f6;
        margin: 0 0 1.5rem;
    }
</style>

<div class="profile-page">

    {{-- Form 1: Edit Nama & Email --}}
    <div class="profile-card">
        <h2>Informasi Profil</h2>
        <p class="subtitle">Perbarui nama dan alamat email akun Anda.</p>

        @if(session('status') === 'profile-updated')
            <div class="alert-success">
                ✅ Profil berhasil diperbarui.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email', $user->email) }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>
    </div>

    {{-- Form 2: Ubah Password --}}
    <div class="profile-card">
        <h2>Ubah Password</h2>
        <p class="subtitle">Pastikan akun Anda menggunakan password yang kuat.</p>

        @if(session('status') === 'password-updated')
            <div class="alert-success">
                ✅ Password berhasil diperbarui.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Password Saat Ini</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                    autocomplete="current-password"
                >
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    autocomplete="new-password"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn-save">Perbarui Password</button>
        </form>
    </div>

</div>
@endsection
