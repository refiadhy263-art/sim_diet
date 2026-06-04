<?php

namespace App\Controllers;

use App\Models\BentukDiet_model;
use App\Models\Logs_model;
use App\Models\Pasien_model;

class BentukDiet extends BaseController
{
    protected $bentuk_diet;
    protected $logs;
    protected $pasien;

    public function __construct()
    {
        $this->bentuk_diet = new BentukDiet_model();
        $this->logs = new Logs_model();
        $this->pasien = new Pasien_model();
    }

    public function index()
    {
        $data = [
            'title' => 'Bentuk Diet',
        ];

        return view('admin/bentuk_diet_tampil_view', $data);
    }

    public function getData()
    {
        $data = $this->bentuk_diet->getAll();
        return $this->response->setJSON($data);
    }

    public function edit($id)
    {
        $data = $this->bentuk_diet->getIdBentukDiet($id);
        return $this->response->setJSON($data);
    }

    public function save()
    {
        $id_bentuk_diet = $this->request->getPost('id_bentuk_diet');
        $nama_bentuk_diet = $this->request->getPost('nama_bentuk_diet');

        if (empty($nama_bentuk_diet)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama diet tidak boleh kosong.']);
        }

        $existingDiet = $id_bentuk_diet ? $this->bentuk_diet->getIdBentukDiet($id_bentuk_diet) : null;

        $data = [
            'nama_bentuk_diet' => $nama_bentuk_diet,
        ];

        if ($id_bentuk_diet && $existingDiet) {
            $this->bentuk_diet->editBentukDiet($data, $id_bentuk_diet);
            $this->logs->saveLog('Edit Bentuk Diet', "Memperbarui data bentuk diet: " . $data['nama_bentuk_diet']);
            $message = 'Data bentuk diet berhasil diperbarui.';
        } else {
            // Generate kode bentuk diet otomatis (e.g. B01, B02)
            $count = $this->bentuk_diet->countAllResults();
            $kd_bentuk_diet = 'B' . str_pad($count + 1, 2, '0', STR_PAD_LEFT);
            
            $data['kd_bentuk_diet'] = $kd_bentuk_diet;
            
            $this->bentuk_diet->tambahBentukDiet($data);
            $this->logs->saveLog('Tambah Bentuk Diet', "Menambahkan data bentuk diet: " . $data['nama_bentuk_diet']);
            $message = 'Data bentuk diet berhasil ditambahkan.';
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $message]);
    }

    public function delete($id)
    {
        // Cek apakah bentuk diet ini sedang digunakan oleh pasien
        
        $check = $this->pasien->getCekBentukDiet($id);

        if ($check > 0) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Bentuk diet tidak dapat dihapus karena masih digunakan oleh pasien.'
            ]);
        }
        $nama_bentuk_diet = $this->bentuk_diet->getIdBentukDiet($id)['nama_bentuk_diet'];
        $this->bentuk_diet->delete($id);
        $this->logs->saveLog('Hapus Bentuk Diet', "Menghapus data bentuk diet: " . $nama_bentuk_diet);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data bentuk diet berhasil dihapus.']);
    }
}
