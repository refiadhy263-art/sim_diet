<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Logs_model;


class Logs extends BaseController
{

protected $users;
protected $logs;

 public function __construct()
{
    $this->users = new Users_model();
    $this->logs = new Logs_model();
   
 }



    public function index()
    {
        // Kirim data list bangsal ke View untuk dropdown filter
        $data = [
            'title'       => 'Log Aktivitas',
            'logsList'    => $this->logs->getAll()
        ];
        

        return view('admin/logs_tampil_view', $data);
    }

    public function getData()
    {
        $data = $this->logs->getAll();
        return $this->response->setJSON($data);
    }
 
    
 
        
 }