<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Tambah Kerjasama | SIMAS</title>
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
    <form action="<?= base_url('kerjasama/create') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <div class="card my-4 card-responsive">

            <div class="card-header bg-white">
                <h3> Tambah Data</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6">

                        <input type="hidden" name="id_user_kerjasama" value="<?php echo session()->get('id_user'); ?>">
                        <input type="hidden" name="status" value="1">

                        <div class="form-group row my-3">

                            <label for="exampleInputPassword1" class="col-sm-4 col-form-label">Jenis</label> <br>
                            <div class="col-sm-8">
                                <input type="radio" class="form-group mr-1" name="id_jenis" value="1" checked>
                                <label class="form-check-label" for="exampleCheck1">MOU</label><br>
                                <input type="radio" class="form-group mr-1" name="id_jenis" value="2">
                                <label class="form-check-label" for="exampleCheck1">MOA</label>
                            </div>
                        </div>
                        <div class="form-group row my-3">

                            <label for="tingkat" class="col-sm-4 col-form-label">Tingkat</label>
                            <div class="col-sm-12 col-md-8 col-lg-6">
                                <select name="id_tingkat" class="form-control select2" data-placeholder="Silahkan Cari" required style="width: 100%;">
                                    <!-- <option value="">Pilih ..</option> -->
                                    <?php foreach ($tingkat as $tampil) :
                                    ?>

                                        <option value="<?php echo $tampil['id_tingkat']
                                                        ?>"><?php echo $tampil['nama_tingkat']
                                                            ?></option>
                                    <?php endforeach;
                                    ?>


                                </select>
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="tgl_awal" class="col-sm-4 col-form-label">Tanggal Awal</label>
                            <div class="col-sm-12 col-md-8 col-lg-6">
                                <input type="date" class="form-control" name="tgl_awal" value="">
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="tgl_akhir" class="col-sm-4 col-form-label">Tanggal Akhir</label>
                            <div class="col-sm-12 col-md-8 col-lg-6">
                                <input type="date" class="form-control" name="tgl_akhir" value="">
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <div class="form-group row my-3">
                            <label for="nomor" class="col-sm-4 col-form-label">Nomor</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="nomor" value="">
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="judul_kerjasama" class="col-sm-4 col-form-label">Judul</label>
                            <div class="col-sm-8">
                                <input type="text" name="judul_kerjasama" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row my-3">
                            <label for="deskripsi" class="col-sm-4 col-form-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea name="deskripsi_kerjasama" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="form-group row my-3">
                            <label for="gambar" class="col-sm-4 col-form-label">Upload File</label>
                            <div class="col-sm-8">
                                <input type="file" name="berkas_kerjasama" id="berkas" class="form-control">
                                <p class="text-danger mt-2"><strong>format file pdf atau docx dan maksimal 5 MB.</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info">
                        <h5 class="text-white">Pihak 1</h5>

                    </div>
                    <div class="card-body">

                        <div class="form-group row my-3">
                            <label for="instansi_pihak1" class="col-sm-4 col-form-label">Instansi Pihak 1</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="instansi_pihak1" value="">
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="alamat_pihak1" class="col-sm-4 col-form-label">Alamat Pihak 1</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alamat_pihak1" value="">
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="pj_pihak1" class="col-sm-4 col-form-label">PJ Pihak 1</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="pj_pihak1" value="">
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="jabatan_pihak1" class="col-sm-4 col-form-label">Jabatan PJ Pihak 1</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="jabatan_pihak1" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success">

                        <h5 class="text-white">Pihak 2</h5>

                    </div>
                    <div class="card-body">

                        <div class="form-group row my-3">
                            <label for="instansi_pihak2" class="col-sm-4 col-form-label">Instansi Pihak 2</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="instansi_pihak2" value="">
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="alamat_pihak2" class="col-sm-4 col-form-label">Alamat Pihak 2</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alamat_pihak2" value="">
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="pj_pihak2" class="col-sm-4 col-form-label">PJ Pihak 2</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="pj_pihak2" value="">
                            </div>
                        </div>
                        <div class="form-group row my-3">
                            <label for="jabatan_pihak2" class="col-sm-4 col-form-label">Jabatan PJ Pihak 2</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="jabatan_pihak2" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group my-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>

</div>

<?= $this->endSection() ?>