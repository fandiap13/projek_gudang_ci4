<table class="table table-sm table-hover table-bordered" style="width: 100%;" id="datadetail">
  <thead>
    <tr>
      <th colspan="5"></th>
      <th colspan="2" class="text-right">
        <h1>
          <?php
          $totalHarga = 0;
          foreach ($tampildata->getResultArray() as $row) :
            $totalHarga += $row['detsubtotal'];
          endforeach;
          ?>

          <?= number_format($totalHarga, 0, ",", "."); ?>
        </h1>
        <input type="hidden" name="totalHarga" id="totalHarga" value="<?= $totalHarga; ?>">
      </th>
    </tr>
  </thead>
  <thead>
    <tr>
      <th>No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Harga Jual</th>
      <th>Jumlah</th>
      <th>Sub.Total</th>
      <th>#</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($tampildata->getResultArray() as $row) :
    ?>
      <tr>
        <td>
          <?= $nomor++; ?>
          <!-- mengambil iddetail barangmasuk -->
          <input type="hidden" value="<?= $row['id']; ?>" id="id">
        </td>
        <td><?= $row['detbrgkode']; ?></td>
        <td><?= $row['brgnama']; ?></td>
        <td class="text-right"><?= number_format($row['dethargajual'], 0, ",", "."); ?></td>
        <td><?= number_format($row['detjml'], 0, ",", "."); ?></td>
        <td class="text-right"><?= number_format($row['detsubtotal'], 0, ",", "."); ?></td>
        <td style="text-align: right;">
          <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem('<?= $row['id']; ?>')">
            <i class="fa fa-trash-alt"></i>
          </button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  function hapusItem(id) {
    Swal.fire({
      title: 'Hapus Item',
      text: 'Yakin item ini dihapus',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus !',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "/barangkeluar/hapusItemDetail",
          data: {
            [csrfToken]: csrfHash,
            id: id
          },
          dataType: "json",
          success: function(response) {
            if (response.sukses) {
              Swal.fire('Berhasil', response.sukses, 'success');
              tampilDataDetail();
              ambilTotalHarga();
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
      } else {
        $('#kodebarang').prop('readonly', false);
        $('#tombolCariBarang').prop('disabled', false);
        $('#tombolSimpanItem').fadeIn();

        $('#tombolEditItem').fadeOut();
        $('#tombolBatal').fadeOut();
      }
    });
  }

  // edit Item
  $('#datadetail tbody').on('click', 'tr', function() {
    let row = $(this).closest('tr');
    let kodebarang = row.find('td:eq(1)').text(); // mengambil nilai kode barang pada tag td
    let id = row.find('td input').val(); // mengambil nilai id detail pada tag td
    $('#kodebarang').val(kodebarang);
    $('#iddetail').val(id);

    $('#tombolBatal').fadeIn();
    $('#tombolEditItem').fadeIn();

    $('#kodebarang').prop('readonly', true);
    $('#tombolCariBarang').prop('disabled', true);

    $('#tombolSimpanItem').fadeOut();
    ambilDataBarang();
  });

  // $(document).on('click', 'tombolBatal', function() {

  // });
  $(document).ready(function() {
    $('#tombolBatal').click(function(e) {
      e.preventDefault();
      kosong();
      tampilDataDetail();
      $('#kodebarang').prop('readonly', false);
      $('#tombolCariBarang').prop('disabled', false);
      $('#tombolSimpanItem').fadeIn();

      $('#tombolEditItem').fadeOut();
      $('#tombolBatal').fadeOut();
    });
  });
</script>