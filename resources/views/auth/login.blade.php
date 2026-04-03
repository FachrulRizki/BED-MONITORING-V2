<x-guest-layout>
    <div class="mb-8">
        <x-ui.badge variant="primary">Akses Petugas</x-ui.badge>
        <h2 class="mt-4 text-3xl font-bold text-foreground">Masuk ke Sistem</h2>
        <p class="mt-2 text-sm leading-6 text-secondary">Gunakan username dan kata sandi Anda untuk mengakses dashboard monitoring bed rumah sakit.</p>
    </div>

    @if ($errors->any())
        <x-ui.alert variant="danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </x-ui.alert>
    @endif

    <form method="POST" action="{{ url('/login') }}" class="space-y-5">
        @csrf

        <x-ui.form-field label="Username" for="username" :error="$errors->first('username')" required>
            <x-ui.input id="username" name="username" :value="old('username')" placeholder="Masukkan username" autocomplete="username" required autofocus />
        </x-ui.form-field>

        <x-ui.form-field label="Kata Sandi" for="password" :error="$errors->first('password')" required>
            <x-ui.input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required />
        </x-ui.form-field>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <a href="/" class="text-sm font-semibold text-primary hover:underline">Lihat Monitor Publik</a>
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="log-in" class="size-4"></i>
                Masuk ke Sistem
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
