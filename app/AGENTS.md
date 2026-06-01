# SIM_DIET: Panduan Instruksi AI Agent

**SIM_DIET** (Sistem Informasi Manajemen Diet) adalah sistem manajemen diet pasien rumah sakit yang dibangun dengan CodeIgniter 4. Dokumen ini membantu AI agent memahami arsitektur kode, konvensi, dan alur kerja pengembangan.

## Ringkasan Proyek

- **Tujuan:** Mengelola pasien, diet, perawat, dan bangsal di rumah sakit dengan akses berbasis peran
- **Framework:** CodeIgniter 4 (PHP 8.2+, MySQL, Tailwind CSS)
- **Peran Pengguna:** Perawat (perawat), Ahli Gizi (gizi), Administrator
- **Arsitektur:** MVC dengan autentikasi berbasis sesi dan frontend pertama AJAX

## Memulai dengan Cepat

```bash
# 1. Setup lingkungan
cp .env.example .env
# Update .env: database.default.database=sim_diet, username=root, password (kosong untuk XAMPP)

# 2. Dependensi dan migrasi
composer install
php spark migrate

# 3. Jalankan server pengembangan
php spark serve  # http://localhost:8080
# Kredensial login: Periksa migrasi Users untuk pengguna demo (jika seeded)
```

**File Kunci:** [.env](.env), [Config/Routes.php](Config/Routes.php), [Config/Database.php](Config/Database.php)

## Esensi Arsitektur

### Titik Masuk & Routing

- **Titik masuk utama:** `public/index.php` (di luar folder app untuk keamanan)
- **Rute:** [Config/Routes.php](Config/Routes.php)
  - Publik: `/` (beranda), `/login`, `/logout`
  - Terauthentikasi: Semua rute dibungkus dalam `$routes->group('', ['filter' => 'authenticate'], ...)`
  - Endpoint AJAX: Pola RESTful seperti `/pasien/getData`, `/perawat/save`

### Lapisan Controller

Semua controller mewarisi dari `BaseController`. Pola standar:

```php
// Konstruktor: Inisialisasi model
protected $pasien;
public function __construct() {
    $this->pasien = new Pasien_model();
}

// Metode AJAX: Kembalikan JSON
public function getData() {
    return $this->response->setJSON(['status' => 'success', 'data' => [...]]);
}

// Pengiriman formulir: Validasi, lalu respons
$rules = ['field' => 'required|min_length[3]'];
if (!$this->validate($rules)) {
    return $this->response->setJSON(['status' => 'error', 'message' => $this->validator->getErrors()]);
}
```

**Controller Kunci:** 
- [Controllers/Login.php](Controllers/Login.php) - Autentikasi
- [Controllers/Pasien.php](Controllers/Pasien.php) - CRUD Pasien, pelacakan status, laporan
- [Controllers/Perawat.php](Controllers/Perawat.php), [Controllers/Bangsal.php](Controllers/Bangsal.php), [Controllers/Bed.php](Controllers/Bed.php) - Manajemen sumber daya

### Lapisan Model

Semua model mewarisi dari class `Model` CodeIgniter dengan konvensi berikut:

```php
protected $table = 'pasien';                    // Nama tabel (snake_case)
protected $primaryKey = 'id_pasien';            // Kunci primer (id_<tabel>)
protected $useTimestamps = true;                // Auto-managed created_at, updated_at
protected $allowedFields = [                    // Daftar putih penugasan massal
    'nama_pasien', 'id_bangsal', 'id_bed', 'id_jenis_diet'
];

// Metode standar:
public function getAll($search = null, $filters = []) {}      // Daftar dengan filter
public function getIdPasien($id) {}                            // Catatan tunggal
public function countAllResults() {}                           // Jumlah catatan
```

**Model Kunci:** Direktori [Models/](Models/) - `Pasien_model`, `Users_model`, `Bangsal_model`, `Bed_model`, dll.

### Autentikasi & Keamanan

- **Berbasis sesi:** Periksa `session('logged_in')` untuk memverifikasi autentikasi
- **Filter:** [Filters/Authenticate.php](Filters/Authenticate.php) melindungi rute
- **Data sesi:** Menyimpan `id_users`, `username`, `role`, `id_bangsal` (bangsal)
- **Password:** Selalu gunakan `password_hash($password, PASSWORD_DEFAULT)` dan `password_verify()`
- **Penyaringan bangsal:** Sebagian besar query disaring oleh `session('id_bangsal')`; ini penting untuk isolasi data

```php
// Periksa autentikasi
if (!session('logged_in')) {
    return redirect()->to(site_url(''));
}

// Dapatkan bangsal pengguna saat ini (penting untuk query)
$id_bangsal = session('id_bangsal');
$patients = $this->pasien->where('id_bangsal', $id_bangsal)->findAll();
```

## Konvensi Penamaan

