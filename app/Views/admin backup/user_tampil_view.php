<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Pengguna | SIMAS</title>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="my-3">
    <h3 class="m-1 font-weight-bold text-primary">Data Pengguna</h3>
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


        <div class="input-group mb-3">



            <div class="btn-group btn-group-sm float-right mb-4 mr-3" role="group" aria-label="Basic mixed styles example">
                <!--<a href="<? //= base_url('admin/news/export') 
                                ?>" class="btn btn-success"><i class="fas fa-file-export mr-2"></i>Export</a>
                        <a href="<? //= base_url('implementasi/cetakPDF')
                                    ?>" class="btn btn-danger"><i class="fas fa-print mr-2"></i>PDF</a>-->
                <a href="<?= base_url('user/import')
                            ?>" class="btn btn-warning"><i class="fas fa-file-import mr-2"></i>Import</a>

                <a href="<?= base_url('user/new')
                            ?>" class="btn btn-primary"><i class="fas fa-user-plus mr-2"></i>Tambah</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama User</th>
                            <th>Kategori User</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $i = 1 ?>
                        <?php foreach ($user as $tampil) : ?>
                            <tr>
                                <td width="10"><?= $i++; ?></td>
                                <!-- <td>
                                    <?php // $news['id_kategori'] 
                                    ?><br>

                                </td> -->
                                <td><?= $tampil['username'] ?></td>
                                <td> <?= $tampil['nama_user'] ?> </td>
                                <td> <?= $tampil['nama_kategori_user'] ?> </td>
                                <td>
                                    <div class="btn-group btn-group-sm inline" role="group" aria-label="Basic mixed styles example">
                                        <a href="<?= base_url('user/' . $tampil['id_user'] . '/edit_password')
                                                    ?>" type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Password"><i class="fas fa-lock"></i></a>
                                        <a href="<?= base_url('user/' . $tampil['id_user'] . '/edit')
                                                    ?>" type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fas fa-user-edit"></i></a>
                                        <a href="#" data-href="<?= base_url('user/' . $tampil['id_user'] . '/delete')
                                                                ?>" onclick=" confirmToDelete(this)" type="button" class="btn  btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>

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