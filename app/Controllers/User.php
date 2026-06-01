<?php

namespace App\Controllers;

use App\Models\KategoriUser_model;
use App\Models\User_model;
use App\Controllers\BaseController;

class User extends BaseController
{
    protected $user;
    protected $kategori;
    public function __construct()
    {

        $this->user = new User_model();
        $this->kategori = new KategoriUser_model();
    }
    public function index()

    {
        $data['user'] = $this->user->getAll();

      //  $data['total'] = getGlobalData();
        return view('admin/user_tampil_view', $data);
    }
    public function new()
    {
        $data['users'] = $this->kategori->getKategoriUser();

       // $data['total'] = getGlobalData();
        return view('admin/user_tambah_view', $data);
    }
    public function create()
    {
        if (!$this->validate([
            'username' => [
                'rules' => 'required|min_length[4]|max_length[20]|is_unique[user.username]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 20 Karakter',
                    'is_unique' => 'Username sudah digunakan sebelumnya'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
            'nama_user' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $data =  [
            'username' => $this->request->getVar('username'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),

            'nama_user' => $this->request->getVar('nama_user'),
            'id_kategori_user' => $this->request->getVar('id_kategori_user'),

        ];

        $this->user->tambahUser($data);
        return redirect()->to('user')->with('success', 'Data berhasil ditambahkan!');
    }


    public function edit($id)

    {
        $data['user'] = $this->user->getIdUser($id);
        $data['users'] = $this->kategori->getKategoriUser();

       // $data['total'] = getGlobalData();
        return view('admin/user_edit_view', $data);
    }
    public function update()
    {
        if (!$this->validate([
            'username' => [
                'rules' => 'required|min_length[4]|max_length[20]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 20 Karakter',

                ]
            ],

            'nama_user' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $id = $this->request->getPost('id_user');

        $data =  [
            'username' => $this->request->getVar('username'),
            'nama_user' => $this->request->getVar('nama_user'),
            'id_kategori_user' => $this->request->getVar('id_kategori_user')
        ];

        $this->user->editUser($data, $id);
        return redirect()->to('user')->with('success', 'Data berhasil diupdate!');
    }

    public function edit_password($id)

    {
        $data['user'] = $this->user->getIdUser($id);
        $data['users'] = $this->user->getNamaKategori($id);


       // $data['total'] = getGlobalData();

        return view('admin/user_edit_password_view', $data);
    }

    public function update_password()
    {
        if (!$this->validate([

            'password' => [
                'rules' => 'required|min_length[4]|max_length[20]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 20 Karakter',

                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $id = $this->request->getPost('id_user');

        $data =  [
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),

        ];

        $this->user->editUser($data, $id);
        return redirect()->to('user')->with('success', 'Data berhasil diupdate!');
    }


    public function delete($id)
    {

        //$data =  $this->user->find($id);
        // $file = $data['berkas'];
        //unlink('img/implementasi/' . $file);
        $this->user->delete($id);
        return redirect('user')->with('success', 'Data berhasil dihapus!');
    }


    public function import()


    {

        $file_excel = $this->request->getFile('fileexcel');



        if (!$file_excel) {

            //$data['total'] = getGlobalData();
            return view('admin/user_import_view'); //menampilkan form upload
        } else {

            $ext = $file_excel->getClientExtension();
            if ($ext == 'xls') {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $render->load($file_excel);
            $imports = $spreadsheet->getActiveSheet()->toArray();

            foreach ($imports as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $data = [

                    'username' => $value[1],
                    'password' => password_hash($value[2], PASSWORD_BCRYPT),
                    'nama_user' => $value[3],
                    'id_kategori_user' => $value[4],
                ];
                $this->user->insert($data);
            }
            $namaFile = $file_excel->getRandomName('fileexcel');
            $file_excel->move('img/import/', $namaFile);

            return redirect()->to('user')->with('success', 'Berhasil import excel');
        }


        //$data['total'] = getGlobalData();
        echo view('admin/user_import_view');
    }
    public function download()
    {
        $filePath = 'format/format_import_user.xlsx'; // direktori file yang akan didownload

        return $this->response->download($filePath, null);
    }
}
