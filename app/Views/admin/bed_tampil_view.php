<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Manajemen Bed | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fi fi-rr-bed text-blue-600 flex items-center"></i> Manajemen Bed Bangsal
        </h2>
    </div>

    <!-- Panel Kontrol -->
    <div class="bg-white p-6 rounded-2xl shadow border border-gray-200 flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Pilih Bangsal</label>
            <select id="bangsalSelect" class="w-full border border-gray-200 rounded-lg p-3 bg-gray-50 focus:ring-blue-500 outline-none transition-all" onchange="renderManajemenBed()">
                <?php if(empty($bangsalList)): ?>
                    <option value="">-- Tidak ada data bangsal --</option>
                <?php else: ?>
                    <?php foreach($bangsalList as $b): ?>
                        <option value="<?= esc($b['id_bangsal']) ?>">
                            <?= esc($b['icon'] ?? '') ?> <?= esc($b['nama_bangsal']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Tambah Bed Baru</label>
            <div class="flex gap-2">
                <input type="text" id="newBedInput" class="w-full border border-gray-200 rounded-lg p-3 bg-gray-50 focus:ring-blue-500 outline-none transition-all" placeholder="Contoh: A-01">
                <button onclick="tambahBed()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition shadow shadow-blue-200 flex items-center gap-1.5">
                    <i class="fi fi-rr-plus flex items-center"></i> Tambah
                </button>
            </div>
        </div>
    </div>

    <!-- Wadah Grid Bed -->
    <div id="bedContainer" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <!-- Bed list akan dimuat via JavaScript -->
    </div>
</div>

<!-- Sertakan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    renderManajemenBed(); // Load bed saat halaman pertama kali dibuka
});

function renderManajemenBed() {
    const idBangsal = document.getElementById('bangsalSelect').value;
    const bedContainer = document.getElementById('bedContainer');

    if (!idBangsal) {
        bedContainer.innerHTML = '<p class="text-gray-400 text-sm col-span-full">Pilih bangsal terlebih dahulu.</p>';
        return;
    }

    // Tampilkan loading skeleton atau teks loading
    bedContainer.innerHTML = '<p class="text-gray-400 text-sm col-span-full">Memuat data bed...</p>';

    fetch(`<?= base_url('bed/getData/') ?>${idBangsal}`)
        .then(response => response.json())
        .then(beds => {
            if (beds.length === 0) {
                bedContainer.innerHTML = '<p class="text-gray-400 text-sm col-span-full">Belum ada bed terdaftar di bangsal ini.</p>';
                return;
            }

            let bedsHtml = '';
            beds.forEach(bed => {
                bedsHtml += `
                    <div class="bg-white p-3 rounded-xl border flex justify-between items-center shadow-sm hover:shadow-md transition">
                        <span class="font-bold text-blue-600">${bed.nama_bed}</span>
                        <button onclick="hapusBed('${bed.id_bed}', '${bed.nama_bed}')" class="text-xs bg-red-50 text-red-600 px-2.5 py-1.5 rounded-lg font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                            <i class="fi fi-rr-trash"></i> Hapus
                        </button>
                    </div>
                `;
            });
            bedContainer.innerHTML = bedsHtml;
        })
        .catch(error => {
            console.error('Error fetching beds:', error);
            bedContainer.innerHTML = '<p class="text-red-400 text-sm col-span-full">Gagal memuat data bed.</p>';
        });
}

function tambahBed() {
    const idBangsal = document.getElementById('bangsalSelect').value;
    const bedNameInput = document.getElementById('newBedInput');
    const bedName = bedNameInput.value.trim();

    if (!idBangsal) return Swal.fire('Peringatan', 'Pilih bangsal dulu!', 'warning');
    if (!bedName) return Swal.fire('Peringatan', 'Nama bed tidak boleh kosong!', 'warning');

    const formData = new FormData();
    formData.append('id_bangsal', idBangsal);
    formData.append('nama_bed', bedName);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>'); // Tambahkan CSRF jika diaktifkan

    fetch(`<?= base_url('bed/save') ?>`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            bedNameInput.value = ''; // Kosongkan input
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            });
            
            renderManajemenBed(); // Refresh list bed
        } else {
            Swal.fire('Gagal!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
        console.error('Error:', error);
    });
}

function hapusBed(idBed, namaBed) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Ingin menghapus bed ${namaBed}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('bed/delete/') ?>${idBed}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    renderManajemenBed(); // Refresh list bed
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                console.error('Error:', error);
            });
        }
    });
}
</script>
<?= $this->endSection() ?>