<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarang extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'brgkode';
    protected $allowedFields    = [
        'brgkode', 'brgnama', 'brgkatid', 'brgsatid', 'brgharga', 'brggambar', 'brgstok'
    ];

    public function tampildata()
    {
        return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid');
    }

    public function tampildata_cari($cari = null, $kategori = null)
    {
        if ($kategori == null) {
            return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->orlike('brgnama', $cari)->orlike('brgkode', $cari);
            exit;
        }
        if ($cari == null) {
            return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->where('katnama', $kategori);
            exit;
        }
        return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->orlike('brgnama', $cari)->orlike('brgkode', $cari)->where('katnama', $kategori);
    }

    public function tampildata_cari_detail($cari)
    {
        // $cari = '12';
        return $this->table('barang')->join('kategori', 'brgkatid=katid')->join('satuan', 'brgsatid=satid')->orlike('brgnama', $cari)->orlike('brgkode', $cari);
    }
}
