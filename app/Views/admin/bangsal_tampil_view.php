<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fi fi-rr-bed-alt text-blue-600 flex items-center"></i> Data Bangsal
        </h2>
        <button onclick="showModalTambahBangsal()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md shadow-blue-100 transition flex items-center gap-1.5 text-sm">
            <i class="fi fi-rr-plus flex items-center"></i> Tambah Bangsal
        </button>
    </div>

    <!-- Bangsal List Container (Grid View) -->
    <div id="bangsalContainer" class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Loader / Data will be loaded here dynamically -->
        <div class="col-span-1 md:col-span-3 text-center py-12">
            <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="text-slate-500 mt-2 text-sm">Memuat data bangsal...</p>
        </div>
    </div>
</div>

<!-- Modal Form (Tambah / Edit) -->
<div id="bangsalModal" class="fixed inset-0 bg-black/50 hidden flex justify-center items-center z-50 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl relative mx-4 transform scale-95 transition-transform duration-300">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 font-bold text-2xl transition-colors">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fi fi-rr-bed-alt text-blue-600 flex items-center"></i> Tambah Bangsal</h3>
        
        <form id="bangsalForm" onsubmit="simpanBangsal(event)">
            <?= csrf_field() ?>
            <input type="hidden" id="id_bangsal" name="id_bangsal">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">ID Bangsal</label>
                    <input type="text" id="kd_bangsal" name="kd_bangsal" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: B001">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Bangsal</label>
                    <input type="text" id="nama_bangsal" name="nama_bangsal" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Kamar 1">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Kapasitas</label>
                    <input type="number" id="kapasitas" name="kapasitas" min="0" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: 4">
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition shadow-md shadow-blue-200">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadBangsalData();
});

// Load Bangsal Data from Server
function loadBangsalData() {
    const container = document.getElementById('bangsalContainer');
    fetch('<?= base_url('bangsal/getData') ?>')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(d => {
                html += `
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                        <div>
                            
                            <div class="flex justify-between items-start gap-2">
                                <div class="font-bold text-gray-800 text-lg">${escapeHtml(d.nama_bangsal)}</div>
                            </div>
                            <div class="text-sm text-slate-500 mt-2 leading-relaxed">Kapasitas: ${escapeHtml(d.kapasitas || '0')}</div>
                        </div>
                        <div class="mt-5 pt-3 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-xs text-slate-400 font-mono">ID: ${d.kd_bangsal}</span>
                            <div class="flex gap-2">
                                <button onclick="editBangsalModal('${d.id_bangsal}')" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                    <i class="fi fi-rr-edit"></i> Edit
                                </button>
                                <button onclick="hapusBangsal('${d.id_bangsal}')" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                                    <i class="fi fi-rr-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            if (data.length === 0) {
                html = `
                    <div class="col-span-1 md:col-span-3 text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-slate-400 text-5xl mb-3">🏢</div>
                        <p class="text-slate-500 font-medium">Belum ada data bangsal.</p>
                    </div>
                `;
            }
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="col-span-1 md:col-span-3 text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-red-400 text-5xl mb-3">⚠️</div>
                    <p class="text-red-500 font-medium">Gagal memuat data dari server.</p>
                </div>
            `;
        });
}

// Modal Handlers
function showModalTambahBangsal() {
    document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-bed-alt text-blue-600 flex items-center"></i> Tambah Bangsal';
    document.getElementById('bangsalForm').reset();
    document.getElementById('id_bangsal').value = '';
    
    const modal = document.getElementById('bangsalModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('bangsalModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

function editBangsalModal(id) {
    fetch(`<?= base_url('bangsal/edit') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-edit text-blue-600 flex items-center"></i> Edit Bangsal';
            document.getElementById('id_bangsal').value = data.id_bangsal;
            document.getElementById('kd_bangsal').value = data.kd_bangsal;
            document.getElementById('nama_bangsal').value = data.nama_bangsal;
            document.getElementById('kapasitas').value = data.kapasitas;

            const modal = document.getElementById('bangsalModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95');
                modal.querySelector('.bg-white').classList.add('scale-100');
            }, 10);
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal mengambil data dari server.'
            });
        });
}

// Save Action
function simpanBangsal(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('bangsalForm'));
    
    fetch('<?= base_url('bangsal/save') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            });
            closeModal();
            loadBangsalData();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Gagal menyimpan data.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan',
            text: 'Tidak dapat terhubung ke server.'
        });
    });
}

// Delete Action
function hapusBangsal(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data bangsal ini akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('bangsal/delete') ?>/${id}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    loadBangsalData();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Gagal menghapus data.'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Tidak dapat terhubung ke server.'
                });
            });
        }
    });
}

// Helper to escape HTML and prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>
<?= $this->endSection() ?>
