<?php

namespace App\Controllers;

use App\Models\JadwalDiet_model;
use App\Models\Logs_model;
use App\Models\Pasien_model;

class JadwalDiet extends BaseController
{
    protected $jadwal_diet;
    protected $logs;
    protected $pasien;


    public function __construct()
    {
        $this->jadwal_diet = new JadwalDiet_model();
        $this->logs = new Logs_model();
        $this->pasien = new Pasien_model();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Jadwal Diet',
            'jadwal_diet' => $this->jadwal_diet->getAll(),
        ];
        //dd($data);

        return view('admin/jadwal_diet_tampil_view', $data);
    }



    public function save()
    {
        $id_jadwal_diet = $this->request->getPost('id_jadwal_diet');
        $jadwal_pagi = $this->request->getPost('jadwal_pagi');
        $jadwal_siang = $this->request->getPost('jadwal_siang');
        $jadwal_malam = $this->request->getPost('jadwal_malam');

        if (empty($jadwal_pagi) || empty($jadwal_siang) || empty($jadwal_malam)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Semua jadwal harus diisi.']);
        }

        $existingDiet = $id_jadwal_diet ? $this->jadwal_diet->getIdJadwalDiet($id_jadwal_diet) : null;

        $data = [
            'jadwal_pagi' => $jadwal_pagi,
            'jadwal_siang' => $jadwal_siang,
            'jadwal_malam' => $jadwal_malam,
        ];

    
            $this->jadwal_diet->editJadwalDiet($data, $id_jadwal_diet);
            $this->logs->saveLog('Edit Jadwal Diet', "Memperbarui data jadwal diet: " . $data['jadwal_pagi']);
            $message = 'Data jadwal diet berhasil diperbarui.';
       

        return $this->response->setJSON(['status' => 'success', 'message' => $message]);
    }

    public function delete($id)
    {
        // Cek apakah jenis diet ini sedang digunakan oleh pasien
        
        $check = $this->pasien->getCekJenisDiet($id);

        if ($check > 0) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Jenis diet tidak dapat dihapus karena masih digunakan oleh pasien.'
            ]);
        }

        $nama_jenis_diet = $this->jenis_diet->getIdJenisDiet($id)['nama_jenis_diet'];
        $this->jenis_diet->delete($id);
        $this->logs->saveLog('Hapus Jenis Diet', "Menghapus data jenis diet: " . $nama_jenis_diet);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data jenis diet berhasil dihapus.']);
    }
}
