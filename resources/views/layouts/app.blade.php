@php
    $appName = config('app.name', 'Hospital Bed Monitoring');
    $title = trim($__env->yieldContent('title', $appName));
    $pageTitle = trim($__env->yieldContent('page-title', $title));
    $pageDescription = trim($__env->yieldContent('page-description', 'Kelola operasional monitoring bed rumah sakit secara real-time.'));
    $user = auth()->user();
    $userName = $user?->name ?? 'User';
    $userRole = $user?->role ? ucfirst($user->role) : 'Petugas';
    $userInitials = collect(preg_split('/\s+/', trim($userName)))
        ->filter()
        ->map(fn (string $part) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))
        ->take(2)
        ->implode('');
    $userId = $user?->id;
    $unreadNotifications = $user ? $user->unreadNotifications()->count() : 0;
    $searchLinks = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
        ['route' => 'amprahans.index', 'label' => 'Laporan Amprahan', 'icon' => 'clipboard-plus'],
        ['route' => 'notifications.index', 'label' => 'Notifikasi', 'icon' => 'bell'],
        ['route' => 'profile.edit', 'label' => 'Profil Saya', 'icon' => 'user-circle-2'],
    ];

    if ($user && $user->isAdmin()) {
        array_splice($searchLinks, 1, 0, [
            ['route' => 'rooms.index', 'label' => 'Manajemen Ruangan', 'icon' => 'bed-double'],
            ['route' => 'users.index', 'label' => 'Manajemen Pengguna', 'icon' => 'users-round'],
        ]);
    }

    $logoutFormId = 'sidebar-logout-form';
    $logoutModalId = 'confirm-logout';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    @stack('head')
