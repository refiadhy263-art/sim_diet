<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin_model extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_admin',
        'id_users',
        'nama_admin',
        'telepon',
        'alamat',
        'photo',
        'nip',
        'created_at',
        'updated_at'];



    //--------------------------------------------------------------------

    public function getAll()
    {
        return $this->select('admin.*, users.username')
            ->join('users', 'users.id_users = admin.id_users', 'left')
            ->findAll();
    }


    

    public function getAdmin($username)
    {
        return $this->where('username', $username)->first();
    }
    public function getIdAdmin($id)
    {
        return $this->join('users', 'users.id_users = admin.id_users', 'left')
        ->where('admin.id_admin', $id)
        ->first();
    }

    public function tambahAdmin($data)
    {


        return $this->insert($data);
    }
    public function editAdmin($data, $id)
    {


        return $this->db->table($this->table)->where('id_admin', $id)->update($data);
    }
}
