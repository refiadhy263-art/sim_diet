<?php

namespace App\Models;

use CodeIgniter\Model;

class Perawat_model extends Model
{
    protected $table = 'perawat';
    protected $primaryKey = 'id_perawat';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_perawat',
        'id_users',
        'nama_perawat',
        'id_bangsal',
        'telepon',
        'alamat',
        'photo',
        'nip',
        'created_at',
        'updated_at'];



    //--------------------------------------------------------------------

    public function getAll($search = null, $id_bangsal = null)
{
    $builder = $this->select('perawat.*, bangsal.nama_bangsal, users.username')
                    ->join('users', 'users.id_users = perawat.id_users', 'left')
                    ->join('bangsal', 'bangsal.id_bangsal = perawat.id_bangsal', 'left');

    if (!empty($id_bangsal)) {
        $builder->where('perawat.id_bangsal', $id_bangsal);
    }

    return $builder->findAll();
}


    

        public function getPerawat($username)
        {


            return $this->where('username', $username)->first();
        }

        public function getUsernameBangsal($username, $id_bangsal)
        {
            return $this->select('perawat.*, users.password, users.username')
                        ->join('users', 'users.id_users = perawat.id_users', 'left')
                        ->where('perawat.id_bangsal', (int)$id_bangsal)
                        ->where('users.username', (string)$username)
                        ->first();
        }




    public function getIdPerawat($id)
    {


        return $this->
        join('users', 'users.id_users = perawat.id_users', 'left')
        ->where('perawat.id_perawat', $id)
        ->first();
    }

    public function tambahPerawat($data)
    {


        return $this->insert($data);
    }
    public function editPerawat($data, $id)
    {


        return $this->db->table($this->table)->where('id_perawat', $id)->update($data);
    }
}
