<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Import Pengguna | SIMDIET</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header Page -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fi fi-rr-file-import text-blue-600 flex items-center"></i> Import Pengguna Excel
        </h2>
        <a href="<?= base_url('user') ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition inline-flex items-center gap-1.5 text-sm">
            <i class="fi fi-rr-arrow-left flex items-center"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6">
        <form method="post" action="<?= base_url('user/import') ?>" enctype="multipart/form-data" class="space-y-5">
            <?= csrf_field(); ?>

            <div class="p-4 bg-slate-50 rounded-xl border border-dashed border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 text-left">
                    <div class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-lg">
                        <i class="fi fi-rr-file-excel flex items-center"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Format Template Excel</h4>
                        <p class="text-xs text-slate-500">Unduh format Excel sebelum melakukan import data.</p>
                    </div>
                </div>
                <a href="<?= base_url('user/download') ?>" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold shadow-md transition flex items-center gap-1.5 text-xs whitespace-nowrap">
                    <i class="fi fi-rr-download flex items-center"></i> Download Format
                </a>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Pilih File Excel <span class="text-xs text-gray-400 font-normal">(.xls, .xlsx)</span></label>
                <input type="file" name="fileexcel" required class="w-full border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-slate-50/50 cursor-pointer">
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="<?= base_url('user') ?>" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition shadow-md shadow-blue-200 flex items-center gap-1.5">
                    <i class="fi fi-rr-upload flex items-center"></i> Upload & Import
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>