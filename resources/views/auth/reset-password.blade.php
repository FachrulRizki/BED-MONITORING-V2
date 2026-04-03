<x-guest-layout>
    <div class="mb-8">
        <x-ui.badge variant="primary">Reset Password</x-ui.badge>
        <h2 class="mt-4 text-3xl font-bold text-foreground">Atur Ulang Password</h2>
        <p class="mt-2 text-sm leading-6 text-secondary">Masukkan email dan password baru untuk memulihkan akses akun.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-ui.form-field label="Email" for="email" :error="$errors->first('email')" required>
            <x-ui.input type="email" id="email" name="email" :value="old('email', $request->email)" required autofocus />
        </x-ui.form-field>

        <x-ui.form-field label="Password Baru" for="password" :error="$errors->first('password')" required>
            <x-ui.input type="password" id="password" name="password" autocomplete="new-password" required />
        </x-ui.form-field>

        <x-ui.form-field label="Konfirmasi Password Baru" for="password_confirmation" :error="$errors->first('password_confirmation')" required>
            <x-ui.input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required />
        </x-ui.form-field>

        <div class="flex justify-end pt-2">
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="save" class="size-4"></i>
                Simpan Password
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
