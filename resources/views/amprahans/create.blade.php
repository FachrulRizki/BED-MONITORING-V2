<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Amprahan</title>
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
        input[type="text"], input[type="number"], input[type="time"], input[type="file"], select {
            width: 100%; padding: .5rem .75rem; border: 1px solid #d1d5db;
            border-radius: 6px; font-size: .875rem; color: #111827; background: #fff;
        }
        input:focus, select:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 2px rgba(79,70,229,.15); }
        .error { color: #dc2626; font-size: .8rem; margin-top: .3rem; }
        .hint { color: #6b7280; font-size: .8rem; margin-top: .3rem; }
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
            <h1>Buat Laporan Amprahan</h1>
            <a href="{{ route('amprahans.index') }}" class="nav-link">← Kembali</a>
        </div>

        <div class="card">
            <form action="{{ route('amprahans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="room_id">Nama Ruangan</label>
                    <select id="room_id" name="room_id" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="shift">Shift</label>
                    <select id="shift" name="shift" required>
                        <option value="">-- Pilih Shift --</option>
                        <option value="pagi" {{ old('shift') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                        <option value="sore" {{ old('shift') == 'sore' ? 'selected' : '' }}>Sore</option>
                        <option value="malam" {{ old('shift') == 'malam' ? 'selected' : '' }}>Malam</option>
                    </select>
                    @error('shift')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="report_time">Jam Amprahan</label>
                    <input type="time" id="report_time" name="report_time"
                           value="{{ old('report_time') }}" required>
                    @error('report_time')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="male_patient_count">Jumlah Pasien Pria</label>
                    <input type="number" id="male_patient_count" name="male_patient_count"
                           value="{{ old('male_patient_count', 0) }}" min="0" required>
                    @error('male_patient_count')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="female_patient_count">Jumlah Pasien Wanita</label>
                    <input type="number" id="female_patient_count" name="female_patient_count"
                           value="{{ old('female_patient_count', 0) }}" min="0" required>
                    @error('female_patient_count')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="officer_name">Nama Petugas <span style="font-weight:400;color:#6b7280;">(opsional)</span></label>
                    <input type="text" id="officer_name" name="officer_name"
                           value="{{ old('officer_name') }}" placeholder="Nama petugas shift ini">
                    @error('officer_name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Gambar <span style="font-weight:400;color:#6b7280;">(opsional)</span></label>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png">
                    <p class="hint">Format: JPG, JPEG, PNG. Maksimal 5 MB.</p>
                    @error('image')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                    <a href="{{ route('amprahans.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
