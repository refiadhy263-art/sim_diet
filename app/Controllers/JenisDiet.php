<?php

namespace App\Controllers;

use App\Models\JenisDiet_model;
use App\Models\Logs_model;
use App\Models\Pasien_model;

class JenisDiet extends BaseController
{
    protected $jenis_diet;
    protected $logs;
    protected $pasien;


    public function __construct()
    {
        $this->jenis_diet = new JenisDiet_model();
        $this->logs = new Logs_model();
        $this->pasien = new Pasien_model();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Jenis Diet',
        ];

        return view('admin/diet_tampil_view', $data);
    }

    public function getData()
    {
        $data = $this->jenis_diet->getAll();
        return $this->response->setJSON($data);
    }

    public function edit($id)
    {
        $data = $this->jenis_diet->getIdJenisDiet($id);
        return $this->response->setJSON($data);
    }

    public function save()
    {
        $id_jenis_diet = $this->request->getPost('id_jenis_diet');
        $nama_jenis_diet = $this->request->getPost('nama_jenis_diet');
        $ket_jenis_diet = $this->request->getPost('ket_jenis_diet');

        if (empty($nama_jenis_diet)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama diet tidak boleh kosong.']);
        }

        $existingDiet = $id_jenis_diet ? $this->jenis_diet->getIdJenisDiet($id_jenis_diet) : null;

        $data = [
            'nama_jenis_diet' => $nama_jenis_diet,
            'ket_jenis_diet'  => $ket_jenis_diet,
        ];

        if ($id_jenis_diet && $existingDiet) {
            $this->jenis_diet->editJenisDiet($data, $id_jenis_diet);
            $this->logs->saveLog('Edit Jenis Diet', "Memperbarui data jenis diet: " . $data['nama_jenis_diet']);
            $message = 'Data jenis diet berhasil diperbarui.';
        } else {
            // Generate kode jenis diet otomatis (e.g. D001, D002)
            $count = $this->jenis_diet->countAllResults();
            $kd_jenis_diet = 'D' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            
            $data['kd_jenis_diet'] = $kd_jenis_diet;
            
            $this->jenis_diet->tambahJenisDiet($data);
            $this->logs->saveLog('Tambah Jenis Diet', "Menambahkan data jenis diet: " . $data['nama_jenis_diet']);
            $message = 'Data jenis diet berhasil ditambahkan.';
        }

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
