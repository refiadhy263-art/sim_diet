<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Tambah Pengguna | SIMAS</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="col-md-12">
    <div class="card card-primary my-4">
        <div class="card-header">
            <h3 class="card-title">Tambah Pengguna</h3>
        </div>


        <form method="post" action="<?= base_url('user/create'); ?>" class="form-horizontal">
            <?= csrf_field(); ?>

            <div class="card-body">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="username" id="inputEmail3" placeholder="Username">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nama_user" id="inputEmail3" placeholder="Nama">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <?php foreach ($users as $tampil) : ?>
                            <div class="form-check">
                                <input type="radio" name="id_kategori_user" class="form-check-input" id="exampleCheck2" value="<?= $tampil['id_kategori_user'] ?> " checked>
                                <label class="form-check-label" for="exampleCheck2"><?= $tampil['nama_kategori_user'] ?></label>
                            </div>

                        <?php endforeach; ?>
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