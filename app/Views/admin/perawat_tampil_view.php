<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Perawat | SIMDIET </title>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold flex items-center gap-2"><i class="fi fi-rr-doctor text-blue-600 flex items-center"></i> Data Perawat</h2>
        <button onclick="showModalTambahPerawat()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md flex items-center gap-1.5 text-sm">
            <i class="fi fi-rr-plus flex items-center"></i> Tambah Perawat
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl shadow flex flex-wrap gap-3 items-end mb-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Cari Perawat (Nama/NIP/Username)</label>
            <input type="text" id="searchPerawatInput" class="w-full border border-gray-200 rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1" placeholder="Cari perawat...">
        </div>
        <div class="w-64">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Filter Bangsal</label>
            <select id="filterBangsalPerawatSelect" class="w-full border border-gray-200 rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                <option value="">Semua Bangsal</option>
                <?php foreach($bangsalList as $b): ?>
                    <option value="<?= esc($b['id_bangsal']) ?>"><?= esc($b['icon'] ?? '🏥') ?> <?= esc($b['nama_bangsal']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button id="resetPerawatFilterBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-200 text-gray-700 font-medium transition">
                Reset
            </button>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-2xl shadow-md border border-slate-100 p-4">
        <table id="tabelPerawat" class="w-full text-sm">
            <thead class="bg-slate-50/75 border-b border-slate-100">
                <tr>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bangsal</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">NIP</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Telp</th>
                    <th class="p-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-500"></tbody>
        </table>
        
        <!-- Pagination Controls -->
        <div class="flex justify-between items-center p-4 border-t border-slate-100 bg-slate-50/50 gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <label for="perPageSelectPerawat" class="text-sm text-slate-600 font-medium">Baris per halaman:</label>
                <select id="perPageSelectPerawat" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
            </div>
            
            <div id="paginationInfo" class="text-sm text-slate-600 font-medium">
                Menampilkan 1 sampai 10 dari <span id="totalRows">0</span> perawat
            </div>
            
            <div id="paginationButtons" class="flex gap-2">
                <!-- Pagination buttons akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="perawatModal" class="fixed inset-0 bg-black/50 hidden flex justify-center items-center z-50 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl relative mx-4 transform scale-95 transition-transform duration-300">
        <button type="button" onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 font-bold text-2xl transition-colors">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fi fi-rr-doctor text-blue-600 flex items-center"></i> Tambah Perawat</h3>
        
        <form id="perawatForm" enctype="multipart/form-data" onsubmit="simpanPerawat(event)">
            <?= csrf_field() ?>
            <input type="hidden" id="id_perawat" name="id_perawat">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- ================= KOLOM KIRI ================= -->
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama_perawat" name="nama_perawat" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
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
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Penempatan Bangsal</label>
                        <select id="id_bangsal" name="id_bangsal" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <option value="">-- Pilih Bangsal --</option>
                            <?php foreach($bangsalList as $b): ?>
                                <option value="<?= esc($b['id_bangsal']) ?>"><?= esc($b['nama_bangsal']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">No. Telp</label>
                        <input type="text" id="telepon" name="telepon" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Upload Photo</label>
                        <input type="file" id="photo" name="photo" class="w-full border-dashed border-2 border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">   
                    </div>
                </div>

                <!-- ================= FULL WIDTH ================= -->
                 <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" rows="3" placeholder="(Alamat lengkap)"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
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
// Vanilla JS Table Handler dengan Pagination
class PerawatTable {
    constructor(tableSelector, options = {}) {
        this.table = document.querySelector(tableSelector);
        this.tbody = this.table.querySelector('tbody');
        this.allRows = [];
        this.rows = [];
        this.currentPage = 1;
        this.perPage = options.perPage || 10;
        this.searchInput = options.searchInput || null;
        this.filterSelect = options.filterSelect || null;
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
        if (this.filterSelect) {
            this.filterSelect.addEventListener('change', () => this.handleFilter());
        }
        if (this.resetBtn) {
            this.resetBtn.addEventListener('click', () => this.handleReset());
        }
        if (this.perPageSelect) {
            this.perPageSelect.addEventListener('change', (e) => this.handlePerPageChange(e));
        }
    }
    
    loadData() {
        const params = new URLSearchParams({
            search: this.searchInput?.value || '',
            id_bangsal: this.filterSelect?.value || ''
        });
        
        fetch(`${this.apiUrl}?${params}`)
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data);
                this.allRows = Array.isArray(data) ? data : (data.data || []);
                this.rows = [...this.allRows];
                this.currentPage = 1;
                this.render();
            })
            .catch(error => {
                console.error('Error loading data:', error);
                this.tbody.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-red-600">Gagal memuat data</td></tr>';
            });
    }
    
    handleSearch() {
        this.currentPage = 1;
        this.loadData();
    }
    
    handleFilter() {
        this.currentPage = 1;
        this.loadData();
    }
    
    handleReset() {
        if (this.searchInput) this.searchInput.value = '';
        if (this.filterSelect) this.filterSelect.value = '';
        this.currentPage = 1;
        this.loadData();
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
            this.tbody.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-slate-500">Tidak ada data perawat ditemukan.</td></tr>';
            return;
        }
        
        this.tbody.innerHTML = paginatedRows.map(row => `
            <tr>
                <td class="p-4 font-bold text-slate-800 align-middle">${row.nama_perawat || '-'}</td>
                <td class="p-4 font-mono text-xs text-slate-500 align-middle">${row.username || '-'}</td>
                <td class="p-4 text-slate-700 align-middle">${row.nama_bangsal || '-'}</td>
                <td class="p-4 font-mono text-xs text-slate-500 align-middle">${row.nip || '-'}</td>
                <td class="p-4 text-slate-700 align-middle">${row.telepon || '-'}</td>
                <td class="p-4 text-center align-middle whitespace-nowrap">
                    <button onclick="editPerawatModal('${row.id_perawat}')" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors mr-2 inline-flex items-center gap-1">
                        <i class="fi fi-rr-edit"></i> Edit
                    </button>
                    <button onclick="hapusPerawat('${row.id_perawat}')" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
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
            if (total === 0) {
                this.paginationInfo.textContent = 'Menampilkan 0 perawat';
            } else {
                this.paginationInfo.innerHTML = `Menampilkan ${start} sampai ${end} dari <span id="totalRows">${total}</span> perawat`;
            }
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
        
        if (this.currentPage > 1) {
            html += `<button onclick="tablePerawat.goToPage(${this.currentPage - 1})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">← Sebelumnya</button>`;
        }
        
        const maxVisible = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        if (startPage > 1) {
            html += `<button onclick="tablePerawat.goToPage(1)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">1</button>`;
            if (startPage > 2) {
                html += `<span class="px-2 py-1.5 text-gray-600">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === this.currentPage) {
                html += `<button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium">${i}</button>`;
            } else {
                html += `<button onclick="tablePerawat.goToPage(${i})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">${i}</button>`;
            }
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span class="px-2 py-1.5 text-gray-600">...</span>`;
            }
            html += `<button onclick="tablePerawat.goToPage(${totalPages})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">${totalPages}</button>`;
        }
        
        if (this.currentPage < totalPages) {
            html += `<button onclick="tablePerawat.goToPage(${this.currentPage + 1})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">Selanjutnya →</button>`;
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

let tablePerawat;

document.addEventListener('DOMContentLoaded', function() {
    tablePerawat = new PerawatTable('#tabelPerawat', {
        searchInput: document.getElementById('searchPerawatInput'),
        filterSelect: document.getElementById('filterBangsalPerawatSelect'),
        resetBtn: document.getElementById('resetPerawatFilterBtn'),
        perPageSelect: document.getElementById('perPageSelectPerawat'),
        paginationButtons: document.getElementById('paginationButtons'),
        paginationInfo: document.getElementById('paginationInfo'),
        totalRowsSpan: document.getElementById('totalRows'),
        apiUrl: '<?= site_url('perawat/getData') ?>'
    });
});

function showModalTambahPerawat() {
    document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-doctor text-blue-600 flex items-center"></i> Tambah Perawat';
    document.getElementById('perawatForm').reset();
    document.getElementById('id_perawat').value = ''; 
    document.getElementById('password').setAttribute('required', 'required'); 
  
    const modal = document.getElementById('perawatModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('perawatModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

function editPerawatModal(id) {
    fetch(`<?= base_url('perawat/edit/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-edit text-blue-600 flex items-center"></i> Edit Perawat';
            document.getElementById('id_perawat').value = data.id_perawat;
            document.getElementById('nama_perawat').value = data.nama_perawat;
            document.getElementById('username').value = data.username;
            document.getElementById('id_bangsal').value = data.id_bangsal;
            document.getElementById('nip').value = data.nip;
            document.getElementById('telepon').value = data.telepon;
            document.getElementById('alamat').value = data.alamat || '';
            
            document.getElementById('password').removeAttribute('required');
            
            const modal = document.getElementById('perawatModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95');
                modal.querySelector('.bg-white').classList.add('scale-100');
            }, 10);
        })
        .catch(error => alert('Gagal mengambil data!'));
}

