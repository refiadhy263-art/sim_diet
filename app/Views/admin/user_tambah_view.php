<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Tambah Pengguna | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Page -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fi fi-rr-user-add text-blue-600 flex items-center"></i> Tambah Pengguna
        </h2>
        <a href="<?= base_url('user') ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition inline-flex items-center gap-1.5 text-sm">
            <i class="fi fi-rr-arrow-left flex items-center"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <span class="font-medium">Error!</span> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
        <form method="post" action="<?= base_url('user/create'); ?>" class="space-y-5">
            <?= csrf_field(); ?>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Username</label>
                <input type="text" name="username" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Masukkan username">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Masukkan password">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_user" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Masukkan nama lengkap">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Kategori Pengguna</label>
                <div class="grid grid-cols-2 gap-3">
                    <?php foreach ($users as $tampil) : ?>
                        <label class="flex items-center gap-2 cursor-pointer bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 transition">
                            <input type="radio" name="id_kategori_user" value="<?= $tampil['id_kategori_user'] ?>" class="text-blue-600 focus:ring-blue-500" checked>
                            <span class="text-sm font-semibold text-gray-700"><?= esc($tampil['nama_kategori_user']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="<?= base_url('user') ?>" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition shadow-md shadow-blue-200 flex items-center gap-1.5">
                    <i class="fi fi-rr-disk"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>