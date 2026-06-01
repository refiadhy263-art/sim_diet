<?php

namespace App\Models;

use CodeIgniter\Model;

class Logs_model extends Model
{
    
    protected $table = 'logs';
    protected $primaryKey = 'id_logs';
    protected $allowedFields    = ['timestamp', 'user', 'action', 'detail', 'ip_address'];

    // Dates
    protected $useTimestamps    = false; // Menggunakan format database datetime manual

    /**
     * Fungsi Otomatis untuk Mencatat Aktivitas Sistem
     * 
     * @param string $action Kategori aksi (misal: 'Tambah Perawat', 'Update Diet', 'Hapus Ahli Gizi')
     * @param string $detail Rincian info (misal: 'Menambahkan perawat bernama Siska NIP 192...')
     * @return bool
     */


    public function getAll()
    {
        return $this->orderBy('timestamp', 'DESC')->findAll();
    }


    public function saveLog(string $action, string $detail): bool
    {
        // 1. Ambil nama user dari session login secara otomatis
        $session = session();
        $user = $session->get('username') ?? 'System/Visitor';

        // 2. Ambil IP Address untuk audit keamanan jika dibutuhkan nanti
        $request = \Config\Services::request();
        $ipAddress = $request->getIPAddress();

        // 3. Simpan ke database
        return $this->insert([
            'timestamp'  => date('Y-m-d H:i:s'),
            'user'       => $user,
            'action'     => $action,
            'detail'     => $detail,
            'ip_address' => $ipAddress
        ]);
    }
}
