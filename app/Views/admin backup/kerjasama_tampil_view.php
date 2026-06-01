<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Kerjasama | SIMAS</title>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="my-2">
    <h3 class="m-1 font-weight-bold text-primary">Data Kerjasama</h3>
</div>
<?php



if (session()->getFlashdata('success')) {
?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php
}
?>
<div class="card shadow mb-4">



    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-12">
                <form action="<?= base_url('kerjasama') ?>" method="post" class="form-inline">

                    <div class="form-group mr-2">
                        <label for="inputGroupSelect02" class="mr-2">Status :</label>
                        <div class="col-md-6">
                            <select name="status" class="form-control select2">
                                <option value="1">Aktif</option>
                                <option value="0">Non Aktif</option>


                            </select>
                        </div>
                    </div>

                    <div class="form-group mr-2">
                        <label for="inputGroupSelect02" class="mr-2">Jenis :</label>
                        <div class="col-md-6">
                            <select name="nama_jenis" class="form-control select2">
                                <!--<option value="">Semua</option>-->
                                <?php foreach ($jenis as $tampil) :
                                ?>
                                    <option value="<?php echo $tampil['nama_jenis']
                                                    ?>"><?php echo $tampil['nama_jenis']
                                                        ?></option>
                                <?php endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Tampil</button>
                </form>
            </div>


        </div>


        <div class="mb-3">
            Hasil Pencarian berdasarkan status : <b> <?php echo status($status3);
                                                        ?> </b> jenis :<b> <?php echo $kerjasama2;
                                                                            ?> </b>
        </div>


        <div class="input-group mb-3 float-right">

            <div class="btn-group btn-group-sm mb-4 mr-3" role="group" aria-label="Basic mixed styles example">
                <a href="<?= base_url('kerjasama/export')
                            ?>" class="btn btn-success"><i class="fas fa-file-export mr-2"></i>Export</a>
                <a href="<?= base_url('kerjasama/cetakPDF')
                            ?>" class="btn btn-danger"><i class="fas fa-file-pdf mr-2"></i>PDF</a>
                <a href="<?= base_url('kerjasama/new')
                            ?>" class="btn btn-primary"><i class="fas fa-plus-square mr-2"></i>Tambah</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Judul</th>
                            <th>Nomor</th>
                            <th>Tgl_Awal</th>
                            <th>Tgl_Akhir</th>
                            <th>Expired</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $i = 1 ?>
                        <?php foreach ($kerjasama as $tampil) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $tampil['nama_jenis'] ?></td>
                                <td><?= $tampil['judul_kerjasama'] ?><br><small class="text-muted"><?= $tampil['tanggal_kerjasama'] ?></small></td>
                                <td><?= $tampil['nomor'] ?></td>
                                <td><?= $tampil['tgl_awal'] ?></td>
                                <td><?= $tampil['tgl_akhir'] ?></td>
                                <td><strong><?= $tampil['expired_tahun'] . ' ' . $tampil['expired_bulan'] ?></strong></td>
                                <!--<td><small class="text-dark"> <?= $tampil['nama_user'] ?></td>-->

                                <td>
                                    <div class="btn-group btn-group-sm inline" role="group" aria-label="Basic mixed styles example">
                                        <a href="<?= base_url('../upload/kerjasama/' . $tampil['berkas_kerjasama']);
                                                    ?>" target="_blank" type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat"><i class="fas fa-file-pdf "></i></a>
                                        <a href=" <?= base_url('kerjasama/' . $tampil['id_kerjasama'] . '/edit')
                                                    ?>" type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fas fa-pen"></i></a>
                                        <a href="#" data-href="<?= base_url('kerjasama/' . $tampil['id_kerjasama'] . '/delete')
                                                                ?>" onclick="confirmToDelete(this)" type="button" class="btn  btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="fas fa-trash"></i></a>
                                    </div>
                            </tr>
                        <?php endforeach ?>
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</div>

<!-- Konfirmasi Hapus Data -->
<div id="confirm-dialog" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="h2">Hapus Data?</h2>
                <p>Data akan terhapus secara permanen</p>
            </div>
            <div class="modal-footer">
                <a href="#" role="button" id="delete-button" class="btn btn-danger">Delete</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmToDelete(el) {
        $("#delete-button").attr("href", el.dataset.href);
        $("#confirm-dialog").modal('show');
    }
</script>
<?= $this->endSection() ?>