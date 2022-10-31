<div class="modal fade" id="modalpembayaran" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Pembayaran Faktur</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open('barangkeluar/simpanPembayaran', ['class' => 'frmpembayaran']); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="">No. Faktur</label>
          <input type="text" name="nomorfaktur" id="nomorfaktur" value="<?= $nofaktur; ?>" class="form-control" readonly>
          <input type="hidden" name="tglfaktur" value="<?= $tglfaktur; ?>">
          <input type="hidden" name="idpelanggan" value="<?= $idpelanggan; ?>">
        </div>
        <div class="form-group">
          <label for="">Total Harga</label>
          <input type="text" name="totalbayar" id="totalbayar" value="<?= $totalharga; ?>" class="form-control" readonly>
        </div>
        <div class="form-group">
          <label for="">Jumlah Uang</label>
          <input type="text" name="jumlahuang" id="jumlahuang" class="form-control" autocomplete="false">
          <div class="invalid-feedback errorJumlahUang">

          </div>
        </div>
        <div class="form-group">
          <label for="">Sisa Uang</label>
          <input type="text" name="sisauang" id="sisauang" class="form-control" readonly>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success btnsimpan">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>

<script src="<?= base_url('dist/js/autoNumeric.js'); ?>"></script>
<script>
  $(document).ready(function() {
    $('#totalbayar').autoNumeric('init', {
      mDec: 0,
      aSep: '.',
      aDec: ','
    });
    $('#jumlahuang').autoNumeric('init', {
      mDec: 0,
      aSep: '.',
      aDec: ','
    });
    $('#sisauang').autoNumeric('init', {
      mDec: 0,
      aSep: '.',
      aDec: ','
    });

    // input jumlah uang
    $('#jumlahuang').keyup(function(e) {
      let totalbayar = $('#totalbayar').autoNumeric('get');
      let jumlahuang = $('#jumlahuang').autoNumeric('get');
      let sisauang;

      if (parseInt(jumlahuang) < parseInt(totalbayar)) {
        sisauang = 0;
      } else {
        sisauang = parseInt(jumlahuang) - parseInt(totalbayar);
      }
      $('#sisauang').autoNumeric('set', sisauang);
    });

    $('.frmpembayaran').submit(function(e) {
      e.preventDefault();

      let pelanggan = $('#namapelanggan').val();
      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        beforeSend: function() {
          // menambahkan atribut
          $('.btnsimpan').prop('disabled', true);
          $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
        },
        complete: function() {
          $('.btnsimpan').prop('disabled', false);
          $('.btnsimpan').html('Simpan');
        },
        success: function(response) {
          if (response.error) {
            let data = response.error;
            if (data.errorJumlahUang) {
              $('#jumlahuang').addClass('is-invalid');
              $('.errorJumlahUang').html(data.errorJumlahUang);
            }
          }

          if (response.sukses) {
            Swal.fire({
              title: 'Cetak Faktur',
              text: response.sukses,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Cetak !',
              cancelButtonText: 'Batal'
            }).then((result) => {
              if (result.isConfirmed) {
                let windowCetak = window.open(response.cetakfaktur, "Cetak Faktur Barang Keluar", "width=300,height=500");
                windowCetak.focus();
                window.location.reload();
              } else {
                window.location.reload();
              }
            });
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + '\n' + thrownError);
        }
      });

      return false;
    });
  });
</script>