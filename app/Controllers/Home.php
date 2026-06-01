<?php

namespace App\Controllers;
use App\Models\Bangsal_model;


class Home extends BaseController
    
{

 
    protected $bangsal;

    public function __construct()
    {

        $this->bangsal = new Bangsal_model();
    }  
    public function index()
    {

        $data = [
        'title' => 'Login',
        'bangsalList' => $this->bangsal->getList() // Ambil data bangsal untuk dropdown
    ];

        // dd($pengaturan);
        echo view('login_view', $data);
    }

    
}
