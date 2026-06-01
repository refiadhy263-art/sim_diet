<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title> Upload Pengguna | SIMAS</title>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="my-3">
    <h3 class="m-1 font-weight-bold text-primary">Import Pengguna</h3>
</div>




<form method="post" action="<?= base_url('user/import') ?>" enctype="multipart/form-data">
    <?= csrf_field(); ?>
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label>File Excel</label>
                <span>
                    <i><strong>(.xls, .xlsx).</strong></i>
                </span><span class="mx-2"><a class="btn btn-sm btn-success mb-2" href="<?= base_url('user/download') ?>"><i class="fas fa-file-excel mr-2"></i>Format</a>
                </span>
                <input type="file" name="fileexcel" class="form-control mb-2" required></p>

            </div>
            <div class="form-group">
                <button class="btn btn-sm btn-primary" type="submit"><i class="fas fa-upload mr-2"></i>Upload</button>
            </div>
        </div>
    </div>
</form>









<?= $this->endSection() ?>