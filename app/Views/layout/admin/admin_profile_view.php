<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Dashboard | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Default box -->
 

<?php if (session()->get('role') == '2'){ ?>
    <!-- Dashboard Perawat -->
<div class="space-y-6">
    
<div id="dashboardContainer" class="space-y-6">
    
    <div class="flex justify-center items-center p-10 h-64 bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="text-center">
            <i class="fi fi-rr-spinner animate-spin text-4xl text-blue-500 mb-3 block"></i>
            <p class="text-slate-500 font-medium">Memuat data dashboard...</p>
        </div>
    </div>

</div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    
    // 1. Panggil fungsi untuk memuat data pertama kali saat halaman dibuka
    loadDashboardPerawat();

    // 2. Set Auto-Refresh (Polling) setiap 1 menit (60000 ms)

        // Global helper to open pasien detail modal (works across dashboard variants)
        function bukaModalBangsal(idBangsal) {
            if (!document.getElementById('modalContainer')) {
                const div = document.createElement('div');
                div.id = 'modalContainer';
                document.body.appendChild(div);
            }
            const container = document.getElementById('modalContainer');
            container.innerHTML = '<div class="fixed inset-0 bg-slate-900/50 z-50 flex justify-center items-center"><div class="bg-white p-4 rounded-lg">Memuat data...</div></div>';

            fetch('<?= site_url('pasien/detail_bangsal/') ?>' + encodeURIComponent(idBangsal))
                .then(resp => resp.text())
                .then(html => { container.innerHTML = html; })
                .catch(() => { alert('Gagal memuat data bangsal.'); container.innerHTML = ''; });
        }

    // Tampilan akan terupdate otomatis secara real-time tanpa refresh layar!
    setInterval(loadDashboardPerawat, 60000); 
    
});

function loadDashboardPerawat() {
    // Sesuaikan URL ini dengan route controller Anda
    const url = '<?= site_url('dashboard/getPerawatDashboardData') ?>'; 

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Jika ada error dari backend (misal: bukan role perawat)
            if (response.error) {
                $('#dashboardContainer').html(`<div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 text-center font-bold">Error: ${response.error}</div>`);
                return;
            }
            
            // Lemparkan data JSON ke fungsi perender HTML
            renderDashboardHTML(response);
        },
        error: function(xhr, status, error) {
            console.error('Gagal memuat dashboard:', error);
            $('#dashboardContainer').html(`<div class="text-center p-10 text-red-500">Gagal terhubung ke server. Silakan muat ulang halaman.</div>`);
        }
    });
}

