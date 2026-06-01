<div class="modal-backdrop fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    
    <div id="print-area-<?= $bangsal['id_bangsal'] ?>" class="bg-white rounded-2xl w-full max-w-5xl p-6 max-h-[90vh] overflow-y-auto shadow-2xl relative">
        
        <div class="flex justify-between items-center mb-4 print:hidden no-print">
            <h3 class="text-xl font-bold"><i class="fi fi-rr-bed-alt"></i> <?= esc($bangsal['nama_bangsal']) ?></h3>
            <div class="flex gap-2">
                <button onclick="cetakArea('print-area-<?= $bangsal['id_bangsal'] ?>')" class="px-3 py-1 bg-slate-700 text-white rounded text-sm flex items-center gap-1">
                    <i class="fi fi-rr-print"></i> Cetak
                </button>
                <button onclick="tutupModalBangsal()" class="text-red-500 text-2xl hover:text-red-700 leading-none">
                    &times;
                </button>
            </div>
        </div>

        <div class="flex gap-3 mb-4 print:hidden no-print">
            <button onclick="prosesBatchStatus('<?= $bangsal['id_bangsal'] ?>', '0', '1')"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg flex-1 font-bold shadow-sm disabled:opacity-50" 
                    <?= empty($hasMenunggu) ? 'disabled' : '' ?>>
                <span class="mr-1"><i class="fi fi-rr-check"></i></span> Terima Semua
            </button>
            <button onclick="prosesBatchStatus('<?= $bangsal['id_bangsal'] ?>', '1', '2')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg flex-1 font-bold shadow-sm disabled:opacity-50" 
                    <?= empty($hasDiproses) ? 'disabled' : '' ?>>
                <span class="mr-1"><i class="fi fi-rr-bowl-rice"></i></span> Siapkan Semua
            </button>
        </div>

        <div class="mb-4 border border-gray-100 p-4 rounded-xl bg-gray-50">
            <h4 class="font-bold mb-2 flex items-center gap-1 text-sm"><i class="fi fi-rr-calculator mr-2"></i> Rekap Diet per Bangsal</h4>
            <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-xs text-gray-700">
                <?php if (!empty($dietBentukCount)): ?>
                    <?php foreach ($dietBentukCount as $key => $count): ?>
                        <div><?= esc($key) ?> = <?= esc($count) ?></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-gray-400">Belum ada data diet.</div>
                <?php endif; ?>
            </div>
            <div class="font-bold mt-3 border-t pt-2 text-sm">Total pasien: <?= count($pasien) ?></div>
        </div>

        <div class="overflow-x-auto rounded-lg border">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-gray-600">
                        <th class="p-3">Bed</th>
                        <th class="p-3">Pasien</th>
                        <th class="p-3">Diet</th>
                        <th class="p-3">Bentuk</th>
                        <th class="p-3 text-orange-700 bg-orange-50"><i class="fi fi-rr-warning mr-2"></i> Catatan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 print:hidden no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pasien)): ?>
                        <?php foreach ($pasien as $p): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-bold text-blue-600"> <?= esc($p['nama_bed'] ?? '-') ?></td>
                                <td class="p-3">
                                    <strong><?= esc($p['nama_pasien']) ?></strong><br>
                                    <span class="text-[10px] text-gray-400"><?= esc($p['no_rm']) ?></span>
                                </td>
                                <td class="p-3"><?= esc($p['nama_jenis_diet'] ?? 'Belum Diatur') ?></td>
                                <td class="p-3"><?= esc($p['nama_bentuk_diet'] ?? 'Biasa') ?></td>
                                <td class="p-3 bg-orange-50 text-red-600 font-bold italic"><?= esc($p['keterangan'] ?? '-') ?></td>
                                <td class="p-3"><span class="px-2 py-1 rounded <?= orderStatus($p['status_order'] ?? '0')['color'] ?> text-xs font-bold"><?= orderStatus($p['status_order'])['label'] ?></span></td>
                                <td class="p-3 print:hidden no-print">
                                    <button onclick="editPasienGiziModal('<?= esc($p['id_pasien']) ?>')" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors mr-2 inline-flex items-center gap-1">
                                        <i class="fi fi-rr-edit"></i> Edit
                    </button> </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="p-4 text-center text-gray-500">Tidak ada pasien dirawat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
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


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>

<script>
// Fungsi untuk memproses update status massal per bangsal
function prosesBatchStatus(idBangsal, statusLama, statusBaru) {
    // Penamaan label untuk notifikasi
    const textLama = statusLama === '0' ? 'Menunggu' : 'Sedang Disiapkan';
    const textBaru = statusBaru === '1' ? 'Sedang Disiapkan' : 'Siap Antar';

    // 1. Munculkan pop-up konfirmasi
    Swal.fire({
        title: 'Konfirmasi Pesanan',
        text: `Ubah semua pesanan "${textLama}" menjadi "${textBaru}" di bangsal ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah Semua!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        
        if (result.isConfirmed) {
            
            // 2. Tampilkan loading agar tidak diklik dua kali
            Swal.fire({ 
                title: 'Memproses...', 
                allowOutsideClick: false, 
                didOpen: () => Swal.showLoading() 
            });

            // 3. Siapkan data form
            const formData = new FormData();
            formData.append('id_bangsal', idBangsal);
            formData.append('status_lama', statusLama);
            formData.append('status_baru', statusBaru);
            // formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>'); // Buka komentar jika CSRF aktif

            // 4. Kirim ke server
            fetch('<?= site_url('pasien/update_batch_status') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => {
                        // Refresh data modal dengan memanggil fungsi buka modal lagi
                        bukaModalBangsal(idBangsal); 
                    });
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error!', 'Terjadi kesalahan saat berkomunikasi dengan server.', 'error');
            });
        }
    });
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
            }).then(() => {
                const bangsalId = document.getElementById('id_bangsal')?.value;
                closeModal();
                if (bangsalId && typeof window.bukaModalBangsal === 'function') {
                    bukaModalBangsal(bangsalId);
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Data gagal disimpan.'
            });
        }
    })
   // Ganti blok catch lama Anda menjadi ini untuk sementara
    .catch(err => {
        console.error("Error aslinya adalah:", err); // Mencetak error asli ke console
    
        Swal.fire(
            'Terjadi Kesalahan JS/JSON', 
            err.message, // Memunculkan pesan error asli langsung di modal
            'error'
        );
    });
}
    
</script>