# SIM_DIET - Workflow Diagram untuk Ahli Gizi

## Flowchart: Main Workflow Ahli Gizi

```mermaid
flowchart TD
    A["🔐 Ahli Gizi Login"] --> B{Login Sukses?}
    B -->|Tidak| C["❌ Tampilkan Error Message"]
    C --> A
    B -->|Ya| D["📊 Tampilkan Dashboard"]
    
    D --> E{Pilih Menu}
    
    E -->|Lihat Pasien| F["📋 View Daftar Pasien di Bangsal Saya"]
    E -->|Assign Diet| G["🍽️ Assign Jenis & Bentuk Diet"]
    E -->|Lihat Detail Pasien| H["👤 View Detail Pasien"]
    E -->|Laporan| I["📈 View Laporan Diet"]
    E -->|Kelola Diet| J{Admin Gizi?}
    E -->|Logout| K["🚪 Logout - Session Destroyed"]
    K --> L["🔒 Kembali ke Login Page"]
    
    J -->|Ya| M["⚙️ Kelola Jenis & Bentuk Diet"]
    J -->|Tidak| N["❌ Akses Ditolak"]
    N --> D
    
    F --> O["📄 Filter berdasarkan Status<br/>(Dirawat, Keluar, dll)"]
    O --> P["✏️ Pilih Pasien untuk Aksi"]
    P --> D
    
    G --> Q["🔍 Cari Pasien"]
    Q --> R["📝 Pilih Pasien"]
    R --> S["🥗 Pilih Jenis Diet<br/>(Diabetes, Rendah Garam, dll)"]
    S --> T["🍲 Pilih Bentuk Diet<br/>(Cair, Lembut, Normal)"]
    T --> U{Konfirmasi?}
    U -->|Batal| D
    U -->|Simpan| V["💾 Update Database"]
    V --> W["✅ Diet berhasil diassign"]
    W --> X["📋 Update Catatan Pasien"]
    X --> D
    
    H --> Y["🔍 Cari Pasien"]
    Y --> Z["👁️ Tampilkan Detail:<br/>- Info Dasar<br/>- Riwayat Diet<br/>- Status Medis<br/>- Catatan Perawat"]
    Z --> AA["📌 Lihat Catatan Khusus Gizi"]
    AA --> D
    
    I --> AB["📊 Filter Laporan:<br/>- Per Bangsal<br/>- Per Diet Type<br/>- Per Tanggal"]
    AB --> AC["📈 Tampilkan Statistik:<br/>- Jumlah Pasien per Diet<br/>- Trend Diet Choices"]
    AC --> AD["🖨️ Export/Print Laporan?"]
    AD -->|Ya| AE["✅ Generate PDF"]
    AD -->|Tidak| AF["👀 View Online"]
    AE --> D
    AF --> D
    
    M --> AG["➕ Tambah/Edit Jenis Diet"]
    M --> AH["➕ Tambah/Edit Bentuk Diet"]
    AG --> AI["💾 Simpan ke Database"]
    AH --> AI
    AI --> AJ["✅ Master Data Updated"]
    AJ --> D
```

## Flowchart: Assign Diet ke Pasien - Detail

