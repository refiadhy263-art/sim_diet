<?php

namespace App\Models;

use CodeIgniter\Model;

class Pasien_model extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'id_pasien';

    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_pasien',
        'no_rm',
        'nama_pasien',
        'tanggal_lahir',
        'diagnosa',
        'status_rawat',
        'id_bangsal',
        'id_bed',
        'status_order',
        'id_jenis_diet',
        'id_bentuk_diet',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    //--------------------------------------------------------------------

    public function getAll($search = null, $id_bangsal = null)
    {
        $builder = $this->select('pasien.*, bangsal.nama_bangsal, bed.nama_bed, jenis_diet.nama_jenis_diet, bentuk_diet.nama_bentuk_diet')
                        ->join('bangsal', 'bangsal.id_bangsal = pasien.id_bangsal', 'left')
                        ->join('bed', 'bed.id_bed = pasien.id_bed', 'left')
                        ->join('jenis_diet', 'jenis_diet.id_jenis_diet = pasien.id_jenis_diet', 'left')
                        ->join('bentuk_diet', 'bentuk_diet.id_bentuk_diet = pasien.id_bentuk_diet', 'left');
                     
        if (!empty($id_bangsal)) {
            $builder->where('pasien.id_bangsal', $id_bangsal);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('pasien.nama_pasien', $search)
                    ->orLike('pasien.no_rm', $search)
                    ->groupEnd();
        }

        return $builder->findAll();
    }

    public function getList() {
        return $this->select('pasien.*, bangsal.nama_bangsal, bed.nama_bed, jenis_diet.nama_jenis_diet, bentuk_diet.nama_bentuk_diet')
                    ->join('bangsal', 'bangsal.id_bangsal = pasien.id_bangsal')
                    ->join('bed', 'bed.id_bed = pasien.id_bed', 'left')
                    ->join('jenis_diet', 'jenis_diet.id_jenis_diet = pasien.id_jenis_diet', 'left')
                    ->join('bentuk_diet', 'bentuk_diet.id_bentuk_diet = pasien.id_bentuk_diet', 'left')
                 
         ->where('pasien.status_rawat', '0')->findAll();
    }

    public function getPasienDirawat($id_bangsal)
    {
        return $this->select('pasien.*, bangsal.nama_bangsal, bed.nama_bed, jenis_diet.nama_jenis_diet, bentuk_diet.nama_bentuk_diet')
                    ->join('bangsal', 'bangsal.id_bangsal = pasien.id_bangsal')
                    ->join('bed', 'bed.id_bed = pasien.id_bed', 'left')
                    ->join('jenis_diet', 'jenis_diet.id_jenis_diet = pasien.id_jenis_diet', 'left')
                    ->join('bentuk_diet', 'bentuk_diet.id_bentuk_diet = pasien.id_bentuk_diet', 'left')
                    ->where('pasien.id_bangsal', $id_bangsal)
                    ->where('pasien.status_rawat', '0')
                    ->orderBy('pasien.id_pasien', 'desc')
                    ->findAll();
    }

    public function getResetOrderBangsal($id_bangsal)
    {
        return $this->builder()
             ->where('id_bangsal', $id_bangsal)
             ->where('status_rawat', '0')
             ->update(['status_order' => '0']);
    }

    public function getCountPasienDirawat($id_bangsal)
    {
        return $this->where('id_bangsal', $id_bangsal)
                    ->where('status_rawat', '0')
                    ->countAllResults();
    }


    public function cekBedTerisi($id_bangsal, $id_bed)
    {
        return $this->where('id_bangsal', $id_bangsal)
                    ->where('id_bed', $id_bed)
                    ->where('status_rawat', '0')
                    ->first();
    }


    public function getPasienPulangMeninggal($id_bangsal, $bulan, $tahun)
    {
       return $this->select('pasien.*, bangsal.nama_bangsal, bed.nama_bed, jenis_diet.nama_jenis_diet, bentuk_diet.nama_bentuk_diet')
             ->join('bangsal', 'bangsal.id_bangsal = pasien.id_bangsal')
             ->join('bed', 'bed.id_bed = pasien.id_bed', 'left')
             ->join('jenis_diet', 'jenis_diet.id_jenis_diet = pasien.id_jenis_diet', 'left')
                    ->join('bentuk_diet', 'bentuk_diet.id_bentuk_diet = pasien.id_bentuk_diet', 'left')
                    ->where('pasien.id_bangsal', $id_bangsal)
                    ->where('MONTH(pasien.updated_at)', $bulan)
                    ->where('YEAR(pasien.updated_at)', $tahun)
                    ->whereIn('pasien.status_rawat', ['1', '2'])
                    ->orderBy('pasien.updated_at', 'desc')
                    ->findAll();
    }

    public function getIdPasienBangsal($id_bangsal)
    {
        return $this->where('id_bangsal', $id_bangsal)->first();
    }

    public function getCekJenisDiet($id_jenis_diet)
    {
        return $this->where('id_jenis_diet', $id_jenis_diet)->countAllResults();
    }


    public function getCekBentukDiet($id_bentuk_diet)
    {
        return $this->where('id_bentuk_diet', $id_bentuk_diet)->countAllResults();
    }

    public function getCekBangsal($id_bangsal)
    {
        return $this->where('id_bangsal', $id_bangsal)->countAllResults();
    }

    public function getCekBed($id_bed)
    {
        return $this->where('id_bed', $id_bed)->countAllResults();
    }

    public function getPasien($username)
    {
        return $this->where('username', $username)->first();
    }
    
    public function getIdPasien($id)
    {
        return $this->select('pasien.*, bangsal.nama_bangsal, bed.nama_bed, jenis_diet.nama_jenis_diet, bentuk_diet.nama_bentuk_diet')
            ->join('bangsal', 'bangsal.id_bangsal = pasien.id_bangsal', 'left')
            ->join('bed', 'bed.id_bed = pasien.id_bed', 'left')
            ->join('jenis_diet', 'jenis_diet.id_jenis_diet = pasien.id_jenis_diet', 'left')
            ->join('bentuk_diet', 'bentuk_diet.id_bentuk_diet = pasien.id_bentuk_diet', 'left')
            ->where('pasien.id_pasien', $id)
            ->first();
    }


    public function getPasienBed($id_bangsal)
    {
        return $this->join('bed', 'bed.id_bed = pasien.id_bed')
                    ->where('id_bangsal', $id_bangsal)
                    ->where('status_rawat', '0')
                    ->where('pasien.id_bed IS NULL', null, false)
                    ->first();
    }

    public function tambahPasien($data)
    {
        return $this->insert($data);
    }
    
    public function editPasien($data, $id)
    {
        return $this->db->table($this->table)->where('id_pasien', $id)->update($data);
    }


    public function updateBatchStatus($id_bangsal, $status_lama, $status_baru)
    {
        return $this->db->table($this->table)
                    ->where('id_bangsal', $id_bangsal)
                    ->where('status_order', $status_lama) // Pastikan hanya mengubah status yang sesuai
                    ->where('status_rawat', '0') // Pastikan pasien masih aktif dirawat
                    ->update(['status_order' => $status_baru]);
    }

    
}
