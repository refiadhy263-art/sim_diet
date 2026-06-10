<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            Data Log Aktivitas <span class="text-sm text-gray-500 font-normal"></span>
        </h2>
        
    </div>

    <!-- Logs List Container (Grid View) -->
    <div id="logsContainer" class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Loader / Data will be loaded here dynamically -->
        <div class="col-span-1 md:col-span-3 text-center py-12">
            <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="text-slate-500 mt-2 text-sm">Memuat data log aktivitas...</p>
        </div>
    </div>
</div>

<div class="bg-white p-4 rounded-xl shadow overflow-x-auto">
         <table id="tabelLogs" class="row-border w-full text-sm bg-white rounded-2xl shadow">
            <thead class="bg-slate-50">
                <tr class="text-left border-b">
                    <th class="p-3">Timestamp</th>
                    <th class="p-3">User</th>
                    <th class="p-3">Aksi</th>
                    <th class="p-3">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($logsList as $log): ?>
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="p-3"><?= esc($log['timestamp']) ?></td>
                        <td class="p-3"><?= esc($log['user']) ?></td>
                        <td class="p-3"><?= esc($log['action']) ?></td>
                        <td class="p-3"><?= esc($log['detail']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>

logsContainer.innerHTML = ''; // Bersihkan loader setelah data dimuat


// Helper to escape HTML and prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>
<?= $this->endSection() ?>