function simpanPerawat(event) {
    event.preventDefault(); 
    
    // Call client-side validation logic first
    if (!validateForm()) {
        return; 
    }

    const formData = new FormData(document.getElementById('perawatForm'));

    fetch(`<?= base_url('perawat/save') ?>`, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
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
            tablePerawat.loadData();
        } else {
            // Dynamically handle validation object structures from the controller
            let errorText = 'Data gagal disimpan.';
            if (data.message && typeof data.message === 'object') {
                errorText = Object.values(data.message).join('<br>');
            } else if (typeof data.message === 'string') {
                errorText = data.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: errorText
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

function hapusPerawat(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data perawat ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af', 
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Ambil CSRF token dari input form
            const csrfInput = document.querySelector('input[name^="csrf"]');
            const headers = {
                'Content-Type': 'application/json'
            };
            
            if (csrfInput) {
                headers['X-CSRF-TOKEN'] = csrfInput.value;
            }
            
            fetch(`<?= base_url('perawat/delete/') ?>${id}`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: headers
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
                    tablePerawat.loadData();
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
    const idPerawat = document.getElementById('id_perawat').value;
    const isEditMode = idPerawat !== '';
    
    const namaPerawat = document.getElementById('nama_perawat').value.trim();
    const username = document.getElementById('username').value.trim();
    const idBangsal = document.getElementById('id_bangsal').value;
    const password = document.getElementById('password').value;
    const fileInput = document.getElementById('photo');
    
    if (!namaPerawat || !username || !idBangsal) {
        Swal.fire('Error', 'Nama, Username, dan Bangsal wajib diisi!', 'error');
        return false;
    }

    if(!isEditMode && !password.trim()) {
        Swal.fire('Error', 'Password wajib diisi untuk perawat baru!', 'error');
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