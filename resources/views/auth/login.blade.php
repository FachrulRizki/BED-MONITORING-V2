<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Hospital Bed Monitoring</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --primary-light: #3b82f6;
            --accent: #06b6d4;
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --white: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.97);
        }

        html, body {
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0e7490 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background circles */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            opacity: 0.08;
            animation: float 8s ease-in-out infinite;
        }
        body::before {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #38bdf8, transparent);
            top: -150px; right: -100px;
        }
        body::after {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #818cf8, transparent);
            bottom: -120px; left: -80px;
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        /* Wrapper */
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 520px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            animation: slideUp 0.5s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Left branding panel */
        .brand-panel {
            flex: 1;
            background: linear-gradient(160deg, #1d4ed8 0%, #0e7490 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            top: -80px; right: -80px;
        }
        .brand-panel::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            bottom: -60px; left: -60px;
        }

        .brand-icon {
            font-size: 5rem;
            margin-bottom: 1.25rem;
            animation: pulse 3s ease-in-out infinite;
            position: relative;
            z-index: 1;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        .brand-name {
            font-size: 1.6rem;
            font-weight: 800;
            text-align: center;
            line-height: 1.3;
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .brand-tagline {
            font-size: 0.9rem;
            opacity: 0.8;
            text-align: center;
            line-height: 1.6;
            max-width: 220px;
            position: relative;
            z-index: 1;
        }

        .brand-divider {
            width: 50px;
            height: 3px;
            background: rgba(255,255,255,0.4);
            border-radius: 2px;
            margin: 1.25rem auto;
            position: relative;
            z-index: 1;
        }

        .brand-features {
            list-style: none;
            margin-top: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .brand-features li {
            font-size: 0.82rem;
            opacity: 0.75;
            padding: 0.3rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .brand-features li::before {
            content: '✓';
            font-weight: 700;
            color: #67e8f9;
        }

        /* Right form panel */
        .form-panel {
            flex: 1;
            background: var(--bg-card);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 2.5rem;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        .form-header p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Error alert */
        .alert-error {
            background: var(--danger-bg);
            border: 1px solid #fecaca;
            border-left: 4px solid var(--danger);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
            color: var(--danger);
        }

        .alert-error p + p { margin-top: 0.25rem; }

        /* Form fields */
        .field {
            margin-bottom: 1.25rem;
        }

        .field label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            pointer-events: none;
            opacity: 0.5;
        }

        .field input {
            width: 100%;
            padding: 0.65rem 0.85rem 0.65rem 2.5rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.9rem;
            color: var(--text);
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field input:focus {
            border-color: var(--primary-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .field-error {
            font-size: 0.78rem;
            color: var(--danger);
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 0.5rem;
            letter-spacing: 0.02em;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            box-shadow: 0 4px 14px rgba(29, 78, 216, 0.35);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(29, 78, 216, 0.45);
            opacity: 0.95;
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(29, 78, 216, 0.3);
        }

        /* Public monitor link */
        .public-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .public-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
        }

        .public-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* ── Mobile: stack vertically ── */
        @media (max-width: 640px) {
            body { padding: 0; align-items: stretch; }

            .login-wrapper {
                flex-direction: column;
                border-radius: 0;
                min-height: 100vh;
                box-shadow: none;
            }

            .brand-panel {
                padding: 2rem 1.5rem 1.75rem;
                flex: none;
            }

            .brand-icon { font-size: 3.5rem; margin-bottom: 0.75rem; }
            .brand-name { font-size: 1.25rem; }
            .brand-features { display: none; }
            .brand-divider { margin: 0.75rem auto; }

            .form-panel {
                flex: 1;
                padding: 2rem 1.5rem;
                border-radius: 20px 20px 0 0;
                margin-top: -16px;
                position: relative;
                z-index: 2;
            }
        }

        @media (min-width: 641px) and (max-width: 768px) {
            .login-wrapper { max-width: 680px; }
            .brand-panel { padding: 2rem 1.5rem; }
            .brand-features { display: none; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">

        {{-- Left: Branding --}}
        <div class="brand-panel">
            <div class="brand-icon">🏥</div>
            <div class="brand-name">Hospital Bed<br>Monitoring</div>
            <div class="brand-divider"></div>
            <p class="brand-tagline">Pantau ketersediaan bed rumah sakit secara real-time</p>
            <ul class="brand-features">
                <li>Monitoring bed real-time</li>
                <li>Laporan amprahan digital</li>
                <li>Notifikasi instan</li>
                <li>Tampilan publik informatif</li>
            </ul>
        </div>

        {{-- Right: Login Form --}}
        <div class="form-panel">
            <div class="form-header">
                <h2>Selamat Datang</h2>
                <p>Masuk untuk mengakses sistem monitoring</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="field">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">✉️</span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@rumahsakit.com"
                            required
                            autofocus
                            autocomplete="email"
                        >
                    </div>
                    @error('email')
                        <p class="field-error">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    @error('password')
                        <p class="field-error">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Masuk ke Sistem</button>
            </form>

            <p class="public-link">
                Tidak perlu login? <a href="/">Lihat Monitor Publik →</a>
            </p>
        </div>

    </div>
</body>
</html>
