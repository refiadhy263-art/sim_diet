# SIM_Diet

SIM_Diet adalah aplikasi manajemen nutrisi rumah sakit berbasis CodeIgniter 4. Aplikasi ini membantu ahli gizi dan admin rumah sakit mengelola pasien, bangsal, bed, jenis diet, bentuk diet, dan log aktivitas.

## Fitur Utama

- Login dan autentikasi pengguna
- Dashboard manajemen rumah sakit
- Manajemen bangsal dan bed
- Manajemen pasien dan status perawatan
- Pencatatan dan penugasan diet pasien
- Master data jenis diet dan bentuk diet
- Manajemen pengguna: admin, ahli gizi, perawat, pramusaji
- Log aktivitas perubahan data

## Struktur Aplikasi

- `app/Controllers/` - controller utama aplikasi
- `app/Models/` - model untuk akses data
- `app/Views/` - tampilan HTML dan layout
- `app/Config/` - konfigurasi CodeIgniter
- `public/` - root web publik
- `writable/` - file cache, logs, session, dan upload
- `vendor/` - dependency Composer

## Persyaratan

- PHP 8.2 atau lebih baru
- CodeIgniter 4
- Composer
- Web server (XAMPP / Apache / Nginx)
- MySQL / MariaDB

## Instalasi

1. Pastikan Apache / PHP / MySQL berjalan (misalnya lewat XAMPP).
2. Salin file `env` ke file `.env` dan aktifkan konfigurasi environment:

```bash
copy env .env
```

3. Buka `.env` dan atur koneksi database:

```ini
database.default.hostname = localhost
database.default.database = nama_database
database.default.username = root
database.default.password = 
```

4. Jalankan Composer untuk menginstal dependency:

```bash
composer install
```

5. Pastikan folder `writable/` bisa ditulis oleh web server.
6. Siapkan database. Jika memiliki migration atau dump SQL, impor ke database Anda.

## Menjalankan Aplikasi

Akses aplikasi melalui browser di:

```text
http://localhost/sim_diet/public
```

Jika menggunakan `spark` dari CodeIgniter, jalankan:

```bash
php spark serve
```

## Penggunaan Umum

- Login terlebih dahulu ke sistem
- Admin dapat mengelola master data dan pengguna
- Ahli gizi dapat melihat daftar pasien, menugaskan diet, serta melihat riwayat dan laporan
- Data pasien dan bangsal dapat dipantau dan diperbarui secara langsung

## Catatan
- Pastikan user dan role di database sudah dikonfigurasi untuk akses modul yang benar.

## Perintah Tambahan

- `composer test` — menjalankan unit test jika tersedia
- `php spark migrate` — jalankan migration jika database migration tersedia
- `php spark db:seed` — jalankan seeder jika tersedia

## Lisensi

Aplikasi ini menggunakan lisensi MIT sesuai konfigurasi `composer.json`.
