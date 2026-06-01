<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Pasien_model;
use App\Models\Bangsal_model;
use App\Models\Bed_model;
use App\Models\JenisDiet_model;
use App\Models\BentukDiet_model;
use App\Models\Logs_model;


class Pasien extends BaseController
{

protected $users;
protected $pasien;
protected $bangsal;
protected $bed;
protected $jenis_diet;
protected $bentuk_diet;
protected $logs;

 public function __construct()
{
    $this->users = new Users_model();
    $this->pasien = new Pasien_model(); 
    $this->bangsal = new Bangsal_model();
    $this->bed = new Bed_model();
    $this->jenis_diet = new JenisDiet_model();
    $this->bentuk_diet = new BentukDiet_model();
    $this->logs = new Logs_model();
   
 }


    #admin
    public function index()
    {
        $bangsalList = $this->bangsal->getList();
        foreach ($bangsalList as $bangsal) {
            $bangsal['pasien_count'] = $this->pasien->getCountPasienDirawat($bangsal['id_bangsal']);
        }
       
        // Kirim data list bangsal ke View untuk dropdown filter
        $data = [
            'title'       => 'Manajemen Pasien',
            'bangsalList' => $bangsalList,
            'dietList' => $this->jenis_diet->getAll(),
            'bentukDietList' => $this->bentuk_diet->getAll(),
        ];
        

        return view('admin/pasien_tampil_view', $data);
    }

  public function getBeds($id_bangsal = null)
{
    if ($id_bangsal) {   
    
            $beds = $this->bed->getBedKosong($id_bangsal);
        
        
        return $this->response->setJSON($beds);
    }

    
    return $this->response->setJSON([]);
}
        
    public function getData()
    {
        // Tangkap request dari AJAX/URL
        $search = $this->request->getGet('search');
        $bangsal = $this->request->getGet('id_bangsal');

        // Panggil method dari Model yang sudah dibuat
        $data = $this->pasien->getAll($search, $bangsal);

        // Kembalikan sebagai format JSON
        return $this->response->setJSON($data);
    }

    public function edit($id)
    {
        $data = $this->pasien->getIdPasien($id);
        return $this->response->setJSON($data);
    }

    public function save()
    {
        $id_pasien = $this->request->getPost('id_pasien');
        $existingPasien = $id_pasien ? $this->pasien->getIdPasien($id_pasien) : null;

        $data = [
            'nama_pasien' => $this->request->getPost('nama_pasien'),
            'id_bangsal'  => $this->request->getPost('id_bangsal'),
            'id_bed'      => $this->request->getPost('id_bed'),
            'no_rm'       => $this->request->getPost('no_rm'),
            'tanggal_lahir'  => $this->request->getPost('tanggal_lahir'),
            'diagnosa'       => $this->request->getPost('diagnosa'),
            'id_jenis_diet'  => $this->request->getPost('id_jenis_diet'),
            'id_bentuk_diet' => $this->request->getPost('id_bentuk_diet'),
            'keterangan'     => $this->request->getPost('keterangan')
            
        ];

        //dd($data);

        if ($id_pasien && $existingPasien) {
            $this->pasien->editPasien($data, $id_pasien);
            $message = 'Data pasien berhasil diperbarui.';
            $this->logs->saveLog('Edit Pasien', "Memperbarui data pasien: " . $data['nama_pasien'] . " (No RM: " . $data['no_rm'] . ")");
        } else {
            $this->pasien->tambahPasien($data);
            $message = 'Data pasien berhasil ditambahkan.';
            $this->logs->saveLog('Tambah Pasien', "Menambahkan pasien baru: " . $data['nama_pasien'] . " (No RM: " . $data['no_rm'] . ")");
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $message]);
    }

    public function delete($id)
    {
        $pasien = $this->pasien->getIdPasien($id);
        if ($pasien) {
            $nama_pasien = $pasien['nama_pasien'];
            $no_rm = $pasien['no_rm'];
            $this->pasien->delete($id);
            $this->logs->saveLog('Hapus Pasien', "Menghapus pasien: " . $nama_pasien . " (No RM: " . $no_rm . ")");
        } else {
            $this->pasien->delete($id);
        }
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data pasien berhasil dihapus.']);
    }


