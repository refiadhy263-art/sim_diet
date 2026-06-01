<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            text-align: center;
        }
        .box {
            border: 2px solid #000;
            padding: 20px;
            width: 300px;
            margin: auto;
        }
        hr { border: 1px solid #000; }
        .text-sm { font-size: 14px; }
        .text-xs { font-size: 11px; }
        .italic { font-style: italic; }
        .text-gray { color: #555; }
    </style>
</head>
<body onload="window.print()">
    <div class="box">
        <h3>SIMDIET - <?= esc($pasien['nama_bangsal'] ?? '-') ?></h3>
        <hr>
        <h2><?= esc($pasien['nama_pasien']) ?></h2>
        
        <p class="text-sm" style="margin: 5px 0;">Tgl Lahir: <?= esc($pasien['tanggal_lahir'] ?? '-') ?></p>
        <p class="text-sm" style="margin: 5px 0;">RM: <?= esc($pasien['no_rm']) ?> | Bed: <?= esc($pasien['nama_bed'] ?? '-') ?></p>
        <br>
        
        <h3 style="margin: 5px 0;">DIET: <?= esc($pasien['nama_jenis_diet'] ?? '-') ?></h3>
        <p class="text-sm" style="margin: 5px 0;">Bentuk: <?= esc($pasien['nama_bentuk_diet'] ?? '-') ?></p>
        <hr>
        
        <p class="text-sm">Catatan: <?= esc($pasien['keterangan'] ?? '-') ?></p>
        <br>
        
        <p class="text-xs italic text-gray">Demi menjaga mutu makanan, sebaiknya makanan dikonsumsi 1 jam dari penyajian.</p>
    </div>
</body>
</html>