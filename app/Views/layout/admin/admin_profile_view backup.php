<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title>Dashboard | SIMAS</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Default box -->
<blockquote class="quote-primary">

    <h5 class="alert-heading">Selamat Datang,<strong> <?= session()->get('nama_user'); ?> !</strong></h5>
    <!--<p><i>Have a nice day!</i></p>
        <hr>
        <p class="text-primary"><i class="fas fa-calendar mr-2"></i>Tanggal : <b><?php
                                                                                    //date_default_timezone_set('Asia/Jakarta');
                                                                                    //echo  date('d-m-Y | H:i:s'); 
                                                                                    ?></b></p>-->


</blockquote>

<div class="card card-info mb-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake mr-2"></i>KERJASAMA</h3>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tingkat</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 1 ?>
                    <?php foreach ($jml_tingkat as $tampil => $value) : ?>
                        <tr>
                            <td width="10" align="center"><small><?= $i++; ?></small></td>
                            <td width="100"><small><?= $tampil
                                                    ?></small></td>
                            <td align="center"><small><?= $value;
                                                        ?></small></td>
                        </tr>
                    <?php endforeach
                    ?>
                    <tr>

                        <td width="100" colspan="2" align="right"><small>Total</small></td>
                        <td align="center"><small><?= $jmlKerjasama;
                                                    ?></small></td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>
</div>

<div class="row">

    
    <div class="col-lg-6">
    <div class="card card-info mb-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake mr-2"></i>IMPLEMENTASI</h3>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tingkat</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 1 ?>
                    <?php foreach ($jml_tingkat_implementasi as $tampil => $value) : ?>
                        <tr>
                            <td width="10" align="center"><small><?= $i++; ?></small></td>
                            <td width="100"><small><?= $tampil
                                                    ?></small></td>
                            <td align="center"><small><?= $value;
                                                        ?></small></td>
                        </tr>
                    <?php endforeach
                    ?>
                    <tr>

                        <td width="100" colspan="2" align="right"><small>Total</small></td>
                        <td align="center"><small><?= $jmlImplementasi; ?></small>
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>
</div>

    </div>
</div>


<div class="card card-info mb-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake mr-2"></i>MBKM</h3>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tingkat</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 1 ?>
                    <?php foreach ($jml_tingkat as $tampil => $value) : ?>
                        <tr>
                            <td width="10" align="center"><small><?= $i++; ?></small></td>
                            <td width="100"><small><?= $tampil
                                                    ?></small></td>
                            <td align="center"><small><?= $value;
                                                        ?></small></td>
                        </tr>
                    <?php endforeach
                    ?>
                    <tr>

                        <td width="100" colspan="2" align="right"><small>Total</small></td>
                        <td align="center"><small><?= $jmlKerjasama;
                                                    ?></small></td>
                    </tr>
                </tbody>

            </table>

        </div>
    </div>
</div>

<div class="card card-info my-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake mr-2"></i>KERJASAMA</h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-lg-2 col-4">
                <a href="<?= base_url('kerjasama') ?>">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $jmlKerjasama; ?></h3>
                            <p>Total</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-1 col-3">
                <a href="<?= base_url('kerjasama') ?>">
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3><?= $jmlMou; ?></h3>
                            <p>MOU</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-1 col-3">
                <a href="<?= base_url('kerjasama') ?>">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3><?= $jmlMoa; ?></h3>
                            <p>MOA</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </a>
            </div>

            <?php
            foreach ($jml_tingkat as $tampil => $value) : ?>
                <div class="col-lg-1 col-3">
                    <a href="<?= base_url('kerjasama') ?>">
                        <div class="small-box bg-info">
                            <div class="inner">

                                <h3><?= $value;
                                    ?></h3>


                                <p><?= $tampil; ?> </p>

                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                        </div>
                    </a>
                </div>

            <?php endforeach ?>
        </div>

    </div>
</div>

<div class="card card-purple my-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-contract mr-2"></i>IMPLEMENTASI</h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-lg-2 col-4">
                <a href="<?= base_url('implementasi') ?>">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3><?= $jmlImplementasi; ?></h3>
                            <p>Total</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </a>
            </div>

            <?php foreach ($jml_tingkat_implementasi as $tampil => $value) : ?>
                <div class="col-lg-1 col-3">
                    <a href="<?= base_url('implementasi') ?>">
                        <div class="small-box bg-purple">
                            <div class="inner">
                                <h3><?= $value; ?> </h3>
                                <p><?= $tampil ?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                        </div>
                    </a>
                </div>

            <?php endforeach ?>
        </div>

    </div>
</div>

<div class="card card-teal my-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chalkboard mr-2"></i>MBKM</h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-lg-2 col-4">
                <a href="<?= base_url('mbkm')
                            ?>">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3><?= $jmlMbkm;
                                ?></h3>
                            <p>Total</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </a>
            </div>

            <?php foreach ($jml_tingkat_mbkm as $tampil => $value) :
            ?>
                <div class="col-lg-1 col-3">
                    <a href="<?= base_url('mbkm') ?>">
                        <div class="small-box bg-teal">
                            <div class=" inner">
                                <h3><?= $value;
                                    ?> </h3>
                                <p class="text-break" style="word-wrap"><?= $tampil
                                                                        ?></p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                        </div>
                    </a>
                </div>

            <?php endforeach
            ?>

        </div>
    </div>





</div>

<div class="col-md-12 mb-2">
    <? //= view_cell('\App\Controllers\GrafikAdmin::index')
    ?>
    <?= view_cell('\App\Controllers\GrafikAdmin::index')
    ?>
</div>
<!-- /.card -->






<?= $this->endSection() ?>