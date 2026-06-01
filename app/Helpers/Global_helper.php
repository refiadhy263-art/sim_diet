<?php


use App\Models\Implementasi_model;
use \App\Models\NewsModel;
use \App\Models\KategoriModel;
use App\Models\Kerjasama_model;
use App\Models\News_model;
use App\Models\Prodi_model;

if (!function_exists('getGlobalData')) {
    function getGlobalData()
    {
        $kerjasama = new Kerjasama_model();
        // $implementasi = new Implementasi_model();
        // $prodi = new Prodi_model();


        return $kerjasama->getTotal();
    }
}