#perawat

    public function dirawat()
    {

        $id_bangsal =session()->get('id_bangsal');
       // dd($id_bangsal);
        
        $nama_bangsal = $this->bangsal->getIdBangsal($id_bangsal)['nama_bangsal'] ?? 'Semua Bangsal';
       
        $pasienCount = $this->pasien->getCountPasienDirawat($id_bangsal);
       
       
        $data = [
            'title' => 'Daftar Pasien Dirawat',
            'pasienList' => $this->pasien->getPasienDirawat($id_bangsal),
            'pasienCount' => $pasienCount,
            'bangsalList' => $this->bangsal->getList(),
            'bedList' => $this->bed->getBedKosong($id_bangsal),
            'dietList' => $this->jenis_diet->getAll(),
            'bentukDietList' => $this->bentuk_diet->getAll(),
            'nama_bangsal' => $nama_bangsal
        ];
        //dd($data['bedList']);

        return view('admin/pasien_dirawat_tampil_view', $data);
    }

    public function getPasienDirawat()
    {
        $id_bangsal = session()->get('id_bangsal');
        $data = $this->pasien->getPasienDirawat($id_bangsal);
      //  dd($data);
        return $this->response->setJSON($data);
    }

    function edit_status_rawat()
    {
       
        $id_bangsal =session()->get('id_bangsal');
       // dd($id_bangsal);
        
        $nama_bangsal = $this->bangsal->getIdBangsal($id_bangsal)['nama_bangsal'] ?? 'Semua Bangsal';
       
        $data = [
            'title' => 'Update Status Rawat Pasien',
            'pasienList' => $this->pasien->getPasienDirawat($id_bangsal),
            'nama_bangsal' => $nama_bangsal
        ];
        return view('admin/pasien_edit_status_rawat_view', $data);
    }
     


    public function update_status_rawat()
    {
        $id_pasien = $this->request->getPost('id_pasien');
        $status_rawat = $this->request->getPost('status_rawat');

        $pasien = $this->pasien->getIdPasien($id_pasien);
        if (!$pasien) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pasien tidak ditemukan.']);
        }

        // Update status rawat pasien
        $updateData = ['status_rawat' => $status_rawat];
        
        // Jika status berubah menjadi pulang atau meninggal, kosongkan bed
        if ($status_rawat === '2' || $status_rawat === '1') {
            $updateData['id_bed'] = null;
        }

        $this->pasien->editPasien($updateData, $id_pasien);

        $statusLabels = [
            '0' => 'Dirawat',
            '1' => 'Pulang',
            '2' => 'Meninggal'
        ];
        $labelBaru = $statusLabels[$status_rawat] ?? $status_rawat;
        $this->logs->saveLog('Update Status Rawat', "Mengubah status rawat pasien " . $pasien['nama_pasien'] . " (No RM: " . $pasien['no_rm'] . ") menjadi " . $labelBaru);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Status rawat pasien berhasil diperbarui.']);

    }

    public function getBangsalTersedia()
    {
        // Ambil bangsal user saat ini dari session
        $user_bangsal = session()->get('id_bangsal'); 
        // Tarik data bangsal lain yang masih memiliki slot bed kosong
        $bangsalTersedia = $this->bangsal->getBangsalTujuanTersedia($user_bangsal);

       
        return $this->response->setJSON(['status' => 'success', 'data' => $bangsalTersedia]);
    }
       
    public function simpanPindahBangsal()
    {
        $id_pasien    = $this->request->getPost('id_pasien');
        $id_bangsal = $this->request->getPost('id_bangsal');
        $id_bed     = $this->request->getPost('id_bed');

        if (empty($id_bed)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Bed tujuan wajib dipilih!'], 400);
        }

        // Ambil data pasien sebelum dipindahkan
        $pasien = $this->pasien->getIdPasien($id_pasien);
        $nama_pasien = $pasien ? $pasien['nama_pasien'] : "ID $id_pasien";
        $no_rm = $pasien ? $pasien['no_rm'] : "";

        // Jalankan update data ke database
        if ($this->pasien->editPasien(['id_bangsal' => $id_bangsal, 'id_bed' => $id_bed], $id_pasien)) {
            
            $bangsal = $this->bangsal->getIdBangsal($id_bangsal);
            $nama_bangsal = $bangsal ? $bangsal['nama_bangsal'] : "ID $id_bangsal";
            
            $bed = $this->bed->getIdBed($id_bed);
            $nama_bed = $bed ? $bed['nama_bed'] : "ID $id_bed";

            $this->logs->saveLog('Pindah Bangsal', "Memindahkan pasien " . $nama_pasien . " (No RM: " . $no_rm . ") ke " . $nama_bangsal . " - Bed " . $nama_bed);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Pasien berhasil dipindahkan ke bangsal baru.'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memproses mutasi pasien.'], 500);
    }


    public function pulang_meninggal()
    {
        $id_bangsal = session()->get('id_bangsal');
        $nama_bangsal = $this->bangsal->getIdBangsal($id_bangsal)['nama_bangsal'] ?? 'Semua Bangsal';
        $data = [
            'title' => 'Update Status Pulang/Meninggal Pasien',
            'pasienList' => $this->pasien->getPasienPulangMeninggal($id_bangsal),
            'nama_bangsal' => $nama_bangsal
        ];
        return view('admin/pasien_histori_tampil_view', $data);
    }

    public function kembalikan_dirawat()
    {
        $id_pasien = $this->request->getPost('id_pasien');
        $status_rawat = $this->request->getPost('status_rawat');

        $pasien = $this->pasien->getIdPasien($id_pasien);
        if (!$pasien) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pasien tidak ditemukan.']);
        }

      // 2. VALIDASI: Jika status dikembalikan ke 'Dirawat' (0)
        if ($status_rawat === '0') {
            $bedTerisi = $this->pasien->cekBedTerisi($pasien['id_bangsal'], $pasien['id_bed']);

            if ($bedTerisi) {
                // Jika bed sudah dipakai orang lain, tolak prosesnya
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal: Bed terakhir pasien (' . ($pasien['nama_bed'] ?? $pasien['id_bed']) . ') sudah ditempati pasien lain. Kosongkan bed tersebut terlebih dahulu.'
                ]);
            }
        }

        // Update status rawat pasien
        $updateData = ['status_rawat' => $status_rawat];
        

        $this->pasien->editPasien($updateData, $id_pasien);

        $this->logs->saveLog('Kembalikan Dirawat', "Mengembalikan status pasien " . $pasien['nama_pasien'] . " (No RM: " . $pasien['no_rm'] . ") menjadi Dirawat");

        return $this->response->setJSON(['status' => 'success', 'message' => 'Status pasien berhasil diperbarui.']);

    }

