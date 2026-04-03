<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Amprahan</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            color: #111827;
            background: #fff;
        }
        .page {
            padding: 24px;
        }
        .header {
            margin-bottom: 24px;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 8px;
        }
        .subtitle {
            font-size: 14px;
            color: #4b5563;
            margin: 0;
        }
        .meta {
            margin-top: 12px;
            font-size: 13px;
            color: #374151;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 10px;
            font-size: 12px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f3f4f6;
            font-weight: 700;
        }
        .toolbar {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        .button {
            border: 1px solid #d1d5db;
            background: #fff;
            color: #111827;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        @media print {
            .toolbar { display: none; }
            .page { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="toolbar">
            <button class="button" onclick="window.print()">Print</button>
            <a class="button" href="{{ route('amprahans.index', request()->only(['date_from', 'date_to'])) }}">Kembali</a>
        </div>

        <div class="header">
            <h1 class="title">Laporan Amprahan</h1>
            <p class="subtitle">Daftar laporan amprahan berdasarkan rentang tanggal yang dipilih.</p>
            <div class="meta">
                Periode:
                <strong>{{ $filters['date_from'] ?? 'awal data' }}</strong>
                s/d
                <strong>{{ $filters['date_to'] ?? now()->toDateString() }}</strong>
                <br>
                Total data: <strong>{{ $reports->count() }}</strong>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Ruangan</th>
                    <th>Shift</th>
                    <th>Jam</th>
                    <th>Pasien</th>
                    <th>Petugas</th>
                    <th>Rencana Tindakan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->room->name ?? '-' }}</td>
                        <td>
                            Saat ini: {{ ucfirst($report->shift) }}<br>
                            Berikutnya: {{ ucfirst($report->next_shift ?: $report->shift) }}
                        </td>
                        <td>{{ $report->report_time }}</td>
                        <td>Pria {{ $report->male_patient_count }}<br>Wanita {{ $report->female_patient_count }}</td>
                        <td>
                            Saat ini: {{ $report->officer_name ?: '-' }}<br>
                            Berikutnya: {{ $report->next_officer_name ?: '-' }}
                        </td>
                        <td>{{ $report->action_plan ?: '-' }}</td>
                        <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Tidak ada laporan pada rentang tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
