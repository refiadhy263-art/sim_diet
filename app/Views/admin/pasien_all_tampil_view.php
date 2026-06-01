<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET </title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="flex justify-between items-center no-print">
        <h2 class="text-xl font-bold"><i class="fi fi-rr-hospital text-blue-500 mr-2"></i>Data Pasien Keseluruhan</h2>
        <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white rounded-xl font-bold shadow-md">
            <i class="fi fi-rr-print mr-2"></i>Cetak List Pasien
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl shadow flex flex-wrap gap-3 items-end no-print">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Cari Nama/RM</label>
            <input type="text" id="searchPasienInput" class="w-full border rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1" placeholder="Cari...">
        </div>
        <div class="w-48">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Filter Bangsal</label>
            <select id="filterBangsalPasienSelect" class="w-full border rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1 ">
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
        <table id="tabelDataPasien" class="w-full text-sm">
            <thead class="bg-slate-50/75 border-b border-slate-100">
                <tr>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">RM</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pasien</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bangsal/Bed</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Diet</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Catatan</th>
                    <th class="p-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider no-print">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-500">
            </tbody>
        </table>
        
        <!-- Pagination Controls -->
        <div class="flex justify-between items-center p-4 border-t border-slate-100 bg-slate-50/50 gap-4 flex-wrap no-print">
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
    <!-- Mengubah max-w-lg menjadi max-w-3xl agar dua kolom memiliki ruang yang cukup -->
    <div class="bg-white rounded-2xl p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl relative mx-4 transform scale-95 transition-transform duration-300">
           <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 font-bold text-2xl transition-colors">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fi fi-rr-hospital text-blue-600 flex items-center"></i> Tambah Pasien</h3>
        
        <form id="pasienForm" onsubmit="simpanPasien(event)">
            <?= csrf_field() ?>
            <input type="hidden" id="id_pasien" name="id_pasien">
            <input type="hidden" name="nama_pasien" id="nama_pasien">
            <input type="hidden" name="no_rm" id="no_rm">
            <input type="hidden" name="tanggal_lahir" id="tanggal_lahir">
            <input type="hidden" name="diagnosa" id="diagnosa">
            <input type="hidden" name="id_bangsal" id="id_bangsal" >
            <input type="hidden" name="id_bed" id="id_bed">
            
            <!-- Grid Utama: 1 Kolom di HP, 2 Kolom di md ke atas -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-2">
                
                <!-- ================= KOLOM KIRI ================= -->
                <div class="space-y-2">
                   
                      <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Jenis Diet</label>
                        <select id="id_jenis_diet" name="id_jenis_diet" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <option value="">-- Pilih Diet --</option>
                            <?php foreach($dietList as $d): ?>
                                <option value="<?= esc($d['id_jenis_diet']) ?>"><?= esc($d['nama_jenis_diet']) ?></option>
                            <?php endforeach; ?>
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

                <!-- ================= ELEMEN FULL WIDTH ================= -->
                 <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Catatan</label>
                    <textarea id="keterangan" name="keterangan" class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" rows="3" placeholder="(Catatan)"></textarea>
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
            bangsal: this.filterSelect?.value || ''
        });
        
        fetch(`${this.apiUrl}?${params}`)
            .then(response => response.json())
            .then(data => {
                this.allRows = data.data || [];
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
            this.tbody.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-slate-500">Tidak ada data pasien ditemukan.</td></tr>';
            return;
        }
        
        this.tbody.innerHTML = paginatedRows.map(row => `
            <tr>
                <td class="p-4 font-mono text-xs text-slate-500 align-middle">${row.no_rm}</td>
                <td class="p-4 font-bold text-slate-800 align-middle">${row.nama_pasien}</td>
                <td class="p-4 align-middle">
                    <div class="text-slate-700 font-medium">${row.nama_bangsal || '-'}</div>
                    <div class="text-xs font-semibold text-blue-600 font-mono mt-0.5">${row.nama_bed || '-'}</div>
                </td>
                <td class="p-4 align-middle">
                    <span class="text-xs font-bold text-blue-600">${row.nama_jenis_diet || '-'}</span>
                    ${row.nama_bentuk_diet ? `<div class="text-xs font-semibold text-slate-500 mt-1">${row.nama_bentuk_diet}</div>` : ''}
                </td>
                <td class="p-4 text-red-600 text-xs italic align-middle">${row.keterangan || '-'}</td>
                <td class="p-4 text-center no-print align-middle whitespace-nowrap">
                    <button onclick="editPasienGiziModal('${row.id_pasien}')" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors mr-2 inline-flex items-center gap-1">
                        <i class="fi fi-rr-edit"></i> Edit
                    </button>
                    <button onclick="printLabelIndividu('${row.id_pasien}')" class="text-xs bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg font-bold hover:bg-slate-200 transition-colors inline-flex items-center gap-1">
                        <i class="fi fi-rr-print"></i> Cetak Label
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
            this.paginationInfo.innerHTML = `Menampilkan ${start} sampai ${end} dari <span id="totalRows">${total}</span> pasien`;
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
    pasienTable = new PasienTable('#tabelDataPasien', {
        searchInput: document.getElementById('searchPasienInput'),
        filterSelect: document.getElementById('filterBangsalPasienSelect'),
        resetBtn: document.getElementById('resetFilterBtn'),
        perPageSelect: document.getElementById('perPageSelectPasien'),
        paginationButtons: document.getElementById('paginationButtons'),
        paginationInfo: document.getElementById('paginationInfo'),
        totalRowsSpan: document.getElementById('totalRows'),
        apiUrl: '<?= site_url('pasien/getAllData') ?>'
    });
});

// Fungsi Cetak (Membuka tab baru ke rute CI4)
function printLabelIndividu(idPasien) {
    const printUrl = `<?= site_url('pasien/print_label/') ?>${idPasien}`;
    window.open(printUrl, '_blank', 'width=600,height=600');
}
function showModalPasien() {
    document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-doctor text-blue-600 flex items-center"></i> Tambah Pasien';
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
function editPasienGiziModal(idPasien) {
    fetch(`<?= base_url('pasien/edit/') ?>${idPasien}`)
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
            
            // Muat data bed untuk bangsal ini, lalu pilih bed yang aktif
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
function getBeds(idBangsal, selectedBedId = null) {
    const bedSelect = document.getElementById('id_bed');
    
    // Tampilkan status loading
    bedSelect.innerHTML = '<option value="">-- Sedang memuat... --</option>';

    // Jika tidak ada bangsal yang dipilih, kembalikan ke default
    if (!idBangsal) {
        bedSelect.innerHTML = '<option value="">-- Pilih Bed --</option>';
        return;
    }

    // Lakukan request ke server
    fetch(`<?= base_url('pasien/getBeds') ?>/${idBangsal}`)
        .then(response => response.json())
        .then(data => {
            bedSelect.innerHTML = '<option value="">-- Pilih Bed --</option>';
            
            // Cek jika data bed tersedia
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
            pasienTable.loadData(); // Reload tabel
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