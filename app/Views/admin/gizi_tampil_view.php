<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET </title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold flex items-center gap-2"><i class="fi fi-rr-salad text-blue-600 flex items-center"></i> Data Ahli Gizi</h2>
        <button onclick="showModalTambahGizi()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md flex items-center gap-1.5 text-sm">
            <i class="fi fi-rr-plus flex items-center"></i> Tambah Ahli Gizi
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl shadow flex flex-wrap gap-3 items-end mb-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Cari Ahli Gizi (Nama/NIP/Username)</label>
            <input type="text" id="searchGiziInput" class="w-full border border-gray-200 rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1" placeholder="Cari ahli gizi...">
        </div>
        <div>
            <button id="resetGiziFilterBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-200 text-gray-700 font-medium transition">
                Reset
            </button>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-2xl shadow-md border border-slate-100 p-4">
        <table id="tabelGizi" class="w-full text-sm">
            <thead class="bg-slate-50/75 border-b border-slate-100">
                <tr>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NIP</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Telp</th>
                    <th class="p-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-500">
            </tbody>
        </table>
        
        <!-- Pagination Controls -->
        <div class="flex justify-between items-center p-4 border-t border-slate-100 bg-slate-50/50 gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <label for="perPageSelectGizi" class="text-sm text-slate-600 font-medium">Baris per halaman:</label>
                <select id="perPageSelectGizi" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
            </div>
            
            <div id="paginationInfoGizi" class="text-sm text-slate-600 font-medium">
                Menampilkan 1 sampai 10 dari <span id="totalRowsGizi">0</span> data
            </div>
            
            <div id="paginationButtonsGizi" class="flex gap-2">
                <!-- Pagination buttons akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>
</div>

<div id="giziModal" class="fixed inset-0 bg-black/50 hidden flex justify-center items-center z-50 backdrop-blur-sm transition-all duration-300">
    <!-- Mengubah max-w-lg menjadi max-w-3xl agar dua kolom memiliki ruang yang cukup -->
    <div class="bg-white rounded-2xl p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl relative mx-4 transform scale-95 transition-transform duration-300">
      <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 font-bold text-2xl transition-colors">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fi fi-rr-salad text-blue-600 flex items-center"></i> Tambah Ahli Gizi</h3>
        
        <form id="giziForm" enctype="multipart/form-data" onsubmit="simpanGizi(event)">
            <?= csrf_field() ?>
            <input type="hidden" id="id_ahli_gizi" name="id_ahli_gizi">
            
            <!-- Grid Utama: 1 Kolom di HP, 2 Kolom di md ke atas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                
                <!-- ================= KOLOM KIRI ================= -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama_ahli_gizi" name="nama_ahli_gizi" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Username</label>
                        <input type="text" id="username" name="username" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Password</label>
                        <input type="password" id="password" name="password" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">NIP</label>
                        <input type="text" id="nip" name="nip" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>

                <!-- ================= KOLOM KANAN ================= -->
                <div class="space-y-4">
                   
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">No. Telp</label>
                        <input type="text" id="telepon" name="telepon" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Upload Foto</label>
                        <input type="file" id="photo" name="photo" class="w-full border-dashed border-2 border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">   
                    </div>
                </div>

                <!-- ================= ELEMEN FULL WIDTH ================= -->
                 <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" rows="3" placeholder="(Alamat lengkap)"></textarea>
                </div>
            </div>

            <!-- Modal Footer / Action Buttons -->
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Vanilla JS Table Handler untuk Ahli Gizi dengan Pagination
class GiziTable {
    constructor(tableSelector, options = {}) {
        this.table = document.querySelector(tableSelector);
        this.tbody = this.table.querySelector('tbody');
        this.allRows = [];
        this.rows = [];
        this.currentPage = 1;
        this.perPage = options.perPage || 10;
        this.searchInput = options.searchInput || null;
        this.resetBtn = options.resetBtn || null;
        this.perPageSelect = options.perPageSelect || null;
        this.paginationButtons = options.paginationButtons || null;
        this.paginationInfo = options.paginationInfo || null;
        this.totalRowsSpan = options.totalRowsSpan || null;
        this.apiUrl = options.apiUrl || '';
        
        this.init();
    }
    
    init() {
        this.loadData();
        
        if (this.searchInput) {
            this.searchInput.addEventListener('keyup', () => this.handleSearch());
        }
        if (this.resetBtn) {
            this.resetBtn.addEventListener('click', () => this.handleReset());
        }
        if (this.perPageSelect) {
            this.perPageSelect.addEventListener('change', (e) => this.handlePerPageChange(e));
        }
    }
    
    loadData() {
        fetch(this.apiUrl)
            .then(response => response.json())
            .then(data => {
                this.allRows = Array.isArray(data) ? data : (data.data || []);
                this.filterRows();
            })
            .catch(error => {
                console.error('Error loading data:', error);
                this.tbody.innerHTML = '<tr><td colspan="5" class="p-4 text-center text-red-600">Gagal memuat data</td></tr>';
            });
    }
    
