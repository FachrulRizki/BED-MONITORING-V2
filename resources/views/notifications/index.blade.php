<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f3f4f6; min-height: 100vh; padding: 2rem 1rem; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }

        .btn { display: inline-block; padding: .5rem 1rem; border-radius: 6px; font-size: .875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-secondary:hover { background: #d1d5db; }
        .btn-sm { padding: .3rem .7rem; font-size: .8rem; }
        .btn-read { background: #4f46e5; color: #fff; }
        .btn-read:hover { background: #4338ca; }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: .875rem; }

        .notification-list { display: flex; flex-direction: column; gap: .75rem; }

        .notification-item {
            background: #fff;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }
        .notification-item.unread {
            background: #eef2ff;
            border-left: 4px solid #4f46e5;
        }
        .notification-item.read {
            border-left: 4px solid #e5e7eb;
            opacity: .85;
        }

        .notif-body { flex: 1; }
        .notif-title { font-size: .9rem; font-weight: 600; color: #111827; margin-bottom: .25rem; }
        .notif-meta { font-size: .8rem; color: #6b7280; margin-bottom: .5rem; }
        .notif-link { font-size: .8rem; color: #4f46e5; text-decoration: none; }
        .notif-link:hover { text-decoration: underline; }
        .badge-unread { display: inline-block; background: #4f46e5; color: #fff; font-size: .7rem; font-weight: 700; padding: .1rem .4rem; border-radius: 4px; margin-left: .4rem; vertical-align: middle; }

        .notif-actions { flex-shrink: 0; }

        .empty-state { text-align: center; padding: 3rem; color: #6b7280; }
        .nav-link { font-size: .875rem; color: #4f46e5; text-decoration: none; }
        .nav-link:hover { text-decoration: underline; }

        /* Toast */
        #toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999; display: flex; flex-direction: column; gap: .5rem; }
        .toast { background: #1e1b4b; color: #fff; padding: .75rem 1.25rem; border-radius: 8px; font-size: .875rem; box-shadow: 0 4px 12px rgba(0,0,0,.2); animation: slideIn .3s ease; max-width: 320px; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
    </style>
</head>
<body>
    <div id="toast-container"></div>

    <div class="container">
        <div class="header-bar">
            <h1>
                Notifikasi
                @php $unreadCount = $notifications->whereNull('read_at')->count(); @endphp
                @if($unreadCount > 0)
                    <span class="badge-unread">{{ $unreadCount }}</span>
                @endif
            </h1>
            <div style="display:flex; gap:1rem; align-items:center;">
                <a href="{{ route('dashboard') }}" class="nav-link">← Dashboard</a>
                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Tandai Semua Dibaca</button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($notifications->isEmpty())
            <div class="empty-state">
                <p>Tidak ada notifikasi.</p>
            </div>
        @else
            <div class="notification-list">
                @foreach($notifications as $notification)
                    @php $isUnread = is_null($notification->read_at); @endphp
                    <div class="notification-item {{ $isUnread ? 'unread' : 'read' }}">
                        <div class="notif-body">
                            <div class="notif-title">
                                Laporan Amprahan Baru — {{ $notification->data['room_name'] ?? '-' }}
                                @if($isUnread)
                                    <span class="badge-unread">Baru</span>
                                @endif
                            </div>
                            <div class="notif-meta">
                                Jam amprahan: {{ $notification->data['report_time'] ?? '-' }}
                                &nbsp;·&nbsp;
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                            @if(!empty($notification->data['link']))
                                <a href="{{ $notification->data['link'] }}" class="notif-link">Lihat Detail Laporan →</a>
                            @endif
                        </div>
                        @if($isUnread)
                            <div class="notif-actions">
                                <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-read">Tandai Dibaca</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Laravel Echo: subscribe ke private channel notifikasi user yang login --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        const pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';
        const pusherCluster = '{{ config('broadcasting.connections.pusher.options.cluster') }}';
        const pusherHost = '{{ config('broadcasting.connections.pusher.host') }}';
        const pusherPort = '{{ config('broadcasting.connections.pusher.port') }}';
        const pusherScheme = '{{ config('broadcasting.connections.pusher.scheme') }}';
        const userId = {{ auth()->id() }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (pusherKey && pusherKey !== 'your-pusher-app-key') {
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

            channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function(data) {
                showToast(data.room_name, data.report_time);
            });

            // Also listen for custom event name
            channel.bind('NewAmprahanNotification', function(data) {
                showToast(data.room_name, data.report_time);
            });
        }

        function showToast(roomName, reportTime) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = 'Laporan baru: ' + (roomName || '-') + ' — Jam ' + (reportTime || '-');
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }
    </script>
</body>
</html>
