<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET </title>
<link href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold flex items-center gap-2"><i class="fi fi-rr-clipboards text-blue-600 flex items-center"></i> Daftar Pasien Update Status Rawat (<?= esc($nama_bangsal) ?>)</h2>
      
    </div>

   <div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <div class="p-4 bg-gray-50 border-b">
        <p class="text-sm text-gray-500 font-medium">Ubah status menjadi 'Pulang' atau 'Meninggal' akan membebaskan bed agar dapat dipakai pasien lain.</p>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-white border-b">
            <tr>
                <th class="p-4 text-left">Pasien</th>
                <th class="p-4 text-left">RM</th>
                <th class="p-4 text-left">Bed</th>
                <th class="p-4 text-center">Status</th>
                <th class="p-4 text-left">Update Status</th>
            </tr>
        </thead>
        <tbody class="divide-y border-t">
            <?php if (!empty($pasienList)): ?>
                <?php foreach ($pasienList as $p): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 font-bold text-gray-700"><?= esc($p['nama_pasien']) ?></td>
                        <td class="p-4 font-mono text-gray-500 text-xs"><?= esc($p['no_rm']) ?></td>
                        <td class="p-4 font-bold text-blue-600"><?= esc($p['nama_bed']) ?></td>
                        <td class="p-4 text-center">
                           <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= esc(rawatStatus($p['status_rawat'])['color']) ?>">
                                <?= esc(rawatStatus($p['status_rawat'])['label']) ?>
                            </span>
                        </td>
                        <td class="p-4">
                            <select onchange="updateStatusPasien('<?= $p['id_pasien'] ?>', this.value, this)" class="border border-gray-300 rounded-lg p-2 bg-white shadow-sm focus:ring-blue-500 outline-none w-full max-w-xs">
                                <option value="0" <?= $p['status_rawat'] === '0' ? 'selected' : '' ?>>Dirawat</option>
                                <option value="1" <?= $p['status_rawat'] === '1' ? 'selected' : '' ?>>Pulang</option>
                                <option value="2" <?= $p['status_rawat'] === '2' ? 'selected' : '' ?>>Meninggal</option>
                              <option value="3" class="font-bold text-blue-600" <?= $p['status_rawat'] == 3 ? 'selected' : '' ?>>Pindah Bangsal...</option></select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="p-10 text-center text-gray-400 font-medium">Semua bed kosong. Tidak ada pasien dirawat.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Helper function untuk mapping status pasien ke label dan warna
function rawatStatus(statusCode) {
    const statusMap = {
        '0': { label: 'Dirawat', color: 'bg-blue-100 text-blue-800' },
        '1': { label: 'Pulang', color: 'bg-green-100 text-green-800' },
        '2': { label: 'Meninggal', color: 'bg-red-100 text-red-800' },
        '3': { label: 'Pindah Bangsal', color: 'bg-yellow-100 text-yellow-800' }
    };
    return statusMap[statusCode] || { label: 'Unknown', color: 'bg-gray-100 text-gray-800' };
}

function updateStatusPasien(pasienId, statusBaru, element) {
    // 1. Validasi jika user memilih 'Pindah Bangsal' (bisa diarahkan ke fungsi/halaman lain)
    if (statusBaru === '3') {
        showModalPindahBangsal(pasienId, element, statusBaru);
        return;
    }

    // 2. Konfirmasi tindakan ke user untuk status krusial
    if (statusBaru === '2' || statusBaru === '1') {
        Swal.fire({
            title: 'Konfirmasi',
            text: `Apakah Anda yakin ingin mengubah status pasien ini menjadi "${rawatStatus(statusBaru)['label']}"? Tindakan ini akan mengosongkan bed.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) {
                element.value = '0'; // Reset kembali ke 'dirawat' jika user membatalkan
                return;
            }
            // Lanjutkan proses update jika user konfirmasi
            proceedWithStatusUpdate(pasienId, statusBaru, element);
        });
        return;
    }

    // Jika bukan status krusial, langsung proses
    proceedWithStatusUpdate(pasienId, statusBaru, element);
}

// Fungsi terpisah untuk handle proses update setelah konfirmasi
function proceedWithStatusUpdate(pasienId, statusBaru, element) {
    const formData = new FormData();
    formData.append('id_pasien', pasienId);
    formData.append('status_rawat', statusBaru);

    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    // 4. Kirim data ke Controller CodeIgniter 4 menggunakan Fetch API
    // Sesuaikan URL jika project Anda menggunakan sub-folder (contoh: '/my-app/pasien/update-status-action')
    fetch('/pasien/update_status_rawat', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Menandakan bahwa ini adalah request AJAX
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Gagal memperbarui status di server.');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // Tampilkan notifikasi sukses menggunakan SweetAlert2
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            });

            // Karena status berubah dari 'dirawat' ke 'pulang/meninggal', 
            // pasien harusnya hilang dari list bangsal aktif ini.
            // Kita hapus baris tabel (<tr>) pasien tersebut demi UX yang real-time
            if (statusBaru === '2' || statusBaru === '1') {
                const row = element.closest('tr');
                row.classList.add('transition-all', 'duration-500', 'opacity-0', 'scale-95');
                setTimeout(() => {
                    row.remove();
                    
                    // Cek jika tabel sudah kosong setelah baris dihapus
                    const tbody = document.querySelector('tbody');
                    if (tbody.querySelectorAll('tr').length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="p-10 text-center text-gray-400 font-medium">Semua bed kosong. Tidak ada pasien dirawat.</td></tr>`;
                    }
                }, 500);
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            });
            element.value = '0'; // Reset jika gagal di sistem
        }
    });
}

