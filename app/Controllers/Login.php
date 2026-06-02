<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Bangsal_model;
use App\Models\Perawat_model;
use App\Models\Pramusaji_model;


class Login extends BaseController
{

    protected $users;
    protected $bangsal;
    protected $perawat;
    protected $pramusaji;

    public function __construct()
    {

        $this->users = new Users_model();
        $this->bangsal = new Bangsal_model();
        $this->perawat = new Perawat_model();
        $this->pramusaji = new Pramusaji_model();
    }
    public function index()
    {

    
        return view('login_view');
    }

    public function process()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $role = $this->request->getVar('role');
        $id_bangsal = $this->request->getVar('id_bangsal');

        // Validasi input
        if (empty($username) || empty($password) || empty($role)) {
            session()->setFlashdata('msg', 'Username, password, dan role harus diisi!');
            return redirect()->to('');
        }

        $bangsal = $this->bangsal->getIdBangsal($id_bangsal);
        $kd_bangsal = $bangsal['kd_bangsal'] ?? null;

        // Untuk perawat (role 2) - validasi dengan bangsal
        if ($role == '2') {
            if (empty($id_bangsal)) {
                session()->setFlashdata('msg', 'Bangsal harus dipilih untuk login perawat!');
                return redirect()->to('');
            }
            
            // Gunakan getUsernameBangsal untuk validasi perawat dengan bangsal
            $data = $this->perawat->getUsernameBangsal($username, $id_bangsal);
            
            if (!$data) {
                session()->setFlashdata('msg', 'Perawat tidak ditemukan di bangsal ini!');
                return redirect()->to('');
            }
            
            $pass = $data['password'] ?? null;
        } else if($role == '4'){
             if (empty($id_bangsal)) {
                session()->setFlashdata('msg', 'Bangsal harus dipilih untuk login pramusaji!');
                return redirect()->to('');
            }
            
            // Gunakan getUsernameBangsal untuk validasi pramusaji dengan bangsal
            $data = $this->pramusaji->getUsernameBangsal($username, $id_bangsal);
            
            if (!$data) {
                session()->setFlashdata('msg', 'Pramusaji tidak ditemukan di bangsal ini!');
                return redirect()->to('');
            } 
            $pass = $data['password'] ?? null;
        }
         else {
            // Untuk user lainnya, validasi dari users table
            $data = $this->users->getUser($username);
            $user_role = $data['role'] ?? null;
            
            if (!$data || $user_role != $role) {
                session()->setFlashdata('msg', 'User tidak ditemukan!');
                return redirect()->to('');
            }
            
            $pass = $data['password'];
        }

        // Verifikasi password
        $verify_pass = password_verify($password, $pass);
        
        if ($verify_pass) {
            $ses_data = [
                'id_users'   => $data['id_users'],
                'username'   => $data['username'],
                'role'       => $role,
                'id_bangsal' => $id_bangsal,
                'kd_bangsal' => $kd_bangsal,
                'logged_in'  => TRUE
            ];

            session()->set($ses_data);
            session()->setFlashdata('msg', 'Anda Berhasil Masuk!');
            return redirect()->to('dashboard');
        } else {
            session()->setFlashdata('msg', 'Password Salah!');
            return redirect()->to('');
        }
    }



    function logout()
    {
        session()->destroy();
        return redirect()->to('');
    }
}
