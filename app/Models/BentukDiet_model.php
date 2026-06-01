<?php

namespace App\Models;

use CodeIgniter\Model;

class BentukDiet_model extends Model
{
    protected $table = 'bentuk_diet';
    protected $primaryKey = 'id_bentuk_diet';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_bentuk_diet', 
        'kd_bentuk_diet', 
        'nama_bentuk_diet', 
        'created_at',
        'updated_at'];



    //--------------------------------------------------------------------

    public function getAll()
{
    $builder = $this->select('bentuk_diet.*');
                    


    return $builder->findAll();
}


    

    public function getIdBentukDiet($id)
    {


        return $this->where('id_bentuk_diet', $id)->first();
    }

    public function tambahBentukDiet($data)
    {


        return $this->insert($data);
    }
    public function editBentukDiet($data, $id)
    {


        return $this->db->table($this->table)->where('id_bentuk_diet', $id)->update($data);
    }
}
