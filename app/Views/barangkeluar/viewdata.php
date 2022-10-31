<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Data Transaksi Barang Keluar
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>

<?= form_button('', '<i class="fa fa-plus-circle"></i> Input Transaksi', [
  'class' => 'btn btn-primary',
  'onclick' => "location.href=('" . site_url('/barangkeluar/input') . "')"
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

<div class="row">
  <div class="col">
    <label for="">Filter Data</label>
  </div>
  <div class="col">
    <input type="date" name="tglawal" class="form-control" id="tglawal">
  </div>
  <div class="col">
    <input type="date" name="tglakhir" class="form-control" id="tglakhir">
  </div>
  <div class="col">
    <button type="button" class="btn btn-block btn-primary" id="tombolTampil">
      Tampilkan
    </button>
  </div>
</div>
<br>
<table class="table table-bordered table-hover dataTable dtr-inline collapsed" id="databarangkeluar" style="width: 100%;">
  <thead>
    <tr>
      <th>No</th>
      <th>Faktur</th>
      <th>Tanggal</th>
      <th>Nama Pelanggan</th>
      <th>Total Harga (Rp)</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>

  </tbody>
</table>
<script>
  let csrfToken = '<?= csrf_token(); ?>';
  let csrfHash = '<?= csrf_hash(); ?>';

  function listDataBarangKeluar() {
    var table = $('#databarangkeluar').DataTable({
      destroy: true,
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "/barangkeluar/listData", // ambil data di controller
        "type": "POST",
        // mengirim data ke controller barangkeluar
        "data": {
          [csrfToken]: csrfHash,
          tglawal: $('#tglawal').val(),
          tglakhir: $('#tglakhir').val(),
        }
      },
      "columnDefs": [{
        "targets": [0, 5], // target order data
        "orderable": false
      }, ],
    });
  }

  function cetak(faktur) {
    let windowCetak = window.open('/barangkeluar/cetakfaktur/' + faktur, "Cetak Faktur Barang Keluar", "width=300,height=500");
    windowCetak.focus();
    window.location.reload();
  }

  function hapus(faktur) {
    Swal.fire({
      title: 'Hapus Transaksi',
      text: "Yakin menghapus transksi !",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "/barangkeluar/hapusTransaksi",
          data: {
            [csrfToken]: csrfHash,
            faktur: faktur
          },
          dataType: "json",
          success: function(response) {
            if (response.sukses) {
              listDataBarangKeluar();
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    })
  }

  function edit(faktur) {
    window.location.href = ('/barangkeluar/edit/') + faktur;
  }

  $(document).ready(function() {
    listDataBarangKeluar();

    // cari data berdasarkan tanggal
    $('#tombolTampil').click(function(e) {
      e.preventDefault();
      listDataBarangKeluar();
    });
  });
</script>

<?= $this->endSection('isi'); ?>