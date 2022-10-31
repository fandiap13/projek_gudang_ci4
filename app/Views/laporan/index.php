<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Cetak Laporan
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>
Silahkan Pilih Laporan Yang Ingin Dicetak
<?= $this->endSection('subJudul'); ?>

<?= $this->section('isi'); ?>

<div class="row">
  <div class="col-lg-4">
    <button type="button" style="padding-top: 50px; padding-bottom: 50px;" class="btn btn-block btn-lg btn-success" onclick="window.location=('/laporan/cetak-barang-masuk')">
      <i class="fa fa-file"></i> LAPORAN BARANG MASUK
    </button>
  </div>
  <div class="col-lg-4">
    <button type="button" style="padding-top: 50px; padding-bottom: 50px;" class="btn btn-block btn-lg btn-primary" onclick="window.location=('/laporan/cetak-barang-keluar')">
      <i class="fa fa-file"></i> LAPORAN BARANG KELUAR
    </button>
  </div>
  <div class="col-lg-4">

  </div>
</div>

<?= $this->endSection('isi'); ?>