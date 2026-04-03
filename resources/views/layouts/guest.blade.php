<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hospital Bed Monitoring') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen bg-muted font-sans text-foreground">
    <div class="relative isolate min-h-screen overflow-hidden">
        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full gap-8 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="hidden rounded-[32px] bg-gradient-to-br from-primary to-emerald-900 p-10 text-white shadow-2xl shadow-primary/20 lg:flex lg:flex-col lg:justify-between">
                    <div>
                        <div class="mb-8 flex size-14 items-center justify-center rounded-2xl bg-white/15">
                            <i data-lucide="cross" class="size-7"></i>
                        </div>
                        <h1 class="max-w-sm text-4xl font-bold leading-tight">Hospital Bed Monitoring</h1>
                        <p class="mt-4 max-w-md text-sm leading-7 text-white/80">
                            Sistem pemantauan bed rumah sakit dengan tampilan konsisten, cepat dibaca, dan siap dipakai petugas setiap shift.
                        </p>
                    </div>

                    <div class="space-y-3 mt-4">
                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4">
                            <p class="text-sm font-semibold">Monitoring real-time</p>
                            <p class="mt-1 text-sm text-white/75">Status ketersediaan kamar dan bed selalu mudah dipantau.</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4">
                            <p class="text-sm font-semibold">Laporan shift terpusat</p>
                            <p class="mt-1 text-sm text-white/75">Amprahan, notifikasi, dan perubahan okupansi berada dalam alur yang sama.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[32px] border border-border bg-white p-6 shadow-2xl shadow-slate-200/70 sm:p-8 lg:p-10">
                    <div class="mb-8 flex items-center gap-3 lg:hidden">
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-primary text-white shadow-lg shadow-primary/20">
                            <i data-lucide="cross" class="size-5"></i>
                        </div>
                        <div>
                            <p class="text-lg font-bold">Hospital Bed Monitoring</p>
                            <p class="text-xs text-secondary">Sistem operasional petugas</p>
                        </div>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
