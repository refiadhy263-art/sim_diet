<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Logs_model;
use App\Models\Bangsal_model;


class Logs extends BaseController
{

protected $users;
protected $logs;
protected $bangsal;

 public function __construct()
{
    $this->users = new Users_model();
    $this->logs = new Logs_model();
    $this->bangsal = new Bangsal_model();
   
 }



    public function index()
    {
              // Sesuaikan juga method JSON ini jika Anda masih menggunakannya untuk fetch API
        $session    = session();
        $role_aktif = $session->get('role');
        $id_aktif   = $session->get('id_users');

        $filter_user = $this->request->getGet('user');
        $filter_role = $this->request->getGet('id_role');
        $filter_bangsal = $this->request->getGet('id_bangsal');

        $data = [
            'title' => 'Manajemen Log Aktivitas',
        ];

        // Jika yang login adalah Admin (role = 1)
        if ($role_aktif == '1') {
            // Tarik semua data user untuk mengisi dropdown form filter
            $data['listUsers'] = $this->users->getLogsUsers(); 
            
            // Tarik data role. Jika Anda punya tabel/model Role, gunakan: 
            // $data['listRoles'] = $this->roles->findAll();
            // Jika tidak ada tabel role, gunakan array manual seperti ini:
            $data['listRoles'] = role();
             $data['bangsal'] = $this->bangsal->getList();
          
        }
        //dd($data);

        // Ambil data log sesuai hak akses dan filter
        $data['logsList'] = $this->logs->getFilteredLogs($role_aktif, $id_aktif, $filter_user, $filter_role, $filter_bangsal);

        return view('admin/logs_tampil_view', $data);
    }

    public function getData()
    {
        // Sesuaikan juga method JSON ini jika Anda masih menggunakannya untuk fetch API
        $session    = session();
        $role_aktif = $session->get('role');
        $id_aktif   = $session->get('id_users');
        
        $filter_user = $this->request->getGet('user');
        $filter_role = $this->request->getGet('id_role');

        $data = $this->logs->getFilteredLogs($role_aktif, $id_aktif, $filter_user, $filter_role);
        
        return $this->response->setJSON($data);
    }
 
    
 
        
 }