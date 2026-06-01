<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET </title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" rel="stylesheet">
<div class="space-y-4">
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fi fi-rr-hospital text-blue-600 flex items-center"></i> Daftar Pasien Dirawat (<?= esc($nama_bangsal) ?>)
        </h2>
        <button onclick="showModalTambahPasien()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md flex items-center gap-1.5">
            <i class="fi fi-rr-plus flex items-center"></i> Tambah Pasien
        </button>
    </div>

    <div class="bg-white p-4 rounded-xl shadow flex flex-wrap gap-3 items-end mb-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Cari Pasien (Nama/No. RM)</label>
            <input type="text" id="customSearchInput" placeholder="Ketik nama atau RM..." class="w-full border border-gray-200 rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1 outline-none">
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
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bed</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Diagnosa</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Diet</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bentuk</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Order</th>
                    <th class="p-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-500">
                <?php foreach($pasienList as $pasien): ?>
                    <tr>
                        <td class="p-4 font-mono text-xs text-slate-500 align-middle"><?= esc($pasien['no_rm']) ?></td>
                        <td class="p-4 font-bold text-slate-800 align-middle"><?= esc($pasien['nama_pasien']) ?></td>
                        <td class="p-4 font-mono text-xs font-semibold text-blue-600 align-middle"><?= esc($pasien['nama_bed']) ?></td>
                        <td class="p-4 text-slate-700 align-middle"><?= esc($pasien['diagnosa']) ?></td>
                        <td class="p-4 text-xs font-bold text-blue-600 align-middle"><?= esc($pasien['nama_jenis_diet']) ?></td>
                        <td class="p-4 text-xs font-semibold text-slate-500 align-middle"><?= esc($pasien['nama_bentuk_diet']) ?></td>
                        <td class="p-4 align-middle">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-700">
                                <?= esc(orderStatus($pasien['status_order'])['label']) ?>
                            </span>
                        </td>
                        <td class="p-4 text-center align-middle whitespace-nowrap">
                            <button onclick="editPasienModal('<?= esc($pasien['id_pasien']) ?>')" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                <i class="fi fi-rr-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
            
            <!-- Grid Utama: 1 Kolom di HP, 2 Kolom di md ke atas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                
                <!-- ================= KOLOM KIRI ================= -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama_pasien" name="nama_pasien" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="(Tanggal Lahir)">
                    </div>
                   
                   <!-- Pada bagian Penempatan Bangsal, tambahkan onchange -->
                     <div>
                         <label class="block text-sm font-semibold text-gray-600 mb-1">Penempatan Bangsal</label>
    
                        
                            <input type="hidden" id="id_bangsal" name="id_bangsal" value="<?= session()->get('id_bangsal') ?>">
        
                            <select disabled class="w-full border border-gray-200 bg-gray-100 rounded-xl p-3 outline-none cursor-not-allowed">
                             <option value="<?= session()->get('id_bangsal') ?>">
                                <?= esc($nama_bangsal) ?>
                             </option>
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

                <!-- ================= KOLOM KANAN ================= -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">No. RM</label>
                        <input type="text" id="no_rm" name="no_rm" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                   <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Diagnosa</label>
                        <input type="text" id="diagnosa" name="diagnosa" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                   
                <!-- Pada bagian Penempatan Bed, kosongkan isinya -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Penempatan Bed</label>
                        <select id="id_bed" name="id_bed" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <option value="">-- Pilih Bed --</option>
                            <?php foreach($bedList as $b): ?>
                                <option value="<?= esc($b['id_bed']) ?>"><?= esc($b['nama_bed']) ?></option>
                            
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>

<script>



$(document).ready(function() {
    table = $('#tabelPasien').DataTable({
        dom: 'rt<"flex justify-between items-center p-4 border-t border-slate-100 bg-slate-50/50"<"text-sm text-slate-500"i><"text-sm"p>>',
        responsive: true,
        language: {
            emptyTable: "Tidak ada pasien dirawat di bangsal ini.",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pasien",
            infoEmpty: "Menampilkan 0 pasien",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            }
        }
    });

    $('#customSearchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#resetFilterBtn').on('click', function() {
        $('#customSearchInput').val('');
        table.search('').draw();
    });
});

// Fungsi Modal Tambah
function showModalTambahPasien() {
    document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-hospital text-blue-600 flex items-center"></i> Tambah Pasien';
    document.getElementById('pasienForm').reset();
    document.getElementById('id_pasien').value = ''; 
    
    const modal = document.getElementById('pasienModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

// Fungsi Modal Tutup
function closeModal() {
    const modal = document.getElementById('pasienModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 150);
}

// Fungsi Modal Edit
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
            
            // Muat data bed untuk bangsal ini, lalu pilih bed yang aktif
            if (typeof getBeds === "function") {
                getBeds(data.id_bangsal, data.id_bed);
            }
            
            const modal = document.getElementById('pasienModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.bg-white').classList.remove('scale-95');
                modal.querySelector('.bg-white').classList.add('scale-100');
            }, 10);
        })
        .catch(error => {
            console.error('Penyebab Error:', error);
            console.error('Gagal mengambil data! Periksa inspect element -> console untuk detail.');
        });
} 
// Fungsi Simpan Data
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
            
            // Setelah 2 detik, tutup modal dan refresh halaman
            setTimeout(() => {
                closeModal();
                location.reload();
            }, 2000);
          
        } else {
            // Menampilkan pesan error objek terurai dari CI4 jika ada
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
</script>
<script>
function getBeds(idBangsal, selectedBedId = null) {
    const bedSelect = document.getElementById('id_bed');
    
    

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
</script>
<?= $this->endSection() ?>