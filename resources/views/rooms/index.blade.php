<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Ruangan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f3f4f6; min-height: 100vh; padding: 2rem 1rem; }
        .container { max-width: 1100px; margin: 0 auto; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }

        .btn { display: inline-block; padding: .5rem 1rem; border-radius: 6px; font-size: .875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-primary:hover { background: #4338ca; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-warning:hover { background: #d97706; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        thead { background: #4f46e5; color: #fff; }
        th { padding: .75rem 1rem; text-align: left; font-size: .875rem; font-weight: 600; }
        td { padding: .75rem 1rem; font-size: .875rem; color: #374151; border-bottom: 1px solid #e5e7eb; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fafb; }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; font-size: .875rem; }
        .empty-state { text-align: center; padding: 3rem; color: #6b7280; }
        .actions { display: flex; gap: .5rem; align-items: center; }
        .nav-link { font-size: .875rem; color: #4f46e5; text-decoration: none; }
        .nav-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-bar">
            <h1>Manajemen Ruangan</h1>
            <div style="display:flex; gap:1rem; align-items:center;">
                <a href="{{ route('dashboard') }}" class="nav-link">← Dashboard</a>
                <a href="{{ route('rooms.create') }}" class="btn btn-primary">+ Tambah Ruangan</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($rooms->isEmpty())
            <div class="empty-state">
                <p>Belum ada data ruangan. <a href="{{ route('rooms.create') }}" class="nav-link">Tambah ruangan pertama</a>.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Nama Ruangan</th>
                        <th>Kapasitas Laki-laki</th>
                        <th>Kapasitas Perempuan</th>
                        <th>Terisi Laki-laki</th>
                        <th>Terisi Perempuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                        <tr>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->male_capacity }}</td>
                            <td>{{ $room->female_capacity }}</td>
                            <td>{{ $room->male_occupied }}</td>
                            <td>{{ $room->female_occupied }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                                          onsubmit="return confirm('Hapus ruangan {{ addslashes($room->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
