<x-guest-layout>
    <div class="mb-8">
        <x-ui.badge variant="primary">Verifikasi</x-ui.badge>
        <h2 class="mt-4 text-3xl font-bold text-foreground">Verifikasi Email</h2>
        <p class="mt-2 text-sm leading-6 text-secondary">Sebelum melanjutkan, verifikasi alamat email Anda dari tautan yang telah dikirim.</p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <x-ui.alert variant="success">
            Link verifikasi baru telah dikirim ke alamat email Anda.
        </x-ui.alert>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="mail-check" class="size-4"></i>
                Kirim Ulang Email Verifikasi
            </x-ui.button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-ui.button variant="secondary" type="submit">
                <i data-lucide="log-out" class="size-4"></i>
                Keluar
            </x-ui.button>
        </form>
    </div>
</x-guest-layout>
