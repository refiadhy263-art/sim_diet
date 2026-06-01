<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Implementasi | SIMAS</title>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="mt-3">
    <h3 class="m-1 font-weight-bold text-primary">Data Implementasi</h3>
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
            <div class="col-sm-12 col-md-12">
                <form action="<?= base_url('implementasi') ?>" method="post" class="form-inline">
                    <div class="form-group mr-2">
                        <label for="inputGroupSelect02" class="mr-2">TA :</label>

                        <select name="nama_semester" class=" form-control select2" required>
                            <!-- <option value="">Silahkan Pilih</option> -->
                            <?php foreach ($semester as $tampil) :
                            ?>
                                <option value=" <?php echo $tampil['nama_semester']
                                                ?>"><?php echo $tampil['nama_semester']
                                                    ?></option>
                            <?php endforeach;
                            ?>
                        </select>

                    </div>
                    <div class="form-group mr-2">
                        <label for="inputGroupSelect02" class="mr-2">Prodi :</label>

                        <select name="nama_program_studi" class=" form-control select2" required>
                            <option value="">Silahkan Pilih</option>
                            <?php foreach ($prodi as $tampil) :
                            ?>
                                <option value=" <?php echo $tampil['nama_program_studi']
                                                ?>"><?php echo $tampil['nama_program_studi']
                                                    ?></option>
                            <?php endforeach;
                            ?>
                        </select>

                    </div>
                    <button type="submit" class="btn btn-info">Tampil</button>
                </form>
            </div>

        </div>
        <?php if ($implementasi2 != 0) : ?>
            <div class="mb-3">
                <h5> <b><?php echo $prodi2; ?> <?php echo $implementasi2; ?> </b> </h5>
            </div>
        <?php endif; ?>


        <div class="input-group mb-3">



            <div class="btn-group btn-group-sm float-right mb-4 mr-3" role="group" aria-label="Basic mixed styles example">
                <a href="<?= base_url('implementasi/export')
                            ?>" class="btn btn-success"><i class="fas fa-file-export mr-2"></i>Export</a>
                <a href="<?= base_url('implementasi/cetakPDF')
                            ?>" class="btn btn-danger"><i class="fas fa-file-pdf mr-2"></i>PDF</a>
                <a href="<?= esc(base_url('implementasi/new'))
                            ?>" class="btn btn-primary"><i class="fas fa-plus-square mr-2"></i>Tambah</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th>No</th>
                            <!--<th>TA</th>
                            <th>Prodi</th>-->
                            <th>Kerjasama</th>
                            <th>Nomor</th>
                            <th>Judul</th>
                            <th>User</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $i = 1 ?>
                        <?php foreach ($implementasi as $tampil) : ?>
                            <tr>
                                <td width="10"><?= $i++; ?></td>
                                <!-- <td>
                                    <?php // $news['id_kategori'] 
                                    ?><br>

                                </td> 
                                <td width="100"><? //= $tampil['nama_semester'] 
                                                ?></td>
                                <td><? //= $tampil['nama_program_studi'] 
                                    ?></td>-->
                                <td width="300"> <?= $tampil['judul_kerjasama'] ?><br> <small class="text-muted"><?= $tampil['tanggal_kerjasama'] ?></small></td>
                                <td><?= $tampil['nomor'] ?></td>
                                <td width="300"> <?= $tampil['judul_implementasi'] ?><br> <small class="text-muted"><?= $tampil['tanggal_implementasi'] ?></small></td>
                                <td><?= $tampil['nama_user'] ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm inline" role="group" aria-label="Basic mixed styles example">
                                        <a href="<?= base_url('../upload/implementasi/' . $tampil['id_semester'] . '/' . $tampil['berkas_implementasi']);
                                                    ?>" target="_blank" type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat"><i class="fas fa-file-pdf "></i></a>
                                        <a href="<?= base_url('implementasi/' . $tampil['id_implementasi'] . '/edit')
                                                    ?>" type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fas fa-pen"></i></a>
                                        <a href="#" data-href="<?= base_url('implementasi/' . $tampil['id_implementasi'] . '/delete')
                                                                ?>" onclick=" confirmToDelete(this)" type="button" class="btn  btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="fas fa-trash"></i></a>
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