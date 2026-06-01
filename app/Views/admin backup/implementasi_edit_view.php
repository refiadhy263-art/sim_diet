<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Edit Implementasi | SIMAS</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="col-md-12">
    <div class="card my-4">
        <div class="card-header bg-white">
            <h3> Edit Data</h3>
        </div>
        <div class="card-body">
            <form action="<?= base_url('implementasi/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <input type="hidden" name="id_implementasi" value="<?= $implementasi['id_implementasi'] ?>">
                <input type="hidden" name="berkas_implementasiLama" value="<?= $implementasi['berkas_implementasi'] ?>">
                <input type="hidden" name="id_semester_lama" value="<?= $implementasi['id_semester'] ?>">
                <input type="hidden" name="id_user_implementasi" class="form-control" value="<?php echo session()->get('id_user');
                                                                                                ?>">
                <div class="form-group row my-4">

                    <label for="semester" class="col-sm-2 col-form-label">Tahun Akademik</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="id_semester" value="<?php echo $implementasi['nama_semester']
                                                                                            ?>" disabled>
                    </div>
                </div>

                <div class="form-group row my-4">

                    <label for="prodi" class="col-sm-2 col-form-label">Program Studi</label>
                    <div class="col-sm-10">
                        <select name="id_prodi" class="form-control select2">
                            <option value="<?= $implementasi['id_prodi'] ?>"><?= $implementasi['nama_program_studi'] ?></option>
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
                        <select name="id_kerjasama" class="form-control select2" required>
                            <option value="<?= $implementasi['id_kerjasama'] ?>"><?= $implementasi['judul_kerjasama'] ?></option>
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

                <div class="form-group row my-4">
                    <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                    <div class="col-sm-10">
                        <input type="text" name="judul_implementasi" class="form-control" required value="<?= $implementasi['judul_implementasi'] ?>">
                    </div>
                </div>

                <div class="form-group row my-4">
                    <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea name="deskripsi_implementasi" class="form-control" cols="30" rows="5"><?= $implementasi['deskripsi_implementasi'] ?> </textarea>
                    </div>
                </div>

                <div class="form-group row my-4">
                    <label for="gambar" class="col-sm-2 col-form-label">Upload File</label>
                    <div class="col-sm-10">
                        <input type="file" name="berkas_implementasi" id="berkas_implementasi" class="form-control" value="<?= $implementasi['berkas_implementasi'] ?>"><small class="text-secondary"><?= $implementasi['berkas_implementasi'] ?></small>
                        <p class="text-danger mt-2"><strong>format file harus pdf dan maksimal 5 MB.</strong></p>
                    </div>
                </div>


                <div class="form-group my-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>