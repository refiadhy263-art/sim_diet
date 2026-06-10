<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalDiet_model extends Model
{
    protected $table = 'jadwal_diet';
    protected $primaryKey = 'id_jadwal_diet';

    
    protected $allowedFields = [
        'id_jadwal_diet',
        'jadwal_pagi',
        'jadwal_siang',
        'jadwal_malam',
    ];



    //--------------------------------------------------------------------

    public function getAll()
{
    $builder = $this->select('jadwal_diet.*');
                    


    return $builder->findAll();
}


    

    public function getIdJadwalDiet($id)
    {


        return $this->where('id_jadwal_diet', $id)->first();
    }

    
    public function getTotalDiet() {
        return $this->countAllResults();
    }

    public function tambahJadwalDiet($data)
    {


        return $this->insert($data);
    }
    public function editJadwalDiet($data, $id)
    {


        return $this->db->table($this->table)->where('id_jadwal_diet', $id)->update($data);
    }
}
