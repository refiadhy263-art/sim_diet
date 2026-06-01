<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisDiet_model extends Model
{
    protected $table = 'jenis_diet';
    protected $primaryKey = 'id_jenis_diet';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_jenis_diet', 
        'kd_jenis_diet', 
        'nama_jenis_diet', 
        'ket_jenis_diet', 
        'created_at',
        'updated_at'];



    //--------------------------------------------------------------------

    public function getAll()
{
    $builder = $this->select('jenis_diet.*');
                    


    return $builder->findAll();
}


    

    public function getIdJenisDiet($id)
    {


        return $this->where('id_jenis_diet', $id)->first();
    }

    public function tambahJenisDiet($data)
    {


        return $this->insert($data);
    }
    public function editJenisDiet($data, $id)
    {


        return $this->db->table($this->table)->where('id_jenis_diet', $id)->update($data);
    }
}
