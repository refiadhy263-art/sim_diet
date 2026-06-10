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

    public function getFilteredLogs($role_user_login, $id_user_login, $filter_user = null, $filter_role = null, $filter_bangsal = null)
    {
        // Join ke tabel users agar bisa membaca role dan nama asli pengguna
        $builder = $this->select('logs.*, users.role as id_role') 
                        ->join('users', 'users.username = logs.user', 'left')
                        ->join('perawat', 'perawat.id_users = users.id_users', 'left')
                        ->join('pramusaji', 'pramusaji.id_users = users.id_users', 'left');

        if ($role_user_login == '1') {
            // JIKA ADMIN: Terapkan filter dari dropdown (jika form disubmit)
            if (!empty($filter_user)) {
                $builder->where('users.id_users', $filter_user);
            }
            if (!empty($filter_role)) {
                $builder->where('users.role', $filter_role); // Ganti 'users.role' sesuai nama kolom role di tabel user Anda
            }
            if (!empty($filter_bangsal)) {
                // Gunakan groupStart() agar logika OR tertutup kurung, misal: AND (perawat.id = X OR pramusaji.id = X)
                $builder->groupStart()
                        ->where('perawat.id_bangsal', $filter_bangsal)
                        ->orWhere('pramusaji.id_bangsal', $filter_bangsal)
                        ->groupEnd();
            }

        } else {
            // JIKA BUKAN ADMIN: Kunci kueri agar HANYA menampilkan log milik user tersebut
            $builder->where('users.id_users', $id_user_login);
        }

        return $builder->orderBy('logs.timestamp', 'DESC')->findAll();
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
