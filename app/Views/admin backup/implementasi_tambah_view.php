<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Tambah Implementasi | SIMAS</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')) {
?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php
}
?>
<div class="col-md-12">
    <div class="card my-4">
        <div class="card-header bg-white">
            <h3> Tambah Data</h3>
        </div>
        <form action="<?= base_url('implementasi/create') ?>" method="post" enctype="multipart/form-data">
            <div class="card-body">

                <?= csrf_field(); ?>

                <input type="hidden" name="id_user_implementasi" class="form-control" value="<?php echo session()->get('id_user');
                                                                                                ?>">
                <div class="form-group row my-4">

                    <label for="semester" class="col-sm-2 col-form-label">Tahun Akademik</label>
                    <div class="col-sm-10">
                        <select name="id_semester" class="form-control select2" style="width: 100%;">
                            <!--<option value="">Pilih ..</option>-->
                            <?php foreach ($semester as $tampil) :
                            ?>

                                <option value="<?php echo $tampil['id_semester']
                                                ?>"><?php echo $tampil['nama_semester']
                                                    ?></option>
                            <?php endforeach;
                            ?>


                        </select>
                    </div>
                </div>


                <div class="form-group row my-4">

                    <label for="prodi" class="col-sm-2 col-form-label">Program Studi</label>
                    <div class="col-sm-10">
                        <select name="id_prodi" class="form-control select2" required style="width: 100%;">
                            <option value="">Silahkan Pilih</option>
                            <?php foreach ($prodi as $tampil) :
                            ?>

                                <option value="<?php echo $tampil['id_prodi']
                                                ?>"><?php echo $tampil['nama_program_studi']
                                                    ?></option>
                            <?php endforeach;
                            ?>


                        </select>
                    </div>
                </div>

                <div class="form-group row my-4">

                    <label for="prodi" class="col-sm-2 col-form-label">Kerjasama</label>
                    <div class="col-sm-10">
                        <select name="id_kerjasama" class="form-control select2" required style="width: 100%;">
                            <option value="">Silahkan Cari</option>
                            <?php foreach ($kerjasama as $tampil) :
                            ?>

                                <option value="<?php echo $tampil['id_kerjasama']
                                                ?>"><?php echo $tampil['judul_kerjasama']
                                                    ?></option>
                            <?php endforeach;
                            ?>


                        </select>
                    </div>
                </div>

                <!--<div class="form-group row my-4">
                    <label for="id_kerjasama" class="col-sm-2 col-form-label">Kerjasama</label>
                    <div class="col-sm-10">
                        <input type="text" name="judul_kerjasama" class="form-control" required>
                    </div>
                </div>-->
                <div class="form-group row my-4">
                    <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                    <div class="col-sm-10">
                        <input type="text" name="judul_implementasi" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row my-4">
                    <label for="deskripsi_implementasi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea name="deskripsi_implementasi" class="form-control" cols="30" rows="5"></textarea>
                    </div>
                </div>

                <div class="form-group row my-4">
                    <label for="gambar" class="col-sm-2 col-form-label">Upload File</label>
                    <div class="col-sm-10">
                        <input type="file" name="berkas_implementasi" id="berkas" class="form-control">
                        <p class="text-danger mt-2"><strong>format file harus pdf dan maksimal 5 MB.</strong></p>
                    </div>
                </div>

                <div class="form-group my-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>


            </div>

        </form>
    </div>
</div>

<?= $this->endSection() ?>