<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Kerjasama Expired | SIMAS</title>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="my-2">
    <h3 class="m-1 font-weight-bold text-primary">Data Kerjasama Expired</h3>
</div>

<div class="card shadow mb-4">



    <div class="card-body">

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


                        </tr>
                    <?php endforeach ?>
                </tbody>

            </table>
        </div>
    </div>

</div>
</div>


<?= $this->endSection() ?>