#Gizi

    public function all()
    {
        $data = [
            'title' => 'Data Pasien Keseluruhan',
            'bangsalList' => $this->bangsal->getList(),
            'dietList' => $this->jenis_diet->getAll(),
            'bentukDietList' => $this->bentuk_diet->getAll(),
            
        ];
        return view('admin/pasien_all_tampil_view', $data);

    }

    public function getAllData()
    {
        $search = $this->request->getGet('search');
        $bangsal = $this->request->getGet('bangsal');

        $data = $this->pasien->getAll($search, $bangsal);
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }


    public function print_label($id_pasien)
    {
        $pasien = $this->pasien->getIdPasien($id_pasien);
        if (!$pasien) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pasien tidak ditemukan.']);
        }

        $data = [
            'title' => 'Print Label',
            'pasien' => $pasien,
        ];
        return view('admin/pasien_print_label_tampil_view', $data);
    }
    

    public function rekap_bangsal()
    {

       $bangsalList = $this->bangsal->getList();
       

            // Simpan semua data komplit per bangsal ke dalam array
            $data = [
                'title' => 'Rekap Diet per Bangsal',
                'bangsalList' => $bangsalList,
                
            ];
        
        
        return view('admin/pasien_rekap_bangsal_view', $data);
    }

    public function rekap_order()
    {
        $id_bangsal = session()->get('id_bangsal');
        $bangsal = $this->bangsal->find($id_bangsal);
        if (!$bangsal) {
            return redirect()->back()->with('error', 'Bangsal tidak ditemukan.');
        }

        $patients = $this->pasien->getPasienDirawat($id_bangsal);

        $hasMenunggu = false;
        $hasDiproses = false;
        $dietRekap = [];

        foreach ($patients as $p) {
            if (isset($p['status_order'])) {
                if ($p['status_order'] === '0') $hasMenunggu = true;
                if ($p['status_order'] === '1') $hasDiproses = true;
            }

            $diet   = !empty($p['nama_jenis_diet']) ? $p['nama_jenis_diet'] : 'Biasa';
            $bentuk = !empty($p['nama_bentuk_diet']) ? $p['nama_bentuk_diet'] : 'Biasa';
            $key    = $diet . ' - ' . $bentuk;
            
            if (!isset($dietRekap[$key])) $dietRekap[$key] = 0;
            $dietRekap[$key]++;
        }

        $data = [
            'title' => 'Rekap Order Bangsal',
            'bangsal' => $bangsal,
            'patients' => $patients,
            'hasMenunggu' => $hasMenunggu,
            'hasDiproses' => $hasDiproses,
            'dietRekap' => $dietRekap,
        ];

        return view('admin/pasien_rekap_order_view', $data);
    }

    public function detail_bangsal($id_bangsal)
    {
        
        $bangsal = $this->bangsal->find($id_bangsal);
        if (!$bangsal) {
            return "Bangsal tidak ditemukan.";
        }

        $pasien = $this->pasien->getPasienDirawat($id_bangsal); // Pastikan fungsi ini ada di Pasien_model

        // Hitung Rekap Diet & Status
        $hasMenunggu = false;
        $hasDiproses = false;
        $dietBentukCount = [];

        foreach ($pasien as $p) {
            if (isset($p['status_order'])) {
                if ($p['status_order'] === '0') $hasMenunggu = true;
                if ($p['status_order'] === '1') $hasDiproses = true;
            }

            $diet   = !empty($p['nama_jenis_diet']) ? $p['nama_jenis_diet'] : 'Belum Diatur';
            $bentuk = !empty($p['nama_bentuk_diet']) ? $p['nama_bentuk_diet'] : 'Biasa';
            $key    = $diet . ' - ' . $bentuk;
            
            if (!isset($dietBentukCount[$key])) $dietBentukCount[$key] = 0;
            $dietBentukCount[$key]++;
        }

        $data = [
            'bangsal'         => $bangsal,
            'pasien'          => $pasien,
            'hasMenunggu'     => $hasMenunggu,
            'hasDiproses'     => $hasDiproses,
            'dietBentukCount' => $dietBentukCount,
            'dietList'        => $this->jenis_diet->getAll(),
            'bentukDietList' => $this->bentuk_diet->getAll()
        ];
        return view('admin/pasien_detail_bangsal_view', $data);
    }


    public function update_batch_status()
    {
        $id_bangsal  = $this->request->getPost('id_bangsal');
        $status_lama = $this->request->getPost('status_lama');
        $status_baru = $this->request->getPost('status_baru');

        if ($this->pasien->updateBatchStatus($id_bangsal, $status_lama, $status_baru)) {
            $bangsal = $this->bangsal->find($id_bangsal);
            $nama_bangsal = $bangsal ? $bangsal['nama_bangsal'] : "ID $id_bangsal";
            
            $this->logs->saveLog('Update Batch Status', "Memperbarui status pesanan di bangsal " . $nama_bangsal . " dari " . $status_lama . " menjadi " . $status_baru);
            
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Status pesanan di bangsal ini berhasil diperbarui.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Gagal memperbarui status ke database.'
        ]);
    }
}