<?php

namespace App\Models;

use CodeIgniter\Model;

class Gizi_model extends Model
{
    protected $table = 'ahli_gizi';
    protected $primaryKey = 'id_ahli_gizi';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_ahli_gizi',
        'id_users',
        'nama_ahli_gizi',
        'telepon',
        'alamat',
        'photo',
        'nip',
        'created_at',
        'updated_at'];



    //--------------------------------------------------------------------

    public function getAll()
    {
        return $this->select('ahli_gizi.*, users.username')
            ->join('users', 'users.id_users = ahli_gizi.id_users', 'left')
            ->findAll();
    }


    

    public function getAhliGizi($username)
    {
        return $this->where('username', $username)->first();
    }
    public function getIdAhliGizi($id)
    {
        return $this->join('users', 'users.id_users = ahli_gizi.id_users', 'left')
        ->where('ahli_gizi.id_ahli_gizi', $id)
        ->first();
    }

    public function tambahAhliGizi($data)
    {


        return $this->insert($data);
    }
    public function editAhliGizi($data, $id)
    {


        return $this->db->table($this->table)->where('id_ahli_gizi', $id)->update($data);
    }
}
