<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Tambah Data | SIMAS</title>
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
        <div class="card-body">
            <form action="<?= base_url('kerjasama/create') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <input type="hidden" name="id_user" value="<?php echo session()->get('id_user'); ?>">
                <input type="hidden" name="status" value="1">

                <div class="form-group row my-2">

                    <label for="exampleInputPassword1" class="col-sm-2 col-form-label">Jenis</label> <br>
                    <div class="col-sm-10">
                        <input type="radio" class="form-group mr-1" name="id_jenis" value="1" checked>
                        <label class="form-check-label" for="exampleCheck1">MOU</label><br>
                        <input type="radio" class="form-group mr-1" name="id_jenis" value="2">
                        <label class="form-check-label" for="exampleCheck1">MOA</label>
                    </div>
                </div>
                <div class="form-group row my-2">

                    <label for="tingkat" class="col-sm-2 col-form-label">Tingkat</label>
                    <div class="col-sm-10">
                        <select name="id_tingkat" class="select2" multiple="multiple" data-placeholder="Silahkan Cari" style="width: 100%;" required>
                            <!-- <option value="">Pilih ..</option> -->
                            <?php foreach ($tingkat as $tampil) :
                            ?>

                                <option value=" <?php echo $tampil['id_tingkat']
                                                ?>"><?php echo $tampil['nama_tingkat']
                                                    ?></option>
                            <?php endforeach;
                            ?>


                        </select>
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                    <div class="col-sm-10">
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="nomor" class="col-sm-2 col-form-label">Nomor</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="nomor" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="tgl_awal" class="col-sm-2 col-form-label">Tgl_Awal</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="tgl_awal" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="tgl_akhir" class="col-sm-2 col-form-label">Tgl_Akhir</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" name="tgl_akhir" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea name="deskripsi" class="form-control" cols="30" rows="5"></textarea>
                    </div>
                </div>
                <div>
                    <h5 class="text-primary">Pihak 1</h5>

                </div>
                <div class="form-group row my-2">
                    <label for="instansi_pihak1" class="col-sm-2 col-form-label">Instansi Pihak 1</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="instansi_pihak1" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="alamat_pihak1" class="col-sm-2 col-form-label">Alamat Pihak 1</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="alamat_pihak1" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="pj_pihak1" class="col-sm-2 col-form-label">PJ Pihak 1</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pj_pihak1" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="jabatan_pihak1" class="col-sm-2 col-form-label">Jabatan PJ Pihak 1</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="jabatan_pihak1" value="">
                    </div>
                </div>
                <div>
                    <h5 class="text-primary">Pihak 2</h5>

                </div>
                <div class="form-group row my-2">
                    <label for="instansi_pihak2" class="col-sm-2 col-form-label">Instansi Pihak 2</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="instansi_pihak2" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="alamat_pihak2" class="col-sm-2 col-form-label">Alamat Pihak 2</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="alamat_pihak2" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="pj_pihak2" class="col-sm-2 col-form-label">PJ Pihak 2</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="pj_pihak2" value="">
                    </div>
                </div>
                <div class="form-group row my-2">
                    <label for="jabatan_pihak2" class="col-sm-2 col-form-label">Jabatan PJ Pihak 2</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="jabatan_pihak2" value="">
                    </div>
                </div>

                <div class="form-group row my-2">
                    <label for="gambar" class="col-sm-2 col-form-label">Upload File</label>
                    <div class="col-sm-10">
                        <input type="file" name="berkas" id="berkas" class="form-control">
                    </div>
                </div>
                <!--<div class="form-group row my-2">
                    <label for="tahun">Tahun</label>
                    <input type="text" class="form-control" name="tahun" value="">
                </div>-->

                <div class="form-group my-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>