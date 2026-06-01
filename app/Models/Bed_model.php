<?php

namespace App\Models;

use CodeIgniter\Model;

class Bed_model extends Model
{
    protected $table = 'bed';
    protected $primaryKey = 'id_bed';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_bed',
        'id_bangsal',
        'nama_bed',
        'status_bed',
        'created_at',
        'updated_at'

    ];

      



    //--------------------------------------------------------------------

    public function getList($id_bangsal)
    {


        return $this->where('id_bangsal', $id_bangsal)->orderBy('id_bed', 'ASC')
            ->findAll();
    }
    public function getAll($id_bangsal)
    {
        return $this->select('bed.*, bangsal.nama_bangsal')
            ->join('bangsal', 'bangsal.id_bangsal = bed.id_bangsal', 'left')
            ->where('bed.id_bangsal', $id_bangsal)
            ->orderBy('bed.id_bed', 'ASC')
            ->findAll();
    }


   
    

    public function getIdBed($id)
    {


        return $this->where('id_bed', $id)->first();
    }

   public function getBedKosong($id_bangsal) 
    {
        // 1. Buat query pencarian bed yang SEDANG DIPAKAI oleh pasien aktif
        $bed_terpakai = $this->db->table('pasien')
                                ->select('id_bed')
                                ->where('id_bangsal', $id_bangsal)
                                ->where('status_rawat', '0') // Hanya pasien yang masih dirawat
                                ->getCompiledSelect(); // getCompiledSelect merubahnya jadi string query mentah

        // 2. Tampilkan semua bed di bangsal tersebut, KECUALI bed yang ada di dalam list $bed_terpakai
        return $this->select('bed.*')
                    ->where('bed.id_bangsal', $id_bangsal)
                    ->where("bed.id_bed NOT IN ($bed_terpakai)", null, false) // false agar CI4 tidak menambahkan escape/tanda kutip otomatis
                    ->findAll();
    }
 

    public function tambahBed($data)
    {


        return $this->insert($data);
    }
    public function editBed($data, $id)
    {


        return $this->db->table($this->table)->where('id_bed', $id)->update($data);
    }
}
