# BED-MONITORING-V2

Aplikasi Laravel untuk monitoring ketersediaan bed rumah sakit, laporan amprahan per shift, notifikasi realtime, dan manajemen pengguna admin.

## Fitur

- Login memakai `username` dan `password`
- Dashboard internal dengan ringkasan okupansi, laporan terbaru, dan notifikasi
- Monitor publik ketersediaan bed secara realtime
- Laporan amprahan dengan:
  - shift saat ini dan shift berikutnya
  - petugas shift saat ini dan petugas shift berikutnya
  - jam amprahan format 24 jam
  - gambar wajib
  - `rencana_tindakan` opsional
- Cetak laporan amprahan dengan filter rentang tanggal
- Notifikasi realtime Pusher untuk admin/pengguna login
- Bunyi notifikasi di browser saat laporan baru masuk
- Manajemen ruangan untuk admin
- Manajemen pengguna untuk admin

## Stack

- PHP 8.2+
- Laravel 12
- MySQL / MariaDB / SQLite
- Vite
- Tailwind CSS
- Pusher

## Setup

1. Install dependency backend:

```bash
composer install
```

2. Install dependency frontend:

```bash
npm install
```

3. Buat file environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Atur koneksi database di `.env`.

5. Jalankan migrasi dan seeder:

```bash
php artisan migrate
php artisan db:seed
```

6. Build asset frontend:

```bash
npm run build
```

7. Jalankan aplikasi:

```bash
php artisan serve
```

Untuk development frontend:

```bash
npm run dev
```

## Akun Default

Seeder membuat 2 akun awal:

- Admin
  - username: `admin`
  - password: `password`
- Petugas
  - username: `petugas`
  - password: `password`

## Realtime Pusher

Aplikasi memakai Pusher untuk:

- update monitor bed
- badge notifikasi
- toast notifikasi
- bunyi notifikasi browser

Pastikan `.env` berisi konfigurasi berikut:

```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=ap1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
```

## Testing

Jalankan test:

```bash
php artisan test
```

- Jalankan `php artisan migrate --force` di server setelah update schema
- Jalankan `npm run build` lalu deploy folder `public/build`
- Pastikan kredensial Pusher valid di server production
- Jika memakai shared hosting, pastikan websocket/broadcast client ke Pusher tidak diblokir

## Lisensi

Proyek ini mengikuti lisensi MIT, mengikuti basis Laravel yang digunakan.
