<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Pengguna | SIMDIET</title>
<link href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fi fi-rr-users text-blue-600 flex items-center"></i> Data Pengguna
        </h2>
        <div class="flex gap-2">
            <a href="<?= base_url('user/import') ?>" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold shadow-md transition flex items-center gap-1.5 text-sm">
                <i class="fi fi-rr-file-import flex items-center"></i> Import
            </a>
            <a href="<?= base_url('user/new') ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-md transition flex items-center gap-1.5 text-sm">
                <i class="fi fi-rr-user-add flex items-center"></i> Tambah
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">Sukses!</span> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white p-4 rounded-xl shadow flex flex-wrap gap-3 items-end mb-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Cari Pengguna (Nama/Username/Kategori)</label>
            <input type="text" id="searchUserInput" class="w-full border border-gray-200 rounded-lg p-2 text-gray-700 focus:border-blue-500 focus:ring-blue-500 focus:ring-1" placeholder="Cari pengguna...">
        </div>
        <div>
            <button id="resetUserFilterBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-200 text-gray-700 font-medium transition">
                Reset
            </button>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-2xl shadow-md border border-slate-100 p-4">
        <table id="dataTable" class="w-full text-sm">
            <thead class="bg-slate-50/75 border-b border-slate-100">
                <tr>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider" width="60">No</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama User</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori User</th>
                    <th class="p-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-500">
                <?php $i = 1 ?>
                <?php foreach ($user as $tampil) : ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 font-mono text-xs text-slate-500 align-middle"><?= $i++; ?></td>
                        <td class="p-4 font-mono text-xs text-slate-700 align-middle font-semibold"><?= esc($tampil['username']) ?></td>
                        <td class="p-4 font-bold text-slate-800 align-middle"><?= esc($tampil['nama_user']) ?></td>
                        <td class="p-4 align-middle">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-full text-[10px] font-bold uppercase whitespace-nowrap">
                                <?= esc($tampil['nama_kategori_user']) ?>
                            </span>
                        </td>
                        <td class="p-4 text-center align-middle whitespace-nowrap">
                            <div class="flex justify-center gap-2">
                                <a href="<?= base_url('user/' . $tampil['id_user'] . '/edit_password') ?>" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-100 transition-colors inline-flex items-center gap-1" title="Password">
                                    <i class="fi fi-rr-lock"></i> Pass
                                </a>
                                <a href="<?= base_url('user/' . $tampil['id_user'] . '/edit') ?>" class="text-xs bg-amber-50 text-amber-600 px-3 py-1.5 rounded-lg font-bold hover:bg-amber-100 transition-colors inline-flex items-center gap-1" title="Edit">
                                    <i class="fi fi-rr-edit"></i> Edit
                                </a>
                                <a href="#" data-href="<?= base_url('user/' . $tampil['id_user'] . '/delete') ?>" onclick="confirmToDelete(this)" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold hover:bg-red-100 transition-colors inline-flex items-center gap-1" title="Hapus">
                                    <i class="fi fi-rr-trash"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let tableUser;
    document.addEventListener('DOMContentLoaded', function() {
        tableUser = $('#dataTable').DataTable({
            dom: 'rt<"flex justify-between items-center p-4 border-t border-slate-100 bg-slate-50/50"<"text-sm text-slate-500"i><"text-sm"p>>',
            responsive: true,
            language: {
                emptyTable: "Tidak ada data pengguna ditemukan.",
                zeroRecords: "Pencarian tidak menemukan hasil.",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pengguna",
                infoEmpty: "Menampilkan 0 pengguna",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Selanjutnya"
                }
            }
        });

        $('#searchUserInput').on('keyup', function() {
            tableUser.search(this.value).draw();
        });

        $('#resetUserFilterBtn').on('click', function() {
            $('#searchUserInput').val('');
            tableUser.search('').draw();
        });
    });

    function confirmToDelete(el) {
        const url = el.dataset.href;
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pengguna ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
<?= $this->endSection() ?>