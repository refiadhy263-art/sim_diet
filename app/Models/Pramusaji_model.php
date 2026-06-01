<?php

namespace App\Models;

use CodeIgniter\Model;

class Pramusaji_model extends Model
{
    protected $table = 'pramusaji';
    protected $primaryKey = 'id_pramusaji';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_pramusaji',
        'id_users',
        'nama_pramusaji',
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
    $builder = $this->select('pramusaji.*, bangsal.nama_bangsal, users.username')
                    ->join('users', 'users.id_users = pramusaji.id_users', 'left')
                    ->join('bangsal', 'bangsal.id_bangsal = pramusaji.id_bangsal', 'left');

    if (!empty($id_bangsal)) {
        $builder->where('pramusaji.id_bangsal', $id_bangsal);
    }

    if (!empty($search)) {
        $builder->groupStart()
                ->like('users.username', $search)
                ->orLike('pramusaji.nama_pramusaji', $search)
                ->groupEnd();
    }

    return $builder->findAll();
}


    

    public function getPramusaji($username)
    {


        return $this->where('username', $username)->first();
    }
    public function getIdPramusaji($id)
    {


         return $this->
        join('users', 'users.id_users = pramusaji.id_users', 'left')
        ->where('pramusaji.id_pramusaji', $id)
        ->first();
    }

    public function tambahPramusaji($data)
    {


        return $this->insert($data);
    }
    public function editPramusaji($data, $id)
    {


        return $this->db->table($this->table)->where('id_pramusaji', $id)->update($data);
    }
}
