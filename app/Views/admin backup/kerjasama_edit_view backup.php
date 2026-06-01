<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Tambah Data | SIMAS</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="col-md-12">
    <div class="card my-4">
        <div class="card-header bg-white">
            <h3> Edit Data</h3>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <input type="hidden" name="id_kerjasama" value="<?= $kerjasama['id_kerjasama'] ?>">
                <input type="hidden" name="id_user" value="<?php echo session()->get('id_user'); ?>">
                <input type="hidden" name="berkasLama" value="<?= $kerjasama['berkas'] ?>">

                <div class="form-group my-4">
                    <label for="exampleInputPassword1">Jenis</label> <br>
                    <input type="radio" class="form-group mr-1" name="id_jenis" value="1" <?php if ($kerjasama['id_jenis'] == '1') {
                                                                                                echo 'checked';
                                                                                            } ?>>
                    <label class="form-check-label" for="exampleCheck1">MOU</label><br>
                    <input type="radio" class="form-group mr-1" name="id_jenis" value="2" <?php if ($kerjasama['id_jenis'] == '2') {
                                                                                                echo 'checked';
                                                                                            } ?>>
                    <label class="form-check-label" for="exampleCheck1">MOA</label>
                </div>

                <div class="form-group my-4">
                    <label for="exampleInputPassword1">Status</label> <br>
                    <input type="radio" class="form-group mr-1" name="status" value="1" <?php if ($kerjasama['status'] == '1') {
                                                                                            echo 'checked';
                                                                                        } ?>>
                    <label class="form-check-label" for="exampleCheck1">Aktif</label><br>
                    <input type="radio" class="form-group mr-1" name="status" value="2" <?php if ($kerjasama['status'] == '0') {
                                                                                            echo 'checked';
                                                                                        } ?>>
                    <label class="form-check-label" for="exampleCheck1">Non Aktif</label>
                </div>
                <div class="form-group my-4">

                    <label for="tingkat">Tingkat</label>
                    <select name="id_tingkat" class="custom-select" id="inputGroupSelect02">
                        <option value="<?= $kerjasama['id_tingkat'] ?>"><?= $kerjasama['nama_tingkat'] ?></option>
                        <?php foreach ($tingkat as $tampil) :
                        ?>

                            <option value="<?php echo $tampil['id_tingkat']
                                            ?>"><?php echo $tampil['nama_tingkat']
                                                ?></option>
                        <?php endforeach;
                        ?>


                    </select>
                </div>

                <div class="form-group my-4">
                    <label for="judul">Judul</label>
                    <input type="text" name="judul" class="form-control" required value="<?= $kerjasama['judul'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="nomor">Nomor</label>
                    <input type="text" class="form-control" name="nomor" value="<?= $kerjasama['nomor'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="tgl_awal">Tgl_Awal</label>
                    <input type="date" class="form-control" name="tgl_awal" value="<?= $kerjasama['tgl_awal'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="tgl_akhir">Tgl_Akhir</label>
                    <input type="date" class="form-control" name="tgl_akhir" value="<?= $kerjasama['tgl_akhir'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" cols="30" rows="5"><?= $kerjasama['deskripsi'] ?></textarea>
                </div>
                <div>
                    <h5 class="text-primary">Pihak 1</h5>

                </div>
                <div class="form-group my-4">
                    <label for="instansi_pihak1">Instansi Pihak 1</label>
                    <input type="text" class="form-control" name="instansi_pihak1" value="<?= $kerjasama['instansi_pihak1'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="alamat_pihak1">Alamat Pihak 1</label>
                    <input type="text" class="form-control" name="alamat_pihak1" value="<?= $kerjasama['alamat_pihak1'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="pj_pihak1">PJ Pihak 1</label>
                    <input type="text" class="form-control" name="pj_pihak1" value="<?= $kerjasama['pj_pihak1'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="jabatan_pihak1">Jabatan PJ Pihak 1</label>
                    <input type="text" class="form-control" name="jabatan_pihak1" value="<?= $kerjasama['jabatan_pihak1'] ?>">
                </div>
                <div>
                    <h5 class="text-primary">Pihak 2</h5>

                </div>
                <div class="form-group my-4">
                    <label for="instansi_pihak2">Instansi Pihak 2</label>
                    <input type="text" class="form-control" name="instansi_pihak2" value="<?= $kerjasama['instansi_pihak2'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="alamat_pihak2">Alamat Pihak 2</label>
                    <input type="text" class="form-control" name="alamat_pihak2" value="<?= $kerjasama['alamat_pihak2'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="pj_pihak2">PJ Pihak 2</label>
                    <input type="text" class="form-control" name="pj_pihak2" value="<?= $kerjasama['pj_pihak2'] ?>">
                </div>
                <div class="form-group my-4">
                    <label for="jabatan_pihak2">Jabatan PJ Pihak 2</label>
                    <input type="text" class="form-control" name="jabatan_pihak2" value="<?= $kerjasama['jabatan_pihak2'] ?>">
                </div>

                <div class="form-group my-4">
                    <label for="gambar">Upload File</label>
                    <input type="file" name="berkas" id="berkas" class="form-control"><small class="text-muted"><?= $kerjasama['berkas'] ?></small>
                </div>
                <!--<div class="form-group my-4">
                    <label for="tahun">Tahun</label>
                    <input type="text" class="form-control" name="tahun" value="">
                </div>-->

                <div class="form-group my-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>