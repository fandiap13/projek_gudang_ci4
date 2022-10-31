<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Edit Barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>

<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
  'class' => 'btn btn-warning',
  'onclick' => "location.href=('" . site_url('barang/index') . "')"
]); ?>

<?= $this->endSection('subJudul'); ?>

<?= $this->section('isi'); ?>

<?= form_open_multipart('barang/updatedata'); ?>
<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('suksess'); ?>
<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label">Kode Barang</label>
  <div class="col-sm-8">
    <input type="text" class="form-control" id="kodebarang" name="kodebarang" value="<?= $kodebarang; ?>" readonly>
  </div>
</div>


<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label">Nama Barang</label>
  <div class="col-sm-8">
    <input type="text" class="form-control" id="namabarang" name="namabarang" value="<?= (old('namabarang') ? old('namabarang') : $namabarang); ?>">
  </div>
</div>

<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label">Pilih Kategori</label>
  <div class="col-sm-4">
    <select name="kategori" id="kategori" class="form-control select2">
      <option value="">-- Pilih --</option>
      <?php foreach ($datakategori as $kat) : ?>
        <?php if (old('kategori')) { ?>
          <option value="<?= $kat['katid']; ?>" <?= (old('kategori') == $kat['katid'] ? 'selected' : ''); ?>><?= $kat['katnama']; ?></option>
        <?php } else { ?>
          <option value="<?= $kat['katid']; ?>" <?= ($kategori == $kat['katid'] ? 'selected' : ''); ?>><?= $kat['katnama']; ?></option>
        <?php } ?>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label">Pilih Satuan</label>
  <div class="col-sm-4">
    <select name="satuan" id="satuan" class="form-control select2">
      <option value="">-- Pilih --</option>
      <?php foreach ($datasatuan as $sat) : ?>
        <?php if (old('satuan')) { ?>
          <option value="<?= $sat['satid']; ?>" <?= (old('satuan') == $sat['satid'] ? 'selected' : ''); ?>><?= $sat['satnama']; ?></option>
        <?php } else { ?>
          <option value="<?= $sat['satid']; ?>" <?= ($satuan == $sat['satid'] ? 'selected' : ''); ?>><?= $sat['satnama']; ?></option>
        <?php } ?>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label">Harga</label>
  <div class="col-sm-8">
    <input type="number" class="form-control" id="harga" name="harga" value="<?= (old('hargabarang') ? old('hargabarang') : $harga); ?>">
  </div>
</div>

<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label">Stok</label>
  <div class="col-sm-8">
    <input type="number" class="form-control" id="stok" name="stok" value="<?= (old('stok') ? old('stok') : $stok); ?>">
  </div>
</div>

<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label">Gambar Yang Sudah Ada</label>
  <div class="col-sm-8">
    <img src="<?= base_url($gambar); ?>" alt="" class="img-thumbnail" style="width: 50%;" alt="Gambar Barang">
  </div>
</div>

<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label">Upload Gambar (<i>Jika Ada...</i>)</label>
  <div class="col-sm-8">
    <input type="file" id="gambar" name="gambar">
  </div>
</div>

<div class="form-group row">
  <label for="" class="col-sm-4 col-form-label"></label>
  <div class="col-sm-8">
    <button type="submit" class="btn btn-success">Simpan</button>
  </div>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi'); ?>