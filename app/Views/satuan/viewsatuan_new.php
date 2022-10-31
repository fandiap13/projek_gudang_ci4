<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Manajemen Data Satuan
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>

<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
  'class' => 'btn btn-primary',
  'onclick' => "location.href=('" . site_url('satuan/formtambah') . "')"
]); ?>

<?= $this->endSection('subJudul'); ?>

<?= $this->section('isi'); ?>

<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<?= session()->getFlashdata('suksess'); ?>

<table class="table table-striped table-bordered" id="datasatuan" style="width: 100%;">
  <thead>
    <Tr>
      <th style="width: 5%;">No</th>
      <th>Satuan</th>
      <th style="width: 15%;">Aksi</th>
    </Tr>
  </thead>
  <tbody>

  </tbody>
</table>

<script>
  function edit(id) {
    window.location = ('/satuan/formedit/' + id);
  }

  function hapus() {
    pesan = confirm('Yakin data satuan dihapus !');
    if (pesan) {
      return true;
    } else {
      return false;
    }
  }

  $(document).ready(function() {
    $('#datasatuan').DataTable({
      processing: true,
      serverSide: true,
      ajax: '/satuan/listData',
      order: [],
      columns: [{
          data: 'nomor',
          orderable: false
        },
        {
          data: 'satnama'
        },
        {
          data: 'aksi',
          orderable: false
        },
      ]
    });
  });
</script>

<?= $this->endSection('isi'); ?>