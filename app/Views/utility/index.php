<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Utility System
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>
Backup Database
<?= $this->endSection('subJudul'); ?>

<?= $this->section('isi'); ?>

<?php if (session()->getFlashdata('pesan')) { ?>
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Berhasil !</h5>
    <?= session()->getFlashdata('pesan'); ?>
  </div>
<?php } ?>

<button type="button" class="btn btn-primary" onclick="location.href=('/utility/doBackup')">
  Click To Backup Database
</button>

<?= $this->endSection('isi'); ?>