<?php

namespace App\Controllers;

use App\Models\Logs_model;
use App\Models\Bangsal_model;
use App\Models\Pasien_model;

class Bangsal extends BaseController
{
    protected $bangsal;
    protected $logs;
    protected $pasien;

    public function __construct()
    {
        $this->bangsal = new Bangsal_model();
        $this->logs = new Logs_model();
        $this->pasien = new Pasien_model();
    }

    public function index()
    {
        $data = [
            'title' => 'Bangsal',
        ];

        return view('admin/bangsal_tampil_view', $data);
    }

    public function getData()
    {
        $data = $this->bangsal->getList();
        return $this->response->setJSON($data);
    }

    public function edit($id)
    {
        $data = $this->bangsal->getIdBangsal($id);
        return $this->response->setJSON($data);
    }

    public function save()
    {
        $id_bangsal = $this->request->getPost('id_bangsal');
        $kd_bangsal = $this->request->getPost('kd_bangsal');
        $nama_bangsal = $this->request->getPost('nama_bangsal');
        $kapasitas = $this->request->getPost('kapasitas');

        if (empty($nama_bangsal)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama bangsal tidak boleh kosong.']);
        }

        $existingBangsal = $id_bangsal ? $this->bangsal->getIdBangsal($id_bangsal) : null;

        $data = [
            'kd_bangsal' => $kd_bangsal,
            'nama_bangsal' => $nama_bangsal,
            'kapasitas' => $kapasitas,
        ];

        if ($id_bangsal && $existingBangsal) {
            $this->bangsal->editBangsal($data, $id_bangsal);
            $message = 'Data bangsal berhasil diperbarui.';
            $this->logs->saveLog('Edit Bangsal', "Memperbarui data bangsal: " . $data['nama_bangsal'] . " (Kode Bangsal: " . $data['kd_bangsal'] . ")");
        } else {
            // // Generate kode bangsal otomatis (e.g. B01, B02)
            // $count = $this->bangsal->countAllResults();
            // $kd_bangsal = 'B' . str_pad($count + 1, 2, '0', STR_PAD_LEFT);
            
            $data['kd_bangsal'] = $kd_bangsal;
            
            $this->bangsal->tambahBangsal($data);
            $message = 'Data bangsal berhasil ditambahkan.';
            $this->logs->saveLog('Tambah Bangsal', "Menambahkan bangsal baru: " . $data['nama_bangsal'] . " (Kode Bangsal: " . $data['kd_bangsal'] . ")");
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $message]);
    }

    public function delete($id)
    {
        // Cek apakah bangsal ini sedang digunakan oleh pasien
        $check = $this->pasien->getCekBangsal($id);

        if ($check > 0) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Bangsal tidak dapat dihapus karena masih digunakan oleh pasien.'
            ]);
        }

        $nama_bangsal = $this->bangsal->getIdBangsal($id)['nama_bangsal'];
        $kode_bangsal = $this->bangsal->getIdBangsal($id)['kd_bangsal'];
        $this->bangsal->delete($id);
        $this->logs->saveLog('Hapus Bangsal', "Menghapus bangsal: " . $nama_bangsal . " (Kode Bangsal: " . $kode_bangsal . ")");
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data bangsal berhasil dihapus.']);
    }
}
