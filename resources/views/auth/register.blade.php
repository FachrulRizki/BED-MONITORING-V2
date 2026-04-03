<x-guest-layout>
    <div class="mb-8">
        <x-ui.badge variant="primary">Registrasi</x-ui.badge>
        <h2 class="mt-4 text-3xl font-bold text-foreground">Buat Akun Baru</h2>
        <p class="mt-2 text-sm leading-6 text-secondary">Lengkapi data akun petugas dengan struktur form yang sama seperti halaman lain.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <x-ui.form-field label="Nama" for="name" :error="$errors->first('name')" required>
            <x-ui.input id="name" name="name" :value="old('name')" autocomplete="name" required autofocus />
        </x-ui.form-field>

        <x-ui.form-field label="Username" for="username" :error="$errors->first('username')" required>
            <x-ui.input id="username" name="username" :value="old('username')" autocomplete="username" required />
        </x-ui.form-field>

        <x-ui.form-field label="Email" for="email" :error="$errors->first('email')" required>
            <x-ui.input type="email" id="email" name="email" :value="old('email')" autocomplete="email" required />
        </x-ui.form-field>

        <x-ui.form-field label="Password" for="password" :error="$errors->first('password')" required>
            <x-ui.input type="password" id="password" name="password" autocomplete="new-password" required />
        </x-ui.form-field>

        <x-ui.form-field label="Konfirmasi Password" for="password_confirmation" :error="$errors->first('password_confirmation')" required>
            <x-ui.input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required />
        </x-ui.form-field>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-primary hover:underline">Sudah punya akun?</a>
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="user-plus" class="size-4"></i>
                Daftar
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