| Item | Pola | Contoh |
|---|---|---|
| **Controller** | PascalCase | `Pasien`, `Perawat` |
| **Model** | PascalCase + `_model` | `Pasien_model`, `Users_model` |
| **View** | snake_case + `_view` | `pasien_tampil_view`, `login_view` |
| **Tabel** | snake_case | `pasien`, `jenis_diet` |
| **Kunci Primer** | `id_<entitas>` | `id_pasien`, `id_bangsal` |
| **Rute** | kebab-case | `/pasien/edit_status_rawat` |

## Skema Database

Migrasi mendefinisikan semua tabel. Hubungan kunci:
- **Users** → Peran (perawat, ahli gizi, admin)
- **Pasien (pasien)** → Bangsal (bangsal) → Tempat tidur (bed)
- **Pasien** → Jenis Diet (jenis_diet) + Bentuk Diet (bentuk_diet)
- **Perawat/Ahli Gizi** → Penugasan bangsal
- **Log** - Melacak operasi sistem

**Migrasi:** [Database/Migrations/](Database/Migrations/) - periksa ini untuk memahami skema dan hubungan.

## Pola Umum

### Format Respons AJAX
```php
// Sukses
['status' => 'success', 'message' => 'Data tersimpan', 'data' => [...]]

// Kesalahan validasi
['status' => 'error', 'message' => ['field' => 'pesan kesalahan']]

// Kesalahan server
['status' => 'error', 'message' => 'Kesalahan server']
```

### Validasi
```php
$rules = [
    'nama_pasien' => 'required|min_length[3]|max_length[100]',
    'id_bangsal' => 'required|numeric',
];
if (!$this->validate($rules)) {
    return $this->response->setJSON([
        'status' => 'error',
        'message' => $this->validator->getErrors()
    ]);
}
```

### Unggah File
```php
$photo = $this->request->getFile('photo');
if ($photo->isValid() && !$photo->hasMoved()) {
    $photo->move('uploads/perawat/', $photo->getRandomName());
}
```

### Bidang Status
- Pola umum: `0` = aktif/tersedia, `1` = inaktif/terpakai (misalnya ketersediaan tempat tidur, status pasien)
- Periksa komentar model atau migrasi untuk makna spesifik

## Lapisan View

- **Framework:** Tailwind CSS (styling utility-first)
- **Ikon:** Ikon Feather dengan pola class `fi fi-rr-*`
- **Tabel:** DataTables untuk paginasi dan pencarian
- **Inheritance layout:** `<?= $this->extend('layout/admin/admin_layout_view') ?>`
- **Bagian:** Gunakan `<?= $this->section('content') ?>...<?= $this->endSection() ?>`
- **Modal:** Fungsi JavaScript seperti `showModalTambahPasien()` menangani tampilan modal dan pengiriman AJAX

**Direktori View:** [Views/](Views/) diorganisir berdasarkan fitur dan peran

## Alur Kerja Pengembangan

### Saat Menambah Fitur

1. **Tentukan rute** di [Config/Routes.php](Config/Routes.php) (sertakan filter: 'authenticate')
2. **Buat metode controller** mengikuti pola standar (daftar, edit, simpan, hapus)
3. **Implementasikan query model** dengan penyaringan yang tepat (terutama berdasarkan bangsal)
4. **Bangun view** menggunakan Tailwind CSS dan inheritance layout
5. **Validasi input** di sisi server di controller; kembalikan JSON untuk AJAX
6. **Uji dengan AJAX** panggilan dari konsol browser atau formulir frontend

### Saat Memodifikasi Database

1. Buat migrasi: `php spark make:migration NamaMigrasi`
2. Jalankan: `php spark migrate`
3. Perbarui model yang sesuai (nama tabel, kunci primer, bidang)

### Masalah Umum

- **Autentikasi gagal:** Pastikan data sesi diatur selama login (`session()->set([...])`)
- **Kebocoran data bangsal:** Selalu saring query oleh `session('id_bangsal')`
- **AJAX tidak merespons:** Periksa konsol browser untuk kesalahan 404; verifikasi rute ada di [Config/Routes.php](Config/Routes.php)
- **Password gagal:** Gunakan `password_verify()`, bukan perbandingan `==`
- **Migrasi gagal:** Periksa charset MySQL adalah `utf8mb4`; periksa ekstensi PHP (intl, mbstring)

## Pembantu & Utilitas

- [Helpers/Global_helper.php](Helpers/Global_helper.php) - Fungsi utilitas global
- [Helpers/Status_helper.php](Helpers/Status_helper.php) - Pembantu terkait status
- Keduanya dimuat otomatis melalui [Config/Autoload.php](Config/Autoload.php)

## Pengujian & Debugging

- **Log server:** Periksa `VSCODE_TARGET_SESSION_LOG` untuk output debug terperinci
- **Query database:** Aktifkan logging di [Config/Database.php](Config/Database.php)
- **Konsol browser:** Periksa permintaan dan respons AJAX

## Sumber Daya

- [Dokumentasi CodeIgniter 4](https://codeigniter.com/user_guide/)
- [Dokumentasi Tailwind CSS](https://tailwindcss.com/docs)
- [Dokumentasi DataTables](https://datatables.net/manual/)
- README Proyek: [README.md](../README.md)
