<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Manajemen Data Barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>
<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Barang', [
  'class' => 'btn btn-primary',
  'onclick' => "location.href=('" . site_url('barang/tambah') . "')"
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

<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('suksess'); ?>

<div class="form-group">
  <label for="">Filter Satuan</label>
  <select name="satuan" id="satuan" class="form-control form-control-sm">
    <option value="">-- Pilih --</option>
    <?php foreach ($datasatuan as $row) : ?>
      <option value="<?= $row['satid']; ?>"><?= $row['satnama']; ?></option>
    <?php endforeach; ?>
  </select>
</div>

<table class="table table-sm table-bordered" id="databarang" style="width: 100%;">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Kategori</th>
      <th>Satuan</th>
      <th>Harga</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <script>
      $(document).ready(function() {
        dataBarang = $('#databarang').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: '/barang/listData',
            data: function(d) {
              d.satuan = $('#satuan').val();
            }
          },
          order: [],
          columns: [{
              data: 'nomor',
              orderable: false
            },
            {
              data: 'brgkode'
            },
            {
              data: 'brgnama'
            },
            {
              data: 'katnama'
            },
            {
              data: 'satnama'
            },
            {
              data: 'brgharga'
            },
            {
              data: 'aksi',
              orderable: false
            },
          ]
        });
        
        $('#satuan').change(function(e) {
          e.preventDefault();
          dataBarang.ajax.reload();
        });
      });

      function edit(id) {
        window.location.href = ('/barang/edit/' + id);
      }

      function hapus(id) {
        pesan = confirm('Yakin barang dengan kode dihapus !');
        if (pesan) {
          return true;
        } else {
          return false;
        }
      }
    </script>
  </tbody>
</table>

<?= $this->endSection('isi'); ?>