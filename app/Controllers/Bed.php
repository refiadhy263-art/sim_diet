<?php

namespace App\Controllers;

use App\Models\Bangsal_model;
use App\Models\Bed_model;
use App\Models\Logs_model;
use App\Models\Pasien_model;

class Bed extends BaseController
{
    protected $bed;
    protected $bangsal;
    protected $logs;
    protected $pasien;


    public function __construct()
    {
        $this->bed = new Bed_model();
        $this->bangsal = new Bangsal_model();
        $this->logs = new Logs_model();
        $this->pasien = new Pasien_model();
    }

    public function index()
    {
        $data = [
            'title' => 'Bed',
            'bangsalList' => $this->bangsal->getList(),
        ];
       // dd($data['bangsalList']);

        return view('admin/bed_tampil_view', $data);
    }

    public function getData($id_bangsal)
    {
        $data = $this->bed->getList($id_bangsal);
        
        return $this->response->setJSON($data);
    }

    public function edit($id)
    {
        $data = $this->bed->getIdBed($id);
        return $this->response->setJSON($data);
    }

    public function save()
    {
        $id_bed = $this->request->getPost('id_bed');
        $kd_bed = $this->request->getPost('kd_bed');
        $nama_bed = $this->request->getPost('nama_bed');
        $id_bangsal = $this->request->getPost('id_bangsal');

        if (empty($nama_bed)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama bed tidak boleh kosong.']);
        }

        $existingBed = $id_bed ? $this->bed->getIdBed($id_bed) : null;
    
        $data = [
            'nama_bed' => $nama_bed,
            'id_bangsal' => $id_bangsal,
        ];

        if ($id_bangsal && $existingBed) {
            $this->bed->editBed($data, $id_bed);
            $this->logs->saveLog('Edit Bed', "Memperbarui data bed: " . $data['nama_bed']);
            $message = 'Data bed berhasil diperbarui.';
        } else {
            // Generate kode bed otomatis (e.g. B01, B02)
           
            
            $this->bed->tambahBed($data);
            
            $this->logs->saveLog('Tambah Bed', "Menambahkan bed baru: " . $data['nama_bed']);
            $message = 'Data bed berhasil ditambahkan.';
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $message]);
    }

    public function delete($id)
    {
        // Cek apakah bed ini sedang digunakan oleh pasien
        
        $check = $this->pasien->getCekBed($id);

        if ($check > 0) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Bed tidak dapat dihapus karena masih digunakan oleh pasien.'
            ]);
        }
        $nama_bed = $this->bed->getIdBed($id)['nama_bed'];
        $this->bed->delete($id);
        $this->logs->saveLog('Hapus Bed', "Menghapus bed: " . $nama_bed);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data bed berhasil dihapus.']);
    }
}
