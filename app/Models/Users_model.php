<?php

namespace App\Models;

use CodeIgniter\Model;

class Users_model extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_users';

    protected $useTimestamps = true;
    protected $allowedFields = ['id_users', 'username', 'password', 'name', 'role', 'created_at', 'updated_at'];



    //--------------------------------------------------------------------

    public function getAll()
    {


        return $this->orderBy('id_users', 'DESC')
            ->findAll();
    }

    public function getLogsUsers()
    {
        return $this->select('users.*, COALESCE(perawat.id_bangsal, pramusaji.id_bangsal) as id_bangsal')
                                  ->join('perawat', 'perawat.id_users = users.id_users', 'left')
                                  ->join('pramusaji', 'pramusaji.id_users = users.id_users', 'left')
                                  ->findAll();
    }





    public function getUser($username)
    {


        return $this->where('username', $username)->first();
    }
    public function getIdUser($id)
    {
        return $this->where('id_users', $id)->first();
    }

    public function tambahUser($data)
    {
        return $this->insert($data);
    }

    public function editUser($data, $id)
    {
        return $this->db->table($this->table)->where('id_users', $id)->update($data);
    }
}
