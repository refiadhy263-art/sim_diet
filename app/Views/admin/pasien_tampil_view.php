<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title; ?> | SIMDIET </title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fi fi-rr-hospital text-blue-600 flex items-center"></i> Data Pasien
        </h2>
        <button onclick="showModalTambahPasien()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md flex items-center gap-1.5 text-sm">
            <i class="fi fi-rr-plus flex items-center"></i> Tambah Pasien
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl shadow flex flex-wrap gap-3 items-end mb-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Cari Nama/RM</label>
            <input type="text" id="searchPasienInput" class="w-full border rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1" placeholder="Cari...">
        </div>
        <div class="w-48">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Filter Bangsal</label>
            <select id="filterBangsalPasienSelect" class="w-full border rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                <option value="">Semua Bangsal</option>
                <?php foreach($bangsalList as $b): ?>
                    <option value="<?= $b['id_bangsal'] ?>">
                        <?= esc($b['icon'] ?? '🏥') ?> <?= esc($b['nama_bangsal']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button id="resetFilterBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-200 text-gray-700 font-medium transition">
                Reset
            </button>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-2xl shadow-md border border-slate-100 p-4">
        <table id="tabelPasien" class="w-full text-sm">
            <thead class="bg-slate-50/75 border-b border-slate-100">
                <tr>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">RM</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pasien</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bangsal/Bed</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Diet</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Catatan</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="p-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-500">
            </tbody>
        </table>
        
        <!-- Pagination Controls -->
        <div class="flex justify-between items-center p-4 border-t border-slate-100 bg-slate-50/50 gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <label for="perPageSelectPasien" class="text-sm text-slate-600 font-medium">Baris per halaman:</label>
                <select id="perPageSelectPasien" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
            </div>
            
            <div id="paginationInfo" class="text-sm text-slate-600 font-medium">
                Menampilkan 1 sampai 10 dari <span id="totalRows">0</span> pasien
            </div>
            
            <div id="paginationButtons" class="flex gap-2">
                <!-- Pagination buttons akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>
</div>

<div id="pasienModal" class="fixed inset-0 bg-black/50 hidden flex justify-center items-center z-50 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-2xl p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl relative mx-4 transform scale-95 transition-transform duration-300">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 font-bold text-2xl transition-colors">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fi fi-rr-hospital text-blue-600 flex items-center"></i> Tambah Pasien</h3>
        
        <form id="pasienForm" onsubmit="simpanPasien(event)">
            <?= csrf_field() ?>
            <input type="hidden" id="id_pasien" name="id_pasien">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama_pasien" name="nama_pasien" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="(Tanggal Lahir)">
                    </div>
                   
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Penempatan Bangsal</label>
                        <select id="id_bangsal" name="id_bangsal" onchange="getBeds(this.value)" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <option value="">-- Pilih Bangsal --</option>
                            <?php foreach($bangsalList as $b): ?>
                                <option value="<?= esc($b['id_bangsal']) ?>"><?= esc($b['nama_bangsal']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Jenis Diet</label>
                        <select id="id_jenis_diet" name="id_jenis_diet" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <option value="">-- Pilih Diet --</option>
                            <?php foreach($dietList as $d): ?>
                                <option value="<?= esc($d['id_jenis_diet']) ?>"><?= esc($d['nama_jenis_diet']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">No. RM</label>
                        <input type="text" id="no_rm" name="no_rm" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Diagnosa</label>
                        <input type="text" id="diagnosa" name="diagnosa" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                   
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Penempatan Bed</label>
                        <select id="id_bed" name="id_bed" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <option value="">-- Pilih Bed --</option>
                        </select>
                    </div>
       
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Bentuk Diet</label>
                        <select id="id_bentuk_diet" name="id_bentuk_diet" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <option value="">-- Pilih Bentuk Diet --</option>
                            <?php foreach($bentukDietList as $b): ?>
                                <option value="<?= esc($b['id_bentuk_diet']) ?>"><?= esc($b['nama_bentuk_diet']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Catatan</label>
                    <textarea id="keterangan" name="keterangan" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" rows="3" placeholder="(Catatan)"></textarea>
                </div>
            </div>

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
class PasienTable {
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
                // Support both array format and object with data property
                this.allRows = Array.isArray(data) ? data : (data.data || []);
                this.rows = [...this.allRows];
                this.currentPage = 1;
                this.render();
            })
            .catch(error => {
                console.error('Error loading data:', error);
                this.tbody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-red-600">Gagal memuat data</td></tr>';
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
            this.tbody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-slate-500">Tidak ada data pasien ditemukan.</td></tr>';
            return;
        }
        
        this.tbody.innerHTML = paginatedRows.map(row => {
            const statusMap = {
                '0': <?= json_encode(rawatStatus('0')) ?>,
                '1': <?= json_encode(rawatStatus('1')) ?>,
                '2': <?= json_encode(rawatStatus('2')) ?>,
                '3': <?= json_encode(rawatStatus('3')) ?>
            };
            const status = statusMap[row.status_rawat] || { label: row.status_rawat || 'Dirawat', color: 'bg-amber-50 text-amber-600' };
            
            return `
            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                <td class="p-4 font-mono text-xs text-slate-500">${row.no_rm || '-'}</td>
                <td class="p-4 font-bold text-slate-800">${row.nama_pasien || '-'}</td>
                <td class="p-4">
                    <div class="text-slate-700 font-medium">${row.nama_bangsal || '-'}</div>
                    <div class="text-xs font-semibold text-blue-600 font-mono mt-0.5">${row.nama_bed || '-'}</div>
                </td>
                <td class="p-4">
                    <div>${row.nama_jenis_diet ? `<span class="text-xs font-bold text-blue-600">${row.nama_jenis_diet}</span>` : '<span class="text-slate-400">-</span>'}</div>
                    ${row.nama_bentuk_diet ? `<div class="text-xs font-semibold text-slate-500 mt-1">${row.nama_bentuk_diet}</div>` : ''}
                </td>
                <td class="p-4 text-red-600 text-xs italic">${row.keterangan ? `<span>${row.keterangan}</span>` : '<span class="text-red-600">-</span>'}</td>
                <td class="p-4">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase ${status.color}">${status.label}</span>
                </td>
                <td class="p-4 text-center whitespace-nowrap">
                    <button onclick="editPasienModal('${row.id_pasien}')" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors mr-2 inline-flex items-center gap-1">
                        <i class="fi fi-rr-edit"></i> Edit
                    </button>
                    <button onclick="hapusPasien('${row.id_pasien}')" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1">
                        <i class="fi fi-rr-trash"></i> Hapus
                    </button>
                </td>
            </tr>
        `;
        }).join('');
    }
    
    updatePaginationInfo() {
        if (this.paginationInfo && this.totalRowsSpan) {
            const total = this.rows.length;
            const start = total === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1;
            const end = Math.min(this.currentPage * this.perPage, total);
            if (total === 0) {
                this.paginationInfo.textContent = 'Menampilkan 0 pasien';
            } else {
                this.paginationInfo.innerHTML = `Menampilkan ${start} sampai ${end} dari <span id="totalRows">${total}</span> pasien`;
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
        
        // Tombol Previous
        if (this.currentPage > 1) {
            html += `<button onclick="pasienTable.goToPage(${this.currentPage - 1})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">← Sebelumnya</button>`;
        }
        
        // Nomor halaman
        const maxVisible = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        if (startPage > 1) {
            html += `<button onclick="pasienTable.goToPage(1)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">1</button>`;
            if (startPage > 2) {
                html += `<span class="px-2 py-1.5 text-gray-600">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === this.currentPage) {
                html += `<button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium">${i}</button>`;
            } else {
                html += `<button onclick="pasienTable.goToPage(${i})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">${i}</button>`;
            }
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span class="px-2 py-1.5 text-gray-600">...</span>`;
            }
            html += `<button onclick="pasienTable.goToPage(${totalPages})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">${totalPages}</button>`;
        }
        
        // Tombol Next
        if (this.currentPage < totalPages) {
            html += `<button onclick="pasienTable.goToPage(${this.currentPage + 1})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">Selanjutnya →</button>`;
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
let pasienTable;

document.addEventListener('DOMContentLoaded', function() {
    pasienTable = new PasienTable('#tabelPasien', {
        searchInput: document.getElementById('searchPasienInput'),
        filterSelect: document.getElementById('filterBangsalPasienSelect'),
        resetBtn: document.getElementById('resetFilterBtn'),
        perPageSelect: document.getElementById('perPageSelectPasien'),
        paginationButtons: document.getElementById('paginationButtons'),
        paginationInfo: document.getElementById('paginationInfo'),
        totalRowsSpan: document.getElementById('totalRows'),
        apiUrl: '<?= site_url('pasien/getData') ?>'
    });
});

function showModalTambahPasien() {
    document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-hospital text-blue-600 flex items-center"></i> Tambah Pasien';
    document.getElementById('pasienForm').reset();
    document.getElementById('id_pasien').value = '';
    document.getElementById('id_bed').innerHTML = '<option value="">-- Pilih Bed --</option>';
   
    const modal = document.getElementById('pasienModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('pasienModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

function editPasienModal(id) {
    fetch(`<?= base_url('pasien/edit/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-edit text-blue-600 flex items-center"></i> Edit Pasien';
            document.getElementById('id_pasien').value = data.id_pasien;
            document.getElementById('nama_pasien').value = data.nama_pasien;
            document.getElementById('no_rm').value = data.no_rm;
            document.getElementById('id_bangsal').value = data.id_bangsal;
            document.getElementById('id_jenis_diet').value = data.id_jenis_diet;
            document.getElementById('tanggal_lahir').value = data.tanggal_lahir;
            document.getElementById('diagnosa').value = data.diagnosa;
            document.getElementById('id_bentuk_diet').value = data.id_bentuk_diet;
            document.getElementById('keterangan').value = data.keterangan;
            
            getBeds(data.id_bangsal, data.id_bed);
            
            const modal = document.getElementById('pasienModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95');
                modal.querySelector('.bg-white').classList.add('scale-100');
            }, 10);
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal memuat data pasien.'
            });
        });
}

function hapusPasien(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pasien ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('pasien/delete/') ?>${id}`, {
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
                    pasienTable.loadData();
                } else {
                    Swal.fire('Gagal!', 'Data gagal dihapus.', 'error');
                }
            });
        }
    });
}

function getBeds(idBangsal, selectedBedId = null) {
    const bedSelect = document.getElementById('id_bed');
    
    bedSelect.innerHTML = '<option value="">-- Sedang memuat... --</option>';

    if (!idBangsal) {
        bedSelect.innerHTML = '<option value="">-- Pilih Bed --</option>';
        return;
    }

    fetch(`<?= base_url('pasien/getBeds') ?>/${idBangsal}`)
        .then(response => response.json())
        .then(data => {
            bedSelect.innerHTML = '<option value="">-- Pilih Bed --</option>';
            
            if(data.length > 0) {
                data.forEach(bed => {
                    bedSelect.innerHTML += `<option value="${bed.id_bed}">${bed.nama_bed}</option>`;
                });
                if (selectedBedId) {
                    bedSelect.value = selectedBedId;
                }
            } else {
                bedSelect.innerHTML = '<option value="">-- Tidak ada Bed tersedia --</option>';
            }
        })
        .catch(error => {
            console.error('Error fetching beds:', error);
            bedSelect.innerHTML = '<option value="">-- Gagal memuat data --</option>';
        });
}

function simpanPasien(event) {
    event.preventDefault(); 
    const formData = new FormData(document.getElementById('pasienForm'));
    
    fetch(`<?= base_url('pasien/save') ?>`, {
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
            pasienTable.loadData();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Data gagal disimpan.'
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
</script>
<?= $this->endSection() ?>