</head>
<body class="min-h-screen overflow-x-hidden bg-white font-sans text-foreground">
    <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/80 lg:hidden" onclick="toggleSidebar()"></div>

    <div class="flex h-screen max-w-full overflow-hidden bg-muted">
        <aside id="sidebar" class="fixed left-0 z-50 flex h-screen w-[280px] -translate-x-full flex-col border-r border-border bg-white transition-transform duration-300 lg:translate-x-0">
            <div class="flex h-[90px] items-center gap-3 border-b border-border px-6">
                <div class="flex size-10 items-center justify-center rounded-xl bg-primary shadow-lg shadow-primary/20">
                    <i data-lucide="cross" class="size-5 text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight">BedMonitor</h1>
                    <p class="text-xs text-secondary">Dashboard Rumah Sakit</p>
                </div>
            </div>

            <nav class="scrollbar-hide flex flex-1 flex-col gap-6 overflow-y-auto p-5">
                <div>
                    <h3 class="mb-4 px-2 text-xs font-bold uppercase tracking-wider text-secondary">Menu Utama</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-secondary hover:bg-muted font-medium' }} flex items-center gap-3 rounded-xl p-3.5 transition-all">
                            <i data-lucide="layout-dashboard" class="size-5"></i>
                            <span>Dashboard</span>
                        </a>

                        @if ($user && $user->isAdmin())
                            <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-secondary hover:bg-muted font-medium' }} flex items-center gap-3 rounded-xl p-3.5 transition-all">
                                <i data-lucide="bed-double" class="size-5"></i>
                                <span>Manajemen Ruangan</span>
                            </a>

                            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-secondary hover:bg-muted font-medium' }} flex items-center gap-3 rounded-xl p-3.5 transition-all">
                                <i data-lucide="users-round" class="size-5"></i>
                                <span>Manajemen Pengguna</span>
                            </a>
                        @endif

                        <a href="{{ route('amprahans.index') }}" class="{{ request()->routeIs('amprahans.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-secondary hover:bg-muted font-medium' }} flex items-center gap-3 rounded-xl p-3.5 transition-all">
                            <i data-lucide="clipboard-plus" class="size-5"></i>
                            <span>Laporan Amprahan</span>
                        </a>

                        <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-secondary hover:bg-muted font-medium' }} flex items-center gap-3 rounded-xl p-3.5 transition-all">
                            <i data-lucide="bell" class="size-5"></i>
                            <span>Notifikasi</span>
                            <x-ui.badge
                                variant="danger"
                                size="sm"
                                class="ml-auto {{ $unreadNotifications > 0 ? '' : 'hidden' }}"
                                data-unread-badge
                                data-count="{{ $unreadNotifications }}"
                            >{{ $unreadNotifications }}</x-ui.badge>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="mb-4 px-2 text-xs font-bold uppercase tracking-wider text-secondary">Akun</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-secondary hover:bg-muted font-medium' }} flex items-center gap-3 rounded-xl p-3.5 transition-all">
                            <i data-lucide="user-circle-2" class="size-5"></i>
                            <span>Profil Saya</span>
                        </a>

                        <a href="{{ route('monitor.index') }}" class="flex items-center gap-3 rounded-xl p-3.5 font-medium text-secondary transition-all hover:bg-muted">
                            <i data-lucide="monitor-play" class="size-5"></i>
                            <span>Monitor Publik</span>
                        </a>
                    </div>
                </div>

                <div class="mt-auto rounded-3xl bg-primary p-5 text-white shadow-xl shadow-primary/20">
                    <div class="flex items-center gap-3">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-white/15 text-sm font-bold">
                            {{ $userInitials }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold">{{ $userName }}</p>
                            <p class="truncate text-xs text-white/75">{{ $userRole }}</p>
                        </div>
                    </div>

                    <form id="{{ $logoutFormId }}" method="POST" action="{{ route('logout') }}" class="hidden">
                        @csrf
                    </form>

                    <button type="button" onclick="openModal('{{ $logoutModalId }}')" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition-all hover:bg-white/20">
                        <i data-lucide="log-out" class="size-4"></i>
                        Logout
                    </button>
                </div>
            </nav>
        </aside>

        <main class="relative flex min-h-screen min-w-0 flex-1 flex-col bg-white lg:ml-[280px]">
            <header class="sticky top-0 z-30 flex min-h-[76px] items-center justify-between gap-3 border-b border-border bg-white px-4 py-3 md:h-[90px] md:px-8 md:py-0">
                <div class="flex min-w-0 flex-1 items-center gap-3 md:gap-4">
                    <button type="button" onclick="toggleSidebar()" class="flex size-10 items-center justify-center rounded-xl bg-muted text-secondary transition-colors hover:bg-primary/10 hover:text-primary lg:hidden">
                        <i data-lucide="menu" class="size-5"></i>
                    </button>
                    <div class="min-w-0 flex-1">
                        <h2 class="max-w-full break-words text-base font-bold leading-tight text-foreground sm:text-lg md:text-2xl">{{ $pageTitle }}</h2>
                        <p class="hidden text-sm text-secondary md:block">{{ $pageDescription }}</p>
                    </div>
                </div>

                <div class="shrink-0 flex items-center gap-2 md:gap-3">
                    <x-ui.button variant="ghost" size="icon" onclick="openModal('global-search-modal')" aria-label="Buka pencarian">
                        <i data-lucide="search" class="size-5"></i>
                    </x-ui.button>

                    <x-ui.button href="{{ route('notifications.index') }}" variant="ghost" size="icon" class="relative" aria-label="Buka notifikasi">
                        <i data-lucide="bell" class="size-5"></i>
                        <span class="absolute right-2 top-2 size-2 rounded-full bg-error {{ $unreadNotifications > 0 ? '' : 'hidden' }}" data-unread-dot></span>
                    </x-ui.button>

                    <div class="hidden items-center gap-3 border-l border-border pl-4 md:flex">
                        <div class="text-right">
                            <p class="text-sm font-bold">{{ $userName }}</p>
                            <p class="text-xs text-secondary">{{ $userRole }}</p>
                        </div>
                        <div class="flex size-10 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">
                            {{ $userInitials }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 min-w-0 overflow-y-auto overflow-x-hidden bg-muted/30 p-4 md:p-8">
                @if (session('success'))
                    <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
                @endif

                @if (session('status') && !in_array(session('status'), ['profile-updated', 'password-updated'], true))
                    <x-ui.alert variant="info">{{ session('status') }}</x-ui.alert>
                @endif

                @hasSection('content')
                    @yield('content')
                @elseif (isset($slot))
                    {{ $slot }}
                @endif
            </div>
        </main>
    </div>

    <x-ui.confirm-modal
        :name="$logoutModalId"
        title="Logout dari sistem?"
        message="Sesi Anda akan diakhiri dan Anda akan kembali ke halaman login."
        :form-id="$logoutFormId"
        confirm-label="Ya, Logout"
        variant="danger"
    />

    <div id="toast-container" class="fixed bottom-4 right-4 z-[150] flex max-w-sm flex-col gap-3"></div>

    <div id="global-search-modal" data-ui-modal class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-2xl overflow-hidden rounded-[28px] border border-border bg-white shadow-2xl">
            <div class="flex items-center gap-3 border-b border-border px-5 py-4">
                <i data-lucide="search" class="size-5 text-secondary"></i>
                <input id="global-search-input" type="text" placeholder="Cari menu atau halaman..." class="flex-1 bg-transparent text-sm text-foreground outline-none placeholder:text-secondary/70">
                <button type="button" class="rounded-lg border border-border bg-white px-2 py-1 text-xs font-bold text-secondary" data-modal-close>ESC</button>
            </div>
            <div id="global-search-results" class="max-h-[360px] overflow-y-auto p-4">
                @foreach ($searchLinks as $searchLink)
                    <a href="{{ route($searchLink['route']) }}" data-search-item data-search-label="{{ \Illuminate\Support\Str::lower($searchLink['label']) }}" class="flex items-center gap-3 rounded-2xl p-3 transition-all hover:bg-muted">
                        <div class="flex size-10 items-center justify-center rounded-xl bg-primary/10 text-primary">
                            <i data-lucide="{{ $searchLink['icon'] }}" class="size-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-foreground">{{ $searchLink['label'] }}</p>
                            <p class="text-xs text-secondary">Buka halaman {{ \Illuminate\Support\Str::lower($searchLink['label']) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @stack('modals')

    @if ($userId)
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            function playNotificationSound() {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;

                if (!AudioContextClass) {
                    return;
                }

                const audioContext = new AudioContextClass();

                if (audioContext.state === 'suspended') {
                    audioContext.resume().catch(() => {});
                }

                const notes = [
                    { frequency: 880, start: 0, duration: 0.09 },
                    { frequency: 1174, start: 0.12, duration: 0.12 },
                ];

                notes.forEach((note) => {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(note.frequency, audioContext.currentTime + note.start);

                    gainNode.gain.setValueAtTime(0.0001, audioContext.currentTime + note.start);
                    gainNode.gain.exponentialRampToValueAtTime(0.12, audioContext.currentTime + note.start + 0.01);
                    gainNode.gain.exponentialRampToValueAtTime(0.0001, audioContext.currentTime + note.start + note.duration);

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.start(audioContext.currentTime + note.start);
                    oscillator.stop(audioContext.currentTime + note.start + note.duration);
                });

                window.setTimeout(() => audioContext.close().catch(() => {}), 800);
            }

            document.addEventListener('DOMContentLoaded', () => {
                const pusherKey = @js(config('broadcasting.connections.pusher.key'));
                const pusherCluster = @js(config('broadcasting.connections.pusher.options.cluster'));
                const pusherHost = @js(config('broadcasting.connections.pusher.host'));
                const pusherPort = @js(config('broadcasting.connections.pusher.port'));
                const pusherScheme = @js(config('broadcasting.connections.pusher.scheme'));
                const userId = {{ $userId }};
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                if (!pusherKey || pusherKey === 'your-pusher-app-key') {
                    return;
                }

                const pusher = new Pusher(pusherKey, {
                    cluster: pusherCluster || 'mt1',
                    wsHost: pusherHost || undefined,
                    wsPort: pusherPort || undefined,
                    wssPort: pusherPort || undefined,
                    forceTLS: pusherScheme === 'https',
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        }
                    }
                });

                const channel = pusher.subscribe('private-notifications.' + userId);

                function updateUnreadIndicators() {
                    document.querySelectorAll('[data-unread-badge]').forEach((element) => {
                        const currentCount = Number(element.dataset.count || element.textContent || 0) + 1;
                        element.dataset.count = String(currentCount);
                        element.textContent = String(currentCount);
                        element.classList.remove('hidden');
                    });

                    document.querySelectorAll('[data-unread-dot]').forEach((element) => {
                        element.classList.remove('hidden');
                    });
                }

                function handleNotification(data) {
                    if (!data) return;

                    showAppToast('Laporan baru: ' + (data.room_name || '-') + ' · Jam ' + (data.report_time || '-'), 'primary');
                    playNotificationSound();
                    updateUnreadIndicators();
                }

                channel.bind('amprahan.notification.created', handleNotification);
            });
        </script>
    @endif

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function openModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.add('is-open');
            const input = modal.querySelector('input, textarea, select');
            if (input) {
                setTimeout(() => input.focus(), 20);
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('is-open');
        }

        function showAppToast(message, variant = 'primary') {
            const palette = {
                primary: 'bg-primary',
                success: 'bg-success',
                danger: 'bg-error',
                warning: 'bg-warning text-foreground',
            };
            const toast = document.createElement('div');
            toast.className = `translate-y-20 opacity-0 transform rounded-2xl px-5 py-3 text-sm font-medium text-white shadow-xl transition-all duration-300 ${palette[variant] || palette.primary}`;
            toast.textContent = message;

            const container = document.getElementById('toast-container');
            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-20', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        document.addEventListener('click', (event) => {
            const closeTrigger = event.target.closest('[data-modal-close]');
            if (closeTrigger) {
                const modal = closeTrigger.closest('[data-ui-modal]');
                if (modal) closeModal(modal.id);
            }

            const backdrop = event.target.closest('[data-ui-modal]');
            if (backdrop && event.target === backdrop) {
                closeModal(backdrop.id);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                document.querySelectorAll('[data-ui-modal].is-open').forEach((modal) => closeModal(modal.id));
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }

            const searchInput = document.getElementById('global-search-input');
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const value = searchInput.value.trim().toLowerCase();
                    document.querySelectorAll('[data-search-item]').forEach((item) => {
                        item.classList.toggle('hidden', !item.dataset.searchLabel.includes(value));
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
