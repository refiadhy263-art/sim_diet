<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Perawat | SIMDIET </title>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="space-y-4">
    <!-- Header -->
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold">👩‍⚕️ Data Perawat</h2>
        <button onclick="showModalTambahPerawat()" class="px-4 py-2 bg-blue-600 text-white rounded-xl">
            + Tambah Perawat
        </button>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-4 rounded-xl shadow flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-600">Cari (Nama/Username)</label>
            <input type="text" id="searchPerawatInput" placeholder="Ketik nama atau username..." class="w-full border rounded-lg p-2">
        </div>
        
        <div class="w-48">
            <label class="block text-sm font-medium text-gray-600">Filter Bangsal</label>
            <select id="filterBangsalPerawatSelect" class="w-full border rounded-lg p-2">
                <option value="">Semua Bangsal</option>
                <!-- Render option dari data Controller menggunakan PHP -->
                <?php foreach($bangsalList as $b): ?>
                    <option value="<?= esc($b['kd_bangsal']) ?>"><?= esc($b['icon']) ?> <?= esc($b['nama_bangsal']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <button id="resetPerawatFilterBtn" class="px-4 py-2 bg-gray-300 rounded-lg">Reset</button>
        </div>
    </div>

    <!-- Container Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm bg-white rounded-2xl shadow">
            <thead class="bg-slate-50">
                <tr class="text-left border-b">
                    <th class="p-3">Nama</th>
                    <th class="p-3">Username</th>
                    <th class="p-3">Bangsal</th>
                    <th class="p-3">NIP</th>
                    <th class="p-3">No. Telp</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody id="perawatTableBody">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Form Perawat -->
<div id="perawatModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg shadow-xl relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-red-500 font-bold text-xl">&times;</button>
        <h3 id="modalTitle" class="text-xl font-bold mb-4">👩‍⚕️ Tambah Perawat</h3>
        
        <form id="perawatForm" onsubmit="simpanPerawat(event)">
            <input type="hidden" id="id_perawat" name="id_perawat">
            
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                    <input type="text" id="nama_perawat" name="nama_perawat" required class="w-full border rounded-lg p-2 mt-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Username</label>
                    <input type="text" id="username" name="username" required class="w-full border rounded-lg p-2 mt-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Password</label>
                    <input type="password" id="password" name="password" required class="w-full border rounded-lg p-2 mt-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Penempatan Bangsal</label>
                    <select id="id_bangsal" name="id_bangsal" required class="w-full border rounded-lg p-2 mt-1">
                        <option value="">-- Pilih Bangsal --</option>
                        <?php foreach($bangsalList as $b): ?>
                            <option value="<?= esc($b['id_bangsal']) ?>"><?= esc($b['nama_bangsal']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">NIP</label>
                        <input type="text" id="nip" name="nip" class="w-full border rounded-lg p-2 mt-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">No. Telp</label>
                        <input type="text" id="telepon" name="telepon" class="w-full border rounded-lg p-2 mt-1">
                    </div>
                    
                </div>
                <div>
                        <label class="block text-sm font-medium text-gray-600">Photo</label>
                        <input type="text" id="photo" name="photo" class="w-full border rounded-lg p-2 mt-1" placeholder="(URL foto)">
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPerawatInput');
    const filterSelect = document.getElementById('filterBangsalPerawatSelect');
    const resetBtn = document.getElementById('resetPerawatFilterBtn');

    // Load data awal saat halaman pertama kali dibuka
    loadData();

    // Event Listeners untuk real-time update
    searchInput.addEventListener('input', loadData);
    filterSelect.addEventListener('change', loadData);

    // Tombol Reset
    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        filterSelect.value = '';
        loadData();
    });

    // Fungsi Fetch ke endpoint CI4
    function loadData() {
        const search = searchInput.value;
        const bangsal = filterSelect.value;
        
        // Buat URL dengan query parameter
        const url = `<?= base_url('perawat/getData') ?>?search=${encodeURIComponent(search)}&bangsal=${encodeURIComponent(bangsal)}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                let html = '';
                
                if (data.length === 0) {
                    html = `<tr><td colspan="6" class="p-3 text-center text-gray-500">Tidak ada data</td></tr>`;
                } else {
                    data.forEach(p => {
                        html += `
                        <tr class="border-b">
                            <td class="p-3">${p.nama_perawat || '-'}</td>
                            <td class="p-3">${p.username}</td>
                            <td class="p-3">${p.nama_bangsal || '-'}</td>
                            <td class="p-3">${p.nip || '-'}</td>
                            <td class="p-3">${p.telepon || '-'}</td>
                            <td class="p-3">
                                <button onclick="editPerawatModal('${p.id_perawat}')" class="text-blue-500 mr-2">Edit</button>
                                <button onclick="hapusPerawat('${p.id_perawat}')" class="text-red-500">Hapus</button>
                            </td>
                        </tr>`;
                    });
                }
                
                document.getElementById('perawatTableBody').innerHTML = html;
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    window.loadData = loadData;
});

// Menampilkan modal untuk Tambah Data
function showModalTambahPerawat() {
    document.getElementById('modalTitle').innerText = 'Tambah Perawat';
    document.getElementById('perawatForm').reset();
    document.getElementById('id_perawat').value = ''; 
    document.getElementById('perawatModal').classList.remove('hidden');
}

// Menutup modal
function closeModal() {
    document.getElementById('perawatModal').classList.add('hidden');
}

// Menampilkan modal untuk Edit Data & Ambil data dari server
function editPerawatModal(id) {
    fetch(`<?= base_url('perawat/edit/') ?>${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').innerText = '✏️ Edit Perawat';
            document.getElementById('id_perawat').value = data.id_perawat;
            document.getElementById('nama_perawat').value = data.nama_perawat;
            document.getElementById('username').value = data.username;
            document.getElementById('id_bangsal').value = data.id_bangsal;
            document.getElementById('nip').value = data.nip;
            document.getElementById('telepon').value = data.telepon;
            
            document.getElementById('perawatModal').classList.remove('hidden');
        })
        .catch(error => alert('Gagal mengambil data!'));
}

// Proses Simpan (Tambah & Edit)
function simpanPerawat(event) {
    event.preventDefault(); // Mencegah reload halaman
    
    const formData = new FormData(document.getElementById('perawatForm'));
    
    fetch(`<?= base_url('perawat/save') ?>`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            alert(data.message);
            closeModal();
            loadData(); // Refresh tabel yang ada di script Anda sebelumnya
        } else {
            alert('Gagal menyimpan data');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Proses Hapus Data
function hapusPerawat(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data perawat ini?')) {
        fetch(`<?= base_url('perawat/delete/') ?>${id}`, {
            method: 'POST' // Menggunakan POST untuk hapus demi keamanan
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                alert(data.message);
                loadData(); // Refresh tabel
            } else {
                alert('Gagal menghapus data');
            }
        });
    }
}
</script>
<?= $this->endSection() ?>


