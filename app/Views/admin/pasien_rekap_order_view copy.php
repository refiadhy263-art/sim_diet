<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= esc($title ?? 'Rekap Order Bangsal') ?> | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Rekap Order Bangsal</h1>
                <p class="text-sm text-gray-600">Bangsal: <strong><?= esc($bangsal['nama_bangsal'] ?? 'Tidak Diketahui') ?></strong></p>
            </div>
            <button onclick="cetakArea('print-area')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
                <i class="fi fi-rr-print"></i> Cetak Rekap
            </button>
        </div>
    </div>

    <div id="print-area" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Ringkasan Diet</h2>
                <?php if (!empty($dietBentukCount)): ?>
                    <div class="grid grid-cols-1 gap-2 text-sm text-gray-700">
                        <?php foreach ($dietBentukCount as $key => $count): ?>
                            <div class="flex justify-between border-b border-gray-100 py-2">
                                <span><?= esc($key) ?></span>
                                <span class="font-bold"><?= esc($count) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-gray-400">Belum ada data diet.</div>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Status Pesanan</h2>
                <div class="grid grid-cols-1 gap-3 text-sm text-gray-700">
                    <div class="rounded-xl border border-gray-100 p-4 bg-slate-50">
                        <div class="text-xs uppercase text-gray-500">Menunggu</div>
                        <div class="text-2xl font-bold text-amber-700"><?= $hasMenunggu ? 'Ada' : 'Tidak Ada' ?></div>
                    </div>
                    <div class="rounded-xl border border-gray-100 p-4 bg-slate-50">
                        <div class="text-xs uppercase text-gray-500">Sedang Diproses</div>
                        <div class="text-2xl font-bold text-blue-700"><?= $hasDiproses ? 'Ada' : 'Tidak Ada' ?></div>
                    </div>
                    <div class="rounded-xl border border-gray-100 p-4 bg-slate-50">
                        <div class="text-xs uppercase text-gray-500">Total Pasien</div>
                        <div class="text-2xl font-bold text-gray-900"><?= count($pasien) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 overflow-x-auto shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-gray-600">
                        <th class="p-4">No</th>
                        <th class="p-4">Bed</th>
                        <th class="p-4">Pasien</th>
                        <th class="p-4">Diet</th>
                        <th class="p-4">Bentuk</th>
                        <th class="p-4">Catatan</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pasien)): ?>
                        <?php foreach ($pasien as $index => $p): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700"><?= $index + 1 ?></td>
                                <td class="p-4 font-bold text-blue-600"><?= esc($p['nama_bed'] ?? '-') ?></td>
                                <td class="p-4">
                                    <div class="font-semibold text-gray-800"><?= esc($p['nama_pasien']) ?></div>
                                    <div class="text-xs text-gray-500"><?= esc($p['no_rm']) ?></div>
                                </td>
                                <td class="p-4"><?= esc($p['nama_jenis_diet'] ?? 'Belum Diatur') ?></td>
                                <td class="p-4"><?= esc($p['nama_bentuk_diet'] ?? 'Biasa') ?></td>
                                <td class="p-4 bg-orange-50 text-orange-700 font-medium"><?= esc($p['keterangan'] ?? '-') ?></td>
                                <td class="p-4"><span class="px-3 py-1 rounded-full <?= orderStatus($p['status_order'] ?? '0')['color'] ?> text-xs font-semibold"><?= orderStatus($p['status_order'] ?? '0')['label'] ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500">Tidak ada pasien dirawat di bangsal ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
function cetakArea(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        alert('Area cetak tidak ditemukan.');
        return;
    }

    const printWindow = window.open('', '_blank');
    if (!printWindow) {
        alert('Tidak dapat membuka jendela baru untuk mencetak.');
        return;
    }

    printWindow.document.write('<html><head><title>Cetak Rekap</title>');
    printWindow.document.write('<link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">');
    printWindow.document.write('</head><body>');
    printWindow.document.write(element.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}
</script>
<?= $this->endSection() ?>