function renderDashboardHTML(data) {
    // --- A. Render Distribusi Diet ---
    let dietHtml = '';
    if (Object.keys(data.dietCount).length > 0) {
        for (const [nama, jml] of Object.entries(data.dietCount)) {
            dietHtml += `
                <div class="flex justify-between items-center border-b border-gray-50 pb-1">
                    <span>${nama}</span>
                    <span class="font-bold text-blue-800 bg-blue-100 px-2 py-0.5 rounded">${jml}</span>
                </div>`;
        }
    } else {
        dietHtml = '<div class="text-gray-400 italic">Belum ada data diet pasien.</div>';
    }

    // --- B. Render Distribusi Bentuk Makanan ---
    let bentukHtml = '';
    if (Object.keys(data.bentukCount).length > 0) {
        for (const [nama, jml] of Object.entries(data.bentukCount)) {
            bentukHtml += `
                <div class="flex justify-between items-center border-b border-gray-50 pb-1">
                    <span>${nama}</span>
                    <span class="font-bold text-blue-800 bg-blue-100 px-2 py-0.5 rounded">${jml}</span>
                </div>`;
        }
    } else {
        bentukHtml = '<div class="text-gray-400 italic">Belum ada data bentuk makanan.</div>';
    }

    // --- C. Render Status Order Makanan ---
    let statsHtml = '';
    for (const key in data.orderStats) {
        const s = data.orderStats[key];
        const opacityClass = s.label === 'Menunggu' ? 'opacity-100' : 'opacity-70'; // Highlight status "Menunggu"
        const bgClass = s.label === 'Menunggu' ? 'bg-amber-50 text-amber-600' :
                        s.label === 'Sedang Disiapkan' ? 'bg-blue-50 text-blue-600' :
                        s.label === 'Siap Antar' ? 'bg-green-50 text-green-600' :
                        s.label === 'Sedang Diantar' ? 'bg-purple-50 text-purple-600' :
                        s.label === 'Selesai' ? 'bg-gray-50 text-gray-600' : 'bg-gray-50 text-gray-600';

        statsHtml += `
            <div class="text-center p-3 rounded-xl ${bgClass} ${opacityClass} border border-gray-50 transition-all hover:shadow-sm">
                <p class="text-3xl font-bold text-slate-800">${s.count}</p>
                <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wide">${s.label}</p>
            </div>
        `;
    }

    // --- D. RANGKAI SEMUA MENJADI SATU KESATUAN HTML ---
    const finalHtml = `
        <div class="bg-green-50 rounded-xl p-4 text-emerald-700 font-medium shadow-sm transition-all">
            <i class="fi fi-rr-clock"></i> Waktu: ${data.waktu} WIB | Jadwal aktif: <strong>${data.jadwalAktif}</strong>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-5 shadow-md">
                <h3 class="font-bold text-lg mb-4 text-gray-800"><i class="fi fi-rr-bowl-rice"></i> Distribusi Diet</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    ${dietHtml}
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-md">
                <h3 class="font-bold text-lg mb-4 text-gray-800"><i class="fi fi-rr-soup"></i> Distribusi Bentuk Makanan</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    ${bentukHtml}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-md">
            <h3 class="font-bold text-lg mb-4 text-gray-800"><i class="fi fi-rr-utensils"></i> Status Order Makanan</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                ${statsHtml}
            </div>
        </div>
    `;

    // Suntikkan HTML yang sudah jadi ke dalam wadah di layar
    $('#dashboardContainer').html(finalHtml);
}
</script>

<?php } else if (session()->get('role') == '3') { ?>
<div id="dashboardGiziContainer" class="space-y-6">
    <div class="flex justify-center items-center p-10 h-64 bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="text-center">
            <i class="fi fi-rr-spinner animate-spin text-4xl text-blue-500 mb-3 block"></i>
            <p class="text-slate-500 font-medium">Memuat data...</p>
        </div>
    </div>
</div>

<div id="modalContainer"></div>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Muat pertama kali
    loadDashboardGizi();

    // Auto-refresh setiap 30 detik (30000 ms) agar waktu dan order update real-time
    setInterval(loadDashboardGizi, 30000); 
});

function loadDashboardGizi() {
    $.ajax({
        url: '<?= site_url('dashboard/getGiziDashboardData') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                renderDashboardGizi(response);
            } else {
                $('#dashboardGiziContainer').html(`<div class="text-red-500 p-5 text-center">Gagal memuat data.</div>`);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            $('#dashboardGiziContainer').html(`<div class="text-red-500 p-5 text-center">Terjadi kesalahan koneksi.</div>`);
        }
    });
}

