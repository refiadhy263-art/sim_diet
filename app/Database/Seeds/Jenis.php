<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Jenis extends Seeder
{
    public function run()
    {
        // membuat data
        $news_data = [
            [
                'title' => 'Pengumuman Kartu Rencana Studi (KRS) Tahun Akademik 2023/2024 Semester Genap',
                'slug'  => 'codeigniter-intro',
                'content' => 'Pengumuman Kartu Rencana Studi (KRS) Tahun Akademik 2023/2024 Semester Genap.'
            ],
            [
                'title' => 'Hello World',
                'slug' => 'hello-world',
                'content' => 'Hello World, ini contoh artikel'
            ],
            [
                'title' => 'Pelaksanaan Ujian Akhir Semester 2023-2024 Ganjil',
                'slug'    => 'codeigniter-meetup',
                'content' => 'Diberitahukan kepada Seluruh Mahasiswa Universitas Aisyiyah Surakarta 1. Pelaksanaan Ujian..'
            ]
        ];

        foreach ($news_data as $data) {
            // insert semua data ke tabel
            $this->db->table('news')->insert($data);
        }
    }
}
