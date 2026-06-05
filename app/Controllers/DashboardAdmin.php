<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Perawat_model;
use App\Models\Pasien_model;
use App\Models\Bangsal_model;
use App\Models\Bed_model;
use App\Models\JenisDiet_model;
use App\Models\Logs_model;



class DashboardAdmin extends BaseController
{

protected $users;
protected $perawat;
protected $pasien;
protected $bangsal;
protected $bed;
protected $jenis_diet;
protected $logs;

    public function __construct()
    {
        $this->users = new Users_model();
        $this->perawat = new Perawat_model();
        $this->bed = new Bed_model();
        $this->pasien = new Pasien_model();
        $this->bangsal = new Bangsal_model();
        $this->jenis_diet = new JenisDiet_model();
        $this->logs = new Logs_model();
    }

    public function index()
    {
        if (session()->get('role') == '1') {
            $data['title'] = 'Dashboard Admin';
        } else if (session()->get('role') == '2') {
            $data['title'] = 'Dashboard Perawat';
        } else if (session()->get('role') == '3') {
            $data['title'] = 'Dashboard Gizi';
        } else if (session()->get('role') == '4') {
            $data['title'] = 'Dashboard Pramusaji';
        } else {
            $data['title'] = 'Dashboard';
        }

        // Get active hospitalized patients
        $allPasienDirawat = $this->pasien->getList();

        $bangsalList = $this->bangsal->getList();

        $data['pasienDirawat'] = $allPasienDirawat;
        $data['totalPasien'] = count($allPasienDirawat);
        $data['totalBangsal'] = $this->bangsal->getTotalBangsal();
        $data['totalDiet'] = $this->jenis_diet->getTotalDiet();

        // Hitung statistik per bangsal
        $data['bangsalStats'] = [];
        foreach ($bangsalList as $b) {
            $count = 0;
            foreach ($allPasienDirawat as $p) {
                if ($p['id_bangsal'] === $b['id_bangsal']) {
                    $count++;
                }
            }
            $b['kapasitas'] = (int) $this->bed->getKapasitas($b['id_bangsal']);
            $persen = $b['kapasitas'] > 0 ? ($count / $b['kapasitas']) * 100 : 0;
            $persen = min(100, round($persen, 2));

            $data['bangsalStats'][] = array_merge($b, [
                'count' => $count,
                'persen' => $persen
            ]);
        }
        
        // Hitung distribusi diet
        $data['dietCount'] = [];
        foreach ($allPasienDirawat as $p) {
            $diet = $p['nama_jenis_diet'] ?? 'Tanpa Diet';
            $data['dietCount'][$diet] = ($data['dietCount'][$diet] ?? 0) + 1;
        }
        $data['dietItems'] = [];
        foreach ($data['dietCount'] as $nama => $jumlah) {
            $data['dietItems'][] = ['nama' => $nama, 'jumlah' => $jumlah];
        }
        
        $statusMap = [
            '0' => 'menunggu',
            '1' => 'sedang_disiapkan',
            '2' => 'siap_antar',
            '3' => 'sedang_diantar',
            '4' => 'selesai'
        ];

        // Status order
        $data['statusOrder'] = ['menunggu', 'sedang_disiapkan', 'siap_antar', 'sedang_diantar', 'selesai'];
        
        $orderCounts = array_fill_keys($data['statusOrder'], 0);
        foreach ($allPasienDirawat as $p) {
            $rawStatus = $p['status_order'] ?? '0';
            $mappedStatus = $statusMap[$rawStatus] ?? 'menunggu';
            if (isset($orderCounts[$mappedStatus])) {
                $orderCounts[$mappedStatus]++;
            }
        }

        $data['orderStats'] = [];
        foreach ($data['statusOrder'] as $s) {
            $data['orderStats'][] = [
                'status' => $s,
                'label' => getOrderStatusLabel($s),
                'count' => $orderCounts[$s]
            ];
        }

        return view('layout/admin/admin_profile_view', $data);
    }

