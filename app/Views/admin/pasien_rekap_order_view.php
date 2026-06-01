<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= esc($title ?? 'Rekap Order Bangsal') ?> | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
       <div class="flex justify-between items-center mb-6 no-print">
              <h2 class="text-xl font-bold"><i class="fi fi-rr-hospital mr-2"></i> <?= esc($bangsal['nama_bangsal'] ?? 'Bangsal') ?> - Daftar Distribusi</h2>
              <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-md"><i class="fi fi-rr-print mr-2"></i> Cetak</button>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
            <h4 class="font-bold text-xs uppercase text-gray-600 mb-3 tracking-widest"><i class="fi fi-rr-chart-histogram mr-2"></i> Rekap Produksi Diet</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-700">
                <?php if (!empty($dietRekap)): ?>
                    <?php foreach ($dietRekap as $key => $value): ?>
                        <div class="flex justify-between border-b border-gray-100 py-2">
                            <span><?= esc($key) ?></span>
                            <span class="font-bold text-blue-600 bg-blue-200 px-2 py-1 rounded"><?= esc($value) ?> Porsi</span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-gray-400 italic">Belum ada data diet.</div>
                <?php endif; ?>
            </div>
            <div class="mt-4 font-black text-right text-lg text-gray-800 border-t pt-2">
                Total Produksi: <?= count($patients) ?> Porsi
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600">
                        <th class="p-3">Bed</th>
                        <th class="p-3">Pasien</th>
                        <th class="p-3">Diet</th>
                        <th class="p-3">Bentuk</th>
                        <th class="p-3 text-orange-700 bg-orange-50">⚠️ Catatan Khusus</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center no-print">Label</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if (!empty($patients)): ?>
                        <?php foreach ($patients as $p): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 font-black text-blue-600"><?= esc($p['nama_bed'] ?? '-') ?></td>
                                <td class="p-3">
                                    <strong><?= esc($p['nama_pasien']) ?></strong><br>
                                    <span class="text-[10px] text-gray-400">RM: <?= esc($p['no_rm']) ?></span>
                                </td>
                                <td class="p-3"><?= esc($p['nama_jenis_diet'] ?? 'Biasa') ?></td>
                                <td class="p-3"><?= esc($p['nama_bentuk_diet'] ?? 'Biasa') ?></td>
                                <td class="p-3 bg-orange-50 text-red-600 font-bold italic text-xs"><?= esc($p['keterangan'] ?? '-') ?></td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded font-bold text-xs uppercase bg-gray-200">
                                        <?= esc(orderStatus($p['status_order'] ?? '0')['label']) ?>
                                    </span>
                                </td>
                                <td class="p-3 text-center no-print">
                                    <button onclick="printLabel('<?= esc($p['id_pasien']) ?>')" class="text-xs bg-gray-100 px-3 py-1 rounded-lg font-bold hover:bg-gray-200 transition-all">
                                        🖨️ Cetak
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="p-5 text-center text-gray-500">Tidak ada pasien dirawat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
function printLabel(idPasien) {
    if (!idPasien) return;
    window.open('<?= base_url('pasien/print_label/') ?>' + idPasien, '_blank');
}
</script>
<?= $this->endSection() ?>