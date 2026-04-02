<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Amprahan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f3f4f6; min-height: 100vh; padding: 2rem 1rem; }
        .container { max-width: 1100px; margin: 0 auto; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }

        .btn { display: inline-block; padding: .5rem 1rem; border-radius: 6px; font-size: .875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-primary:hover { background: #4338ca; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        thead { background: #4f46e5; color: #fff; }
        th { padding: .75rem 1rem; text-align: left; font-size: .875rem; font-weight: 600; }
        td { padding: .75rem 1rem; font-size: .875rem; color: #374151; border-bottom: 1px solid #e5e7eb; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fafb; }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: .875rem; }
        .empty-state { text-align: center; padding: 3rem; color: #6b7280; }
        .nav-link { font-size: .875rem; color: #4f46e5; text-decoration: none; }
        .nav-link:hover { text-decoration: underline; }
        .pagination { display: flex; gap: .5rem; margin-top: 1.25rem; justify-content: center; }
        .pagination a, .pagination span { padding: .4rem .75rem; border-radius: 6px; font-size: .875rem; border: 1px solid #d1d5db; text-decoration: none; color: #374151; }
        .pagination a:hover { background: #f3f4f6; }
        .pagination .active { background: #4f46e5; color: #fff; border-color: #4f46e5; }

        .shift-badge {
            display: inline-block; padding: .2rem .6rem; border-radius: 4px;
            font-size: .75rem; font-weight: 600; text-transform: capitalize;
        }
        .shift-pagi   { background: #fef9c3; color: #854d0e; }
        .shift-sore   { background: #ffedd5; color: #9a3412; }
        .shift-malam  { background: #e0e7ff; color: #3730a3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-bar">
            <h1>Laporan Amprahan</h1>
            <div style="display:flex; gap:1rem; align-items:center;">
                <a href="{{ route('dashboard') }}" class="nav-link">← Dashboard</a>
                <a href="{{ route('amprahans.create') }}" class="btn btn-primary">+ Buat Laporan</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($reports->isEmpty())
            <div class="empty-state">
                <p>Belum ada laporan amprahan. <a href="{{ route('amprahans.create') }}" class="nav-link">Buat laporan pertama</a>.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Ruangan</th>
                        <th>Shift</th>
                        <th>Jam Amprahan</th>
                        <th>Pasien Pria</th>
                        <th>Pasien Wanita</th>
                        <th>Nama Petugas</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->room->name ?? '-' }}</td>
                            <td>
                                <span class="shift-badge shift-{{ $report->shift }}">{{ ucfirst($report->shift) }}</span>
                            </td>
                            <td>{{ $report->report_time }}</td>
                            <td>{{ $report->male_patient_count }}</td>
                            <td>{{ $report->female_patient_count }}</td>
                            <td>{{ $report->officer_name ?: '-' }}</td>
                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('amprahans.show', $report) }}" class="nav-link">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</body>
</html>
