<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET </title>
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
            <i class="fi fi-rr-exit text-blue-600 flex items-center"></i> 
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
    
    <div class="bg-white p-4 rounded-xl shadow overflow-x-auto">
        <table id="tabelRiwayat" class="w-full text-sm">
                <thead class="bg-slate-50/75 border-b border-slate-100">
                    <tr>
                        <th class="p-4 text-left">RM</th>
                        <th class="p-4 text-left">Pasien</th>
                        <th class="p-4 text-left">Diagnosa Terakhir</th>
                        <th class="p-4 text-left">Bed Terakhir</th>
                        <th class="p-4 text-center">Status Akhir</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-500">
                    <?php if (!empty($pasienList)): ?>
                        <?php foreach ($pasienList as $p): ?>
                            <tr class="hover:bg-gray-50 transition-colors align-middle">
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
            
            <!-- Pagination Controls -->
            <div class="flex justify-between items-center p-4 border-t border-slate-100 bg-slate-50/50 gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <label for="perPageSelect" class="text-sm text-slate-600 font-medium">Baris per halaman:</label>
                    <select id="perPageSelect" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
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
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Simple Table Handler - Vanilla JS dengan Pagination
class SimpleTable {
    constructor(tableSelector, options = {}) {
        this.table = document.querySelector(tableSelector);
        this.tbody = this.table.querySelector('tbody');
        this.rows = Array.from(this.tbody.querySelectorAll('tr'));
        this.allRows = [...this.rows];
        this.currentPage = 1;
        this.perPage = options.perPage || 10;
        this.perPageSelect = options.perPageSelect || null;
        this.paginationButtons = options.paginationButtons || null;
        this.paginationInfo = options.paginationInfo || null;
        this.totalRowsSpan = options.totalRowsSpan || null;
        
        this.init();
    }
    
    init() {
        if (this.perPageSelect) {
            this.perPageSelect.addEventListener('change', (e) => this.handlePerPageChange(e));
        }
        this.updateTotalRows();
        this.render();
    }
    
    handlePerPageChange(e) {
        this.perPage = parseInt(e.target.value);
        this.currentPage = 1;
        this.render();
    }
    
    updateTotalRows() {
        if (this.totalRowsSpan) {
            this.totalRowsSpan.textContent = this.rows.length;
        }
    }
    
    getTotalPages() {
        return Math.ceil(this.rows.length / this.perPage);
    }
    
    getPaginatedRows() {
        const start = (this.currentPage - 1) * this.perPage;
        const end = start + this.perPage;
        return this.rows.slice(start, end);
    }
    
    updatePaginationInfo() {
        if (this.paginationInfo) {
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
        
        if (this.currentPage > 1) {
            html += `<button onclick="table.goToPage(${this.currentPage - 1})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">← Sebelumnya</button>`;
        }
        
        const maxVisible = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }
        
        if (startPage > 1) {
            html += `<button onclick="table.goToPage(1)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">1</button>`;
            if (startPage > 2) {
                html += `<span class="px-2 py-1.5 text-gray-600">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === this.currentPage) {
                html += `<button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium">${i}</button>`;
            } else {
                html += `<button onclick="table.goToPage(${i})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">${i}</button>`;
            }
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span class="px-2 py-1.5 text-gray-600">...</span>`;
            }
            html += `<button onclick="table.goToPage(${totalPages})" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">${totalPages}</button>`;
        }
        
        if (this.currentPage < totalPages) {
            html += `<button onclick="table.goToPage(${this.currentPage + 1})" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition">Selanjutnya →</button>`;
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
        this.allRows.forEach(row => row.style.display = 'none');
        
        const paginatedRows = this.getPaginatedRows();
        paginatedRows.forEach(row => row.style.display = '');
        
        this.updateTotalRows();
        this.updatePaginationInfo();
        this.renderPaginationButtons();
    }
}

let table;

document.addEventListener('DOMContentLoaded', function() {
    table = new SimpleTable('#tabelRiwayat', {
        perPageSelect: document.getElementById('perPageSelect'),
        paginationButtons: document.getElementById('paginationButtons'),
        paginationInfo: document.getElementById('paginationInfo'),
        totalRowsSpan: document.getElementById('totalRows')
    });
});

function updateStatusPasien(pasienId, statusBaru, element) {
    Swal.fire({
        title: 'Kembalikan Pasien?',
        text: "Apakah Anda yakin ingin mengembalikan pasien ini ke status Dirawat?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
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
                    Swal.fire({icon: 'success', title: 'Berhasil!', showConfirmButton: false, timer: 1500});
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire({icon: 'error', title: 'Gagal!', text: data.message});
                    element.innerHTML = originalText;
                    element.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({icon: 'error', title: 'Gagal!', text: 'Kesalahan koneksi.'});
                element.innerHTML = originalText;
                element.disabled = false;
            });
        }
    });
}
</script>
<?= $this->endSection() ?>