    filterRows() {
        const searchTerm = (this.searchInput?.value || '').toLowerCase();
        
        if (searchTerm === '') {
            this.rows = [...this.allRows];
        } else {
            this.rows = this.allRows.filter(row => {
                return (
                    (row.nama_ahli_gizi && row.nama_ahli_gizi.toLowerCase().includes(searchTerm)) ||
                    (row.username && row.username.toLowerCase().includes(searchTerm)) ||
                    (row.nip && row.nip.toLowerCase().includes(searchTerm))
                );
            });
        }
        
        this.currentPage = 1;
        this.render();
    }
    
    handleSearch() {
        this.filterRows();
    }
    
    handleReset() {
        if (this.searchInput) this.searchInput.value = '';
        this.filterRows();
    }
    
    handlePerPageChange(e) {
        this.perPage = parseInt(e.target.value);
        this.currentPage = 1;
        this.render();
    }
    
    getTotalPages() {
        return Math.ceil(this.rows.length / this.perPage);
    }
    
    getPaginatedRows() {
        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        return this.rows.slice(start, end);
    }
    
    renderRows() {
        const paginatedRows = this.getPaginatedRows();
        
        if (paginatedRows.length === 0) {
            this.tbody.innerHTML = '<tr><td colspan="5" class="p-4 text-center text-slate-500">Tidak ada data ahli gizi ditemukan.</td></tr>';
            return;
        }
        
        this.tbody.innerHTML = paginatedRows.map(row => `
            <tr>
                <td class="p-4 font-bold text-slate-800 align-middle">${row.nama_ahli_gizi || '-'}</td>
                <td class="p-4 font-mono text-xs text-slate-500 align-middle">${row.username || '-'}</td>
                <td class="p-4 font-mono text-xs text-slate-500 align-middle">${row.nip || '-'}</td>
                <td class="p-4 text-slate-700 align-middle">${row.telepon || '-'}</td>
                <td class="p-4 text-center align-middle whitespace-nowrap">
                    <button onclick="editAhliGiziModal('${row.id_ahli_gizi}')" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors mr-2 inline-flex items-center gap-1">
                        <i class="fi fi-rr-edit"></i> Edit
                    </button>
                    <button onclick="hapusAhliGizi('${row.id_ahli_gizi}')" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                        <i class="fi fi-rr-trash"></i> Hapus
                    </button>
                </td>
            </tr>
        `).join('');
    }
    
    updatePaginationInfo() {
        if (this.paginationInfo && this.totalRowsSpan) {
            const total = this.rows.length;
            const start = total === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1;
            const end = Math.min(this.currentPage * this.perPage, total);
            this.paginationInfo.innerHTML = `Menampilkan ${start} sampai ${end} dari <span id="totalRowsGizi">${total}</span> data`;
        }
    }
    
    renderPaginationButtons() {
        if (!this.paginationButtons) return;
        
        const totalPages = this.getTotalPages();
        let html = '';
        
        if (totalPages <= 1) {
            this.paginationButtons.innerHTML = '';
            return;
        }
        
        // Tombol Previous
        if (this.currentPage > 1) {
            html += `<button onclick="giziTable.goToPage(${this.currentPage - 1})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">← Sebelumnya</button>`;
        }
        
        // Nomor halaman
        const maxVisible = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        if (startPage > 1) {
            html += `<button onclick="giziTable.goToPage(1)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">1</button>`;
            if (startPage > 2) {
                html += `<span class="px-2 py-1.5 text-gray-600">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === this.currentPage) {
                html += `<button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium">${i}</button>`;
            } else {
                html += `<button onclick="giziTable.goToPage(${i})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">${i}</button>`;
            }
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span class="px-2 py-1.5 text-gray-600">...</span>`;
            }
            html += `<button onclick="giziTable.goToPage(${totalPages})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">${totalPages}</button>`;
        }
        
        // Tombol Next
        if (this.currentPage < totalPages) {
            html += `<button onclick="giziTable.goToPage(${this.currentPage + 1})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">Selanjutnya →</button>`;
        }
        
        this.paginationButtons.innerHTML = html;
    }
    
    goToPage(pageNumber) {
        const totalPages = this.getTotalPages();
        if (pageNumber >= 1 && pageNumber <= totalPages) {
            this.currentPage = pageNumber;
            this.render();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    
    render() {
        this.renderRows();
        this.updatePaginationInfo();
        this.renderPaginationButtons();
    }
}

// Global variable
let giziTable;

document.addEventListener('DOMContentLoaded', function() {
    giziTable = new GiziTable('#tabelGizi', {
        searchInput: document.getElementById('searchGiziInput'),
        resetBtn: document.getElementById('resetGiziFilterBtn'),
        perPageSelect: document.getElementById('perPageSelectGizi'),
        paginationButtons: document.getElementById('paginationButtonsGizi'),
        paginationInfo: document.getElementById('paginationInfoGizi'),
        totalRowsSpan: document.getElementById('totalRowsGizi'),
        apiUrl: '<?= base_url('gizi/getData') ?>'
    });
});

// Fungsi Modal Aksi
function showModalTambahGizi() {
    document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-salad text-blue-600 flex items-center"></i> Tambah Ahli Gizi';
    document.getElementById('giziForm').reset();
    document.getElementById('id_ahli_gizi').value = ''; 
    document.getElementById('password').setAttribute('required', 'required'); // Wajib isi password saat tambah
   
    const modal = document.getElementById('giziModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('giziModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

function editAhliGiziModal(id) {
    fetch(`<?= base_url('gizi/edit/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-edit text-blue-600 flex items-center"></i> Edit Ahli Gizi';
            document.getElementById('id_ahli_gizi').value = data.id_ahli_gizi;
            document.getElementById('nama_ahli_gizi').value = data.nama_ahli_gizi;
            document.getElementById('username').value = data.username;
            document.getElementById('nip').value = data.nip;
            document.getElementById('telepon').value = data.telepon;
            document.getElementById('alamat').value = data.alamat || '';
            
            // Hapus atribut required pada password saat mode edit
            document.getElementById('password').removeAttribute('required');
            
            const modal = document.getElementById('giziModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95');
                modal.querySelector('.bg-white').classList.add('scale-100');
            }, 10);
        })
        .catch(error => alert('Gagal mengambil data!'));
}

