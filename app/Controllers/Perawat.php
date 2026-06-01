<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Perawat_model;
use App\Models\Bangsal_model;
use App\Models\Logs_model;


class Perawat extends BaseController
{

protected $users;
protected $perawat;
protected $bangsal;
protected $logs;

 public function __construct()
{
    $this->users = new Users_model();
    $this->perawat = new Perawat_model(); 
    $this->bangsal = new Bangsal_model();
    $this->logs = new Logs_model();
    
 }



    public function index()
    {
       
        
        // Kirim data list bangsal ke View untuk dropdown filter
        $data = [
            'title'       => 'Manajemen Perawat',
            'bangsalList' => $this->bangsal->getList()
        ];
        

        return view('admin/perawat_tampil_view', $data);
    }

 
        
    public function getData()
    {
        // Tangkap request dari AJAX/URL
        $bangsal = $this->request->getGet('id_bangsal'); // Sesuai dengan parameter di request Anda

        // Panggil method dari Model yang sudah dibuat
        $data = $this->perawat->getAll(null, $bangsal);

        // Kembalikan sebagai format JSON
        return $this->response->setJSON($data);
    }

    public function edit($id)
    {
        $data = $this->perawat->getIdPerawat($id);
        return $this->response->setJSON($data);
    }

    public function save()
{
    $id_perawat = $this->request->getPost('id_perawat');
    $password   = $this->request->getPost('password');
    $existingPerawat = $id_perawat ? $this->perawat->getIdPerawat($id_perawat) : null;

    // Menyusun rule validasi foto secara dinamis berdasarkan kondisi tambah/edit
    if ($id_perawat) {
        // Mode EDIT: Foto tidak wajib diisi (permit_empty)
        $photoRule = 'permit_empty|uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
    } else {
        // Mode TAMBAH: Foto WAJIB diisi menggunakan 'uploaded[photo]' bukan 'required'
        $photoRule = 'uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
    }

    $validationRules = [
        'nama_perawat' => 'required',
        'id_bangsal'   => 'required',
        'nip'          => 'required',
        'telepon'      => 'required',
        'alamat'       => 'required',
        'photo'        => $photoRule, // Menggunakan rule dinamis yang sudah diperbaiki
        'username'     => 'required|is_unique[users.username,id_users,' . ($existingPerawat ? $existingPerawat['id_users'] : '0') . ']',
        'password'     => $id_perawat ? 'permit_empty|min_length[6]' : 'required|min_length[6]',
    ];

    if (!$this->validate($validationRules)) {
        return $this->response->setJSON(['status' => 'error', 'message' => $this->validator->getErrors()]);
    }
    
    $photo = $this->request->getFile('photo');
    if ($photo && $photo->isValid() && !$photo->hasMoved()) {
        $nama_file = $photo->getRandomName();
        $photo->move('uploads/perawat/', $nama_file);
    } else {
        $nama_file = $existingPerawat ? $existingPerawat['photo'] : null; // Gunakan foto lama jika tidak ada foto baru yang diupload
    }       

    $data = [
        'nama_perawat' => $this->request->getPost('nama_perawat'),
        'id_bangsal'   => $this->request->getPost('id_bangsal'),
        'nip'          => $this->request->getPost('nip'),
        'telepon'      => $this->request->getPost('telepon'),
        'photo'        => $nama_file, 
        'alamat'       => $this->request->getPost('alamat')
    ];

    $userData = [
        'username' => $this->request->getPost('username'),
        'role'     => '2'
    ];

    if (!empty($password)) {
        $userData['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    if ($id_perawat && $existingPerawat) {
        $this->users->editUser($userData, $existingPerawat['id_users']);
        $this->perawat->editPerawat($data, $id_perawat);
        $this->logs->saveLog('Edit Perawat', "Memperbarui data perawat: " . $data['nama_perawat']);
        $message = 'Data perawat berhasil diperbarui.';
    } else {
        $newUserId = $this->users->tambahUser($userData);
        $data['id_users'] = $newUserId;
        $this->perawat->tambahPerawat($data);
        $this->logs->saveLog('Tambah Perawat', " Menambahkan data perawat: " . $data['nama_perawat']);
        $message = 'Data perawat berhasil ditambahkan.';
    }

    return $this->response->setJSON(['status' => 'success', 'message' => $message]);
}

    public function delete($id)
    {
        $nama_perawat = $this->perawat->getIdPerawat($id)['nama_perawat'];
        $this->perawat->delete($id);
        $this->logs->saveLog('Hapus Perawat', "Menghapus data perawat: " . $nama_perawat);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data perawat berhasil dihapus.']);
    }

       
}