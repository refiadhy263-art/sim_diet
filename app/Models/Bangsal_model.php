<?php

namespace App\Models;

use CodeIgniter\Model;

class Bangsal_model extends Model
{
    protected $table = 'bangsal';
    protected $primaryKey = 'id_bangsal';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_bangsal',
        'kd_bangsal',
        'nama_bangsal',
        'kapasitas',
        'icon',
        'created_at',
        'updated_at'

    ];

      



    //--------------------------------------------------------------------

    public function getList()
    {


        return $this->orderBy('id_bangsal', 'ASC')
            ->findAll();
    }


    
 public function getBangsalTujuanTersedia($currentBangsal)
{
    // 1. Ambil semua bangsal kecuali bangsal aktif tempat user bertugas
    $allBangsal = $this->where('id_bangsal !=', $currentBangsal)->findAll();
    $availableBangsal = [];

    foreach ($allBangsal as $b) {
        $idBangsal = $b['id_bangsal'];

        // 2. Ambil SEMUA MASTER BED terdaftar di bangsal ini (id_bed dan nama_bed)
        $masterBedsData = $this->db->table('bed')
                                   ->select('id_bed, nama_bed')
                                   ->where('id_bangsal', $idBangsal)
                                   ->get()
                                   ->getResultArray();
        
        // 3. Ambil ID BED yang sedang dipakai oleh pasien aktif
        $occupiedBedsData = $this->db->table('pasien')
                                     ->select('id_bed')
                                     ->where('id_bangsal', $idBangsal)
                                     ->where('status_rawat', '0')
                                     ->get()
                                     ->getResultArray();
        
        $occupiedBeds = array_column($occupiedBedsData, 'id_bed');

        // 4. Filter masterBedsData: Hanya ambil bed yang ID-nya TIDAK ADA di occupiedBeds
        $filteredBeds = [];
        foreach ($masterBedsData as $bed) {
            if (!in_array($bed['id_bed'], $occupiedBeds)) {
                $filteredBeds[] = [
                    'id_bed'   => $bed['id_bed'],
                    'nama_bed' => $bed['nama_bed']
                ];
            }
        }

        // 5. Jika bangsal tersebut memiliki bed yang kosong, masukkan ke daftar tersedia
        if (!empty($filteredBeds)) {
            $availableBangsal[] = [
                'id_bangsal'   => $b['id_bangsal'],
                'nama_bangsal' => $b['nama_bangsal'] ?? $b['nama'],
                'icon'         => $b['icon'] ?? 'fa-solid fa-hospital',
                'beds_kosong'  => $filteredBeds // Sudah berupa array objek [{id_bed, nama_bed}, ...] yang rapi
            ];
        }
    }

    return $availableBangsal;
}
    public function getIdBangsal($id)
    {


        return $this->where('id_bangsal', $id)->first();
    }

    public function tambahBangsal($data)
    {


        return $this->insert($data);
    }
    public function editBangsal($data, $id)
    {


        return $this->db->table($this->table)->where('id_bangsal', $id)->update($data);
    }
}
