<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Perawat_model;
use App\Models\Pasien_model;
use App\Models\Bangsal_model;
use App\Models\Bed_model;
use App\Models\JenisDiet_model;
use App\Models\Logs_model;
use App\Models\Pramusaji_model;
use App\Models\JadwalDiet_model;



class DashboardAdmin extends BaseController
{

protected $users;
protected $perawat;
protected $pramusaji;
protected $pasien;
protected $bangsal;
protected $bed;
protected $jenis_diet;
protected $logs;
protected $jadwal_diet;

    public function __construct()
    {
        $this->users = new Users_model();
        $this->perawat = new Perawat_model();
        $this->pramusaji = new Pramusaji_model();
        $this->bed = new Bed_model();
        $this->pasien = new Pasien_model();
        $this->bangsal = new Bangsal_model();
        $this->jenis_diet = new JenisDiet_model();
        $this->jadwal_diet = new JadwalDiet_model();
        $this->logs = new Logs_model();
    }

    protected function getDefaultJadwalDiet()
    {
        return [
            'jadwal_pagi' => '05:30',
            'jadwal_siang' => '10:00',
            'jadwal_malam' => '15:30',
        ];
    }

    protected function getJadwalDietConfig()
    {
        $jadwalList = $this->jadwal_diet->getAll();
        $jadwal = $jadwalList[0] ?? null;

        if (empty($jadwal) || empty($jadwal['jadwal_pagi']) || empty($jadwal['jadwal_siang']) || empty($jadwal['jadwal_malam'])) {
            return $this->getDefaultJadwalDiet();
        }

        return [
            'jadwal_pagi' => substr($jadwal['jadwal_pagi'], 0, 5),
            'jadwal_siang' => substr($jadwal['jadwal_siang'], 0, 5),
            'jadwal_malam' => substr($jadwal['jadwal_malam'], 0, 5),
        ];
    }

