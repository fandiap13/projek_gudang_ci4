<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelkategori;
use \Hermawan\DataTables\DataTable;

class Kategori extends BaseController
{
    public function __construct()
    {
        $this->kategori = new Modelkategori();
    }

    public function index()
    {
        return view('kategori/viewkategori_new');
    }

    public function listData()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('kategori')
                ->select('katid, katnama');  // field yang boleh ditampilkan pada datatable

            return DataTable::of($builder)
                ->add('aksi', function ($row) {
                    $token = csrf_field();
                    return "
                    <button type='button' class='btn btn-sm btn-info' title='Edit Data' onclick='edit(\"$row->katid\");'><i class='fa fa-edit'></i></button>
                    <form action='/kategori/hapus/$row->katid' method='post' style='display: inline;' onsubmit='return hapus(\"$row->katid\");'>
                        $token
                        <input type='hidden' value='DELETE' name='_method'>
                        <button type='submit' class='btn btn-sm btn-danger' title='Hapus Data'><i class='fa fa-trash-alt'></i></button>
                    </form>
                    ";
                })
                ->addNumbering('nomor')
                ->toJson(true);
        } else {
            exit('Maaf, tidak dapat diperoses');
        }
    }

    // public function index()
    // {
    //     $tombolcari = $this->request->getPost('tombolcari');

    //     if (isset($tombolcari)) {
    //         $cari = $this->request->getPost('cari');
    //         session()->set('cari_kategori', $cari);
    //         redirect()->to('/kategori/index');
    //     } else {
    //         $cari = session()->get('cari_kategori');
    //     }

    //     $dataKategori = $cari ? $this->kategori->cariData($cari)->paginate(5, 'kategori') : $this->kategori->paginate(5, 'kategori');

    //     $nohalaman = $this->request->getVar('page_kategori') ? $this->request->getVar('page_kategori') : 1;
    //     $data = [
    //         'tampildata' => $dataKategori,
    //         'pager' => $this->kategori->pager,
    //         'nohalaman' => $nohalaman,
    //         'cari' => $cari
    //     ];
    //     return view('kategori/viewkategori', $data);
    // }

    public function formtambah()
    {
        return view('kategori/formtambah');
    }

    public function simpandata()
    {
        $namaKategori = $this->request->getVar('namakategori');
        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namakategori' => [
                'rules' => 'required',
                'label' => 'Nama Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong !'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaKategori' => '<div class="alert alert-danger mt-2">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/formtambah');
        } else {
            $this->kategori->insert([
                'katnama' => $namaKategori
            ]);
            $pesan = [
                'suksess' => '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data Kategori Berhasil Ditambahkan...
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/index');
        }
    }

    public function formedit($id)
    {
        $rowData = $this->kategori->find($id);
        if ($rowData) {
            $data = [
                'id' => $id,
                'nama' => $rowData['katnama']
            ];
            return view('/kategori/formedit', $data);
        } else {
            exit("Data tidak ditemukan");
        }
    }

    public function updatedata()
    {
        $idkategori = $this->request->getVar('idkategori');
        $namaKategori = $this->request->getVar('namakategori');
        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namakategori' => [
                'rules' => 'required',
                'label' => 'Nama Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong !'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaKategori' => '<div class="alert alert-danger mt-2">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/formedit/' . $idkategori);
        } else {
            $this->kategori->update($idkategori, [
                'katnama' => $namaKategori
            ]);
            $pesan = [
                'suksess' => '
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data Kategori Berhasil Diupdate...
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/index');
        }
    }

    public function hapus($id)
    {
        $rowData = $this->kategori->find($id);
        if ($rowData) {
            $this->kategori->delete($id);
            $pesan = [
                'suksess' => '
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data Kategori Berhasil Dihapus...
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/kategori/index');
        } else {
            exit("Data tidak ditemukan");
        }
    }
}