```mermaid
flowchart TD
    A["🍽️ Mulai: Assign Diet ke Pasien"] --> B["🔓 Cek Permission: Role = gizi?"]
    B -->|Tidak| C["❌ Akses Ditolak"]
    C --> D["🚫 Redirect ke Dashboard"]
    
    B -->|Ya| E["📥 Ambil id_bangsal dari Session"]
    E --> F["📋 Query: SELECT pasien WHERE id_bangsal = session"]
    F --> G["🔍 Filter Pasien:<br/>- Status Rawat = Aktif<br/>- Urutkan: Terbaru"]
    
    G --> H["👁️ Tampilkan Daftar Pasien"]
    H --> I{Pasien Ada?}
    
    I -->|Tidak| J["⚠️ Tidak ada pasien untuk assign"]
    J --> K["🔙 Kembali ke Menu"]
    
    I -->|Ya| L["✏️ Klik nama Pasien"]
    L --> M["📊 Tampilkan Form Assign Diet"]
    
    M --> N["🔍 GET Jenis Diet dari Database"]
    N --> O["🔍 GET Bentuk Diet dari Database"]
    O --> P["📝 Tampilkan Dropdown:<br/>- Jenis Diet (Diabetes, Rendah Garam, etc)<br/>- Bentuk Diet (Cair, Lembut, Biasa)"]
    
    P --> Q["👤 Pengguna Pilih Jenis Diet"]
    Q --> R["👤 Pengguna Pilih Bentuk Diet"]
    R --> S["📝 (Opsional) Tambah Catatan Khusus"]
    
    S --> T{Validasi Input}
    T -->|Gagal| U["❌ Tampilkan Error Message<br/>- Jenis Diet wajib diisi<br/>- Bentuk Diet wajib diisi"]
    U --> P
    
    T -->|Sukses| V["💾 Validasi Session id_bangsal"]
    V --> W["⚔️ Verifikasi: Pasien milik id_bangsal saya?"]
    W -->|Tidak| X["🚫 Security Error: Data tidak sesuai"]
    X --> D
    
    W -->|Ya| Y["💾 UPDATE pasien SET<br/>id_jenis_diet = ?,<br/>bentuk_diet = ?,<br/>catatan_gizi = ?"]
    Y --> Z["📝 INSERT ke logs:<br/>action=assign_diet,<br/>id_pasien=?, user=?"]
    
    Z --> AA{Update Sukses?}
    AA -->|Gagal| AB["❌ Database Error"]
    AB --> AC["🔄 Retry atau Hubungi Admin"]
    
    AA -->|Sukses| AD["✅ Diet berhasil diassign"]
    AD --> AE["📬 Kirim notifikasi ke Perawat:<br/>(Optional) via system log"]
    AE --> AF["🔙 Redirect ke Daftar Pasien"]
    AF --> K
```

## Flowchart: View Patient Details & Nutrition History

```mermaid
flowchart TD
    A["👤 View Detail Pasien"] --> B["🔍 Cek id_pasien dari URL"]
    B --> C["🔓 Validasi Permission:<br/>Pasien ada di id_bangsal saya?"]
    
    C -->|Tidak| D["🚫 Security Error: Unauthorized"]
    D --> E["❌ Redirect ke Daftar Pasien"]
    
    C -->|Ya| F["📋 Query: SELECT pasien WHERE id_pasien = ?"]
    F --> G["💾 Query: SELECT jenis_diet,<br/>bentuk_diet untuk pasien ini"]
    
    G --> H["📝 Query: SELECT riwayat diet<br/>dari logs (last 10 changes)"]
    H --> I["👥 Query: Perawat yang<br/>menangani pasien ini"]
    
    I --> J["📊 Tampilkan Card Detail Pasien:<br/>- Nama Pasien<br/>- Umur / Tanggal Lahir<br/>- No. Identitas"]
    
    J --> K["🍽️ Tampilkan Info Diet Saat Ini:<br/>- Jenis Diet<br/>- Bentuk Diet<br/>- Tanggal Assign"]
    
    K --> L["📋 Tampilkan Catatan Gizi:<br/>- Catatan Khusus<br/>- Alergi<br/>- Pantangan"]
    
    L --> M["📊 Tampilkan Riwayat Diet:<br/>Tabel dengan:<br/>- Tanggal<br/>- Jenis Diet Sebelumnya<br/>- Jenis Diet Baru<br/>- Alasan Perubahan<br/>- Ahli Gizi yang Assign"]
    
    M --> N["👥 Tampilkan Info Perawat<br/>yang Menangani"]
    N --> O["🔧 Tombol Aksi:<br/>- Edit Diet<br/>- Tambah Catatan<br/>- Print History<br/>- Kembali"]
    
    O --> P{User Klik?}
    
    P -->|Edit Diet| Q["🍽️ Buka Form Edit Diet"]
    Q --> R["🔄 Repeat: Assign Diet Flow"]
    
    P -->|Tambah Catatan| S["📝 Form Tambah Catatan"]
    S --> T["💾 UPDATE pasien SET catatan_gizi = ?"]
    
    P -->|Print History| U["🖨️ Generate PDF<br/>Riwayat Diet Pasien"]
    U --> V["📄 Download / Print"]
    
    P -->|Kembali| W["🔙 Kembali ke Daftar Pasien"]
```