function renderDashboardGizi(data) {
    // 1. Buat HTML untuk Kotak Bangsal (bStats)
    let bStatsHtml = '';
    
    if (data.bStats && data.bStats.length > 0) {
        data.bStats.forEach(function(b) {
            bStatsHtml += `
                <div class="bg-white rounded-2xl p-5 shadow-md hover:shadow-lg transition-all border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl text-blue-600">
                            <i class="${b.icon}"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">${b.nama_bangsal}</h3>
                            <p class="text-sm text-gray-500 font-medium">Total Pasien: ${b.total}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 text-[10px] mb-5 font-bold uppercase tracking-wide">
                        <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">
                            Menunggu: ${b.menunggu}
                        </span>
                        <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-700">
                            Disiapkan: ${b.disiapkan}
                        </span>
                        <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">
                            Siap Antar: ${b.siap}
                        </span>
                    </div>
                    
                    <button onclick="bukaModalBangsal('${b.id_bangsal}')" 
                            class="w-full bg-green-600 text-white py-2.5 rounded-xl text-sm font-bold shadow-md shadow-green-200 hover:bg-green-700 transition-colors flex justify-center items-center gap-2">
                        <i class="fi fi-rr-clipboard-list-check"></i> Detail & Proses
                    </button>
                </div>
            `;
        });
    } else {
        bStatsHtml = `
            <div class="col-span-1 md:col-span-3 text-center p-10 text-gray-400 font-medium bg-gray-50 rounded-xl border border-dashed">
                Belum ada data bangsal untuk ditampilkan.
            </div>
        `;
    }

    // 2. Rangkai dengan HTML Banner Jadwal
    const finalHtml = `
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg no-print shadow-sm transition-all">
            <p class="text-gray-800">
                <i class="fi fi-rr-calendar"></i> <strong>${data.tanggalSekarang}</strong> | <i class="fi fi-rr-clock"></i> <span class="font-mono">${data.waktuSekarang} WIB</span>
            </p>
            <p class="font-bold text-gray-800 mt-1">
                Jadwal aktif: <span class="text-green-700">${data.aktif}</span> | 
                Berikutnya: <span class="text-blue-600">${data.next}</span>
            </p>
            <p class="text-xs text-gray-500 mt-2 italic">* Auto accept jam 05:30, 10:00, 15:30</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            ${bStatsHtml}
        </div>
    `;

    // 3. Masukkan ke dalam DOM layar
    $('#dashboardGiziContainer').html(finalHtml);
}
</script>

<?php } else if (session()->get('role') == '4'): ?>
    <!-- Dashboard Pramusaji -->
    <div class="space-y-6">
        <!-- Dashboard Card -->
        <div class="bg-white rounded-2xl p-6 shadow-md max-w-lg mx-auto text-center">
            <div id="sidebar-bangsal" class="text-4xl mb-2 font-bold text-emerald-700">...</div>
            <h3 id="bangsal-name" class="text-2xl font-bold mb-1">Memuat...</h3>
            <p id="total-pasien" class="text-gray-500 mb-4">Total Pasien Aktif: 0</p>
            
            <!-- Status Badges -->
            <div class="flex justify-center gap-3 mb-6">
                <span id="siap-antar-badge" class="px-3 py-1 <?= orderStatus('2')['color'] ?> rounded-full text-xs font-bold">
                    Siap Antar: 0
                </span>
                <span id="sedang-diantar-badge" class="px-3 py-1 <?= orderStatus('3')['color'] ?> rounded-full text-xs font-bold">
                    Sedang Diantar: 0
                </span>
            </div>

            <!-- Action Buttons -->
            <div id="content-area" class="space-y-3">
                <button id="proses-antar-btn" style="display: none;" onclick="prosesAntar()" 
                    class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg hover:bg-blue-700 transition-all">
                    <i class="fi fi-rr-truck-side"></i> Proses Antar Semua
                </button>
                <button id="verifikasi-btn" style="display: none;" onclick="showVerifikasi()" 
                    class="w-full py-3 bg-green-600 text-white rounded-xl font-bold shadow-lg hover:bg-green-700 transition-all">
                    <i class="fi fi-rr-check-circle"></i> Verifikasi Penerimaan
                </button>
                <p id="no-data-msg" class="text-sm text-gray-400 italic font-medium">
                    Memuat data...
                </p>
            </div>
        </div>
    </div>

    <script>
        // Load dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            // Refresh every 30 seconds
            setInterval(loadDashboardData, 30000);
        });

        function loadDashboardData() {
            fetch('<?php echo base_url('dashboard/getPramusajiDashboardData'); ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('no-data-msg').textContent = 'Error: ' + data.error;
                        return;
                    }

                    // Update bangsal info
                    document.getElementById('sidebar-bangsal').innerHTML = data.bangsal.icon || '<i class="fi fi-rr-bed-alt"></i>';
                    document.getElementById('bangsal-name').textContent = data.bangsal.nama_bangsal || 'Bangsal';
                    document.getElementById('total-pasien').textContent = 'Total Pasien Aktif: ' + data.totalPasien;

                    // Update badges
                    document.getElementById('siap-antar-badge').textContent = 'Siap Antar: ' + data.siapAntar;
                    document.getElementById('sedang-diantar-badge').textContent = 'Sedang Diantar: ' + data.sedangDiantar;

                    // Show/hide buttons
                    const prosesAntarBtn = document.getElementById('proses-antar-btn');
                    const verifikasiBtn = document.getElementById('verifikasi-btn');
                    const noDataMsg = document.getElementById('no-data-msg');

                    prosesAntarBtn.style.display = data.siapAntar > 0 ? 'block' : 'none';
                    verifikasiBtn.style.display = data.sedangDiantar > 0 ? 'block' : 'none';
                    noDataMsg.style.display = (data.siapAntar === 0 && data.sedangDiantar === 0) ? 'block' : 'none';
                    noDataMsg.textContent = 'Pesanan saat ini Sedang Disiapkan';
                })
                .catch(error => {
                    console.error('Error loading dashboard data:', error);
                    document.getElementById('no-data-msg').textContent = 'Error memuat data dashboard';
                });
        }

