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

<?= session()->getFlashdata('suksess'); ?>

<?= form_open('satuan/index'); ?>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Cari data satuan..." aria-label="Recipient's username" aria-describedby="button-addon2" name="cari" value="<?= $cari; ?>" autofocus>
  <button class="btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari"> <i class="fa fa-search"></i> </button>
</div>
<?= form_close(); ?>

<table class="table table-striped table-bordered" style="width: 100%;">
  <thead>
    <Tr>
      <th style="width: 5%;">No</th>
      <th>Satuan</th>
      <th style="width: 15%;">Aksi</th>
    </Tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1 + (($nohalaman - 1) * 5);
    foreach ($tampildata as $row) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $row['satnama']; ?></td>
        <td>
          <button type="button" class="btn btn-info" title="Edit Data" onclick="edit('<?= $row['satid']; ?>');"><i class="fa fa-edit"></i></button>

          <form action="/satuan/hapus/<?= $row['satid']; ?>" method="post" style="display: inline;" onsubmit="return hapus();">
            <input type="hidden" value="DELETE" name="_method">
            <button type="submit" class="btn btn-danger" title="Hapus Data"><i class="fa fa-trash-alt"></i></button>
          </form>

        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="float-center mt-2">
  <?= $pager->links('satuan', 'paging'); ?>
</div>

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
    // Swal.fire({
    //   title: 'Hapus ?',
    //   text: "Yakin Data satuan Dihapus!",
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