    // Perawat Dashboard Functions (Role 2)
    public function getPerawatDashboardData()
    {
        if (session()->get('role') != '2') {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

       // Asumsi ID Bangsal diambil dari session user yang sedang login
        $idBangsal = (int)(session()->get('id_bangsal') ?? 0); 
        
        if ($idBangsal <= 0) {
            return $this->response->setJSON(['error' => 'Bangsal tidak ditemukan']);
        }
        
        // Ambil data pasien yang sedang dirawat (status_rawat = '0')
        $pasien = $this->pasien->getPasienDirawat($idBangsal);

        // 1. Inisialisasi Variabel Perhitungan
        $dietCount   = [];
        $bentukCount = [];
            // Status order mapping from db values ('0'-'4') to view string statuses
        $statusMap = [
            '0' => 'menunggu',
            '1' => 'sedang_disiapkan',
            '2' => 'siap_antar',
            '3' => 'sedang_diantar',
            '4' => 'selesai'
        ];

        // Status order
        $statusOrder = ['menunggu', 'sedang_disiapkan', 'siap_antar', 'sedang_diantar', 'selesai'];
        
        $orderCounts = array_fill_keys($statusOrder, 0);
        foreach ($pasien as $p) {
            $rawStatus = $p['status_order'] ?? '0';
            $mappedStatus = $statusMap[$rawStatus] ?? 'menunggu';
            if (isset($orderCounts[$mappedStatus])) {
                $orderCounts[$mappedStatus]++;
            }
        }

        $orderStats = [];
        foreach ($statusOrder as $s) {
            $orderStats[] = [
                'status' => $s,
                'label' => getOrderStatusLabel($s),
                'count' => $orderCounts[$s]
            ];
        }

        // 2. Lakukan Perhitungan (Looping Data Pasien)
        foreach ($pasien as $p) {
            // Hitung Distribusi Diet
            $diet = !empty($p['nama_jenis_diet']) ? $p['nama_jenis_diet'] : 'Belum Atur Diet';
            if (!isset($dietCount[$diet])) $dietCount[$diet] = 0;
            $dietCount[$diet]++;

            // Hitung Distribusi Bentuk Makanan
            $bentuk = !empty($p['nama_bentuk_diet']) ? $p['nama_bentuk_diet'] : 'Makanan Biasa';
            if (!isset($bentukCount[$bentuk])) $bentukCount[$bentuk] = 0;
            $bentukCount[$bentuk]++;

            // Hitung Status Order
            $status = !empty($p['status_pesanan']) ? $p['status_pesanan'] : 'menunggu';
            if (array_key_exists($status, $orderStats)) {
                $orderStats[$status]['count']++;
            }
        }

        // 3. Logika Penentuan Jadwal Aktif
        date_default_timezone_set('Asia/Jakarta');
        $jam   = (int) date('H');
        $menit = (int) date('i');
        $jadwalAktif = 'Belum ada jadwal';

      
        if ($jam < 5 || ($jam == 5 && $menit < 30)) {
           $jadwalAktif = 'Belum Ada'; $next = 'Makan Pagi (05:30)';
        } elseif ($jam < 10) {
            $jadwalAktif = 'Makan Pagi (05:30)'; $next = 'Makan Siang (10:00)';
        } elseif ($jam < 15 || ($jam == 15 && $menit < 30)) {
            $jadwalAktif = 'Makan Siang (10:00)'; $next = 'Makan Malam (15:30)';
        } else {
            $jadwalAktif = 'Makan Malam (15:30)'; $next = 'Makan Pagi (05:30 Besok)';
        }

        // 4. Siapkan Data untuk View
        $data = [
            'title'       => 'Dashboard Bangsal',
            'waktu'       => date('H:i:s'),
            'jadwalAktif' => $jadwalAktif,
            'dietCount'   => $dietCount,
            'bentukCount' => $bentukCount,
            'orderStats'  => $orderStats
        ];
        return $this->response->setJSON($data);
    }

    public function getGiziDashboardData()
    {

        // 1. Logika Waktu & Jadwal
        date_default_timezone_set('Asia/Jakarta');
        
       
        $tanggalSekarang = hari(date('w')) . ', ' . tanggal_indo(date('Y-m-d'));
        $waktuSekarang   = date('H:i:s');
        
        $jam   = (int) date('H');
        $menit = (int) date('i');
        
        // Penentuan Jadwal Aktif
        if ($jam < 5 || ($jam == 5 && $menit < 30)) {
            $aktif = 'Belum Ada'; $next = 'Makan Pagi (05:30)';
        } elseif ($jam < 10) {
            $aktif = 'Makan Pagi'; $next = 'Makan Siang (10:00)';
        } elseif ($jam < 15 || ($jam == 15 && $menit < 30)) {
            $aktif = 'Makan Siang'; $next = 'Makan Malam (15:30)';
        } else {
            $aktif = 'Makan Malam'; $next = 'Makan Pagi (05:30 Besok)';
        }

        // 2. Mengambil Data dan Perhitungan Statistik per Bangsal
        $bangsalList = $this->bangsal->getList();
        $pasienAktif = $this->pasien->getList(); // Ambil semua pasien yang sedang dirawat tanpa filter bangsal

        $bStats = [];
        foreach ($bangsalList as $b) {
            $idBangsal = $b['id_bangsal'];
            
            $menunggu = 0; 
            $disiapkan = 0; 
            $siap = 0; 
            $total = 0;

            foreach ($pasienAktif as $p) {
                if ($p['id_bangsal'] == $idBangsal) {
                    $total++;
                    $status = $p['status_order'] ?? '';
                    
                    if ($status === '0') $menunggu++;
                    elseif ($status === '1') $disiapkan++;
                    elseif ($status === '2') $siap++;
                }
            }

            $bStats[] = [
                'id_bangsal'   => $b['id_bangsal'],
                'nama_bangsal' => $b['nama_bangsal'],
                'icon'         => $b['icon'] ?? 'fi fi-rr-bed-alt',
                'total'        => $total,
                'menunggu'     => $menunggu,
                'disiapkan'    => $disiapkan,
                'siap'         => $siap
            ];
        }

        // 3. Kirim JSON Balasan
        return $this->response->setJSON([
            'status'          => 'success',
            'tanggalSekarang' => $tanggalSekarang,
            'waktuSekarang'   => $waktuSekarang,
            'aktif'           => $aktif,
            'next'            => $next,
            'bStats'          => $bStats
        ]);
    }



    // Pramusaji Dashboard Functions (Role 4)
    public function getDashboardData()
    {
        if (session()->get('role') != '4') {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $session = session();
        $id_bangsal = (int)($session->get('id_bangsal') ?? 0);

        if ($id_bangsal <= 0) {
            return $this->response->setJSON(['error' => 'Bangsal tidak ditemukan']);
        }

        $bangsal = $this->bangsal->find($id_bangsal);
        $pasien = $this->pasien->getPasienDirawat($id_bangsal);
        
        $siapAntar = count(array_filter($pasien, fn($p) => $p['status_order'] === '2'));
        $sedangDiantar = count(array_filter($pasien, fn($p) => $p['status_order'] === '3'));

        return $this->response->setJSON([
            'bangsal' => $bangsal,
            'totalPasien' => count($pasien),
            'siapAntar' => $siapAntar,
            'sedangDiantar' => $sedangDiantar
        ]);
    }

    public function prosesAntar()
    {
        if (session()->get('role') != '4') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $session = session();
        $id_bangsal = (int)($session->get('id_bangsal') ?? 0);

        if ($id_bangsal <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Bangsal tidak ditemukan']);
        }

        try {
            $affectedRows = $this->pasien->updateBatchStatus($id_bangsal, '2', '3');
            
            if ($affectedRows > 0) {
                $this->logs->saveLog('Proses Antar', "Pramusaji mengubah status $affectedRows porsi menjadi SEDANG DIANTAR");
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => "✅ $affectedRows porsi telah diubah statusnya menjadi SEDANG DIANTAR.",
                'count' => $affectedRows
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function verifyReception()
    {
        if (session()->get('role') != '4') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $json = $this->request->getJSON(true) ?? [];
        $username = trim((string)($json['username'] ?? ''));
        $password = (string)($json['password'] ?? '');
        $session = session();
        $id_bangsal = (int)($session->get('id_bangsal') ?? 0);

        // Validasi input
        if ($username === '' || $password === '') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Username dan password harus diisi']);
        }

        if ($id_bangsal <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Bangsal tidak ditemukan']);
        }

        // Cari perawat berdasarkan username dan bangsal
        $perawat = $this->perawat->getUsernameBangsal($username, $id_bangsal);

        if (!$perawat) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Perawat tidak ditemukan untuk bangsal ini.']);
        }

        // Verifikasi password
        $hashedPassword = $perawat['password'] ?? null;
        if (!$hashedPassword || !password_verify($password, $hashedPassword)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password salah!']);
        }

        try {
            $affectedRows = $this->pasien->updateBatchStatus($id_bangsal, '3', '4');
            
            if ($affectedRows > 0) {
                $this->logs->saveLog('Verifikasi Penerimaan', "Perawat {$perawat['nama_perawat']} memverifikasi penerimaan $affectedRows porsi");
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => " Order diselesaikan dan diverifikasi oleh {$perawat['nama_perawat']}.",
                'perawatName' => $perawat['nama_perawat']
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    // public function total()
    // {

    //     return view('layout/admin/admin_profile_view', $data);
    // }
}
//getOrderStatusLabel() adalah fungsi pembantu yang bisa Anda definisikan untuk mengubah status menjadi label yang lebih user-friendly. Misalnya:
function getOrderStatusLabel($status) {
    switch ($status) {
        case 'menunggu': return 'Menunggu';
        case 'sedang_disiapkan': return 'Sedang Disiapkan';
        case 'siap_antar': return 'Siap Antar';
        case 'sedang_diantar': return 'Sedang Diantar';
        case 'selesai': return 'Selesai';
        default: return 'Unknown';
    }
}