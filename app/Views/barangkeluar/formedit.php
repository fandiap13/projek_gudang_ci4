<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Edit Transaksi Barang Keluar
<?= $this->endSection('judul'); ?>

<?= $this->section('subJudul'); ?>

<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
  'class' => 'btn btn-warning',
  'onclick' => "location.href=('" . site_url('/barangkeluar/data') . "')"
]); ?>

<?= $this->endSection('subJudul'); ?>

<?= $this->section('isi'); ?>
<style>
  /* style untuk edit Item barang keluar */
  table#datadetail tbody tr:hover {
    cursor: pointer;
    background-color: red;
    color: white;
  }
</style>

<table class="table table-sm table-striped table-hover" style="width:100%;">
  <tr>
    <input type="hidden" id="nofaktur" value="<?= $nofaktur; ?>">
    <td style="width: 20%;">No.Faktur</td>
    <td style="width: 2%;">:</td>
    <td style="width: 28%"><?= $nofaktur; ?></td>
    <td rowspan="3" style="width: 50%; font-weight: bold; color:red; font-size: 20pt; text-align: center; vertical-align: middle;" id="lbTotalHarga">

    </td>
  </tr>
  <tr>
    <td>Tanggal</td>
    <td>:</td>
    <td><?= date('d-m-Y', strtotime($tanggal)); ?></td>
  </tr>
  <tr>
    <td>Nama Pelanggan</td>
    <td>:</td>
    <td><?= $namapelanggan; ?></td>
  </tr>
</table>

<div class="row mt-4">
  <div class="col-lg-2">
    <div class="form-group">
      <label for="">Kode Barang</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="kodebarang" id="kodebarang">
        <input type="hidden" id="iddetail">
        <div class="input-group-append">
          <button class="btn btn-outline-primary" type="submit" id="tombolCariBarang" name="tombolCariBarang" title="Cari Barang"> <i class="fa fa-search"></i> </button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="form-group">
      <label for="">Nama Barang</label>
      <input type="text" name="namabarang" id="namabarang" class="form-control" readonly>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label for="">Harga Jual</label>
      <input type="number" name="hargajual" id="hargajual" class="form-control" readonly>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label for="">Qty</label>
      <input type="number" name="jml" id="jml" class="form-control" value="1">
    </div>
  </div>
  <div class="col-lg-3">
    <div class="form-group">
      <label for="">#</label>
      <div class="input-group mb-3">
        <button type="button" class="btn btn-success" title="Simpan Item" id="tombolSimpanItem">
          <i class="fa fa-save"></i>
        </button>
        &nbsp;
        <button type="button" style="display: none;" class="btn btn-primary" title="Edit Item" id="tombolEditItem">
          <i class="fa fa-edit"></i>
        </button>
        &nbsp;
        <button type="button" style="display: none;" class="btn btn-default" title="Batalkan" id="tombolBatal">
          <i class="fa fa-sync-alt"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 tampilDataDetail">

  </div>
</div>

<div class="viewmodal" style="display: none;"></div>

<script>
  let csrfToken = '<?= csrf_token(); ?>';
  let csrfHash = '<?= csrf_hash(); ?>';

  function ambilTotalHarga() {
    let nofaktur = $('#nofaktur').val();
    $.ajax({
      type: "post",
      url: "/barangkeluar/ambilTotalHarga",
      data: {
        [csrfToken]: csrfHash,
        nofaktur: nofaktur
      },
      dataType: "json",
      success: function(response) {
        if (response.totalharga) {
          $('#lbTotalHarga').html(response.totalharga);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function kosong() {
    $('#kodebarang').val('');
    $('#namabarang').val('');
    $('#hargajual').val('');
    $('#jml').val('1');
    $('#kodebarang').focus('');
  }

  function tampilDataDetail() {
    let nofaktur = $('#nofaktur').val();
    $.ajax({
      type: "post",
      url: "/barangkeluar/tampilDataDetail",
      data: {
        [csrfToken]: csrfHash,
        nofaktur: nofaktur
      },
      beforeSend: function() {
        $('.tampilDataDetail').html('<i class="fa fa-spin fa-spinner"></i>');
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.tampilDataDetail').html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function ambilDataBarang() {
    let kodebarang = $('#kodebarang').val();
    if (kodebarang.length == 0) {
      Swal.fire('Error', 'Kode barang harus diinputkan', 'error');
      kosong();
    } else {
      $.ajax({
        type: "post",
        url: "/barangkeluar/ambilDataBarang",
        data: {
          [csrfToken]: csrfHash,
          kodebarang: kodebarang
        },
        dataType: "json",
        success: function(response) {
          if (response.error) {
            Swal.fire('Error', response.error, 'error');
            kosong();
          }

          if (response.sukses) {
            let data = response.sukses;
            $('#namabarang').val(data.namabarang);
            $('#hargajual').val(data.hargajual);
            $('#jml').focus();
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + thrownError);
        }
      });
    }
  }

  function simpanItem() {
    let nofaktur = $('#nofaktur').val();
    let kodebarang = $('#kodebarang').val();
    let namabarang = $('#namabarang').val();
    let hargajual = $('#hargajual').val();
    let jml = $('#jml').val();
    if (kodebarang.length == 0) {
      Swal.fire('Error', 'Kode barang harus diinputkan', 'error');
      kosong();
    } else {
      $.ajax({
        type: "post",
        url: "/barangkeluar/simpanItemDetail",
        data: {
          [csrfToken]: csrfHash,
          nofaktur: nofaktur,
          kodebarang: kodebarang,
          hargajual: hargajual,
          namabarang: namabarang,
          jml: jml,
        },
        dataType: "json",
        success: function(response) {
          if (response.sukses) {
            Swal.fire('Berhasil', response.sukses, 'success');
            tampilDataDetail();
            ambilTotalHarga();
            kosong();
          }

          if (response.error) {
            Swal.fire('Error', response.error, 'error');
            kosong();
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + thrownError);
        }
      });
    }
  }

  $(document).ready(function() {
    ambilTotalHarga();
    tampilDataDetail();

    $('#tombolEditItem').click(function(e) {
      e.preventDefault();
      $.ajax({
        type: "post",
        url: "/barangkeluar/editItem",
        data: {
          [csrfToken]: csrfHash,
          iddetail: $('#iddetail').val(),
          jml: $('#jml').val()
        },
        dataType: "json",
        success: function(response) {
          if (response.sukses) {
            Swal.fire('Berhasil', response.sukses, 'success');
            ambilTotalHarga();
            tampilDataDetail();
            kosong();

            $('#kodebarang').prop('readonly', false);
            $('#tombolCariBarang').prop('disabled', false);
            $('#tombolSimpanItem').fadeIn();

            $('#tombolEditItem').fadeOut();
            $('#tombolBatal').fadeOut();
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + thrownError);
        }
      });
    });

    // cari barang
    $('#tombolCariBarang').click(function(e) {
      e.preventDefault();
      $.ajax({
        url: "/barangkeluar/modalCariBarang",
        dataType: "json",
        success: function(response) {
          if (response.data) {
            $('.viewmodal').html(response.data).show();
            $('#modalcaribarang').modal('show');
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + thrownError);
        }
      });
    });

    // cari barang
    $('#kodebarang').keydown(function(e) {
      if (e.keyCode == 13) {
        e.preventDefault();
        ambilDataBarang();
      }
    });

    // tambah item / simpan item ke detail
    $('#tombolSimpanItem').click(function(e) {
      e.preventDefault();
      simpanItem();
    });
  });
</script>

<?= $this->endSection('isi'); ?>