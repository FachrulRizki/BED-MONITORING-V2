<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ruangan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f3f4f6; min-height: 100vh; padding: 2rem 1rem; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .nav-link { font-size: .875rem; color: #4f46e5; text-decoration: none; }
        .nav-link:hover { text-decoration: underline; }

        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.08); padding: 1.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: .875rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
        input[type="text"], input[type="number"] {
            width: 100%; padding: .5rem .75rem; border: 1px solid #d1d5db;
            border-radius: 6px; font-size: .875rem; color: #111827;
        }
        input:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 2px rgba(79,70,229,.15); }
        .error { color: #dc2626; font-size: .8rem; margin-top: .3rem; }
        .btn { display: inline-block; padding: .5rem 1.25rem; border-radius: 6px; font-size: .875rem; font-weight: 600; cursor: pointer; border: none; }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-primary:hover { background: #4338ca; }
        .btn-secondary { background: #e5e7eb; color: #374151; text-decoration: none; }
        .btn-secondary:hover { background: #d1d5db; }
        .form-actions { display: flex; gap: .75rem; margin-top: 1.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-bar">
            <h1>Tambah Ruangan</h1>
            <a href="{{ route('rooms.index') }}" class="nav-link">← Kembali</a>
        </div>

        <div class="card">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Ruangan</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           placeholder="Contoh: Ruang Mawar" required>
                    @error('name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="male_capacity">Kapasitas Bed Laki-laki</label>
                    <input type="number" id="male_capacity" name="male_capacity"
                           value="{{ old('male_capacity', 0) }}" min="0" required>
                    @error('male_capacity')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="female_capacity">Kapasitas Bed Perempuan</label>
                    <input type="number" id="female_capacity" name="female_capacity"
                           value="{{ old('female_capacity', 0) }}" min="0" required>
                    @error('female_capacity')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