function simpanGizi(event) {
    event.preventDefault(); 
    
    // Call client-side validation logic first
    if (!validateForm()) {
        return; 
    }
     const formData = new FormData(document.getElementById('giziForm'));
    
    
    fetch(`<?= base_url('gizi/save') ?>`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // Notifikasi Sukses yang otomatis hilang dalam 2 detik
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            });
            giziTable.loadData();
            closeModal();
        } else {
            // Mengurai objek eror validasi bertingkat dari backend secara aman
            let errorText = 'Data gagal disimpan.';
            if (data.message && typeof data.message === 'object') {
                errorText = Object.values(data.message).join('<br>');
            } else if (typeof data.message === 'string') {
                errorText = data.message;
            }
            // Notifikasi Error
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: errorText || 'Data gagal disimpan.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Tidak dapat terhubung ke server.'
        });
    });
}

// Proses Hapus Data
function hapusAhliGizi(id) {
    // Notifikasi Konfirmasi Hapus yang lebih elegan
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ahli gizi ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444', // Warna merah Tailwind (red-500)
        cancelButtonColor: '#9ca3af', // Warna abu-abu Tailwind (gray-400)
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika user klik "Ya, Hapus!"
            const csrfInput = document.querySelector('input[name^="csrf"]');
            const headers = {
                'Content-Type': 'application/json'
            };
            
            if (csrfInput) {
                headers['X-CSRF-TOKEN'] = csrfInput.value;
            }
            
            fetch(`<?= base_url('gizi/delete/') ?>${id}`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Notifikasi Sukses Hapus
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    giziTable.loadData();
                } else {
                    Swal.fire('Gagal!', 'Data gagal dihapus.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
            });
        }
    });
}

function validateForm() {
    const nama = document.getElementById('nama_ahli_gizi').value.trim();
    const username = document.getElementById('username').value.trim();      
    const nip = document.getElementById('nip').value.trim();
    const telepon = document.getElementById('telepon').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const idAhliGizi = document.getElementById('id_ahli_gizi').value;
    const password = document.getElementById('password').value;
    const fileInput = document.getElementById('photo');

    
    const isEditMode = idAhliGizi !== '';

    if (!nama || !username || !nip || !telepon || !alamat) {
        Swal.fire('Validasi Gagal', 'Semua field harus diisi!', 'warning');
        return false;
    }
    if(!isEditMode && !password.trim()) {
        Swal.fire('Error', 'Password wajib diisi untuk ahli gizi baru!', 'error');
        return false;
    }

    if(password && password.length < 6) {
        Swal.fire('Error', 'Password minimal 6 karakter!', 'error');
        return false;
    }

    if(fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!validTypes.includes(file.type)) {
            Swal.fire('Error', 'Format foto tidak valid! Hanya JPG, JPEG, PNG yang diperbolehkan.', 'error');
            return false;
        }
        if (file.size > 2 * 1024 * 1024) { // 2MB
            Swal.fire('Error', 'Ukuran foto terlalu besar! Maksimal 2MB.', 'error');
            return false;
        }
    }
    return true;
}
</script>
<?= $this->endSection() ?>