function showModalPindahBangsal(pasienId, selectElement, oldStatus) {
    
    // 1. Tarik data ketersediaan bangsal secara real-time dari server via AJAX
    fetch('/pasien/getBangsalTersedia', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(res => res.json())
        .then(response => {
            if (response.status !== 'success' || response.data.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Semua bangsal tujuan sedang penuh (tidak ada bed kosong). Pasien tidak dapat dipindahkan.',
                    showConfirmButton: false,
                    timer: 2000
                });
                if (selectElement) selectElement.value = oldStatus;
                return;
            }

            const targetBangsals = response.data; // Berisi daftar bangsal beserta array bed kosongnya

            // Helper lokal untuk render option bed berdasarkan bangsal terpilih
            function getBedOptionsHtml(bangsalId) {
                const b = targetBangsals.find(x => x.id_bangsal.toString() === bangsalId.toString());
    
                // Validasi apakah bangsal ditemukan dan memiliki properti beds_kosong berbentuk Array
                if (!b || !Array.isArray(b.beds_kosong) || b.beds_kosong.length === 0) {
                 return '<option value="">-- Tidak ada bed tersedia --</option>';
             }
    
             // UBAH DI SINI: value diisi ID BED, sedangkan teks di luar diisi NAMA BED
                 return b.beds_kosong.map(bed => `
                 <option value="${bed.id_bed}">${bed.nama_bed}</option>
                `).join('');    }       

            // Ambil inisialisasi awal untuk komponen select
            const initialBangsalId = targetBangsals[0].id_bangsal; // Default ke bangsal pertama yang tersedia      

        
            const initialBedOptions = getBedOptionsHtml(initialBangsalId);

            // 2. Buat & Munculkan Modal HTML ke DOM
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4'; // CSS backdrop modal Tailwind
            modal.innerHTML = `
            
                <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl transform transition-all scale-100">
                    <h3 class="text-xl font-bold mb-2 flex items-center gap-2"><i class="fi fi-rr-refresh text-blue-600 flex items-center"></i> Pindah Bangsal</h3>
                    <p class="mb-4 text-sm text-gray-500">Pilih bangsal dan bed kosong tujuan untuk pasien ini.</p>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-600 mb-1">Bangsal Tujuan (Tersedia)</label>
                            <select id="id_bangsal" class="w-full border border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-blue-500 outline-none">
                                ${targetBangsals.map(b => `<option value="${b.id_bangsal}">${b.icon || '🏥'} ${b.nama_bangsal}</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-600 mb-1">Bed Tersedia</label>
                            <select id="id_bed" class="w-full border border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-blue-500 outline-none">
                                ${initialBedOptions}
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button id="btnSimpanPindah" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all">Pindahkan Pasien</button>
                        <button id="btnBatalPindah" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition-all">Batal</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            // 3. Daftarkan Event Listener Elemen di Dalam Modal
            const bangsalSelect = modal.querySelector('#id_bangsal');
            const bedSelect = modal.querySelector('#id_bed');
            const btnSimpan = modal.querySelector('#btnSimpanPindah');

            // Perubahan opsi bed dinamis sewaktu user mengganti opsi bangsal
            bangsalSelect.addEventListener('change', function() {
                bedSelect.innerHTML = getBedOptionsHtml(this.value);
            });

            // Aksi tombol batal
            modal.querySelector('#btnBatalPindah').onclick = () => {
                if (selectElement) selectElement.value = oldStatus;
                modal.remove();
            };

            // Aksi tombol simpan (Kirim Data ke Server)
            btnSimpan.onclick = () => {
                const newBangsalId = bangsalSelect.value;
                const newBedId = bedSelect.value;
                
                if (!newBedId) return alert('Pilih bed tujuan terlebih dahulu!');

                // Siapkan form data submit
                const formData = new FormData();
                formData.append('id_pasien', pasienId);
                formData.append('id_bangsal', newBangsalId);
                formData.append('id_bed', newBedId);

                // Tambahkan token CSRF jika filter aktif di CodeIgniter
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                fetch('/pasien/pindah_bangsal', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        modal.remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: "✅ Pasien berhasil dipindahkan dan tidak akan tampil lagi di daftar bangsal Anda.",
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // Hapus baris tabel pasien saat ini dari view karena sudah berbeda bangsal
                        if (selectElement) {
                            const row = selectElement.closest('tr');
                            if (row) row.remove();
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal memindahkan: ' + data.message
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Terjadi error koneksi saat memproses perpindahan.'
                    });
                });
            };
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal memuat status ketersediaan bangsal dari server.'
            });
            if (selectElement) selectElement.value = oldStatus;
        });
        
}
</script>
<?= $this->endSection() ?>