<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fi fi-rr-bowl-rice text-blue-600 flex items-center"></i> Jenis Diet
        </h2>
        <button onclick="showModalTambahDiet()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md shadow-blue-100 transition flex items-center gap-1.5 text-sm">
            <i class="fi fi-rr-plus flex items-center"></i> Tambah Diet
        </button>
    </div>

    <!-- Diet List Container (Grid View) -->
    <div id="dietContainer" class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Loader / Data will be loaded here dynamically -->
        <div class="col-span-1 md:col-span-3 text-center py-12">
            <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="text-slate-500 mt-2 text-sm">Memuat data jenis diet...</p>
        </div>
    </div>
</div>

<!-- Modal Form (Tambah / Edit) -->
<div id="dietModal" class="fixed inset-0 bg-black/50 hidden flex justify-center items-center z-50 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl relative mx-4 transform scale-95 transition-transform duration-300">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 font-bold text-2xl transition-colors">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fi fi-rr-salad text-blue-600 flex items-center"></i> Tambah Jenis Diet</h3>
        
        <form id="dietForm" onsubmit="simpanDiet(event)">
            <?= csrf_field() ?>
            <input type="hidden" id="id_jenis_diet" name="id_jenis_diet">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Diet</label>
                    <input type="text" id="nama_jenis_diet" name="nama_jenis_diet" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Diet Diabetes">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Deskripsi / Keterangan</label>
                    <textarea id="ket_jenis_diet" name="ket_jenis_diet" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" rows="4" placeholder="Contoh: Rendah karbohidrat sederhana..."></textarea>
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
    loadDietData();
});

// Load Diet Data from Server
function loadDietData() {
    const container = document.getElementById('dietContainer');
    fetch('<?= base_url('jenis_diet/getData') ?>')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(d => {
                html += `
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start gap-2">
                                <div class="font-bold text-gray-800 text-lg">${escapeHtml(d.nama_jenis_diet)}</div>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wider whitespace-nowrap">${escapeHtml(d.kd_jenis_diet)}</span>
                            </div>
                            <div class="text-sm text-slate-500 mt-2 leading-relaxed">${escapeHtml(d.ket_jenis_diet || 'Tidak ada deskripsi.')}</div>
                        </div>
                        <div class="mt-5 pt-3 border-t border-slate-100 flex justify-between items-center">
                             <div class="flex gap-3">
                             </div>
                            <div class="flex gap-2">
                                <button onclick="editDietModal('${d.id_jenis_diet}')" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                    <i class="fi fi-rr-edit"></i> Edit
                                </button>
                                <button onclick="hapusDiet('${d.id_jenis_diet}')" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
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
                        <div class="text-slate-400 text-5xl mb-3">🥗</div>
                        <p class="text-slate-500 font-medium">Belum ada data jenis diet.</p>
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
function showModalTambahDiet() {
    document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-salad text-blue-600 flex items-center"></i> Tambah Jenis Diet';
    document.getElementById('dietForm').reset();
    document.getElementById('id_jenis_diet').value = '';
    
    const modal = document.getElementById('dietModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('dietModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

function editDietModal(id) {
    fetch(`<?= base_url('jenis_diet/edit') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-edit text-blue-600 flex items-center"></i> Edit Jenis Diet';
            document.getElementById('id_jenis_diet').value = data.id_jenis_diet;
            document.getElementById('nama_jenis_diet').value = data.nama_jenis_diet;
            document.getElementById('ket_jenis_diet').value = data.ket_jenis_diet;
            
            const modal = document.getElementById('dietModal');
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
function simpanDiet(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('dietForm'));
    
    fetch('<?= base_url('jenis_diet/save') ?>', {
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
            loadDietData();
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
function hapusDiet(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data jenis diet ini akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Buat FormData untuk mengirim CSRF token
            const formData = new FormData();
            const csrfInput = document.querySelector('input[name^="csrf"]');
            if (csrfInput) {
                formData.append(csrfInput.name, csrfInput.value);
            }
            
            fetch(`<?= base_url('jenis_diet/delete/') ?>${id}`, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
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
                    loadDietData();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Gagal menghapus data.'
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
