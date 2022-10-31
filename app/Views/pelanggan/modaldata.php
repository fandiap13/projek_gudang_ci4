<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<div class="modal fade" id="modaldatapelanggan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Silahkan Cari Data Pelanggan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-hover dataTable dtr-inline collapsed" id="datapelanggan" style="width: 100%;">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Pelanggan</th>
              <th>Telp/No.HP</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function listDataPelanggan() {
    var table = $('#datapelanggan').DataTable({
      destroy: true,
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "/pelanggan/listData", // ambil data di controller
        "type": "POST",
        "data": {
          [csrfToken]: csrfHash,
        }
      },
      "columnDefs": [{
        "targets": [0, 3], // target order data
        "orderable": false
      }, ],
    });
  }

  function hapus(pelid, pelnama) {
    Swal.fire({
      title: 'Hapus Pelanggan?',
      text: "Yakin menghapus data pelanggan dengan nama " + pelnama + " ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus !'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "/pelanggan/hapus",
          data: {
            pelid: pelid
          },
          dataType: "json",
          success: function(response) {
            if (response.sukses) {
              Swal.fire({
                icon: 'success',
                title: 'Hapus Data',
                text: response.sukses
              });
              // reload data
              listDataPelanggan();
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      }
    })
  }

  // jika memilih pelanggan saat pencarian
  function pilih(pelid, pelnama) {
    $('#namapelanggan').val(pelnama);
    $('#idpelanggan').val(pelid);
    $('#modaldatapelanggan').modal('hide');
  }

  $(document).ready(function() {
    listDataPelanggan();
  });
</script>