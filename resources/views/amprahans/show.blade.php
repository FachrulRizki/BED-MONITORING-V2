<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan Amprahan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f3f4f6; min-height: 100vh; padding: 2rem 1rem; }
        .container { max-width: 700px; margin: 0 auto; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .nav-link { font-size: .875rem; color: #4f46e5; text-decoration: none; }
        .nav-link:hover { text-decoration: underline; }

        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.08); padding: 1.5rem; }
        .field { display: flex; padding: .75rem 0; border-bottom: 1px solid #e5e7eb; }
        .field:last-child { border-bottom: none; }
        .field-label { width: 200px; flex-shrink: 0; font-size: .875rem; font-weight: 600; color: #6b7280; }
        .field-value { font-size: .875rem; color: #111827; flex: 1; }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: .875rem; }

        .report-image { max-width: 100%; border-radius: 8px; margin-top: .5rem; border: 1px solid #e5e7eb; }

        .shift-badge {
            display: inline-block; padding: .2rem .6rem; border-radius: 4px;
            font-size: .8rem; font-weight: 600; text-transform: capitalize;
        }
        .shift-pagi   { background: #fef9c3; color: #854d0e; }
        .shift-sore   { background: #ffedd5; color: #9a3412; }
        .shift-malam  { background: #e0e7ff; color: #3730a3; }

        .nav-actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-bar">
            <h1>Detail Laporan Amprahan</h1>
            <div style="display:flex; gap:1rem; align-items:center;">
                <a href="{{ route('dashboard') }}" class="nav-link">← Dashboard</a>
                <a href="{{ route('amprahans.index') }}" class="nav-link">Daftar Laporan</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="field">
                <span class="field-label">Ruangan</span>
                <span class="field-value">{{ $amprahan->room->name ?? '-' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Shift</span>
                <span class="field-value">
                    <span class="shift-badge shift-{{ $amprahan->shift }}">{{ ucfirst($amprahan->shift) }}</span>
                </span>
            </div>
            <div class="field">
                <span class="field-label">Jam Amprahan</span>
                <span class="field-value">{{ $amprahan->report_time }}</span>
            </div>
            <div class="field">
                <span class="field-label">Jumlah Pasien Pria</span>
                <span class="field-value">{{ $amprahan->male_patient_count }}</span>
            </div>
            <div class="field">
                <span class="field-label">Jumlah Pasien Wanita</span>
                <span class="field-value">{{ $amprahan->female_patient_count }}</span>
            </div>
            <div class="field">
                <span class="field-label">Nama Petugas</span>
                <span class="field-value">{{ $amprahan->officer_name ?: '-' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Disubmit Oleh</span>
                <span class="field-value">{{ $amprahan->submittedBy->name ?? '-' }}</span>
            </div>
            <div class="field">
                <span class="field-label">Tanggal Dibuat</span>
                <span class="field-value">{{ $amprahan->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($amprahan->image_path)
            <div class="field" style="flex-direction: column;">
                <span class="field-label" style="margin-bottom:.5rem;">Gambar</span>
                <img src="{{ Storage::url($amprahan->image_path) }}" alt="Gambar laporan amprahan" class="report-image">
            </div>
            @endif
        </div>

        <div class="nav-actions">
            <a href="{{ route('amprahans.index') }}" class="nav-link">← Kembali ke Daftar Laporan</a>
        </div>
    </div>
</body>
</html>
