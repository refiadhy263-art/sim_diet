<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET </title>
<link href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold flex items-center gap-2"><i class="fi fi-rr-history text-blue-600 flex items-center"></i> Riwayat Pasien Pulang/Meninggal (<?= esc($nama_bangsal) ?>)</h2>
    </div>
    
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="p-4 bg-gray-50 border-b">
            <p class="text-sm text-gray-500 font-medium">Daftar pasien di bawah ini sudah tidak menempati bed dan tidak akan menerima suplai makanan.</p>
        </div>
        
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="p-4 text-left">RM</th>
                    <th class="p-4 text-left">Pasien</th>
                    <th class="p-4 text-left">Diagnosa Terakhir</th>
                    <th class="p-4 text-left">Bed Terakhir</th>
                    <th class="p-4 text-center">Status Akhir</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php if (!empty($pasienList)): ?>
                    <?php foreach ($pasienList as $p): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-mono text-xs text-gray-500"><?= esc($p['no_rm']) ?></td>
                            <td class="p-4 font-bold"><?= esc($p['nama_pasien']) ?></td>
                            <td class="p-4"><?= esc($p['diagnosa'] ?? '-') ?></td>
                            <td class="p-4 text-gray-400 font-mono"><?= esc($p['nama_bed'] ?? $p['id_bed']) ?></td>
                            
                            <td class="p-4 text-center">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= esc(rawatStatus($p['status_rawat'])['color']) ?>">
                                    <?= esc(rawatStatus($p['status_rawat'])['label']) ?>
                                </span>
                            </td>
                            
                            <td class="p-4 text-center">
                                <button onclick="updateStatusPasien('<?= $p['id_pasien'] ?>', '0', this)" 
                                         class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                    <i class="fi fi-rr-undo"></i> Kembalikan Dirawat
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400 font-medium">Belum ada riwayat pasien pulang atau meninggal di bangsal ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>

<script>
function updateStatusPasien(pasienId, statusBaru, element) {
    // Konfirmasi sebelum mengeksekusi
    const konfirmasi = confirm('Apakah Anda yakin ingin mengembalikan pasien ini ke status Dirawat? Pastikan bed yang ditempati masih tersedia.');
    if (!konfirmasi) return;

    // Ubah teks tombol menjadi loading agar tidak diklik dua kali
    const originalText = element.innerText;
    element.innerText = "Memproses...";
    element.disabled = true;

    // Siapkan data yang akan dikirim
    const formData = new FormData();
    formData.append('id_pasien', pasienId);
    formData.append('status_rawat', statusBaru);
    // formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>'); // Hapus komentar jika filter CSRF aktif

    // Kirim data ke backend
    fetch('<?= site_url('pasien/kembalikan_dirawat') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
             Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            });
            
            // Hapus baris pasien dari tabel dengan efek animasi
            const row = element.closest('tr');
            row.classList.add('transition-all', 'duration-500', 'opacity-0', 'scale-95');
            setTimeout(() => {
                row.remove();
                
                // Jika tabel kosong setelah dihapus, tampilkan pesan kosong
                const tbody = document.querySelector('tbody');
                if (tbody.querySelectorAll('tr').length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" class="p-10 text-center text-gray-400 font-medium">Belum ada riwayat pasien pulang atau meninggal di bangsal ini.</td></tr>`;
                }
            }, 500);

        } else {
             Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            });
            // Kembalikan tombol ke semula jika gagal
            element.innerText = originalText;
            element.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan koneksi ke server.',
            showConfirmButton: false,
            timer: 2000
        });
        element.innerText = originalText;
        element.disabled = false;
    });
}


</script>
<?= $this->endSection() ?>