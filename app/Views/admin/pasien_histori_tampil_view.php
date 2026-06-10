<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET </title>
<link href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
    // Mengambil nilai filter dari URL (jika ada) untuk mempertahakan pilihan dropdown
    $request       = \Config\Services::request();
    $filter_bulan  = $request->getGet('bulan') ?? date('m');
    $filter_tahun  = $request->getGet('tahun') ?? date('Y');

    // Daftar Bulan
    $list_bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    // Daftar Tahun (Menampilkan tahun ini mundur ke 3 tahun ke belakang)
    $tahun_sekarang = date('Y');
?>

<div class="space-y-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fi fi-rr-history text-blue-600 flex items-center"></i> 
            Riwayat Pasien Pulang/Meninggal (<?= esc($nama_bangsal) ?>)
        </h2>
        <br>

     
    </div>
       <form method="GET" action="" class="flex items-center gap-2 w-full md:w-auto">
            <select name="bulan" class="bg-white border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 outline-none">
                <option value="">-- Semua Bulan --</option>
                <?php foreach ($list_bulan as $num => $name): ?>
                    <option value="<?= $num ?>" <?= ($filter_bulan == $num) ? 'selected' : '' ?>>
                        <?= $name ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="tahun" class="bg-white border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 outline-none">
                <option value="">-- Semua Tahun --</option>
                <?php for ($t = $tahun_sekarang; $t >= $tahun_sekarang - 3; $t--): ?>
                    <option value="<?= $t ?>" <?= ($filter_tahun == $t) ? 'selected' : '' ?>>
                        <?= $t ?>
                    </option>
                <?php endfor; ?>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors flex items-center gap-2">
                <i class="fi fi-rr-filter"></i> Filter
            </button>
        </form>
    
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="p-4 bg-gray-50 border-b">
            <p class="text-sm text-gray-500 font-medium">Daftar pasien di bawah ini sudah tidak menempati bed dan tidak akan menerima suplai makanan.</p>
        </div>
        
        <div class="p-4">
            <table id="tableRiwayat" class="w-full text-sm">
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
                            <td colspan="6" class="p-10 text-center text-gray-400 font-medium">Belum ada riwayat pasien pulang atau meninggal di bangsal ini pada periode tersebut.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Inisialisasi DataTables
$(document).ready(function() {
    $('#tableRiwayat').DataTable({
        responsive: true,
        language: {
            search: "Cari data:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            zeroRecords: "Data tidak ditemukan"
        }
    });
});

function updateStatusPasien(pasienId, statusBaru, element) {
    // Menggunakan SweetAlert2 untuk konfirmasi
    Swal.fire({
        title: 'Kembalikan Pasien?',
        text: "Apakah Anda yakin ingin mengembalikan pasien ini ke status Dirawat? Pastikan bed yang ditempati masih tersedia.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            
            // Ubah teks tombol menjadi loading
            const originalText = element.innerHTML;
            element.innerHTML = '<i class="fi fi-rr-spinner animate-spin"></i> Memproses...';
            element.disabled = true;

            const formData = new FormData();
            formData.append('id_pasien', pasienId);
            formData.append('status_rawat', statusBaru);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

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
                    
                    // Efek hapus baris
                    const row = element.closest('tr');
                    row.classList.add('transition-all', 'duration-500', 'opacity-0', 'scale-95');
                    setTimeout(() => {
                        row.remove();
                    }, 500);

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    element.innerHTML = originalText;
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
                element.innerHTML = originalText;
                element.disabled = false;
            });
        }
    });
}
</script>
<?= $this->endSection() ?>