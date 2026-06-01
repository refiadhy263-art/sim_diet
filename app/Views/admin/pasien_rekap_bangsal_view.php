<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= esc($title ?? 'Rekap Bangsal') ?> | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php if (!empty($bangsalList)): ?>
        <?php foreach ($bangsalList as $b): ?>
            <div onclick="bukaModalBangsal('<?= esc($b['id_bangsal']) ?>')" 
                 class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center cursor-pointer hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center gap-3">
                    <i class="fi fi-rr-bed-alt text-blue-600 text-2xl"></i>
                    <div class="font-bold text-gray-800 text-lg">
                        <?= esc($b['nama_bangsal']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-1 md:col-span-3 text-center p-10 text-gray-400 font-medium bg-gray-50 rounded-xl border border-dashed">
            Belum ada data bangsal yang terdaftar.
        </div>
    <?php endif; ?>
</div>

<div id="modalContainer"></div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Tarik HTML Modal dari Server menggunakan jQuery AJAX
    function bukaModalBangsal(idBangsal) {
        // Tampilkan loading sementara jika berkenan (opsional)
        $('#modalContainer').html('<div class="fixed inset-0 bg-slate-900/50 z-50 flex justify-center items-center"><div class="bg-white p-4 rounded-lg">Memuat data...</div></div>');

        // Fetch data and execute any inline or external scripts in the returned HTML so modal functions work
        $.get('<?= site_url('pasien/detail_bangsal/') ?>' + idBangsal, function(htmlResponse) {
            // Insert HTML first
            $('#modalContainer').html(htmlResponse);

            // Parse the response to find script tags (both inline and external)
            var $parsed = $('<div>').html(htmlResponse);
            $parsed.find('script').each(function() {
                var $s = $(this);
                var src = $s.attr('src');
                var type = $s.attr('type');

                if (src) {
                    // Avoid loading the same external script multiple times
                    if (!$('script[src="' + src + '"]').length) {
                        var scriptEl = document.createElement('script');
                        if (type) scriptEl.type = type;
                        scriptEl.src = src;
                        document.body.appendChild(scriptEl);
                    }
                } else {
                    // Inline script: execute in global scope
                    try {
                        $.globalEval($s.html() || $s.text() || '');
                    } catch (e) {
                        console.error('Error executing inline script from modal:', e);
                    }
                }
            });

        }).fail(function() {
            alert("Gagal memuat data bangsal.");
            $('#modalContainer').html(''); // Bersihkan jika gagal
        });
    }

    // Menghapus modal dari layar
    function tutupModalBangsal() {
        $('#modalContainer').html('');
    }

    // Fungsi cetak area spesifik
    function cetakArea(elementId) {
        const printContent = document.getElementById(elementId).innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        
        document.body.innerHTML = originalContent;
        window.location.reload(); 
    }

// Fallbacks: define global functions if not provided by modal HTML (prevents 'is not defined' errors)
if (typeof window.tutupModalBangsal !== 'function') {
    window.tutupModalBangsal = function() { $('#modalContainer').html(''); };
}
if (typeof window.cetakArea !== 'function') {
    window.cetakArea = function(elementId) {
        var el = document.getElementById(elementId);
        if (!el) { alert('Area untuk dicetak tidak ditemukan.'); return; }
        var w = window.open('', '_blank');
        w.document.write('<html><head><title>Cetak</title></head><body>' + el.innerHTML + '</body></html>');
        w.document.close();
        w.focus();
        w.print();
        w.close();
    };
}
if (typeof window.editPasienGiziModal !== 'function') {
    window.editPasienGiziModal = function(idPasien) {
        fetch(`<?= base_url('pasien/edit/') ?>${idPasien}`)
            .then(response => response.json())
            .then(data => {
                // populate pasienModal if exists
                if (document.getElementById('pasienModal')) {
                    document.getElementById('modalTitle').innerHTML = '<i class="fi fi-rr-edit text-blue-600 flex items-center"></i> Edit Pasien';
                    document.getElementById('id_pasien').value = data.id_pasien || '';
                    document.getElementById('nama_pasien').value = data.nama_pasien || '';
                    document.getElementById('no_rm').value = data.no_rm || '';
                    document.getElementById('id_bangsal').value = data.id_bangsal || '';
                    document.getElementById('id_jenis_diet').value = data.id_jenis_diet || '';
                    document.getElementById('tanggal_lahir').value = data.tanggal_lahir || '';
                    document.getElementById('diagnosa').value = data.diagnosa || '';
                    document.getElementById('id_bentuk_diet').value = data.id_bentuk_diet || '';
                    document.getElementById('keterangan').value = data.keterangan || '';

                    const modal = document.getElementById('pasienModal');
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.querySelector('.bg-white').classList.remove('scale-95');
                        modal.querySelector('.bg-white').classList.add('scale-100');
                    }, 10);
                }
            })
            .catch(error => console.error('Error loading pasien for edit:', error));
    };
}
</script>
<?= $this->endSection() ?>