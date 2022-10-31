<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\Modelkategori;
use App\Models\ModelSatuan;

class Barang extends BaseController
{
    public function __construct()
    {
        $this->barang = new Modelbarang();
    }

    public function index()
    {
        $modelkategori = new Modelkategori();
        $modelsatuan = new ModelSatuan();

        $tombolcari = htmlentities(strip_tags(trim($this->request->getVar('tombolcari'))));
        if (isset($tombolcari)) {
            $cari = $this->request->getPost('cari');
            $cari_kategori = $this->request->getPost('kategori');
            $cari_satuan = $this->request->getPost('satuan');

            $simpan_session = [
                'cari_barang' => $cari,
                'c_kategori' => $cari_kategori,
                'c_satuan' => $cari_satuan
            ];

            session()->set($simpan_session);
            redirect()->to('/barang/index');
        } else {
            $cari = session()->get('cari_barang');
            $cari_kategori = session()->get('c_kategori');
            $cari_satuan = session()->get('c_barang');
        }

        $totaldata = $cari ? $this->barang->tampildata_cari($cari)->countAllResults() : $this->barang->tampildata()->countAllResults();

        $databarang = $cari ? $this->barang->tampildata_cari($cari)->paginate(10, 'barang') : $this->barang->tampildata()->paginate(10, 'barang');

        $nohalaman = $this->request->getVar('page_barang') ? $this->request->getVar('page_barang') : 1;
        $data = [
            'tampildata' => $databarang,
            'pager' => $this->barang->pager,
            'nohalaman' => $nohalaman,
            'cari' => $cari,
            'cari_kategori' => $cari_kategori,
            'cari_satuan' => $cari_satuan,
            'totaldata' => $totaldata,
            'datakategori' => $modelkategori->findAll(),
            'datasatuan' => $modelsatuan->findAll(),
        ];
        return view('barang/viewbarang', $data);
    }

    public function tambah()
    {
        $modelkategori = new Modelkategori();
        $modelsatuan = new ModelSatuan();
        $data = [
            'datakategori' => $modelkategori->findAll(),
            'datasatuan' => $modelsatuan->findAll()
        ];
        return view('barang/formtambah', $data);
    }

