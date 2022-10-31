<div class="modal fade" id="modaltambahpelanggan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Form Input Pelanggan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('pelanggan/simpan', ['class' => 'formsimpan']); ?>
        <div class="form-group">
          <label for="">Input Nama Pelanggan</label>
          <input type="text" name="namaPelanggan" id="namaPelanggan" class="form-control">
          <div class="invalid-feedback errorNamaPelanggan">
          </div>
        </div>
        <div class="form-group">
          <label for="">Telp/HP</label>
          <input type="text" name="telp" id="telp" class="form-control">
          <div class="invalid-feedback errorTelp">
          </div>
        </div>
        <div class="form-group">
          <label for=""></label>
          <button type="submit" class="btn btn-block btn-success" id="tombolsimpan">
            Simpan
          </button>
        </div>
        <?= form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.formsimpan').submit(function(e) {
      e.preventDefault();

      $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: "json",
        beforeSend: function() {
          // menambahkan atribut
          $('#tombolsimpan').prop('disabled', true);
          $('#tombolsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
        },
        complete: function() {
          $('#tombolsimpan').prop('disabled', false);
          $('#tombolsimpan').html('Simpan');
        },
        success: function(response) {
          if (response.error) {
            let err = response.error;
            if (err.errorNamaPelanggan) {
              $('#namaPelanggan').addClass('is-invalid');
              $('.errorNamaPelanggan').html(err.errorNamaPelanggan);
            }
            // console.log(err.errorTelp);
            if (err.errorTelp) {
              $('#telp').addClass('is-invalid');
              $('.errorTelp').html(err.errorTelp);
            }
          }

          if (response.sukses) {
            Swal.fire({
              title: 'Berhasil',
              text: response.sukses,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Ambil !',
              cancelButtonText: 'Batal'
            }).then((result) => {
              if (result.isConfirmed) {
                $('#namapelanggan').val(response.namaPelanggan);
                $('#idpelanggan').val(response.idPelanggan);
                $('#modaltambahpelanggan').modal('hide');
              } else {
                $('#modaltambahpelanggan').modal('hide');
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