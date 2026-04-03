<x-guest-layout>
    <div class="mb-8">
        <x-ui.badge variant="warning">Pemulihan Akses</x-ui.badge>
        <h2 class="mt-4 text-3xl font-bold text-foreground">Lupa Password</h2>
        <p class="mt-2 text-sm leading-6 text-secondary">Masukkan email akun dan kami akan mengirimkan tautan reset password.</p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <x-ui.form-field label="Email" for="email" :error="$errors->first('email')" required>
            <x-ui.input type="email" id="email" name="email" :value="old('email')" required autofocus />
        </x-ui.form-field>

        <div class="flex justify-end pt-2">
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="send" class="size-4"></i>
                Kirim Link Reset
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
