<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\Modeltempbarangmasuk;
use App\Models\Modelbarangmasuk;
use App\Models\Modeldetailbarangmasuk;

class Barangmasuk extends BaseController
{
    public function index()
    {
        $data = [
            'nofaktur' => $this->buatFaktur()
        ];
        return view('barangmasuk/forminput', $data);
    }

    private function buatFaktur()
    {
        $tanggalSekarang = date('Y-m-d');
        $modelBarangMasuk = new Modelbarangmasuk();

        $cek = $modelBarangMasuk->noFaktur($tanggalSekarang)->getNumRows();
        if ($cek == 0) {
            $noFaktur = date('dmy', strtotime($tanggalSekarang)) . '0001';
        } else {
            $hasil = $modelBarangMasuk->noFaktur($tanggalSekarang)->getRowArray();
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
        $modelBarangMasuk = new Modelbarangmasuk();

        $cek = $modelBarangMasuk->noFaktur($tanggalSekarang)->getNumRows();
        if ($cek == 0) {
            $noFaktur = date('dmy', strtotime($tanggalSekarang)) . '0001';
        } else {
            $hasil = $modelBarangMasuk->noFaktur($tanggalSekarang)->getRowArray();
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

    public function dataTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelTemp = new Modeltempbarangmasuk();
            $data = [
                'datatemp' => $modelTemp->tampilDataTemp($faktur),
            ];

            $json = [
                'data' => view('barangmasuk/datatemp', $data)
            ];
            // mengirim data menjadi json
            echo json_encode($json);
        } else {
            exit('Maaf Tidak Dapat Diperoses');
        }
    }

    public function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kodebarang');
            $modelBarang = new Modelbarang();
            $ambilData = $modelBarang->find($kodebarang);

            if ($ambilData == NULL) {
                $json = [
                    'error' => 'Data barang tidak ditemukan...'
                ];
            } else {
                $data = [
                    'namabarang' => $ambilData['brgnama'],
                    'hargajual' => $ambilData['brgharga'],
                ];

                $json = [
                    'sukses' => $data
                ];
            }
            echo json_encode($json);
        } else {
            exit('Maaf Tidak Dapat Diperoses');
        }
    }

    public function simpanTemp()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kdbarang = $this->request->getPost('kdbarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelTempBarang = new Modeltempbarangmasuk();
            $modelTempBarang->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kdbarang,
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah * $hargabeli)
            ]);
            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf Tidak Dapat Diperoses');
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $modelTempBarang = new Modeltempbarangmasuk();
            $modelTempBarang->delete($id);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf Tidak Dapat Diperoses');
        }
    }

    public function cariDataBarang()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('barangmasuk/modalcaribarang')
            ];
            echo json_encode($json);
        } else {
            exit('Maaf Tidak Dapat Diperoses');
        }
    }

    public function detailCariBarang()
    {
        if ($this->request->isAJAX()) {
            $cari = $this->request->getPost('cari');
            $modelBarang = new Modelbarang();
            $data = $modelBarang->tampildata_cari_detail($cari)->get();
            if ($data !== null) {
                $json = [
                    'datadetailbarang' => view('barangmasuk/detaildatabarang', [
                        'tampildata' => $data
                    ])
                ];
                echo json_encode($json);
            }
        } else {
            exit('Maaf Tidak Dapat Diperoses');
        }
    }

    public function selesaiTransaksi()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $tglfaktur = $this->request->getPost('tglfaktur');

            $modelTemp = new Modeltempbarangmasuk();
            $dataTemp = $modelTemp->getWhere(['detfaktur' => $faktur]);

            if ($dataTemp->getNumRows() == 0) {
                $json = [
                    'error' => 'Maaf, data item untuk faktur ini belum ada...'
                ];
            } else {
                // Jumlah Total dari sub total
                $modelBarangMasuk = new Modelbarangmasuk();

                $totalSubTotal = 0;
                foreach ($dataTemp->getResultArray() as $total) :
                    // perulangan, menjumlahkan semua total harga di table temp_barangmasuk
                    // $totalSubTotal = $totalSubTotal + intval($total['detsubtotal']);
                    // intval = mengonvert nilai angka pada field table 
                    $totalSubTotal += intval($total['detsubtotal']);
                endforeach;

                // SImpan ke tabel barang masuk
                $modelBarangMasuk->insert([
                    'faktur' => $faktur,
                    'tglfaktur' => $tglfaktur,
                    'totalharga' => $totalSubTotal
                ]);

                // Simpan Ke tabel detail_barangmasuk
                $medelDetailBarangMasuk = new Modeldetailbarangmasuk();
                foreach ($dataTemp->getResultArray() as $row) :
                    $medelDetailBarangMasuk->insert([
                        'detfaktur' => $row['detfaktur'],
                        'detbrgkode' => $row['detbrgkode'],
                        'dethargamasuk' => $row['dethargamasuk'],
                        'dethargajual' => $row['dethargajual'],
                        'detjml' => $row['detjml'],
                        'detsubtotal' => $row['detsubtotal'],
                    ]);

                    // menambah jumlah barang setelah input detail barang masuk
                    $modelbarang = new Modelbarang();
                    $barang = $modelbarang->find($row['detbrgkode']);
                    $modelbarang->update($row['detbrgkode'], [
                        'brgstok' => intval($barang['brgstok'] + $row['detjml'])
                    ]);
                endforeach;

                // Kosongkan/hapus data yang ada di tabel temp_barangmasuk
                $modelTemp->emptyTable();

                $json = [
                    'sukses' => 'Transaksi berhasil disimpan'
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf Tidak Dapat Diperoses');
        }
    }

    public function data()
    {
        $tombolcari = $this->request->getPost('tombolcari');
        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            session()->set('cari_faktur', $cari);
            redirect()->to('/barangmasuk/data');
        } else {
            $cari = session()->get('cari_faktur');
        }
        $modelBarangMasuk = new Modelbarangmasuk();
        $totaldata = $cari ? $modelBarangMasuk->tampildata_cari($cari)->countAllResults() : $modelBarangMasuk->countAllResults();

        $databarangmasuk = $cari ? $modelBarangMasuk->tampildata_cari($cari)->paginate(10, 'barangmasuk') : $modelBarangMasuk->paginate(10, 'barangmasuk');

        $nohalaman = $this->request->getVar('page_barangmasuk') ? $this->request->getVar('page_barangmasuk') : 1;
        $data = [
            'tampildata' => $databarangmasuk,
            'pager' => $modelBarangMasuk->pager,
            'nohalaman' => $nohalaman,
            'cari' => $cari,
            'totaldata' => $totaldata
        ];
        return view('barangmasuk/viewdata', $data);
    }

    public function detailItem()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $modeDetail = new Modeldetailbarangmasuk();
            $dataDetail = $modeDetail->dataDetail($faktur);

            $data = [
                'tampildatadetail' => $dataDetail
            ];

            $json = [
                'data' => view('barangmasuk/modaldetailitem', $data)
            ];
            echo json_encode($json);
        } else {
            exit("Maaf, Tidak dapat diperoses");
        }
    }

    public function edit($faktur)
    {
        $modelBarangMasuk = new Modelbarangmasuk();
        $cekFaktur = $modelBarangMasuk->cekFaktur($faktur);
        if ($cekFaktur->getNumRows() > 0) {
            $row = $cekFaktur->getRowArray();
            $data = [
                'nofaktur' => $row['faktur'],
                'tanggal' => $row['tglfaktur'],
            ];
            return view('barangmasuk/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function dataDetail()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();

            $data = [
                'datadetail' => $modelDetail->dataDetail($faktur),
            ];

            $totalHargaFaktur = number_format($modelDetail->ambilTotalHarga($faktur), 0, ",", ".");

            $json = [
                'data' => view('barangmasuk/datadetail', $data),
                'totalharga' => $totalHargaFaktur
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, tidak dapat diperoses');
        }
    }

    public function editItem()
    {
        if ($this->request->isAJAX()) {
            $iddetail = $this->request->getPost('iddetail');
            $modelDetail = new Modeldetailbarangmasuk();

            $ambilData = $modelDetail->ambilDetailBerdasarkanID($iddetail);

            $row = $ambilData->getRowArray();
            $data = [
                'kodebarang' => $row['detbrgkode'],
                'namabarang' => $row['brgnama'],
                'hargajual' => $row['dethargajual'],
                'hargabeli' => $row['dethargamasuk'],
                'jumlah' => $row['detjml'],
            ];

            $json = [
                'sukses' => $data
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak Dapat Diperoses');
        }
    }

    public function simpanDetail()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kdbarang = $this->request->getPost('kdbarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelDetailBarang = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();
            $modelBarang = new Modelbarang();

            $modelDetailBarang->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kdbarang,
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah * $hargabeli)
            ]);

            // menambah jumlah barang saat input/menambah barang masuk
            $barang = $modelBarang->find($kdbarang);
            $modelBarang->update($kdbarang, [
                'brgstok' => intval($barang['brgstok'] + $jumlah)
            ]);

            $ambilTotalHarga = $modelDetailBarang->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);
            $json = [
                'sukses' => 'Item berhasil ditambahkan',
                'totalharga' => $ambilTotalHarga
            ];
            echo json_encode($json);
        } else {
            exit('Maaf Tidak Dapat Diperoses');
        }
    }

    public function updateitem()
    {
        if ($this->request->isAJAX()) {
            $iddetail = $this->request->getPost('iddetail');
            $faktur = $this->request->getPost('faktur');
            $hargajual = $this->request->getPost('hargajual');
            $hargabeli = $this->request->getPost('hargabeli');
            $kdbarang = $this->request->getPost('kdbarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelDetailBarang = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();
            $modelBarang = new Modelbarang();

            $detailbarangLama = $modelDetailBarang->find($iddetail);

            // mengubah detail_barangmasuk
            $modelDetailBarang->update($iddetail, [
                'dethargamasuk' => $hargabeli,
                'dethargajual' => $hargajual,
                'detjml' => $jumlah,
                'detsubtotal' => intval($jumlah * $hargabeli)
            ]);

            // mengubah stok barang
            $barang = $modelBarang->find($kdbarang);
            $modelBarang->update($kdbarang, [
                'brgstok' => intval(($barang['brgstok'] - $detailbarangLama['detjml']) + $jumlah)
            ]);

            $ambilTotalHarga = $modelDetailBarang->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil diubah',
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak Dapat Diperoses');
        }
    }

    public function hapusItemDetail()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $faktur = $this->request->getPost('faktur');

            $modelDetailBarang = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();
            $modelBarang = new Modelbarang();

            // update jumlah barang
            $dataDetail = $modelDetailBarang->getWhere(['iddetail' => $id])->getRowArray();
            $dataBarang = $modelBarang->find($dataDetail['detbrgkode']);
            // mengurangi jumlah barang
            $modelBarang->update($dataDetail['detbrgkode'], [
                'brgstok' => intval($dataBarang['brgstok'] - $dataDetail['detjml'])
            ]);

            // hapus detail barang
            $modelDetailBarang->delete($id);

            // update total bayar dulu
            $ambilTotalHarga = $modelDetailBarang->ambilTotalHarga($faktur);
            $modelBarangMasuk->update($faktur, [
                'totalharga' => $ambilTotalHarga
            ]);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak Dapat Diperoses');
        }
    }

    public function hapusTransaksi()
    {
        if ($this->request->isAJAX()) {
            $faktur = $this->request->getPost('faktur');

            $db = \Config\Database::connect();
            $modelBarangMasuk = new Modelbarangmasuk();
            $modelBarang = new Modelbarang();
            $modelDetail = new Modeldetailbarangmasuk();

            // mengurangi jumlah barang
            $detailBarang = $modelDetail->getWhere(['detfaktur' => $faktur]);
            foreach ($detailBarang->getResultArray() as $d) {
                $dataBarang = $modelBarang->find($d['detbrgkode']);
                $kdbarang = $dataBarang['brgkode'];
                $modelBarang->update($kdbarang, [
                    'brgstok' => intval($dataBarang['brgstok'] - $d['detjml'])
                ]);
            }

            // menghapus detail_barangmasuk dan transaksi
            $db->table('detail_barangmasuk')->delete([
                'detfaktur' => $faktur
            ]);
            $modelBarangMasuk->delete($faktur);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf, Tidak Dapat Diperoses');
        }
    }
}
