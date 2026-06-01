<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Edit Password | SIMAS</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="col-md-12">
    <div class="card card-primary my-4">
        <div class="card-header">
            <h3 class="card-title">Edit Password</h3>
        </div>


        <form method="post" action="<?= base_url('user/update_password'); ?>" class="form-horizontal">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
            <div class="card-body">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="username" id="inputEmail3" placeholder="Username" value="<?= $user['username'] ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nama_user" id="inputEmail3" placeholder="Nama" value="<?= $user['nama_user'] ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nama_user" id="inputEmail3" placeholder="Nama" value="<?= $users['nama_kategori_user'] ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password" required>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>

        </form>
    </div>

</div>


<?= $this->endSection() ?>