    protected function getJadwalAktifFromDb()
    {
        date_default_timezone_set('Asia/Jakarta');
        $now = date('H:i');
        $jadwal = $this->getJadwalDietConfig();

        $pagi = $jadwal['jadwal_pagi'];
        $siang = $jadwal['jadwal_siang'];
        $malam = $jadwal['jadwal_malam'];

        $next = 'Belum Ada';
        $shift = '';
        $aktif = 'Belum ada jadwal';

        if ($now >= $pagi && $now < $siang) {
            $shift = 'pagi';
            $aktif = sprintf('Makan Pagi (%s - %s)', $pagi, date('H:i', strtotime($siang . ' -1 minute')));
            $next = sprintf('Makan Siang (%s)', $siang);
        } elseif ($now >= $siang && $now < $malam) {
            $shift = 'siang';
            $aktif = sprintf('Makan Siang (%s - %s)', $siang, date('H:i', strtotime($malam . ' -1 minute')));
            $next = sprintf('Makan Malam (%s)', $malam);
        } else {
            $shift = 'malam';
            $aktif = sprintf('Makan Malam (%s - %s)', $malam, date('H:i', strtotime($pagi . ' -1 minute')));
            $next = sprintf('Makan Pagi (%s Besok)', $pagi);
        }

        return [
            'waktu' => date('H:i:s'),
            'aktif' => $aktif,
            'next' => $next,
            'shift' => $shift,
            'jadwal_pagi' => $pagi,
            'jadwal_siang' => $siang,
            'jadwal_malam' => $malam,
        ];
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

        // Tentukan jadwal aktif dan kode shift sekarang (dipakai untuk auto-reset)
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $jadwalAktifDataForReset = $this->getJadwalAktifFromDb();
        $shift_sekarang = $jadwalAktifDataForReset['shift'] ?? '';
        $kode_shift_sekarang = $tanggal . '_' . $shift_sekarang;

        // Ambil daftar bangsal dan lakukan auto-reset jika belum untuk shift ini
        $bangsalList = $this->bangsal->getList();
        foreach ($bangsalList as $b) {
            $lastReset = $b['reset_order'] ?? '';
            if ($shift_sekarang && $lastReset !== $kode_shift_sekarang) {
                // Reset status_order untuk bangsal ini
                $this->pasien->getResetOrderBangsal($b['id_bangsal']);
                // Update flag reset di bangsal agar tidak di-reset ulang
                $this->bangsal->update($b['id_bangsal'], ['reset_order' => $kode_shift_sekarang]);
                $this->logs->saveLog('Reset Shift', "Auto-reset order untuk bangsal {$b['id_bangsal']} ke {$kode_shift_sekarang}");
            }
        }

        // Get active hospitalized patients (setelah potensi reset)
        $allPasienDirawat = $this->pasien->getList();

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
        
        // Tentukan jadwal aktif untuk ditampilkan di view
        $jadwalAktifData = $this->getJadwalAktifFromDb();
        $data['jadwal_aktif'] = $jadwalAktifData['aktif'];

        // Hitung status order menggunakan kode numerik yang sesuai dengan view
        $statusOrder = ['0', '1', '2', '3', '4'];
        $statusLabels = [
            '0' => 'Menunggu',
            '1' => 'Sedang Disiapkan',
            '2' => 'Siap Antar',
            '3' => 'Sedang Diantar',
            '4' => 'Selesai'
        ];

        $orderCounts = array_fill_keys($statusOrder, 0);
        foreach ($allPasienDirawat as $p) {
            $rawStatus = (string)($p['status_order'] ?? '0');
            if (isset($orderCounts[$rawStatus])) {
                $orderCounts[$rawStatus]++;
            }
        }

        $data['orderStats'] = [];
        foreach ($statusOrder as $s) {
            $data['orderStats'][] = [
                'status' => $s,
                'label' => $statusLabels[$s],
                'count' => $orderCounts[$s] ?? 0
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

        // Tentukan jadwal aktif saat ini dan kode shift untuk auto-reset
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $jadwalAktifData = $this->getJadwalAktifFromDb();
        $shift_sekarang = $jadwalAktifData['shift'] ?? '';
        $kode_shift_sekarang = $tanggal . '_' . $shift_sekarang;

        // Jika bangsal belum di-reset untuk shift ini, reset status_order pasien di bangsal
        $bangsalRecord = $this->bangsal->find($idBangsal);
        $lastReset = $bangsalRecord['reset_order'] ?? '';
        if ($shift_sekarang && $lastReset !== $kode_shift_sekarang) {
            $this->pasien->getResetOrderBangsal($idBangsal);
            $this->bangsal->update($idBangsal, ['reset_order' => $kode_shift_sekarang]);
            $this->logs->saveLog('Reset Shift', "Auto-reset order untuk bangsal {$idBangsal} ke {$kode_shift_sekarang}");
        }

        // Ambil data pasien yang sedang dirawat (status_rawat = '0')
        $pasien = $this->pasien->getPasienDirawat($idBangsal);

        // 1. Inisialisasi Variabel Perhitungan
        $dietCount   = [];
        $bentukCount = [];

        // Hitung status order berdasarkan kode numerik ('0'..'4')
        $statusOrder = ['0', '1', '2', '3', '4'];
        $statusLabels = [
            '0' => 'Menunggu',
            '1' => 'Sedang Disiapkan',
            '2' => 'Siap Antar',
            '3' => 'Sedang Diantar',
            '4' => 'Selesai'
        ];

        $orderCounts = array_fill_keys($statusOrder, 0);
        foreach ($pasien as $p) {
            $rawStatus = (string)($p['status_order'] ?? '0');
            if (isset($orderCounts[$rawStatus])) {
                $orderCounts[$rawStatus]++;
            }
        }

        $orderStats = [];
        foreach ($statusOrder as $s) {
            $orderStats[] = [
                'status' => $s,
                'label' => $statusLabels[$s],
                'count' => $orderCounts[$s] ?? 0
            ];
        }

        // 2. Lakukan Perhitungan (Looping Data Pasien) - hitung distribusi diet & bentuk
        foreach ($pasien as $p) {
            // Hitung Distribusi Diet
            $diet = !empty($p['nama_jenis_diet']) ? $p['nama_jenis_diet'] : 'Belum Atur Diet';
            $dietCount[$diet] = ($dietCount[$diet] ?? 0) + 1;

            // Hitung Distribusi Bentuk Makanan
            $bentuk = !empty($p['nama_bentuk_diet']) ? $p['nama_bentuk_diet'] : 'Makanan Biasa';
            $bentukCount[$bentuk] = ($bentukCount[$bentuk] ?? 0) + 1;
        }

        // 3. Logika Penentuan Jadwal Aktif dari database
        $jadwalAktifData = $this->getJadwalAktifFromDb();

        // 4. Siapkan Data untuk View
        $data = [
            'title'       => 'Dashboard Bangsal',
            'waktu'       => $jadwalAktifData['waktu'],
            'jadwalAktif' => $jadwalAktifData['aktif'],
            'next'        => $jadwalAktifData['next'],
            'dietCount'   => $dietCount,
            'bentukCount' => $bentukCount,
            'orderStats'  => $orderStats
        ];
        return $this->response->setJSON($data);
    }

    public function getGiziDashboardData()
    {

         // 1. Setup Zona Waktu
        date_default_timezone_set('Asia/Jakarta');
        $tanggalSekarang = hari(date('w')) . ', ' . tanggal_indo(date('Y-m-d'));
        $waktuSekarang   = date('H:i:s');
        $jam_menit       = date('H:i');
        $tanggal         = date('Y-m-d');

        $jadwalAktifData = $this->getJadwalAktifFromDb();
        $jadwalAktif = $jadwalAktifData['aktif'];
        $next = $jadwalAktifData['next'];
        $shift_sekarang = $jadwalAktifData['shift'];
        $is_waktu_proses = !empty($shift_sekarang);
        $kode_shift_sekarang = $tanggal . '_' . $shift_sekarang; // Contoh: 2026-06-05_siang

       // ====================================================================
        // 2. LOGIKA AUTO-RESET (Dijalankan SEBELUM mengambil data pasien)
        // ====================================================================
        $bangsalList = $this->bangsal->getList();
        
        // foreach ($bangsalList as $b) {
        //     // Cek apakah bangsal ini sudah di-reset untuk shift saat ini
        //     $shift_terakhir_bangsal = $b['reset_order'];
        //     //print_r($shift_terakhir_bangsal);

        //     if ($shift_terakhir_bangsal !== $kode_shift_sekarang) {
        //         // A. Reset status order semua pasien di bangsal ini menjadi '0' (Menunggu)
        //         // (Menggunakan fungsi reset order aman yang sudah Anda buat sebelumnya)
        //         $this->pasien->getResetOrderBangsal($b['id_bangsal']);

        //         // B. Update kolom reset_order di tabel bangsal agar tidak di-reset berkali-kali
        //         $this->bangsal->update($b['id_bangsal'], ['reset_order' => $kode_shift_sekarang]);

        //         // Opsional: Catat di Log Aktivitas jika Anda ingin melacaknya
        //          $this->logs->saveLog('Reset Shift', "Shift {$shift_sekarang} dimulai", 'Sistem');
        //     }
        // }
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
            'aktif'           => $jadwalAktif,
            'next'            => $next,
            'bStats'          => $bStats,
            //'shift_terakhir_bangsal' =>$shift_terakhir_bangsal,
            'kode_shift_sekarang' => $kode_shift_sekarang
        ]);
    }



    // Pramusaji Dashboard Functions (Role 4)
    public function getPramusajiDashboardData()
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
            $username = session()->get('username') ?? '';
            $id_bangsal = (int)(session()->get('id_bangsal') ?? 0);
       if ($id_bangsal <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Bangsal tidak ditemukan']);
        }

        $pramusaji = $this->pramusaji->getUsernameBangsal($username ?? '', $id_bangsal);

        try {
            $affectedRows = $this->pasien->updateBatchStatus($id_bangsal, '3', '4');
            
            if ($affectedRows > 0) {
                $this->logs->saveLog('Verifikasi Penerimaan', "Pramusaji {$pramusaji['nama_pramusaji']} memverifikasi penerimaan $affectedRows porsi");
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => " Order diselesaikan oleh {$pramusaji['nama_pramusaji']}.",
                'pramusajiName' => $pramusaji['nama_pramusaji'],
                'count' => $affectedRows
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