## Flowchart: Generate Laporan Diet

```mermaid
flowchart TD
    A["📈 Mulai: Generate Laporan"] --> B["🎯 Pilih Tipe Laporan"]
    
    B --> C{Tipe Laporan?}
    
    C -->|Laporan per Jenis Diet| D["📊 Filter:<br/>- Jenis Diet<br/>- Tanggal Start - End<br/>- Bangsal (jika multi-bangsal)"]
    
    C -->|Laporan per Pasien| E["📋 Filter:<br/>- Daftar Pasien<br/>- Tanggal Start - End"]
    
    C -->|Laporan Trend| F["📈 Filter:<br/>- Periode<br/>- Bangsal"]
    
    D --> G["🔍 Query Database:<br/>SELECT COUNT pasien<br/>GROUP BY jenis_diet"]
    E --> G
    F --> G
    
    G --> H["💾 Query: Hitung jumlah pasien<br/>untuk setiap jenis diet"]
    
    H --> I["📊 Generate Data:<br/>- Total Pasien<br/>- Breakdown per Diet Type<br/>- Persentase"]
    
    I --> J{Format Output?}
    
    J -->|View Online| K["👁️ Render HTML Table<br/>dengan Chart"]
    J -->|Export PDF| L["🖨️ Generate PDF<br/>dengan Charts & Grafik"]
    J -->|Export Excel| M["📊 Generate Excel File<br/>dengan Pivot Table"]
    
    K --> N["✅ Tampilkan Laporan"]
    L --> O["📄 Download PDF"]
    M --> P["📊 Download Excel"]
    
    N --> Q["🔍 Fitur Tambahan:<br/>- Search/Filter<br/>- Sort by Kolom<br/>- Export ke CSV"]
    O --> R["✅ Laporan Siap"]
    P --> R
    
    Q --> R
    R --> S["🔙 Kembali ke Menu"]
```

## Flowchart: Kelola Master Data (Jenis & Bentuk Diet)

```mermaid
flowchart TD
    A["⚙️ Kelola Master Diet"] --> B["🔓 Cek Permission:<br/>Role = gizi & is_admin?"]
    
    B -->|Tidak| C["❌ Akses Ditolak"]
    C --> D["🚫 Redirect ke Dashboard"]
    
    B -->|Ya| E["📋 Tampilkan List:<br/>- Jenis Diet<br/>- Bentuk Diet"]
    
    E --> F{Aksi?}
    
    F -->|Lihat Jenis Diet| G["📋 Daftar Jenis Diet:<br/>Diabetes, Rendah Garam,<br/>Rendah Kalori, dll"]
    
    F -->|Tambah Jenis Diet| H["📝 Form Tambah:<br/>- Nama Jenis Diet<br/>- Deskripsi<br/>- Pantangan Makanan"]
    H --> I["✔️ Validasi Input"]
    I -->|Error| J["❌ Tampilkan Error"]
    J --> H
    I -->|Valid| K["💾 INSERT INTO jenis_diet"]
    K --> L["✅ Jenis Diet Berhasil Ditambah"]
    L --> G
    
    F -->|Edit Jenis Diet| M["✏️ Pilih Diet untuk Edit"]
    M --> N["📝 Form Edit:<br/>- Update Nama<br/>- Update Deskripsi<br/>- Update Pantangan"]
    N --> O["✔️ Validasi Input"]
    O -->|Error| P["❌ Tampilkan Error"]
    P --> N
    O -->|Valid| Q["💾 UPDATE jenis_diet"]
    Q --> R["✅ Jenis Diet Berhasil Diupdate"]
    R --> G
    
    F -->|Hapus Jenis Diet| S["⚠️ Konfirmasi Hapus"]
    S --> T{Ada Pasien Pakai?}
    T -->|Ya| U["🚫 Tidak bisa hapus:<br/>Ada pasien menggunakan diet ini"]
    U --> G
    T -->|Tidak| V["🗑️ DELETE FROM jenis_diet"]
    V --> W["✅ Jenis Diet Berhasil Dihapus"]
    W --> G
    
    F -->|Lihat Bentuk Diet| X["📋 Daftar Bentuk Diet:<br/>Cair, Lembut, Biasa, dll"]
    F -->|Tambah Bentuk Diet| Y["📝 Form Tambah:<br/>- Nama Bentuk Diet<br/>- Deskripsi"]
    Y --> Z["✔️ Validasi Input"]
    Z -->|Error| AA["❌ Tampilkan Error"]
    AA --> Y
    Z -->|Valid| AB["💾 INSERT INTO bentuk_diet"]
    AB --> AC["✅ Bentuk Diet Berhasil Ditambah"]
    AC --> X
    
    X --> AD{Aksi Lain?}
    G --> AD
    AD -->|Ya| E
    AD -->|Tidak| AE["🔙 Kembali ke Menu"]
```

