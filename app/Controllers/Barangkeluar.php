<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\ModelBarangKeluar;
use App\Models\Modeldatabarang;
use App\Models\ModelDataBarangKeluar;
use App\Models\ModelDetailBarangKeluar;
use App\Models\ModelPelanggan;
use App\Models\ModelTempBarangKeluar;
use Config\Services;

class Barangkeluar extends BaseController
{
    private function buatFaktur()
    {
        $tanggalSekarang = date('Y-m-d');
        $modelBarangKeluar = new ModelBarangKeluar();

        $cek = $modelBarangKeluar->noFaktur($tanggalSekarang)->getNumRows();
        if ($cek == 0) {
            $noFaktur = date('dmy', strtotime($tanggalSekarang)) . '0001';
        } else {
            $hasil = $modelBarangKeluar->noFaktur($tanggalSekarang)->getRowArray();
            $data = $hasil['nofaktur'];
            $lastNoUrut = substr($data, -4);
            $nextNoUrut = intval($lastNoUrut) + 1;
            $noFaktur = date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);
        }
        return $noFaktur;
    }

    public function buatNoFaktur()
    {
        $tanggalSekarang = $this->request->getPost('tanggal');
        $modelBarangKeluar = new ModelBarangKeluar();

        $cek = $modelBarangKeluar->noFaktur($tanggalSekarang)->getNumRows();
        if ($cek == 0) {
            $noFaktur = date('dmy', strtotime($tanggalSekarang)) . '0001';
        } else {
            $hasil = $modelBarangKeluar->noFaktur($tanggalSekarang)->getRowArray();
            $data = $hasil['nofaktur'];
            $lastNoUrut = substr($data, -4);
            $nextNoUrut = intval($lastNoUrut) + 1;
            $noFaktur = date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);
        }
        $json = [
            'nofaktur' => $noFaktur
        ];
        echo json_encode($json);
    }

    public function data()
    {
        return view('barangkeluar/viewdata');
    }

    public function input()
    {
        $data = [
            'nofaktur' => $this->buatFaktur()
        ];
        return view('barangkeluar/forminput', $data);
    }

    public function tampilDataTemp()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $modelTempBarangKeluar = new ModelTempBarangKeluar();
            $dataTemp = $modelTempBarangKeluar->tampilDataTemp($nofaktur);
            $data = [
                'tampildata' => $dataTemp
            ];
            $json = [
                'data' => view('barangkeluar/datatemp', $data)
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, tidak dapat diperoses');
        }
    }

    public function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kodebarang');
            $modelBarang = new Modelbarang();
            $cekData = $modelBarang->find($kodebarang);
            if ($cekData == null) {
                $json = [
                    'error' => 'Maaf data barang tidak ditemukan'
                ];
            } else {
                $data = [
                    'namabarang' => $cekData['brgnama'],
                    'hargajual' => $cekData['brgharga'],
                ];
                $json = [
                    'sukses' => $data
                ];
            }
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }

    public function simpanItem()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $namabarang = $this->request->getPost('namabarang');
            $kodebarang = $this->request->getPost('kodebarang');
            $jml = $this->request->getPost('jml');
            $hargajual = $this->request->getPost('hargajual');

            $modelTempBarangKeluar = new ModelTempBarangKeluar();
            $modelBarang = new Modelbarang();

            // mengecek stok
            $ambilDataBarang = $modelBarang->find($kodebarang);
            $stokBarang = $ambilDataBarang['brgstok'];
            if ($jml > intval($stokBarang)) {
                $json = [
                    'error' => 'Stok tidak mencukupi, sisa stok ' . $stokBarang
                ];
            } else {
                $modelTempBarangKeluar->insert([
                    'detfaktur' => $nofaktur,
                    'detbrgkode' => $kodebarang,
                    'dethargajual' => $hargajual,
                    'detjml' => $jml,
                    'detsubtotal' => intval($hargajual * $jml)
                ]);
                $json = [
                    'sukses' => 'Item berhasil ditambahkan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }

    public function hapusItem()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $modelTempBarang = new ModelTempBarangKeluar();
            $modelTempBarang->delete($id);
            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }

    public function modalCariBarang()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('barangkeluar/modalcaribarang')
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }

    public function listDataBarang()
    {
        $request = Services::request();
        $datamodel = new Modeldatabarang($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolPilih = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $list->brgkode . "')\">Pilih</button>";

                $row[] = $no;
                $row[] = $list->brgkode;
                $row[] = $list->brgnama;
                $row[] = number_format($list->brgharga, 0, ",", ".");
                $row[] = number_format($list->brgstok, 0, ",", ".");
                $row[] = $tombolPilih;
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

    public function modalPembayaran()
    {
        $nofaktur = $this->request->getPost('nofaktur');
        $tglfaktur = $this->request->getPost('tglfaktur');
        $idpelanggan = $this->request->getPost('idpelanggan');
        $totalharga = $this->request->getPost('totalharga');

        $modelTempBarangKeluar = new ModelTempBarangKeluar();
        $cekData = $modelTempBarangKeluar->tampilDataTemp($nofaktur);
        if ($cekData->getNumRows() > 0) {
            $data = [
                'nofaktur' => $nofaktur,
                'totalharga' => $totalharga,
                'tglfaktur' => $tglfaktur,
                'idpelanggan' => $idpelanggan,
            ];
            $json = [
                'data' => view('barangkeluar/modalpembayaran', $data)
            ];
        } else {
            $json = [
                'error' => 'Maaf item belum ada..'
            ];
        }
        echo json_encode($json);
    }

    public function simpanPembayaran()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nomorfaktur');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $idpelanggan = $this->request->getPost('idpelanggan');
            $jumlahuang = str_replace(".", "", $this->request->getPost('jumlahuang'));
            $sisauang = str_replace(".", "", $this->request->getPost('sisauang'));
            $totalbayar = str_replace(".", "", $this->request->getPost('totalbayar'));

            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'jumlahuang' => [
                    'rules' => 'required',
                    'label' => 'Jumlah Uang',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong'
                    ]
                ]
            ]);

            if (!$valid) {
                $json = [
                    'error' => [
                        'errorJumlahUang' => $validation->getError('jumlahuang')
                    ]
                ];
            } else {
                // insert ke table barangkeluar
                $modelBarangKeluar = new ModelBarangKeluar();
                $modelBarangKeluar->insert([
                    'faktur' => $nofaktur,
                    'tglfaktur' => $tglfaktur,
                    'idpel' => $idpelanggan,
                    'totalbayar' => $totalbayar,
                    'jumlahuang' => $jumlahuang,
                    'sisauang' => $sisauang,
                ]);

                $modelTempBarangKeluar = new ModelTempBarangKeluar();
                $dataTemp = $modelTempBarangKeluar->getWhere(['detfaktur' => $nofaktur]);

                // mengurangi stok barang
                $modelBarang = new Modelbarang();
                foreach ($dataTemp->getResultArray() as $r) :
                    $dataBarang = $modelBarang->find($r['detbrgkode']);
                    $modelBarang->update($r['detbrgkode'], [
                        'brgstok' => intval($dataBarang['brgstok']) - intval($r['detjml'])
                    ]);
                endforeach;

                // insert data ke detail_barangkeluar
                $fieldDetail = [];
                foreach ($dataTemp->getResultArray() as $row) :
                    $fieldDetail[] = [
                        'detfaktur' => $row['detfaktur'],
                        'detbrgkode' => $row['detbrgkode'],
                        'dethargajual' => $row['dethargajual'],
                        'detjml' => $row['detjml'],
                        'detsubtotal' => $row['detsubtotal'],
                    ];
                endforeach;
                $modelDetail = new ModelDetailBarangKeluar();
                $modelDetail->insertBatch($fieldDetail);

                // meghapus data tabel temp_barangkeluar berdasarkan nofaktur
                $modelTempBarangKeluar->hapusData($nofaktur);

                $json = [
                    'sukses' => 'Transaksi berhasil disimpan',
                    'cetakfaktur' => site_url('barangkeluar/cetakfaktur/' . $nofaktur)
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf, tidak dapat diperoses');
        }
    }

    public function cetakfaktur($faktur)
    {
        $modelBarangKeluar = new ModelBarangKeluar();
        $modelDetail = new ModelDetailBarangKeluar();
        $modelPelanggan = new ModelPelanggan();

        $cekData = $modelBarangKeluar->find($faktur);
        // mencari data pelanggan yang melakukan transaksi
        $dataPelanggan = $modelPelanggan->find($cekData['idpel']);

        $namaPelanggan = ($dataPelanggan != null) ? $dataPelanggan['pelnama'] : '-';

        if ($cekData != null) {
            $data = [
                'faktur' => $faktur,
                'tanggal' => $cekData['tglfaktur'],
                'jumlahuang' => $cekData['jumlahuang'],
                'sisauang' => $cekData['sisauang'],
                'namapelanggan' => $namaPelanggan,
                'detailbarang' => $modelDetail->tampilDataTemp($faktur)
            ];
            return view('barangkeluar/cetakfaktur', $data);
        } else {
            return redirect()->to(site_url('barangkeluar/input'));
        }
    }

    public function listData()
    {
        // data yang didapat
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $request = Services::request();
        $datamodel = new ModelDataBarangKeluar($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables($tglawal, $tglakhir);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolCetak = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"cetak('" . $list->faktur . "')\"><i class=\"fa fa-print\"></i></button>";
                $tombolHapus = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->faktur . "')\"><i class=\"fa fa-trash-alt\"></i></button>";
                $tombolEdit = "<button type=\"button\" class=\"btn btn-sm btn-primary\" onclick=\"edit('" . $list->faktur . "')\"><i class=\"fa fa-edit\"></i></button>";

                $row[] = $no;
                $row[] = $list->faktur;
                $row[] = $list->tglfaktur;
                $row[] = $list->pelnama;
                $row[] = number_format($list->totalbayar, 0, ",", ".");
                $row[] = $tombolCetak . ' ' . $tombolHapus . ' ' . $tombolEdit;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $datamodel->count_filtered($tglawal, $tglakhir),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    public function hapusTransaksi()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $modelBarangKeluar = new ModelBarangKeluar();

            // ubah stok barang
            // menambah stok barang jika transaksi dihapus
            $modelDetail = new ModelDetailBarangKeluar();
            $modelBarang = new Modelbarang();
            $dataDetail = $modelDetail->tampilDataTemp($faktur);
            foreach ($dataDetail->getResultArray() as $r) :
                $dataBarang = $modelBarang->find($r['detbrgkode']);
                $modelBarang->update($r['detbrgkode'], [
                    'brgstok' => intval($dataBarang['brgstok']) + intval($r['detjml']),
                ]);
            endforeach;

            // hapus detail_barangkeluar
            $db = \Config\Database::connect();
            $db->table('detail_barangkeluar')->delete(['detfaktur' => $faktur]);

            // hapus barangkeluar
            $modelBarangKeluar->delete($faktur);

            $json = [
                'sukses' => 'Transaksi berhasil dihapus'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }

    public function edit($faktur)
    {
        $modelBarangKeluar = new ModelBarangKeluar();
        $modelPelanggan = new ModelPelanggan();
        $rowData = $modelBarangKeluar->find($faktur);
        $rowPelanggan = $modelPelanggan->find($rowData['idpel']);

        if ($rowPelanggan == null) {
            $pelanggan = '';
        } else {
            $pelanggan = $rowPelanggan['pelnama'];
        }

        if ($rowData != null) {
            $data = [
                'nofaktur' => $faktur,
                'tanggal' => $rowData['tglfaktur'],
                'namapelanggan' => $pelanggan,
            ];
            return view('barangkeluar/formedit', $data);
        } else {
            return redirect()->to('/barangkeluar/data');
        }
    }

    public function ambilTotalHarga()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $modelDetail = new ModelDetailBarangKeluar();
            $totalharga = $modelDetail->ambilTotalHarga($nofaktur);
            $json = [
                'totalharga' => "Rp. " . number_format($totalharga, 0, ",", ".")
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, tidak dapat diperoses');
        }
    }

    public function tampilDataDetail()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $modelDetail = new ModelDetailBarangKeluar();
            $dataDetail = $modelDetail->tampilDataTemp($nofaktur);
            $data = [
                'tampildata' => $dataDetail
            ];
            $json = [
                'data' => view('barangkeluar/datadetail', $data)
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, tidak dapat diperoses');
        }
    }

    public function hapusItemDetail()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $modelDetail = new ModelDetailBarangKeluar();
            $modelBarangKeluar = new ModelBarangKeluar();
            $modelBarang = new Modelbarang();

            $rowData = $modelDetail->find($id);
            $noFaktur = $rowData['detfaktur'];

            $modelDetail->delete($id);
            $totalHarga = $modelDetail->ambilTotalHarga($noFaktur);

            // update jumlah barang
            $dataBarang = $modelBarang->find($rowData['detbrgkode']);
            $modelBarang->update($rowData['detbrgkode'], [
                'brgstok' => $dataBarang['brgstok'] + $rowData['detjml']
            ]);

            // lakukan update total di table barangkeluar
            $modelBarangKeluar->update($noFaktur, [
                'totalbayar' => $totalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }

    public function editItem()
    {
        if ($this->request->isAJAX()) {
            $iddetail = $this->request->getPost('iddetail');
            $jml = $this->request->getPost('jml');

            $modelDetail = new ModelDetailBarangKeluar();
            $modelBarangKeluar = new ModelBarangKeluar();
            $modelBarang = new Modelbarang();

            $rowData = $modelDetail->find($iddetail);
            $noFaktur = $rowData['detfaktur'];
            $hargaJual = $rowData['dethargajual'];
            $kodeBarang = $rowData['detbrgkode'];
            $jumlahBarangLama = $rowData['detjml'];

            // update pada tabel detail
            $modelDetail->update($iddetail, [
                'detjml' => $jml,
                'detsubtotal' => intval($hargaJual) * $jml
            ]);

            // ubah stok barang
            $dataBarang = $modelBarang->find($kodeBarang);
            $modelBarang->update($kodeBarang, [
                'brgstok' => intval(($dataBarang['brgstok'] + $jumlahBarangLama) - $jml)
            ]);

            // ambil total harga
            $totalHarga = $modelDetail->ambilTotalHarga($noFaktur);
            // update total bayar barangkeluar
            $modelBarangKeluar->update($noFaktur, [
                'totalbayar' => $totalHarga,
            ]);

            $json = [
                'sukses' => 'Item berhasil diupdate'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }

    public function simpanItemDetail()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $namabarang = $this->request->getPost('namabarang');
            $kodebarang = $this->request->getPost('kodebarang');
            $jml = $this->request->getPost('jml');
            $hargajual = $this->request->getPost('hargajual');

            $modelDetail = new ModelDetailBarangKeluar();
            $modelBarang = new Modelbarang();
            $modelBarangKeluar = new ModelBarangKeluar();

            // mengecek stok
            $ambilDataBarang = $modelBarang->find($kodebarang);
            $stokBarang = $ambilDataBarang['brgstok'];
            if ($jml > intval($stokBarang)) {
                $json = [
                    'error' => 'Stok tidak mencukupi, sisa stok ' . $stokBarang
                ];
            } else {
                $modelDetail->insert([
                    'detfaktur' => $nofaktur,
                    'detbrgkode' => $kodebarang,
                    'dethargajual' => $hargajual,
                    'detjml' => $jml,
                    'detsubtotal' => intval($hargajual) * $jml
                ]);

                // ubah stok barang
                $modelBarang->update($kodebarang, [
                    'brgstok' => (intval($stokBarang)) - $jml
                ]);

                // ambil total harga
                $totalHarga = $modelDetail->ambilTotalHarga($nofaktur);
                // update total bayar barangkeluar
                $modelBarangKeluar->update($nofaktur, [
                    'totalbayar' => $totalHarga,
                ]);

                $json = [
                    'sukses' => 'Item berhasil ditambahkan'
                ];
            }
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak dapat diperoses');
        }
    }
}
