<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET</title>
<link href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
    $session = session();
    $role_aktif = $session->get('role');
    
    $request = \Config\Services::request();
    $filter_user    = $request->getGet('id_user') ?? '';
    $filter_role    = $request->getGet('id_role') ?? '';
    $filter_bangsal = $request->getGet('id_bangsal') ?? '';
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fi fi-rr-time-past text-blue-600"></i> Data Log Aktivitas 
            <span class="text-sm text-gray-500 font-normal">
                <?= ($role_aktif == '1') ? '(Semua Pengguna)' : '('.session()->get('username').')' ?>
            </span>
        </h2>
    </div>

    <?php if ($role_aktif == '1'): ?>
    <form method="GET" action="" class="flex flex-wrap items-center gap-2 w-full">
        
        <select id="selectRole" name="id_role" class="bg-white border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 outline-none">
            <option value="">-- Role --</option>
            <?php if(isset($listRoles)): foreach($listRoles as $r => $value): ?>
                <option value="<?= $r ?>" <?= ($filter_role == $r) ? 'selected' : '' ?>>
                    <?= esc($value) ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <div id="wrapBangsal" class="hidden">
            <select id="selectBangsal" name="id_bangsal" class="bg-white border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 outline-none">
                <option value="">-- Bangsal --</option>
                <?php if(isset($bangsal)): foreach($bangsal as $b): ?>
                    <option value="<?= $b['id_bangsal'] ?>" <?= ($filter_bangsal == $b['id_bangsal']) ? 'selected' : '' ?>>
                        <?= esc($b['nama_bangsal']) ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </div>

        <select id="selectUser" name="id_user" class="bg-white border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 outline-none min-w-[150px]">
            <option value="">-- User --</option>
            </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors flex items-center gap-2">
            <i class="fi fi-rr-filter"></i> Filter
        </button>
        
        <?php if(!empty($filter_user) || !empty($filter_role) || !empty($filter_bangsal)): ?>
            <a href="<?= current_url() ?>" class="bg-gray-100 text-gray-600 px-3 py-2 rounded-lg text-sm font-bold hover:bg-gray-200 transition-colors">
                Reset
            </a>
        <?php endif; ?>
    </form>
    <?php endif; ?>

    <div class="bg-white p-4 rounded-xl shadow overflow-x-auto">
        <table id="tabelLogs" class="w-full text-sm bg-white rounded-2xl">
            <thead class="bg-slate-50">
                <tr class="text-left border-b">
                    <th class="p-3">Waktu (Timestamp)</th>
                    <?php if ($role_aktif == '1'): ?>
                        <th class="p-3">User</th>
                    <?php endif; ?>
                    <th class="p-3">Aksi</th>
                    <th class="p-3">Detail</th>
                </tr>
            </thead>
         <tbody>
                <?php if(!empty($logsList)): ?>
                    <?php foreach($logsList as $log): ?>
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="p-3 font-mono text-xs text-gray-600"><?= esc($log['timestamp']) ?></td>
                            
                            <?php if ($role_aktif == '1'): ?>
                                <td class="p-3 font-bold text-gray-800"><?= esc($log['user'] ?? 'Sistem') ?></td>
                            <?php endif; ?>
                            
                            <td class="p-3 font-medium text-blue-600"><?= esc($log['action']) ?></td>
                            <td class="p-3 text-gray-600"><?= esc($log['detail']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>

<script>
$(document).ready(function() {
    $('#tabelLogs').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        language: {
            search: "Cari log:",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ log",
            infoEmpty: "Tidak ada log tersedia",
            zeroRecords: "Data pencarian tidak ditemukan"
        }
    });

    <?php if ($role_aktif == '1'): ?>
    // 1. Simpan seluruh data user dari PHP ke variabel JavaScript
    const allUsers = <?= json_encode($listUsers ?? []) ?>;
    
    // Variabel filter aktif (dari URL saat halaman di-refresh)
    const activeUserId = '<?= $filter_user ?>';

    // 2. Fungsi untuk memfilter Combobox
    function updateDropdowns() {
        let roleId = $('#selectRole').val();
        let bangsalId = $('#selectBangsal').val();

        // LOGIKA BANGSAL: Tampilkan combobox bangsal HANYA jika role adalah Perawat (3) atau Pramusaji (4)
        if (roleId === '2' || roleId === '4') {
            $('#wrapBangsal').removeClass('hidden');
        } else {
            $('#wrapBangsal').addClass('hidden');
            $('#selectBangsal').val(''); // Reset bangsal
            bangsalId = ''; // Kosongkan nilai pencarian bangsal
        }

        // LOGIKA USER: Kosongkan isi select user saat ini, lalu isi ulang
        let userSelect = $('#selectUser');
        userSelect.empty();
        userSelect.append('<option value="">-- User --</option>');

        // Lakukan looping pada semua user
        allUsers.forEach(function(user) {
            // Cek apakah user ini sesuai dengan filter role dan bangsal yang dipilih
            // (Sesuaikan user.id_role dan user.id_bangsal dengan nama kolom di database Anda)
            let matchRole = (roleId === '' || user.id_role == roleId || user.role == roleId);
            let matchBangsal = (bangsalId === '' || user.id_bangsal == bangsalId);

            if (matchRole && matchBangsal) {
                // Berikan atribut 'selected' jika ID user sama dengan filter dari URL
                let isSelected = (user.id_users == activeUserId) ? 'selected' : '';
                userSelect.append(`<option value="${user.id_users}" ${isSelected}>${user.username}</option>`);
            }
        });
    }

    // 3. Jalankan fungsi setiap kali Role atau Bangsal diubah
    $('#selectRole, #selectBangsal').on('change', function() {
        updateDropdowns();
    });

    // 4. Jalankan fungsi sekali saat halaman pertama kali dimuat
    updateDropdowns();
    
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>