    public function simpandata()
    {
        $kodebarang = $this->request->getVar('kodebarang');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $harga = $this->request->getVar('harga');
        $stok = $this->request->getVar('stok');

        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'kodebarang' => [
                'rules' => 'required|is_unique[barang.brgkode]',
                'label' => 'Kode Barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} sudah ada...'
                ]
            ],
            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama Barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kategori' => [
                'rules' => 'required',
                'label' => 'Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'satuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric',
                'label' => 'Harga',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} hanya dalam bentuk angka !'
                ]
            ],
            'stok' => [
                'rules' => 'required|numeric',
                'label' => 'Stok',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} hanya dalam bentuk angka !'
                ]
            ],
            'gambar' => [
                'rules' => 'max_size[gambar,3024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/svg]',
                'label' => 'Gambar',
                'errors' => [
                    'mime_in' => '{field} harus berformat png/jpg/jpeg',
                    'is_image' => 'Yang anda upload bukan gambar',
                    'max_size' => '{field} max 3 Mb',
                ]
            ]
        ]);

        if (!$valid) {
            $ses_Pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-ban"></i> Error!</h5>
                  ' . $validation->listErrors() . '
                </div>'
            ];

            session()->setFlashdata($ses_Pesan);
            return redirect()->to('/barang/tambah')->withInput();
        } else {
            $gambar = $_FILES['gambar']['name'];

            if ($gambar != NULL) {
                $namaFileGambar = $kodebarang;
                $fileGambar = $this->request->getFile('gambar');
                $fileGambar->move('upload', $namaFileGambar . '.' . $fileGambar->getExtension());

                $pathGambar = 'upload/' . $fileGambar->getName();
            } else {
                $pathGambar = '';
            }
            $this->barang->insert([
                'brgkode' => $kodebarang,
                'brgnama' => $namabarang,
                'brgkatid' => $kategori,
                'brgsatid' => $satuan,
                'brgharga' => $harga,
                'brgstok' => $stok,
                'brggambar' => $pathGambar,
            ]);

            $pesan_suksess = [
                'suksess' => '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data Barang dengan kode <Strong>' . $kodebarang . '</Strong> berhasil disimpan
                </div>'
            ];
            session()->setFlashdata($pesan_suksess);
            return redirect()->to('/barang/tambah');
        }
    }

    public function edit($kode)
    {
        $cekData = $this->barang->find($kode);
        if ($cekData) {
            $modelkategori = new Modelkategori();
            $modelsatuan = new ModelSatuan();
            $data = [
                'kodebarang' => $cekData['brgkode'],
                'namabarang' => $cekData['brgnama'],
                'kategori' => $cekData['brgkatid'],
                'satuan' => $cekData['brgsatid'],
                'harga' => $cekData['brgharga'],
                'stok' => $cekData['brgstok'],
                'datakategori' => $modelkategori->findAll(),
                'datasatuan' => $modelsatuan->findAll(),
                'gambar' => $cekData['brggambar'],
            ];
            return view('barang/formedit', $data);
        } else {
            $pesan_error = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-ban"></i> Error!</h5>
                  Data barang tidak ditemukan...
                </div>'
            ];

            session()->setFlashdata($pesan_error);
            return redirect()->to('/barang/index');
        }
    }

    public function updatedata()
    {
        $kodebarang = $this->request->getVar('kodebarang');
        $namabarang = $this->request->getVar('namabarang');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $harga = $this->request->getVar('harga');
        $stok = $this->request->getVar('stok');

        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'namabarang' => [
                'rules' => 'required',
                'label' => 'Nama Barang',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kategori' => [
                'rules' => 'required',
                'label' => 'Kategori',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'satuan' => [
                'rules' => 'required',
                'label' => 'Satuan',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric',
                'label' => 'Harga',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} hanya dalam bentuk angka !'
                ]
            ],
            'stok' => [
                'rules' => 'required|numeric',
                'label' => 'Stok',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} hanya dalam bentuk angka !'
                ]
            ],
            'gambar' => [
                'rules' => 'max_size[gambar,3024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/svg]',
                'label' => 'Gambar',
                'errors' => [
                    'mime_in' => '{field} harus berformat png/jpg/jpeg',
                    'is_image' => 'Yang anda upload bukan gambar',
                    'max_size' => '{field} max 3 Mb',
                ]
            ]
        ]);

        if (!$valid) {
            $ses_Pesan = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-ban"></i> Error!</h5>
                  ' . $validation->listErrors() . '
                </div>'
            ];

            session()->setFlashdata($ses_Pesan);
            return redirect()->to('/barang/edit/' . $kodebarang)->withInput();
        } else {
            $cekData = $this->barang->find($kodebarang);
            $pathGambarLama = $cekData['brggambar'];
            $gambar = $_FILES['gambar']['name'];

            if ($gambar != NULL) {
                ($pathGambarLama == '' || $pathGambarLama == NULL) ? '' : unlink($pathGambarLama);

                $namaFileGambar = $kodebarang;
                $fileGambar = $this->request->getFile('gambar');
                $fileGambar->move('upload', $namaFileGambar . '.' . $fileGambar->getExtension());

                $pathGambar = 'upload/' . $fileGambar->getName();
            } else {
                $pathGambar = $pathGambarLama;
            }
            $this->barang->update($kodebarang, [
                'brgnama' => $namabarang,
                'brgkatid' => $kategori,
                'brgsatid' => $satuan,
                'brgharga' => $harga,
                'brgstok' => $stok,
                'brggambar' => $pathGambar,
            ]);

            $pesan_suksess = [
                'suksess' => '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data Barang dengan kode <Strong>' . $kodebarang . '</Strong> berhasil diupdate
                </div>'
            ];
            session()->setFlashdata($pesan_suksess);
            return redirect()->to('/barang/index');
        }
    }

    public function hapus($kode)
    {
        $cekData = $this->barang->find($kode);
        if ($cekData) {
            $patchGambarLama = $cekData['brggambar'];
            unlink($patchGambarLama);
            
            $this->barang->delete($kode);
            $pesan_suksess = [
                'suksess' => '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
                  Data Barang dengan kode <Strong>' . $kode . '</Strong> berhasil dihapus
                </div>'
            ];
            session()->setFlashdata($pesan_suksess);
            return redirect()->to('/barang/index');
        } else {
            $pesan_error = [
                'error' => '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-ban"></i> Error!</h5>
                  Data barang tidak ditemukan...
                </div>'
            ];

            session()->setFlashdata($pesan_error);
            return redirect()->to('/barang/index');
        }
    }
}
