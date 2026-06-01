<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Gizi_model;
use App\Models\Logs_model;

class Gizi extends BaseController
{

protected $users;
protected $gizi;
protected $logs;
 public function __construct()
{
    $this->users = new Users_model();
    $this->gizi = new Gizi_model(); 
    $this->logs = new Logs_model();
 }



    public function index()
    {
        // Kirim data list bangsal ke View untuk dropdown filter
        $data = [
            'title'       => 'Manajemen Ahli Gizi',
        ];
        

        return view('admin/gizi_tampil_view', $data);
    }

    public function getData()
    {
        $data = $this->gizi->getAll();
        return $this->response->setJSON($data);
    }
 
     public function edit($id)
     {
         $data = $this->gizi->getIdAhliGizi($id);
         return $this->response->setJSON($data);
     }
 
     public function save()
     {
         $id_ahli_gizi = $this->request->getPost('id_ahli_gizi');
         $password   = $this->request->getPost('password');
         $existingAhliGizi = $id_ahli_gizi ? $this->gizi->getIdAhliGizi($id_ahli_gizi) : null;
        
          // Menyusun rule validasi foto secara dinamis berdasarkan kondisi tambah/edit
        if ($id_ahli_gizi && $existingAhliGizi) {
        // Mode EDIT: Foto tidak wajib diisi (permit_empty)
        $photoRule = 'permit_empty|uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
        } else {
        // Mode TAMBAH: Foto WAJIB diisi menggunakan 'uploaded[photo]' bukan 'required'
        $photoRule = 'uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
        }




         $validationRules = [
            'nama_ahli_gizi' => 'required',
            'nip'          => 'required',
            'telepon'      => 'required',
            'alamat'       => 'required',
            'photo'        => $photoRule,
            'username'     => 'required|is_unique[users.username,id_users,' . ($existingAhliGizi ? $existingAhliGizi['id_users'] : '0') . ']',
            'password'     => $id_ahli_gizi ? 'permit_empty|min_length[6]' : 'required|min_length[6]',
        ];


        if (!$this->validate($validationRules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => $this->validator->getErrors()]);
        }
        
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
        $nama_file = $photo->getRandomName();
        $photo->move('uploads/gizi/', $nama_file);
        } else {
        $nama_file = $existingAhliGizi ? $existingAhliGizi['photo'] : null; // Gunakan foto lama jika tidak ada foto baru yang diupload
        }     



         $data = [
             'nama_ahli_gizi' => $this->request->getPost('nama_ahli_gizi'),
             'nip'          => $this->request->getPost('nip'),
             'telepon'      => $this->request->getPost('telepon'),
             'photo'        => $nama_file,
             'alamat'       => $this->request->getPost('alamat')
         ];
 
         $userData = [
             'username' => $this->request->getPost('username'),
             'role'     => '3'
         ];
 
         if (!empty($password)) {
             $userData['password'] = password_hash($password, PASSWORD_BCRYPT);
         }
 
         if ($id_ahli_gizi && $existingAhliGizi) {
             // Validasi username unik saat update (kecuali username milik sendiri)
             $existingUser = $this->users->getUser($userData['username']);
             if ($existingUser && $existingUser['id_users'] != $existingAhliGizi['id_users']) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Username sudah digunakan oleh pengguna lain.']);
             }

             $this->users->editUser($userData, $existingAhliGizi['id_users']);
             $this->gizi->editAhliGizi($data, $id_ahli_gizi);
             $this->logs->saveLog('Edit Ahli Gizi', "Memperbarui data ahli gizi: " . $data['nama_ahli_gizi']);
             $message = 'Data ahli gizi berhasil diperbarui.';
         } else {
             // Validasi username unik saat insert baru
             $existingUser = $this->users->getUser($userData['username']);
             if ($existingUser) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Username sudah digunakan.']);
             }

             $newUserId = $this->users->tambahUser($userData);
             $data['id_users'] = $newUserId;
             $this->gizi->tambahAhliGizi($data);
             $this->logs->saveLog('Tambah Ahli Gizi', "Menambahkan data ahli gizi: " . $data['nama_ahli_gizi']);
             $message = 'Data ahli gizi berhasil ditambahkan.';
         }
 
         return $this->response->setJSON(['status' => 'success', 'message' => $message]);
     }
 
     public function delete($id)
     {
         $nama_ahli_gizi = $this->gizi->getIdAhliGizi($id)['nama_ahli_gizi'];
         $this->gizi->delete($id);
         $this->logs->saveLog('Hapus Ahli Gizi', "Menghapus data ahli gizi: " . $nama_ahli_gizi);
         return $this->response->setJSON(['status' => 'success', 'message' => 'Data ahli gizi berhasil dihapus.']);
     }
 
        
 }