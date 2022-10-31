<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Data Transaksi Barang Masuk
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>

<?= form_button('', '<i class="fa fa-plus-circle"></i> Input Transaksi', [
  'class' => 'btn btn-primary',
  'onclick' => "location.href=('" . site_url('/barangmasuk/index') . "')"
]); ?>

<?= $this->endSection('subJudul'); ?>

<?= $this->section('isi'); ?>

<?= form_open('barangmasuk/data'); ?>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Cari berdasarkan Faktur..." name="cari" value="<?= $cari; ?>" autofocus>
  <div class="input-group-append">
    <button class="btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari"> <i class="fa fa-search"></i> </button>
  </div>
</div>
<?= form_close(); ?>
<span class="badge badge-success">Total Data : <?= $totaldata; ?></span>
<table class="table table-sm table-bordered table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Faktur</th>
      <th>Tanggal</th>
      <th>Jumlah Item</th>
      <th>Total Harga (Rp)</th>
      <th>#</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1 + (($nohalaman - 1) * 10);
    foreach ($tampildata as $row) :
    ?>
      <tr>
        <td><?= $nohalaman++; ?></td>
        <td><?= $row['faktur']; ?></td>
        <td><?= date('d-m-Y', strtotime($row['tglfaktur'])); ?></td>
        <td align="center">
          <?php
          $db = \Config\Database::connect();
          $jumlahItem = $db->table('detail_barangmasuk')->where('detfaktur', $row['faktur'])->countAllResults();
          ?>
          <span style="cursor: pointer; font-weight: bold; color:blue;" onclick="detailItem('<?= $row['faktur']; ?>')"><?= $jumlahItem; ?></span>
        </td>
        <td><?= number_format($row['totalharga'], 0, ",", "."); ?></td>
        <td>
          <button type="button" class="btn btn-sm btn-outline-info" title="Edit Transaksi" onclick="edit('<?= sha1($row['faktur']); ?>')"><i class="fa fa-edit"></i></button> &nbsp;
          <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Transaksi" onclick="hapusTransaksi('<?= $row['faktur']; ?>')"><i class="fa fa-trash-alt"></i></button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="viewmodal" style="display: none;"></div>

<div class="float-left mt-4">
  <?= $pager->links('barangmasuk', 'paging'); ?>
</div>

<script>
  let csrfToken = '<?= csrf_token(); ?>';
  let csrfHash = '<?= csrf_hash(); ?>';
  
  function detailItem(faktur) {
    $.ajax({
      type: "post",
      url: "/barangmasuk/detailItem",
      data: {
        [csrfToken]: csrfHash,
        faktur: faktur
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').html(response.data).show();
          $('#modalitem').modal('show');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function edit(faktur) {
    window.location.href = ('/barangmasuk/edit/') + faktur;
  }

  function hapusTransaksi(faktur) {
    Swal.fire({
      title: 'Hapus Transaksi',
      text: "Yakin menghapus transaksi ini ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "/barangmasuk/hapusTransaksi",
          data: {
            [csrfToken]: csrfHash,
            faktur: faktur
          },
          dataType: "json",
          success: function(response) {
            if (response.sukses) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.sukses,
              }).then((result) => {
                window.location.reload();
              });
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    });
  }
</script>

<?= $this->endSection('isi'); ?>