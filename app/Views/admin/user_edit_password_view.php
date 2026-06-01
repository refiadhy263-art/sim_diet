<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Edit Password | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Page -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fi fi-rr-lock text-blue-600 flex items-center"></i> Edit Password Pengguna
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
        <form method="post" action="<?= base_url('user/update_password'); ?>" class="space-y-5">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Username</label>
                <input type="text" disabled class="w-full border border-gray-100 rounded-xl p-3 bg-gray-50 text-gray-400 outline-none cursor-not-allowed" value="<?= esc($user['username']) ?>">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" disabled class="w-full border border-gray-100 rounded-xl p-3 bg-gray-50 text-gray-400 outline-none cursor-not-allowed" value="<?= esc($user['nama_user']) ?>">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Kategori Pengguna</label>
                <input type="text" disabled class="w-full border border-gray-100 rounded-xl p-3 bg-gray-50 text-gray-400 outline-none cursor-not-allowed" value="<?= esc($users['nama_kategori_user']) ?>">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Password Baru</label>
                <input type="password" name="password" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Masukkan password baru">
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="<?= base_url('user') ?>" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition shadow-md shadow-blue-200 flex items-center gap-1.5">
                    <i class="fi fi-rr-disk"></i> Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>