function prosesAntar() {
    Swal.fire({
        title: 'Konfirmasi Antar',
        text: 'Apakah Anda yakin ingin memproses semua makanan menjadi SEDANG DIANTAR?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6', // Warna biru
        cancelButtonColor: '#d33',     // Warna merah
        confirmButtonText: 'Ya, Proses!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        // Jika user klik "Ya, Proses!"
        if (result.isConfirmed) {
            
            // Opsional: Tampilkan animasi loading saat fetch berjalan
            Swal.showLoading();

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch('<?php echo base_url('dashboard/prosesAntar'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Alert Sukses
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success'
                    });
                    loadDashboardData();
                } else {
                    // Alert Gagal dari Controller
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Alert Error Sistem
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memproses antar.',
                    icon: 'error'
                });
            });
        }
    });
}

function showVerifikasi() {
    Swal.fire({
        title: 'Verifikasi Penerimaan',
        text: 'Apakah pasien sudah menerima makanannya? Klik Ya untuk memverifikasi semua pesanan menjadi SELESAI.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745', // Warna hijau
        cancelButtonColor: '#d33',     // Warna merah
        confirmButtonText: 'Ya, Selesai!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        // Logika IF yang memastikan proses hanya jalan kalau diklik "Ya"
        if (result.isConfirmed) {
            
            Swal.showLoading();

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch('<?php echo base_url('dashboard/verifyReception'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Selesai!',
                        text: data.message,
                        icon: 'success'
                    });
                    loadDashboardData();
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memverifikasi penerimaan.',
                    icon: 'error'
                });
            }); 
        }
    });
}
    </script>
