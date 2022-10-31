<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Manajemen Data Kategori
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>

<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
  'class' => 'btn btn-primary',
  'onclick' => "location.href=('" . site_url('kategori/formtambah') . "')"
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

<table class="table table-striped table-bordered" id="datakategori" style="width: 100%;">
  <thead>
    <Tr>
      <th style="width: 5%;">No</th>
      <th>Nama Kategori</th>
      <th style="width: 15%;">Aksi</th>
    </Tr>
  </thead>
  <tbody>
  </tbody>
</table>

<script>
  function edit(id) {
    window.location = ('/kategori/formedit/' + id);
  }

  function hapus() {
    pesan = confirm('Yakin data kategori dihapus !');
    if (pesan) {
      return true;
    } else {
      return false;
    }
  }

  $(document).ready(function() {
    $('#datakategori').DataTable({
      processing: true,
      serverSide: true,
      ajax: '/kategori/listData',
      order: [],
      columns: [{
          data: 'nomor',
          orderable: false
        },
        {
          data: 'katnama'
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