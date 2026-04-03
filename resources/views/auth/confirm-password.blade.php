<x-guest-layout>
    <div class="mb-8">
        <x-ui.badge variant="warning">Konfirmasi</x-ui.badge>
        <h2 class="mt-4 text-3xl font-bold text-foreground">Konfirmasi Password</h2>
        <p class="mt-2 text-sm leading-6 text-secondary">Masukkan password Anda untuk melanjutkan ke tindakan yang memerlukan konfirmasi tambahan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <x-ui.form-field label="Password" for="password" :error="$errors->first('password')" required>
            <x-ui.input type="password" id="password" name="password" autocomplete="current-password" required />
        </x-ui.form-field>

        <div class="flex justify-end pt-2">
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="shield-check" class="size-4"></i>
                Konfirmasi
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
