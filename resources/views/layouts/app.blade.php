<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hospital Bed Monitoring')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f3f4f6; min-height: 100vh; }

        /* Navbar */
        .navbar {
            background: #4f46e5;
            color: #fff;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 56px;
            box-shadow: 0 2px 6px rgba(0,0,0,.15);
            position: relative;
        }
        .navbar-brand {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            letter-spacing: .02em;
            flex-shrink: 0;
        }
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: .25rem;
            list-style: none;
        }
        .nav-link {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            padding: .4rem .75rem;
            border-radius: 5px;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .nav-link:hover { background: rgba(255,255,255,.15); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,.2); color: #fff; }

        /* Notification badge */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ef4444;
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            padding: 0 4px;
            margin-left: 4px;
            vertical-align: middle;
            line-height: 1;
        }

        /* Logout button */
        .btn-logout {
            background: rgba(255,255,255,.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,.3);
            font-size: .875rem;
            font-weight: 500;
            padding: .35rem .75rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background .15s;
            white-space: nowrap;
        }
        .btn-logout:hover { background: rgba(255,255,255,.25); }

        /* Hamburger button */
        .navbar-toggler {
            display: none;
            background: none;
            border: 1px solid rgba(255,255,255,.4);
            border-radius: 5px;
            padding: .35rem .5rem;
            cursor: pointer;
            flex-direction: column;
            gap: 4px;
            align-items: center;
            justify-content: center;
        }
        .navbar-toggler span {
            display: block;
            width: 20px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: transform .2s, opacity .2s;
        }
        .navbar-toggler[aria-expanded="true"] span:nth-child(1) {
            transform: translateY(6px) rotate(45deg);
        }
        .navbar-toggler[aria-expanded="true"] span:nth-child(2) {
            opacity: 0;
        }
        .navbar-toggler[aria-expanded="true"] span:nth-child(3) {
            transform: translateY(-6px) rotate(-45deg);
        }

        /* Mobile nav */
        @media (max-width: 768px) {
            .navbar {
                flex-wrap: wrap;
                height: auto;
                padding: .75rem 1rem;
            }
            .navbar-brand {
                flex: 1;
            }
            .navbar-toggler {
                display: flex;
            }
            .navbar-nav {
                display: none;
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                padding: .5rem 0 .25rem;
                gap: .1rem;
            }
            .navbar-nav.open {
                display: flex;
            }
            .navbar-nav li {
                width: 100%;
            }
            .navbar-nav .nav-link {
                display: block;
                padding: .6rem .75rem;
                border-radius: 5px;
            }
            .navbar-nav form {
                display: block !important;
                width: 100%;
            }
            .btn-logout {
                display: block;
                width: 100%;
                text-align: left;
                padding: .6rem .75rem;
                border-radius: 5px;
                border: none;
                background: rgba(255,255,255,.1);
            }
            .btn-logout:hover { background: rgba(255,255,255,.2); }
        }

        /* Main content */
        .main-content {
            padding: 2rem 1rem;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* Toast notification (simple, bottom-right) */
        #toast-container {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9998;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }
        .toast {
            background: #1e1b4b;
            color: #fff;
            padding: .75rem 1.25rem;
            border-radius: 8px;
            font-size: .875rem;
            box-shadow: 0 4px 12px rgba(0,0,0,.2);
            max-width: 320px;
            animation: slideIn .25s ease;
        }
        .toast strong { display: block; margin-bottom: .2rem; font-size: .9rem; }

        /* Prominent notification popup (top-right) */
        #notif-popup {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 10000;
            width: 340px;
            background: #fff;
            border-left: 5px solid #ef4444;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,.22), 0 2px 8px rgba(239,68,68,.15);
            padding: 1rem 1.25rem 1rem 1.1rem;
            display: none;
            animation: popupSlideIn .3s cubic-bezier(.22,1,.36,1);
        }
        #notif-popup.show { display: block; }
        #notif-popup .popup-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .5rem;
        }
        #notif-popup .popup-title {
            font-size: .95rem;
            font-weight: 700;
            color: #ef4444;
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        #notif-popup .popup-title .popup-icon {
            font-size: 1.1rem;
            animation: pulse 1s infinite;
        }
        #notif-popup .popup-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 1.1rem;
            line-height: 1;
            padding: 0 .2rem;
            transition: color .15s;
        }
        #notif-popup .popup-close:hover { color: #374151; }
        #notif-popup .popup-body {
            font-size: .875rem;
            color: #374151;
            line-height: 1.5;
        }
        #notif-popup .popup-room {
            font-weight: 600;
            color: #1e1b4b;
            font-size: .95rem;
        }
        #notif-popup .popup-time {
            color: #6b7280;
            font-size: .8rem;
            margin-top: .15rem;
        }
        #notif-popup .popup-link {
            display: inline-block;
            margin-top: .6rem;
            font-size: .8rem;
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #4f46e5;
            border-radius: 4px;
            padding: .2rem .6rem;
            transition: background .15s, color .15s;
        }
        #notif-popup .popup-link:hover { background: #4f46e5; color: #fff; }
        #notif-popup .popup-progress {
            height: 3px;
            background: #ef4444;
            border-radius: 2px;
            margin-top: .75rem;
            transform-origin: left;
            animation: progressBar 6s linear forwards;
        }
        @keyframes popupSlideIn {
            from { opacity: 0; transform: translateX(60px) scale(.95); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.25); }
        }
        @keyframes progressBar {
            from { transform: scaleX(1); }
            to   { transform: scaleX(0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            #notif-popup {
                width: calc(100vw - 2rem);
                right: 1rem;
                top: 1rem;
            }
            .main-content {
                padding: 1rem .75rem;
            }
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar">
        <a href="{{ route('monitor.index') }}" class="navbar-brand">🏥 Hospital Bed Monitoring</a>

        <button class="navbar-toggler" id="navbar-toggler" aria-expanded="false" aria-label="Toggle navigasi">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul class="navbar-nav" id="navbar-nav">
            @auth
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                @if(auth()->check() && auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('rooms.index') }}"
                       class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                        Ruangan
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('amprahans.index') }}"
                       class="nav-link {{ request()->routeIs('amprahans.*') ? 'active' : '' }}">
                        Amprahan
                    </a>
                </li>
                <li>
                    <a href="{{ route('notifications.index') }}"
                       class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                       id="notif-nav-link">
                        Notifikasi
                        @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="badge" id="notif-badge">{{ $unreadCount }}</span>
                        @else
                            <span class="badge" id="notif-badge" style="display:none;">0</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}"
                       class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        Profile
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                        Login Petugas
                    </a>
                </li>
            @endauth
        </ul>
    </nav>

    {{-- Page Content --}}
    <div class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </div>

    {{-- Toast container --}}
    <div id="toast-container"></div>

    {{-- Prominent notification popup --}}
    <div id="notif-popup" role="alert" aria-live="assertive">
        <div class="popup-header">
            <span class="popup-title">
                <span class="popup-icon">🔔</span>
                Laporan Amprahan Baru
            </span>
            <button class="popup-close" id="notif-popup-close" aria-label="Tutup">&times;</button>
        </div>
        <div class="popup-body">
            <div class="popup-room" id="notif-popup-room"></div>
            <div class="popup-time" id="notif-popup-time"></div>
            <a href="#" class="popup-link" id="notif-popup-link">Lihat Laporan &rarr;</a>
        </div>
        <div class="popup-progress" id="notif-popup-progress"></div>
    </div>

    {{-- Hamburger toggle script --}}
    <script>
        (function () {
            var toggler = document.getElementById('navbar-toggler');
            var nav     = document.getElementById('navbar-nav');
            if (!toggler || !nav) return;
            toggler.addEventListener('click', function () {
                var isOpen = nav.classList.toggle('open');
                toggler.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        })();
    </script>

    {{-- Pusher JS --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    {{-- Laravel Echo + Pusher configuration --}}
    <script>
        const PUSHER_KEY     = '{{ config('broadcasting.connections.pusher.key') }}';
        const PUSHER_CLUSTER = '{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}';
        const PUSHER_HOST    = '{{ config('broadcasting.connections.pusher.host', '') }}';
        const PUSHER_PORT    = {{ config('broadcasting.connections.pusher.port', 443) }};
        const PUSHER_SCHEME  = '{{ config('broadcasting.connections.pusher.scheme', 'https') }}';
        const CSRF_TOKEN     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        @auth
        const AUTH_USER_ID   = {{ auth()->id() }};
        @endauth

        // Helper: show simple toast (bottom-right)
        function showToast(title, message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = '<strong>' + title + '</strong>' + (message || '');
            container.appendChild(toast);
            setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity .3s';
                setTimeout(function() { toast.remove(); }, 300);
            }, 5000);
        }

        // Helper: show prominent popup notification (top-right)
        var _popupDismissTimer = null;
        function showPopupNotification(roomName, reportTime, link) {
            var popup    = document.getElementById('notif-popup');
            var roomEl   = document.getElementById('notif-popup-room');
            var timeEl   = document.getElementById('notif-popup-time');
            var linkEl   = document.getElementById('notif-popup-link');
            var progress = document.getElementById('notif-popup-progress');

            roomEl.textContent = roomName || 'Ruangan';
            timeEl.textContent = reportTime ? 'Jam: ' + reportTime : '';
            if (link) {
                linkEl.href = link;
                linkEl.style.display = '';
            } else {
                linkEl.style.display = 'none';
            }

            // Reset progress bar animation
            progress.style.animation = 'none';
            void progress.offsetWidth; // force reflow
            progress.style.animation = '';

            popup.classList.add('show');

            if (_popupDismissTimer) clearTimeout(_popupDismissTimer);
            _popupDismissTimer = setTimeout(function() { dismissPopup(); }, 6000);
        }

        function dismissPopup() {
            var popup = document.getElementById('notif-popup');
            popup.style.opacity = '0';
            popup.style.transition = 'opacity .3s';
            setTimeout(function() {
                popup.classList.remove('show');
                popup.style.opacity = '';
                popup.style.transition = '';
            }, 300);
        }

        document.getElementById('notif-popup-close').addEventListener('click', function() {
            if (_popupDismissTimer) clearTimeout(_popupDismissTimer);
            dismissPopup();
        });

        // Helper: play alert sound via Web Audio API
        function playAlertSound() {
            try {
                var ctx = new (window.AudioContext || window.webkitAudioContext)();
                var oscillator = ctx.createOscillator();
                var gainNode = ctx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);
                oscillator.frequency.value = 880;
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.4, ctx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                oscillator.start();
                oscillator.stop(ctx.currentTime + 0.4);
            } catch (e) {
                // fallback: silent if Web Audio API unavailable
            }
        }

        // Helper: increment notification badge
        function incrementNotifBadge() {
            var badge = document.getElementById('notif-badge');
            if (!badge) return;
            var current = parseInt(badge.textContent) || 0;
            badge.textContent = current + 1;
            badge.style.display = '';
        }

        if (PUSHER_KEY && PUSHER_KEY !== 'your-pusher-app-key') {
            var pusherOptions = {
                cluster: PUSHER_CLUSTER || 'mt1',
            };

            // Support Laravel Reverb (self-hosted) via custom host
            if (PUSHER_HOST && PUSHER_HOST !== '') {
                pusherOptions.wsHost       = PUSHER_HOST;
                pusherOptions.wsPort       = PUSHER_PORT;
                pusherOptions.wssPort      = PUSHER_PORT;
                pusherOptions.forceTLS     = PUSHER_SCHEME === 'https';
                pusherOptions.enabledTransports = ['ws', 'wss'];
                pusherOptions.disableStats = true;
            }

            var pusher = new Pusher(PUSHER_KEY, pusherOptions);

            // Subscribe to public bed-availability channel on all pages
            var bedChannel = pusher.subscribe('bed-availability');
            bedChannel.bind('BedAvailabilityUpdated', function(data) {
                if (typeof window.onBedAvailabilityUpdated === 'function') {
                    window.onBedAvailabilityUpdated(data);
                }
            });

            @auth
            // Subscribe to private notifications channel for logged-in users
            pusher.config.authEndpoint = '/broadcasting/auth';
            pusher.config.auth = {
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
            };

            var notifChannel = pusher.subscribe('private-notifications.' + AUTH_USER_ID);
            notifChannel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function(data) {
                var roomName   = data.room_name   || 'Ruangan';
                var reportTime = data.report_time || '';
                var link       = data.link        || '';
                showPopupNotification(roomName, reportTime, link);
                playAlertSound();
                incrementNotifBadge();
            });
            @endauth
        }
    </script>

    @yield('scripts')

</body>
</html>
