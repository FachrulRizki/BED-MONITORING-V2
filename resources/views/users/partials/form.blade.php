@php
    $user = $user ?? null;
@endphp

<x-ui.card :title="$user ? 'Perbarui data pengguna' : 'Tambahkan pengguna baru'" description="Kelola akun admin dan petugas dari satu formulir yang konsisten.">
    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @if (($method ?? 'POST') !== 'POST')
            @method($method)
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <x-ui.form-field label="Nama Lengkap" for="name" :error="$errors->first('name')" required>
                    <x-ui.input id="name" name="name" :value="old('name', $user?->name)" placeholder="Nama pengguna" required />
                </x-ui.form-field>
            </div>

            <x-ui.form-field label="Username" for="username" :error="$errors->first('username')" required>
                <x-ui.input id="username" name="username" :value="old('username', $user?->username)" placeholder="username" required />
            </x-ui.form-field>

            <x-ui.form-field label="Email" for="email" :error="$errors->first('email')" required>
                <x-ui.input type="email" id="email" name="email" :value="old('email', $user?->email)" placeholder="email@hospital.com" required />
            </x-ui.form-field>

            <x-ui.form-field label="Role" for="role" :error="$errors->first('role')" required>
                <x-ui.select id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" @selected(old('role', $user?->role) === 'admin')>Admin</option>
                    <option value="petugas" @selected(old('role', $user?->role) === 'petugas')>Petugas</option>
                </x-ui.select>
            </x-ui.form-field>

            <div class="md:col-span-2 rounded-2xl border border-border bg-card-grey/40 p-5">
                <div class="mb-4">
                    <p class="text-sm font-semibold text-foreground">{{ $user ? 'Ubah Password' : 'Password Akun' }}</p>
                    <p class="mt-1 text-xs text-secondary">{{ $user ? 'Kosongkan jika tidak ingin mengubah password.' : 'Password wajib diisi untuk akun baru.' }}</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <x-ui.form-field label="Password" for="password" :error="$errors->first('password')" :required="! $user">
                        <x-ui.input type="password" id="password" name="password" autocomplete="new-password" />
                    </x-ui.form-field>

                    <x-ui.form-field label="Konfirmasi Password" for="password_confirmation" :error="$errors->first('password_confirmation')" :required="! $user">
                        <x-ui.input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" />
                    </x-ui.form-field>
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <x-ui.button :href="route('users.index')" variant="secondary">Batal</x-ui.button>
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="{{ $user ? 'save' : 'user-plus' }}" class="size-4"></i>
                {{ $submitLabel }}
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
