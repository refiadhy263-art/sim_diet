<?php

namespace App\Models;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Model;
use DateTime;

class Pegawai_model extends Model
{

    protected $table      = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'id_pegawai',
        'nik_pegawai',
        'status_jabatan',
        'status_dosen',
        'status_pegawai',
        'status_keluar',
        'nama_pegawai',
        'nama_singkat_pegawai',
        'tempat_pegawai',
        'tgl_pegawai',
        'jk_pegawai',
        'hp_pegawai',
        'id_unit',
        'kd_jabatan',
        'status_kepegawaian',
        'status_kepegawaian_lanjutan',
        'id_golongan',
        'id_jafa',
        'tgl_sk_pegawai_terakhir',
        'nip',
        'nidn',
        'nuptk',
        'ttd_pegawai',
        'file_ktp_pegawai',
        'kd_bank',
        'rek_pegawai',
        'nama_rek_pegawai',
        'kd_pendamping_kajian',

    ];

    // model admin
    public function getList()
    {
        return $this
            ->where('pegawai.status_pegawai', '1')
            ->orderBy('id_pegawai')
            ->get()->getResultArray();
    }

    public function getCountPegawai()
    {
        return $this
            ->where('pegawai.status_pegawai', '1')
            ->countAllResults();
    }

    public function getEmeeting()
    {
        return $this
            ->where('status_pegawai', '1')
            ->where('status_dosen !=', '0')
            ->orderBy('nama_pegawai')
            ->get()->getResultArray();
    }

    public function getDosenPBM()
    {
        return $this
            ->where('status_pegawai', '1')
            ->where('status_jabatan', '1')
            ->where('status_dosen!=', '0')
            ->orderBy('nama_pegawai')
            ->get()->getResultArray();
    }

    public function getDosenPengampu()
    {
        return $this
            ->where('status_pegawai', '1')
            ->where('status_jabatan', '1')
            ->orderBy('nama_pegawai')
            ->get()->getResultArray();
    }

    public function getTugasPegawai()
    {
        return $this
            ->where('status_pegawai', '1')
            ->where('status_dosen!=', '0')
            ->orderBy('nama_pegawai')
            ->get()->getResultArray();
    }


    public function getKelompokKajianAll()
    {
        return $this
            ->where('status_pegawai', '1')
            ->where('status_dosen!=', '0')
            ->orderBy('kd_pendamping_kajian')
            ->orderBy('nama_pegawai')
            ->get()->getResultArray();
    }


    public function getAumPegawai()
    {
        return $this
            ->where('status_pegawai', '1')
            ->where('status_dosen!=', '0')
            ->orderBy('nama_pegawai')
            ->get()->getResultArray();
    }


    public function getKelompokKajian($kd_pendamping_kajian)
    {
        return $this
            ->where('status_pegawai', '1')
            ->where('kd_pendamping_kajian', $kd_pendamping_kajian)
            ->orderBy('nama_pegawai')
            ->get()->getResultArray();
    }

    public function getPegawai()
    {
        return $this

            ->orderBy('id_pegawai', 'desc')
            ->first();
    }

    public function getDosenDalamTanggal($tanggal)
    {
        return $this
            ->join('golongan', 'golongan.id_golongan = pegawai.id_golongan')
            ->join('jafa', 'jafa.id_jafa = pegawai.id_jafa')
            ->where('pegawai.status_pegawai', '1')
            ->where('pegawai.status_jabatan', '1')
            ->where('pegawai.status_dosen', '1')
            ->where('pegawai.tgl_sk_pegawai_terakhir', $tanggal)
            ->get()->getResultArray();
    }

    public function getDosenDalam()
    {
        return $this
            ->join('golongan', 'golongan.id_golongan = pegawai.id_golongan')
            ->join('jafa', 'jafa.id_jafa = pegawai.id_jafa')
            ->where('pegawai.status_pegawai', '1')
            ->where('pegawai.status_jabatan', '1')
            ->where('pegawai.status_dosen', '1')
            ->get()->getResultArray();
    }

    public function getDosenLuar()
    {
        return $this
            ->where('status_pegawai', '1')
            ->where('status_jabatan', '1')
            ->where('status_dosen', '0')
            ->get()->getResultArray();
    }

    public function getTendikUnivTanggal($tanggal)
    {
        return $this
            ->join('golongan', 'golongan.id_golongan = pegawai.id_golongan')
            ->where('pegawai.status_pegawai', '1')
            ->where('pegawai.status_jabatan', '2')
            ->where('pegawai.status_kepegawaian !=', '5')
            ->where('pegawai.tgl_sk_pegawai_terakhir', $tanggal)
            ->get()->getResultArray();
    }

    public function getTendikUniv()
    {
        return $this
            ->join('golongan', 'golongan.id_golongan = pegawai.id_golongan')
            ->where('pegawai.status_pegawai', '1')
            ->where('pegawai.status_jabatan', '2')
            ->where('pegawai.status_kepegawaian !=', '5')
            ->get()->getResultArray();
    }

    public function getTendikBph()
    {
        return $this
            ->join('jabatan', 'jabatan.kd_jabatan = pegawai.kd_jabatan')
            ->where('pegawai.status_pegawai', '1')
            ->where('pegawai.status_jabatan', '2')
            ->where('pegawai.status_kepegawaian', '5')
            ->get()->getResultArray();
    }

    public function getNonAktif()
    {
        return $this
            ->join('golongan', 'golongan.id_golongan = pegawai.id_golongan')
            ->where('pegawai.status_pegawai', '2')
            ->get()->getResultArray();
    }


    public function getRektor()
    {
        return $this
            ->join('jabatan', 'jabatan.kd_jabatan = pegawai.kd_jabatan')
            ->where('pegawai.kd_jabatan', 'J01')
            ->first();
    }

    public function getKaBaku()
    {
        return $this
            ->join('jabatan', 'jabatan.kd_jabatan = pegawai.kd_jabatan')
            ->where('pegawai.kd_jabatan', 'J16')
            ->first();
    }

    public function getP3Si()
    {
        return $this
            ->join('jabatan', 'jabatan.kd_jabatan = pegawai.kd_jabatan')
            ->where('pegawai.kd_jabatan', 'J21')
            ->first();
    }


    public function getDekan($kd_jabatan_fakultas)
    {
        return $this
            ->join('jabatan', 'jabatan.kd_jabatan = pegawai.kd_jabatan')
            ->where('pegawai.kd_jabatan', $kd_jabatan_fakultas)
            ->get()->getResultArray();
    }

    public function getJabatan($kd_jabatan)
    {
        return $this
            ->join('jabatan', 'jabatan.kd_jabatan = pegawai.kd_jabatan')
            ->where('pegawai.kd_jabatan', $kd_jabatan)
            ->first();
    }

     public function getKdJabatan($kd_jabatan)
    {
        return $this
            ->where('kd_jabatan', $kd_jabatan)
            ->first();
    }

    public function getNamaPegawai($nik_pegawai_jabatan)
    {
        return $this
            ->where('nik_pegawai', $nik_pegawai_jabatan)
            ->get()->getResultArray();
        // ->first();
    }


    public function getNikPegawai($nik_pegawai)
    {
        return $this
            ->where('nik_pegawai', $nik_pegawai)
            ->first();
    }

    public function getAkmDosen($nik_pegawai)
    {

        return $this
            ->join('akm', 'akm.nik_pegawai = pegawai.nik_pegawai')
            ->where('pegawai.nik_pegawai', $nik_pegawai)
            ->first();
    }


    public function getPembimbingTA($nim, $id_semester)
    {

        return $this
            ->join('ujian_ta', 'ujian_ta.penguji3_ta = pegawai.nik_pegawai')
            ->where('ujian_ta.nim', $nim)
            ->where('ujian_ta.id_semester', $id_semester)
            ->first();
    }





    public function getJabatan2($nidn)
    {

        return $this
            ->join('jabatan', 'jabatan.kd_jabatan = pegawai.kd_jabatan')
            ->where('pegawai.nidn', $nidn)
            ->get()->getResultArray();
    }


    public function getDosenUjian($nuptk)
    {
        return $this
            ->where('nuptk', $nuptk)
            ->first();
    }

    public function getDosen($nidn)
    {
        return $this
            ->where('nidn', $nidn)
            ->get()->getResultArray();
    }
    public function getDosenPA($nidn)
    {
        return $this
            ->where('nidn', $nidn)
            ->get()->getResultArray();
    }
    public function getGolongan($nidn)
    {
        return $this
            ->join('golongan', 'golongan.id_golongan = pegawai.id_golongan')
            ->where('pegawai.nidn', $nidn)
            ->get()->getResultArray();
    }
    public function getTtdProgramStudi($ttd_program_studi)
    {
        return $this
            ->join('prodi', 'pegawai.nidn = prodi.ttd_program_studi')
            ->where('pegawai.nidn', $ttd_program_studi)
            ->get()->getResultArray();
    }
    // public function FilterProdi($kode_program_studi)
    // {
    //     return $this
    //         ->join('prodi', 'kode_program_studi.prodi = kode_program_studi.matkul')
    //         ->where("'matkul.kode_program_studi = $kode_program_studi'")
    //         ->orderBy('matkul.kode_mata_kuliah')
    //         ->get()->getResultArray();
    // }

    public function FilterProdi($id_prodi3)
    {
        return $this
            ->where("id_prodi = '$id_prodi3'")
            ->orderBy('nama_program_studi_internal')
            ->get()->getResultArray();
    }

    // function get_content($url, $post = '')
    // {
    //     $client = \Config\Services::curlrequest();

    //     $headers = [
    //         'Content-Type' => 'application/json',
    //         'Accept-Encoding' => 'gzip, deflate',
    //         'Cache-Control' => 'max-age=0',
    //         'Connection' => 'keep-alive',
    //         'Accept-Language' => 'en-US,en;q=0.8,id;q=0.6',
    //     ];

    //     $options = [
    //         'headers' => $headers,
    //         'http_errors' => false,
    //         'verify' => false,
    //         'form_params' => $post ? json_decode($post, true) : []
    //     ];

    //     $response = $client->post($url, $options);
    //     return $response->getBody();
    // }
    // public function getKampus()
    // {
    //     return $this
    //         ->where("'kd_kampus' = '1'")

    //         ->get()->getResultArray();
    // }

    // public function getCountRuang($kd_kampus)
    // {

    //     return $this
    //         ->join('ruang', 'kampus.kd_kampus = ruang.kd_kampus')
    //         ->where("ruang.kd_kampus='$kd_kampus' ")->countAllResults();
    // }

    public function getData($id)
    {
        if (!$id) {
            throw PageNotFoundException::forPageNotFound();
        }
        return  $this
            ->join('unit', 'unit.id_unit = pegawai.id_unit')
            ->join('jabatan', 'jabatan.kd_jabatan = pegawai.kd_jabatan')
            ->join('golongan', 'golongan.id_golongan = pegawai.id_golongan')
            ->join('jafa', 'jafa.id_jafa = pegawai.id_jafa')
            ->orderBy('pegawai.id_pegawai')->where('pegawai.id_pegawai', $id)->first();
    }

    public function getRekPegawai($id)
    {
        if (!$id) {
            throw PageNotFoundException::forPageNotFound();
        }
        return  $this
            ->join('bank', 'bank.kd_bank = pegawai.kd_bank')
            ->orderBy('pegawai.id_pegawai')->where('pegawai.id_pegawai', $id)->first();
    }

    public function getKdBank($nik_pegawai)
    {
        return  $this
            ->join('bank', 'bank.kd_bank = pegawai.kd_bank')
            ->orderBy('pegawai.nik_pegawai')->where('pegawai.nik_pegawai', $nik_pegawai)->first();
    }


    public function simpanPegawai($data)
    {
        return $this->insert($data);
    }

    public function ubahPegawai($data, $id)
    {
        return $this->db->table($this->table)->where('id_pegawai', $id)->update($data);
    }


    public function ubahPendampingKajian($data, $nik_pegawai)
    {
        return $this->db->table($this->table)->where('nik_pegawai', $nik_pegawai)->update($data);
    }
}























        //tampil data all dengan datatables


        //return $this->join('kategori', 'kategori.id_kategori = news.id_kategori')->groupBy('news.status')->orderBy('news.id', 'DESC')->paginate('10', 'news'),
        //return $this->join('kategori', 'kategori.id_kategori = news.id_kategori')->orderBy('news.id', 'DESC')->paginate('10', 'news'), //tampil data dengan pagination bawaan ci4
        //return $this->groupBy('status')->orderBy('id', 'DESC')->paginate('10', 'news'),