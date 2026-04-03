<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    <title>@yield('title', 'Hospital Bed Monitoring')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    @stack('head')
</head>
<body class="min-h-screen overflow-x-hidden bg-muted font-sans text-foreground">
    <div class="min-h-screen">
        <header class="border-b border-border bg-white/95 backdrop-blur">
            <div class="@yield('header-class', 'mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8 lg:py-5')">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex size-11 items-center justify-center rounded-2xl bg-primary text-white shadow-lg shadow-primary/20">
                        <i data-lucide="cross" class="size-5"></i>
                    </div>
                    <div class="min-w-0">
                        <h1 class="break-words text-base font-bold tracking-tight sm:text-lg">Hospital Bed Monitoring</h1>
                        <p class="text-xs text-secondary">Monitoring ketersediaan bed publik</p>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-border bg-white px-4 py-3 text-sm font-semibold text-foreground transition-all hover:bg-card-grey lg:w-auto">
                    <i data-lucide="log-in" class="size-4"></i>
                    Login Petugas
                </a>
            </div>
        </header>

        <main class="@yield('main-class', 'mx-auto max-w-7xl min-w-0 px-4 py-6 sm:px-6 lg:px-8')">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
