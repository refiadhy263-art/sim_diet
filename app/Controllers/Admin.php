<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Admin_model;
use App\Models\Logs_model;

class Admin extends BaseController
{

protected $users;
protected $admin;
protected $logs;

 public function __construct()
{
    $this->users = new Users_model();
    $this->admin = new Admin_model();
    $this->logs = new Logs_model();
 }



    public function index()
    {
        // Kirim data list bangsal ke View untuk dropdown filter
        $data = [
            'title'       => 'Manajemen Admin',
        ];
        

        return view('admin/admin_tampil_view', $data);
    }

    public function getData()
    {
        $data = $this->admin->getAll();
        return $this->response->setJSON($data);
    }
 
     public function edit($id)
     {
         $data = $this->admin->getIdAdmin($id);
         return $this->response->setJSON($data);
     }
 
     public function save()
     {
         $id_admin = $this->request->getPost('id_admin');
         $password   = $this->request->getPost('password');
         $existingAdmin = $id_admin ? $this->admin->getIdAdmin($id_admin) : null;
        
          // Menyusun rule validasi foto secara dinamis berdasarkan kondisi tambah/edit
        if ($id_admin && $existingAdmin) {
        // Mode EDIT: Foto tidak wajib diisi (permit_empty)
        $photoRule = 'permit_empty|uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
        } else {
        // Mode TAMBAH: Foto WAJIB diisi menggunakan 'uploaded[photo]' bukan 'required'
        $photoRule = 'uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
        }




         $validationRules = [
            'nama_admin' => 'required',
            'nip'          => 'required',
            'telepon'      => 'required',
            'alamat'       => 'required',
            'photo'        => $photoRule,
            'username'     => 'required|is_unique[users.username,id_users,' . ($existingAdmin ? $existingAdmin['id_users'] : '0') . ']',
            'password'     => $id_admin ? 'permit_empty|min_length[6]' : 'required|min_length[6]',
        ];


        if (!$this->validate($validationRules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => $this->validator->getErrors()]);
        }
        
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
        $nama_file = $photo->getRandomName();
        $photo->move('uploads/admin/', $nama_file);
        } else {
        $nama_file = $existingAdmin ? $existingAdmin['photo'] : null; // Gunakan foto lama jika tidak ada foto baru yang diupload
        }     



         $data = [
             'nama_admin' => $this->request->getPost('nama_admin'),
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
 
         if ($id_admin && $existingAdmin) {
             // Validasi username unik saat update (kecuali username milik sendiri)
             $existingUser = $this->users->getUser($userData['username']);
             if ($existingUser && $existingUser['id_users'] != $existingAdmin['id_users']) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Username sudah digunakan oleh pengguna lain.']);
             }

             $this->users->editUser($userData, $existingAdmin['id_users']);
             $this->admin->editAdmin($data, $id_admin);
             $this->logs->saveLog('Edit Admin', "Memperbarui data admin: " . $data['nama_admin']);
             $message = 'Data admin berhasil diperbarui.';
         } else {
             // Validasi username unik saat insert baru
             $existingUser = $this->users->getUser($userData['username']);
             if ($existingUser) {
                 return $this->response->setJSON(['status' => 'error', 'message' => 'Username sudah digunakan.']);
             }

             $newUserId = $this->users->tambahUser($userData);
             $data['id_users'] = $newUserId;
             $this->admin->tambahAdmin($data);
             $this->logs->saveLog('Tambah Admin', "Menambahkan data admin: " . $data['nama_admin']);
             $message = 'Data admin berhasil ditambahkan.';
         }
 
         return $this->response->setJSON(['status' => 'success', 'message' => $message]);
     }
 
     public function delete($id)
     {
         $nama_admin = $this->admin->getIdAdmin($id)['nama_admin'];
         $this->admin->delete($id);
         $this->logs->saveLog('Hapus Admin', "Menghapus data admin: " . $nama_admin);
         return $this->response->setJSON(['status' => 'success', 'message' => 'Data admin berhasil dihapus.']);
     }
 
        
 }