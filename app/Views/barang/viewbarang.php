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

<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('suksess'); ?>

<?= form_open('barang/index'); ?>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Cari barang berdasarkan nama dan kode..." aria-label="Cari data" aria-describedby="button-addon2" name="cari" value="<?= $cari; ?>" autofocus>
  <select name="kategori" id="" class="form-control">
    <option value="">-- Pilih Kategori</option>
    <?php foreach ($datakategori as $k) : ?>
      <option value="<?= $k['katnama']; ?>" <?= ($cari_kategori == $k['katnama'] ? 'selected' : ''); ?>><?= $k['katnama']; ?></option>
    <?php endforeach; ?>
  </select>

  <button class="btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari"> <i class="fa fa-search"></i> </button>
</div>
<?= form_close(); ?>

<span class="badge badge-success mb-2">
  <h6>Total Data : <?= $totaldata; ?></h6>
</span>$row->brgkode

<table class="table table-striped table-bordered" style="width: 100%;">
  <thead>
    <Tr>
      <th style="width: 5%;">No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Kategori</th>
      <th>Satuan</th>
      <th>Harga</th>
      <th>Stok</th>
      <th style="width: 15%;">Aksi</th>
    </Tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1 + (($nohalaman - 1) * 10);
    foreach ($tampildata as $row) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $row['brgkode']; ?></td>
        <td><?= $row['brgnama']; ?></td>
        <td><?= $row['katnama']; ?></td>
        <td><?= $row['satnama']; ?></td>
        <td>Rp. <?= number_format($row['brgharga'], 0); ?></td>
        <td><?= number_format($row['brgstok'], 0); ?></td>
        <td>
          <button type="button" class="btn btn-sm btn-info" title="Edit Data" onclick="edit('<?= $row['brgkode']; ?>');"><i class="fa fa-edit"></i></button>

          <form action="/barang/hapus/<?= $row['brgkode']; ?>" method="post" style="display: inline;" onsubmit="return hapus('<?= $row['brgkode']; ?>');">
            <input type="hidden" value="DELETE" name="_method">
            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Data"><i class="fa fa-trash-alt"></i></button>
          </form>

        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="float-center mt-2">
  <?= $pager->links('barang', 'paging'); ?>
</div>

<script>
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
    // Swal.fire({
    //   title: 'Hapus ?',
    //   text: "Yakin Data Kategori Dihapus!",
    //   icon: 'warning',
    //   showDenyButton: true,
    //   denyButtonText: 'Tidak',
    //   confirmButtonColor: '#3085d6',
    //   cancelButtonColor: '#d33',
    //   confirmButtonText: 'Ya, Hapus!'
    // }).then((result) => {
    //   /* Read more about isConfirmed, isDenied below */
    //   if (result.isConfirmed) {
    //     return true;
    //   } else if (result.isDenied) {
    //     return false;
    //   }
    // });
  }
</script>

<?= $this->endSection('isi'); ?>