## Activity Diagram: Daily Workflow Ahli Gizi

```mermaid
graph TD
    A["📅 Pagi: Login ke Sistem"] --> B["📊 Buka Dashboard"]
    B --> C["📋 Cek Daftar Pasien Baru"]
    C --> D["👤 Review Detail Pasien Baru"]
    D --> E["🥗 Assign Jenis Diet Sesuai Diagnosis"]
    E --> F["🍲 Assign Bentuk Diet Sesuai Kemampuan Pasien"]
    
    F --> G["📝 Tambahkan Catatan Khusus:<br/>- Alergi<br/>- Pantangan<br/>- Rekomendasi"]
    
    G --> H["🔔 Notifikasi Perawat tentang Diet"]
    
    H --> I["☕ Break"]
    
    I --> J["📋 Cek Perkembangan Pasien"]
    J --> K{Ada Pasien yang<br/>Perlu Update Diet?}
    
    K -->|Ya| L["✏️ Edit Diet Pasien<br/>& Update Catatan"]
    K -->|Tidak| M["✓ Status Sesuai"]
    
    L --> N["📊 Generate Laporan Harian"]
    M --> N
    
    N --> O["📈 Laporan:<br/>- Pasien Baru: X orang<br/>- Diet Diubah: X orang<br/>- Pasien Total: X orang"]
    
    O --> P["🖨️ Print/Export Laporan"]
    P --> Q["📬 Kirim Laporan ke Manager"]
    
    Q --> R["🚪 Logout"]
```

## Summary: Fitur & Akses Ahli Gizi

| Fitur | Akses | Keterangan |
|-------|-------|-----------|
| Login | ✅ Ya | Autentikasi berbasis username/password |
| View Dashboard | ✅ Ya | Ringkasan pasien dan diet |
| View Daftar Pasien | ✅ Ya | Hanya pasien di bangsal mereka (filter id_bangsal) |
| View Detail Pasien | ✅ Ya | Info lengkap + riwayat diet |
| Assign Jenis Diet | ✅ Ya | Pilih dari master data jenis diet |
| Assign Bentuk Diet | ✅ Ya | Pilih dari master data bentuk diet |
| Edit Diet Pasien | ✅ Ya | Update diet yang sudah diassign |
| Tambah Catatan | ✅ Ya | Catatan khusus untuk pasien |
| View Riwayat Diet | ✅ Ya | Lihat perubahan diet sebelumnya |
| Generate Laporan | ✅ Ya | Laporan diet per jenis/pasien/trend |
| Kelola Jenis Diet | ⚠️ Conditional | Hanya admin gizi |
| Kelola Bentuk Diet | ⚠️ Conditional | Hanya admin gizi |
| Kelola Perawat | ❌ Tidak | Admin/Manager saja |
| Kelola Bangsal | ❌ Tidak | Admin/Manager saja |
| Logout | ✅ Ya | Destroy session |