<?php else: ?>
<div class="space-y-6">
    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <?php
        $stats = [
            ['title' => 'Total Pasien Dirawat', 'value' => $totalPasien, 'icon' => '<i class="fi fi-rr-hospital text-blue-600 text-xl flex items-center"></i>'],
            ['title' => 'Total Bangsal', 'value' => $totalBangsal, 'icon' => '<i class="fi fi-rr-bed-alt text-blue-600 text-xl flex items-center"></i>'],
            ['title' => 'Jenis Diet', 'value' => $totalDiet, 'icon' => '<i class="fi fi-rr-bowl-rice text-blue-600 text-xl flex items-center"></i>'],
            ['title' => 'Order Hari Ini', 'value' => $totalPasien, 'icon' => '<i class="fi fi-rr-file-invoice text-blue-600 text-xl flex items-center"></i>']
        ];
        foreach ($stats as $s): ?>
        <div class="bg-white rounded-2xl p-5 shadow-md border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500"><?= $s['title'] ?></p>
                    <p class="text-3xl font-bold text-slate-800"><?= $s['value'] ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <?= $s['icon'] ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pasien per Bangsal -->
        <div class="bg-white rounded-2xl p-5 shadow-md">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fi fi-rr-chart-histogram text-blue-600 flex items-center"></i> Pasien per Bangsal</h3>
            <div class="space-y-4">
                <?php foreach ($bangsalStats as $b): ?>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span><?= $b['icon'] ?> <?= $b['nama_bangsal'] ?></span>
                        <span><?= $b['count'] ?>/<?= $b['kapasitas'] ?></span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full" style="width: <?= $b['persen'] ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Distribusi Diet -->
        <div class="bg-white rounded-2xl p-5 shadow-md">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fi fi-rr-salad text-blue-600 flex items-center"></i> Distribusi Diet</h3>
            <div class="grid grid-cols-2 gap-3">
                <?php foreach ($dietItems as $d): ?>
                <div class="bg-slate-50 rounded-xl p-3 flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                        <?= $d['jumlah'] ?>
                    </div>
                    <span class="text-sm font-medium"><?= $d['nama'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Status Order Makanan -->
    <div class="bg-white rounded-2xl p-5 shadow-md">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fi fi-rr-clipboards text-blue-600 flex items-center"></i> Status Order Makanan <?= $jadwal_aktif ?> Hari Ini</h3>
        <div class="grid grid-cols-5 gap-3">
            <?php foreach ($orderStats as $s): ?>
            <div class="text-center p-3 rounded-xl <?= $s['count'] > 0 ? 'bg-opacity-20' : 'bg-slate-50' ?> 
                <?php
                    if ($s['status'] === '0') echo 'bg-amber-50';
                    elseif ($s['status'] === '1') echo 'bg-sky-50';
                    elseif ($s['status'] === '2') echo 'bg-green-50';
                    elseif ($s['status'] === '3') echo 'bg-blue-50';
                    else echo 'bg-red-50';
                ?>">
                <p class="text-2xl font-bold text-slate-800"><?= $s['count'] ?></p>
                <p class="text-xs font-medium text-slate-500"><?= $s['label'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>


<script>
// Pastikan fungsi bukaModalBangsal tersedia secara global untuk semua dashboard
if (typeof window.bukaModalBangsal !== 'function') {
    window.bukaModalBangsal = function(idBangsal) {
        var container = document.getElementById('modalContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'modalContainer';
            document.body.appendChild(container);
        }
        container.innerHTML = '<div class="fixed inset-0 bg-slate-900/50 z-50 flex justify-center items-center"><div class="bg-white p-4 rounded-lg">Memuat data...</div></div>';
        fetch('<?= site_url('pasien/detail_bangsal/') ?>' + encodeURIComponent(idBangsal))
            .then(function(resp) { return resp.text(); })
            .then(function(html) {
                container.innerHTML = html;

                // Parse and execute scripts from the returned HTML
                var $parsed = $('<div>').html(html);
                $parsed.find('script').each(function() {
                    var $s = $(this);
                    var src = $s.attr('src');
                    var type = $s.attr('type');

                    if (src) {
                        if (!$('script[src="' + src + '"]').length) {
                            var scriptEl = document.createElement('script');
                            if (type) scriptEl.type = type;
                            scriptEl.src = src;
                            document.body.appendChild(scriptEl);
                        }
                    } else {
                        try {
                            $.globalEval($s.html() || $s.text() || '');
                        } catch (e) {
                            console.error('Error executing inline script from modal:', e);
                        }
                    }
                });
            })
            .catch(function(err) { console.error(err); alert('Gagal memuat data bangsal.'); container.innerHTML = ''; });
    };
}
</script>

<script>

    // Fungsi cetak area spesifik
    function cetakArea(elementId) {
        const printContent = document.getElementById(elementId).innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        
        document.body.innerHTML = originalContent;
        window.location.reload(); 
    }

// Fallbacks untuk fungsi modal agar tombol tidak error jika skrip modal tidak didefinisikan
if (typeof window.tutupModalBangsal !== 'function') {
    window.tutupModalBangsal = function() { var c = document.getElementById('modalContainer'); if (c) c.innerHTML = ''; };
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
                var modal = document.getElementById('pasienModal');
                if (!modal) return;
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
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.querySelector('.bg-white').classList.remove('scale-95');
                    modal.querySelector('.bg-white').classList.add('scale-100');
                }, 10);
            })
            .catch(err => console.error('Error loading pasien for edit:', err));
    };
}
</script>





<?= $this->endSection() ?>