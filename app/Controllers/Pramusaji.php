<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Gizi_model;
use App\Models\Logs_model;
use App\Models\Bangsal_model;   
use App\Models\Pramusaji_model;
use App\Models\Pasien_model;
use App\Models\Perawat_model;

class Pramusaji extends BaseController
{

protected $users;
protected $pramusaji;
protected $bangsal;
protected $logs;
protected $pasien;
protected $perawat;

 public function __construct()
{
    $this->users = new Users_model();
    $this->pramusaji = new Pramusaji_model(); 
    $this->bangsal = new Bangsal_model();
    $this->logs = new Logs_model();
    $this->pasien = new Pasien_model();
    $this->perawat = new Perawat_model();
 }



    public function index()
    {
        // Kirim data list bangsal ke View untuk dropdown filter
        $data = [
            'title'       => 'Manajemen Pramusaji',
            'bangsalList' => $this->bangsal->getList()
        ];
        

        return view('admin/pramusaji_tampil_view', $data);
    }

    public function getData()
    {
        // Tangkap request dari AJAX/URL
        $search = $this->request->getGet('search');
        $bangsal = $this->request->getGet('id_bangsal');

        // Panggil method dari Model yang sudah dibuat
        $data = $this->pramusaji->getAll($search, $bangsal);

        // Kembalikan sebagai format JSON
        return $this->response->setJSON($data);
    }
 
     public function edit($id)
     {
         $data = $this->pramusaji->getIdPramusaji($id);
         return $this->response->setJSON($data);
     }
 
     public function save()
     {
         $id_pramusaji = $this->request->getPost('id_pramusaji');
         $password   = $this->request->getPost('password');
         $id_bangsal = $this->request->getPost('id_bangsal');
         $existingPramusaji = $id_pramusaji ? $this->pramusaji->getIdPramusaji($id_pramusaji) : null;
        
          // Menyusun rule validasi foto secara dinamis berdasarkan kondisi tambah/edit
        if ($id_pramusaji && $existingPramusaji) {
        // Mode EDIT: Foto tidak wajib diisi (permit_empty)
        $photoRule = 'permit_empty|uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
        } else {
        // Mode TAMBAH: Foto WAJIB diisi menggunakan 'uploaded[photo]' bukan 'required'
        $photoRule = 'uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
        }




         $validationRules = [
            'nama_pramusaji' => 'required',
            'nip'          => 'required',
            'telepon'      => 'required',
            'alamat'       => 'required',
            'photo'        => $photoRule,
            'username'     => 'required|is_unique[users.username,id_users,' . ($existingPramusaji ? $existingPramusaji['id_users'] : '0') . ']',
            'password'     => $id_pramusaji ? 'permit_empty|min_length[6]' : 'required|min_length[6]',
        ];


        if (!$this->validate($validationRules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => $this->validator->getErrors()]);
        }
        
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
        $nama_file = $photo->getRandomName();
        $photo->move('uploads/pramusaji/', $nama_file);
        } else {
        $nama_file = $existingPramusaji ? $existingPramusaji['photo'] : null; // Gunakan foto lama jika tidak ada foto baru yang diupload
        }     



         $data = [
             'nama_pramusaji' => $this->request->getPost('nama_pramusaji'),
             'nip'          => $this->request->getPost('nip'),
             'telepon'      => $this->request->getPost('telepon'),
             'photo'        => $nama_file,
             'alamat'       => $this->request->getPost('alamat'),
            'id_bangsal'   => $id_bangsal
         ];
 
         $userData = [
             'username' => $this->request->getPost('username'),
             'role'     => '4'
         ];
 
         if (!empty($password)) {
             $userData['password'] = password_hash($password, PASSWORD_BCRYPT);
         }
 
         if ($id_pramusaji && $existingPramusaji) {
             // Validasi username unik saat update (kecuali username milik sendiri)
             $existingUser = $this->users->getUser($userData['username']);
             if ($existingUser && $existingUser['id_users'] != $existingPramusaji['id_users']) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Username sudah digunakan oleh pengguna lain.']);
             }

             $this->users->editUser($userData, $existingPramusaji['id_users']);
             $this->pramusaji->editPramusaji($data, $id_pramusaji);
             $this->logs->saveLog('Edit Pramusaji', "Memperbarui data pramusaji: " . $data['nama_pramusaji']);
             $message = 'Data pramusaji berhasil diperbarui.';
         } else {
             // Validasi username unik saat insert baru
             $existingUser = $this->users->getUser($userData['username']);
             if ($existingUser) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Username sudah digunakan.']);
             }

             $newUserId = $this->users->tambahUser($userData);
             $data['id_users'] = $newUserId;
             $this->pramusaji->tambahPramusaji($data);
             $this->logs->saveLog('Tambah Pramusaji', "Menambahkan data pramusaji: " . $data['nama_pramusaji']);
             $message = 'Data pramusaji berhasil ditambahkan.';
         }
 
         return $this->response->setJSON(['status' => 'success', 'message' => $message]);
     }
 
     public function delete($id)
     {
         $nama_pramusaji = $this->pramusaji->getIdPramusaji($id)['nama_pramusaji'];
         $this->pramusaji->delete($id);
         $this->logs->saveLog('Hapus Pramusaji', "Menghapus data pramusaji: " . $nama_pramusaji);
         return $this->response->setJSON(['status' => 'success', 'message' => 'Data pramusaji berhasil dihapus.']);
     }

       

        
 }