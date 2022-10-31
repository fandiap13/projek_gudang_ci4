<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modeldatapelanggan;
use App\Models\ModelPelanggan;
use Config\Services;

class Pelanggan extends BaseController
{
    public function formtambah()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('pelanggan/modaltambah')
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }

    public function simpan()
    {
        $namaPelanggan = $this->request->getPost('namaPelanggan');
        $telp = $this->request->getPost('telp');

        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'namaPelanggan' => [
                'rules' => 'required',
                'label' => 'Nama Pelanggan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'telp' => [
                'rules' => 'required|is_unique[pelanggan.peltel]',
                'label' => 'Telepon/HP',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} tidak boleh ada yang sama',
                ]
            ]
        ]);

        if (!$valid) {
            $json = [
                'error' => [
                    'errorNamaPelanggan' => $validation->getError('namaPelanggan'),
                    'errorTelp' => $validation->getError('telp')
                ]
            ];
        } else {
            $modelPelanggan = new ModelPelanggan();
            $modelPelanggan->insert([
                'pelnama' => $namaPelanggan,
                'peltel' => $telp
            ]);

            $rowData = $modelPelanggan->ambilDataTerakhir()->getRowArray();

            $json = [
                'sukses' => 'Data Pelanggan berhasil disimpan, ambil data terakhir ?',
                'namaPelanggan' => $rowData['pelnama'],
                'idPelanggan' => $rowData['pelid']
            ];
        }
        echo json_encode($json);
    }

    public function modalData()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('pelanggan/modaldata')
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, tidak dapat diperoses');
        }
    }

    public function listData()
    {
        $request = Services::request();
        $datamodel = new Modeldatapelanggan($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolPilih = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $list->pelid . "','" . $list->pelnama . "')\">Pilih</button>";
                $tombolHapus = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->pelid . "','" . $list->pelnama . "')\">Hapus</button>";

                $row[] = $no;
                $row[] = $list->pelnama;
                $row[] = $list->peltel;
                $row[] = $tombolPilih . " " . $tombolHapus;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('pelid');
            $modelPelanggan = new ModelPelanggan();
            $modelPelanggan->delete($id);
            $json = [
                'sukses' => 'Data pelanggan berhasil dihapus..'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, tidak dapat diperoses');
        }
    }
}
