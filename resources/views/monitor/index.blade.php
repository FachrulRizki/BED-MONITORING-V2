<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Monitor Ketersediaan Bed — Hospital Bed Monitoring</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #0f172a;
            color: #f1f5f9;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: #1e293b;
            border-bottom: 2px solid #334155;
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: .03em;
            color: #f8fafc;
        }
        .header-title span {
            color: #38bdf8;
        }
        .header-meta {
            font-size: 1rem;
            color: #94a3b8;
            text-align: right;
        }
        .header-meta .clock {
            font-size: 1.5rem;
            font-weight: 700;
            color: #e2e8f0;
            display: block;
        }

        /* Login link */
        .login-link {
            display: inline-block;
            margin-top: .4rem;
            font-size: .85rem;
            color: #64748b;
            text-decoration: none;
            transition: color .15s;
        }
        .login-link:hover { color: #94a3b8; }

        /* Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }

        /* Room card */
        .card {
            border-radius: 16px;
            padding: 1.75rem 2rem;
            position: relative;
            overflow: hidden;
            transition: transform .2s;
            color: #ffffff;
        }
        .card:hover { transform: translateY(-3px); }

        .card.green {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            border: 2px solid #10b981;
            box-shadow: 0 0 24px rgba(16, 185, 129, .25);
        }
        .card.red {
            background: linear-gradient(135deg, #450a0a 0%, #7f1d1d 100%);
            border: 2px solid #ef4444;
            box-shadow: 0 0 24px rgba(239, 68, 68, .25);
        }

        /* Status indicator dot */
        .status-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: inline-block;
            margin-right: .5rem;
            vertical-align: middle;
            animation: pulse-dot 2s infinite;
        }
        .green .status-dot { background: #10b981; box-shadow: 0 0 8px #10b981; }
        .red   .status-dot { background: #ef4444; box-shadow: 0 0 8px #ef4444; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .7; transform: scale(1.2); }
        }

        /* Card content */
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        .room-name {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: .02em;
            color: #ffffff;
            line-height: 1.2;
        }

        .status-label {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .green .status-label { color: #a7f3d0; }
        .red   .status-label { color: #fecaca; }

        /* Percentage */
        .percentage-wrap {
            margin-bottom: 1.25rem;
        }
        .percentage-value {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -.02em;
        }
        .green .percentage-value { color: #ffffff; }
        .red   .percentage-value { color: #ffffff; }
        .percentage-label {
            font-size: .9rem;
            color: #e2e8f0;
            margin-top: .2rem;
        }

        /* Progress bar */
        .progress-bar-bg {
            background: rgba(255,255,255,.1);
            border-radius: 8px;
            height: 12px;
            margin-bottom: 1.25rem;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 8px;
            transition: width .5s ease;
        }
        .green .progress-bar-fill { background: #10b981; }
        .red   .progress-bar-fill { background: #ef4444; }

        /* Bed details */
        .bed-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }
        .bed-item {
            background: rgba(255,255,255,.07);
            border-radius: 10px;
            padding: .75rem 1rem;
        }
        .bed-item-label {
            font-size: .75rem;
            color: #e2e8f0;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .3rem;
        }
        .bed-item-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
        }
        .bed-item-sub {
            font-size: .75rem;
            color: #cbd5e1;
            margin-top: .1rem;
        }

        /* Empty state */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            color: #475569;
            font-size: 1.25rem;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 1rem 2rem 1.5rem;
            color: #475569;
            font-size: .85rem;
        }
        .footer a { color: #64748b; text-decoration: none; }
        .footer a:hover { color: #94a3b8; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="header-title">🏥 <span>Monitor</span> Ketersediaan Bed</div>
            <a href="{{ route('login') }}" class="login-link">Login Petugas →</a>
        </div>
        <div class="header-meta">
            <span class="clock" id="clock">--:--:--</span>
            <span>Diperbarui otomatis setiap 30 detik</span>
        </div>
    </div>

    <div class="grid" id="rooms-grid">
        @forelse ($rooms as $room)
            @php
                $color    = $room['status_color'];   // 'green' or 'red'
                $pct      = $room['usage_percentage'];
                $maleAvail   = $room['male_capacity']   - $room['male_occupied'];
                $femaleAvail = $room['female_capacity'] - $room['female_occupied'];
            @endphp
            <div class="card {{ $color }}" data-room-id="{{ $room['id'] }}">
                <div class="card-header">
                    <span class="status-dot"></span>
                    <span class="room-name">{{ $room['name'] }}</span>
                </div>

                <div class="status-label">
                    {{ $color === 'green' ? 'Tersedia' : 'Penuh' }}
                </div>

                <div class="percentage-wrap">
                    <div class="percentage-value">{{ $pct }}%</div>
                    <div class="percentage-label">Tingkat penggunaan</div>
                </div>

                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" style="width: {{ $pct }}%"></div>
                </div>

                <div class="bed-details">
                    <div class="bed-item">
                        <div class="bed-item-label">♂ <br>Laki-laki</div>
                        <div class="bed-item-value">{{ $maleAvail }}</div>
                        <div class="bed-item-sub">tersedia / {{ $room['male_capacity'] }}</div>
                    </div>
                    <div class="bed-item">
                        <div class="bed-item-label">♀ <br>Perempuan</div>
                        <div class="bed-item-value">{{ $femaleAvail }}</div>
                        <div class="bed-item-sub">tersedia / {{ $room['female_capacity'] }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                Belum ada data ruangan.
            </div>
        @endforelse
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Hospital Bed Monitoring &mdash;
        <a href="{{ route('login') }}">Login Petugas</a>
    </div>

    {{-- Live clock --}}
    <script>
        function updateClock() {
            var now = new Date();
            var h = String(now.getHours()).padStart(2, '0');
            var m = String(now.getMinutes()).padStart(2, '0');
            var s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = h + ':' + m + ':' + s;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    {{-- Real-time updates via Pusher / Laravel Echo --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        var PUSHER_KEY     = '{{ config("broadcasting.connections.pusher.key") }}';
        var PUSHER_CLUSTER = '{{ config("broadcasting.connections.pusher.options.cluster", "mt1") }}';
        var PUSHER_HOST    = '{{ config("broadcasting.connections.pusher.host", "") }}';
        var PUSHER_PORT    = {{ config("broadcasting.connections.pusher.port", 443) }};
        var PUSHER_SCHEME  = '{{ config("broadcasting.connections.pusher.scheme", "https") }}';

        if (PUSHER_KEY && PUSHER_KEY !== 'your-pusher-app-key') {
            var pusherOptions = { cluster: PUSHER_CLUSTER || 'mt1' };

            if (PUSHER_HOST && PUSHER_HOST !== '') {
                pusherOptions.wsHost            = PUSHER_HOST;
                pusherOptions.wsPort            = PUSHER_PORT;
                pusherOptions.wssPort           = PUSHER_PORT;
                pusherOptions.forceTLS          = PUSHER_SCHEME === 'https';
                pusherOptions.enabledTransports = ['ws', 'wss'];
                pusherOptions.disableStats      = true;
            }

            var pusher     = new Pusher(PUSHER_KEY, pusherOptions);
            var bedChannel = pusher.subscribe('bed-availability');

            bedChannel.bind('BedAvailabilityUpdated', function (data) {
                if (!data || !data.rooms) return;

                data.rooms.forEach(function (room) {
                    var card = document.querySelector('[data-room-id="' + room.id + '"]');
                    if (!card) return;

                    var totalCap  = room.male_capacity   + room.female_capacity;
                    var totalOcc  = room.male_occupied    + room.female_occupied;
                    var pct       = totalCap > 0 ? Math.round((totalOcc / totalCap) * 1000) / 10 : 0;
                    var isFull    = totalOcc >= totalCap;
                    var color     = isFull ? 'red' : 'green';
                    var maleAvail = room.male_capacity   - room.male_occupied;
                    var femAvail  = room.female_capacity - room.female_occupied;

                    // Update card color class
                    card.classList.remove('green', 'red');
                    card.classList.add(color);

                    // Update status label
                    var statusLabel = card.querySelector('.status-label');
                    if (statusLabel) statusLabel.textContent = isFull ? 'Penuh' : 'Tersedia';

                    // Update percentage
                    var pctEl = card.querySelector('.percentage-value');
                    if (pctEl) pctEl.textContent = pct + '%';

                    // Update progress bar
                    var bar = card.querySelector('.progress-bar-fill');
                    if (bar) bar.style.width = pct + '%';

                    // Update bed details
                    var bedItems = card.querySelectorAll('.bed-item');
                    if (bedItems[0]) {
                        bedItems[0].querySelector('.bed-item-value').textContent = maleAvail;
                        bedItems[0].querySelector('.bed-item-sub').textContent   = 'tersedia / ' + room.male_capacity;
                    }
                    if (bedItems[1]) {
                        bedItems[1].querySelector('.bed-item-value').textContent = femAvail;
                        bedItems[1].querySelector('.bed-item-sub').textContent   = 'tersedia / ' + room.female_capacity;
                    }
                });
            });
        }
    </script>

</body>
</html>
