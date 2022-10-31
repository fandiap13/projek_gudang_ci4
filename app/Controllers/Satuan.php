<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelSatuan;
use \Hermawan\DataTables\DataTable;

class Satuan extends BaseController
{
    public function __construct()
    {
        $this->satuan = new ModelSatuan();
    }

    public function index()
    {
        return view('satuan/viewsatuan_new');
    }

    public function listData()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('satuan')
                ->select('satid, satnama');  // field yang boleh ditampilkan pada datatable

            // return DataTable::of($builder)->toJson();
            return DataTable::of($builder)
                ->add('aksi', function ($row) {
                    $token = csrf_field();
                    return "
                    <button type='button' class='btn btn-sm btn-info' title='Edit Data' onclick='edit(\"$row->satid\");'><i class='fa fa-edit'></i></button>
                    <form action='/satuan/hapus/$row->satid' method='post' style='display: inline;' onsubmit='return hapus(\"$row->satid\");'>
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
    //     $tombolcari = $this->request->getVar('tombolcari');

    //     if (isset($tombolcari)) {
    //         $cari = $this->request->getVar('cari');
    //         session()->set('cari_satuan', $cari);
    //         redirect()->to('/satuan/index');
    //     } else {
    //         $cari = session()->get('cari_satuan');
    //     }

    //     $dataSatuan = $cari ? $this->satuan->cariData($cari)->paginate(5, 'satuan') : $this->satuan->paginate(5, 'satuan');

    //     $nohalaman = $this->request->getVar('page_satuan') ? $this->request->getVar('page_satuan') : 1;

    //     $data = [
    //         'tampildata' => $dataSatuan,
    //         'pager' => $this->satuan->pager,
    //         'nohalaman' => $nohalaman,
    //         'cari' => $cari
    //     ];
    //     return view('satuan/viewsatuan', $data);
    // }

    public function formtambah()
    {
        return view('satuan/formtambah');
    }

    public function simpandata()
    {
        $namasatuan = $this->request->getVar('namasatuan');
        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong !'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaSatuan' => '<div class="alert alert-danger mt-2">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/formtambah');
        } else {
            $this->satuan->insert([
                'satnama' => $namasatuan
            ]);
            $pesan = [
                'suksess' => '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data Satuan Berhasil Ditambahkan...
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/index');
        }
    }

    public function formedit($id)
    {
        $rowData = $this->satuan->find($id);
        if ($rowData) {
            $data = [
                'id' => $id,
                'nama' => $rowData['satnama']
            ];
            return view('/satuan/formedit', $data);
        } else {
            exit("Data tidak ditemukan");
        }
    }

    public function updatedata()
    {
        $idsatuan = $this->request->getVar('idsatuan');
        $namasatuan = $this->request->getVar('namasatuan');
        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'namasatuan' => [
                'rules' => 'required',
                'label' => 'Nama satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong !'
                ]
            ]
        ]);

        if (!$valid) {
            $pesan = [
                'errorNamaSatuan' => '<div class="alert alert-danger mt-2">' . $validation->getError() . '</div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/formedit/' . $idsatuan);
        } else {
            $this->satuan->update($idsatuan, [
                'satnama' => $namasatuan
            ]);
            $pesan = [
                'suksess' => '
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data satuan Berhasil Diupdate...
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/index');
        }
    }

    public function hapus($id)
    {
        $rowData = $this->satuan->find($id);
        if ($rowData) {
            $this->satuan->delete($id);
            $pesan = [
                'suksess' => '
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data satuan Berhasil Dihapus...
                </div>'
            ];
            session()->setFlashdata($pesan);
            return redirect()->to('/satuan/index');
        } else {
            exit("Data tidak ditemukan");
        }
    }
}
