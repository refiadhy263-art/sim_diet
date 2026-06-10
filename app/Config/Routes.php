<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */




$routes->get('/', 'Home::index');

//login
$routes->get('login', 'Login::index', ['filter' => 'redirectIfAuthenticated']);
$routes->post('login/process', 'Login::process', ['filter' => 'redirectIfAuthenticated']);
$routes->get('logout', 'Login::logout');

//role admin
$routes->group(
    '',
    ['filter' => 'authenticate'],
    function ($routes) {
        $routes->get('dashboard', 'DashboardAdmin::index');
        $routes->get('dashboard/getPerawatDashboardData', 'DashboardAdmin::getPerawatDashboardData');
         $routes->get('dashboard/getGiziDashboardData', 'DashboardAdmin::getGiziDashboardData');
         $routes->get('dashboard/getPramusajiDashboardData', 'DashboardAdmin::getPramusajiDashboardData');
     $routes->get('dashboard/getDashboardData', 'DashboardAdmin::getDashboardData');
        $routes->post('dashboard/prosesAntar', 'DashboardAdmin::prosesAntar');
        $routes->post('dashboard/verifyReception', 'DashboardAdmin::verifyReception');
        //$routes->get('/', 'DashboardAdmin::total');


        //halaman perawat
        $routes->get('perawat', 'Perawat::index');
        $routes->get('perawat/getData', 'Perawat::getData');
        $routes->post('perawat/save', 'Perawat::save');
        $routes->get('perawat/edit/(:num)', 'Perawat::edit/$1');
        $routes->post('perawat/update', 'Perawat::update');
        $routes->post('perawat/delete/(:num)', 'Perawat::delete/$1');


        //halaman ahli gizi
        $routes->get('gizi', 'Gizi::index');
        $routes->get('gizi/getData', 'Gizi::getData');
        $routes->post('gizi/save', 'Gizi::save');
        $routes->get('gizi/edit/(:num)', 'Gizi::edit/$1');
        $routes->post('gizi/update', 'Gizi::update');
        $routes->post('gizi/delete/(:num)', 'Gizi::delete/$1');

        //halaman pramusaji
        $routes->get('pramusaji', 'Pramusaji::index');
        $routes->get('pramusaji/getData', 'Pramusaji::getData');
        $routes->post('pramusaji/save', 'Pramusaji::save');
        $routes->get('pramusaji/edit/(:num)', 'Pramusaji::edit/$1');
        $routes->post('pramusaji/update', 'Pramusaji::update');
        $routes->post('pramusaji/delete/(:num)', 'Pramusaji::delete/$1');


        //halaman pasien admin
        $routes->get('pasien', 'Pasien::index');
        $routes->get('pasien/getBeds/(:num)', 'Pasien::getBeds/$1');
        $routes->get('pasien/getData', 'Pasien::getData');
        $routes->post('pasien/save', 'Pasien::save');
        $routes->get('pasien/edit/(:num)', 'Pasien::edit/$1');
        $routes->post('pasien/update', 'Pasien::update');
        $routes->post('pasien/delete/(:num)', 'Pasien::delete/$1');

        //halaman pasien perawat
        $routes->get('pasien/dirawat', 'Pasien::dirawat');
        $routes->get('pasien/edit_status_rawat', 'Pasien::edit_status_rawat');
        $routes->post('pasien/update_status_rawat', 'Pasien::update_status_rawat');
        $routes->get('pasien/getPasienDirawat', 'Pasien::getPasienDirawat');
        $routes->get('pasien/getBangsalTersedia', 'Pasien::getBangsalTersedia');
        $routes->post('pasien/pindah_bangsal', 'Pasien::simpanPindahBangsal');
        $routes->get('pasien/pulang_meninggal', 'Pasien::pulang_meninggal');
        $routes->post('pasien/kembalikan_dirawat', 'Pasien::kembalikan_dirawat');

        //halaman pasien gizi
        $routes->get('pasien/all', 'Pasien::all');
        $routes->get('pasien/getAllData', 'Pasien::getAllData');
        $routes->get('pasien/print_label/(:num)', 'Pasien::print_label/$1');
        $routes->get('pasien/rekap_bangsal', 'Pasien::rekap_bangsal');
        $routes->get('pasien/detail_bangsal/(:num)', 'Pasien::detail_bangsal/$1');
        $routes->post('pasien/update_batch_status', 'Pasien::update_batch_status');
        $routes->get('pasien/rekap_order', 'Pasien::rekap_order');



        
        //halaman jenis diet
        $routes->get('jenis_diet', 'JenisDiet::index');
        $routes->get('jenis_diet/getData', 'JenisDiet::getData');
        $routes->post('jenis_diet/save', 'JenisDiet::save');
        $routes->get('jenis_diet/edit/(:num)', 'JenisDiet::edit/$1');
        $routes->post('jenis_diet/update', 'JenisDiet::update');
        $routes->post('jenis_diet/delete/(:num)', 'JenisDiet::delete/$1');

        //halaman bentuk diet
        $routes->get('bentuk_diet', 'BentukDiet::index');
        $routes->get('bentuk_diet/getData', 'BentukDiet::getData');
        $routes->post('bentuk_diet/save', 'BentukDiet::save');
        $routes->get('bentuk_diet/edit/(:num)', 'BentukDiet::edit/$1');
        $routes->post('bentuk_diet/update', 'BentukDiet::update');
        $routes->post('bentuk_diet/delete/(:num)', 'BentukDiet::delete/$1');

        //halaman bangsal
        $routes->get('bangsal', 'Bangsal::index');
        $routes->get('bangsal/getData', 'Bangsal::getData');
        $routes->post('bangsal/save', 'Bangsal::save');
        $routes->get('bangsal/edit/(:num)', 'Bangsal::edit/$1');
        $routes->post('bangsal/update', 'Bangsal::update');
        $routes->post('bangsal/delete/(:num)', 'Bangsal::delete/$1');

        //halaman bed
        $routes->get('bed', 'Bed::index');
        $routes->get('bed/getData/(:num)', 'Bed::getData/$1');
        $routes->post('bed/save', 'Bed::save');
        $routes->get('bed/edit/(:num)', 'Bed::edit/$1');
        $routes->post('bed/update', 'Bed::update');
        $routes->post('bed/delete/(:num)', 'Bed::delete/$1');


        //jadwal_diet
        $routes->get('jadwal_diet', 'JadwalDiet::index');
        $routes->get('jadwal_diet/getData', 'JadwalDiet::getData');
        $routes->post('jadwal_diet/save', 'JadwalDiet::save');
        $routes->get('jadwal_diet/edit/(:num)', 'JadwalDiet::edit/$1');
        $routes->post('jadwal_diet/update', 'JadwalDiet::update');
        $routes->post('jadwal_diet/delete/(:num)', 'JadwalDiet::delete/$1');

        //logs
        $routes->get('logs', 'Logs::index');
        $routes->get('logs/getData', 'Logs::getData');

    

    }




);
$routes->resource('pages');
