<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Gudang Barang Masuk dan Barang Keluar</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>/dist/css/adminlte.min.css">
  <!-- sweetalert -->
  <link rel="stylesheet" href="<?= base_url(); ?>/sweeetalert2/dist/sweetalert2.min.css">
  <!-- DataTables -->
  <!-- <link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
  <!-- select -->
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/select2/css/select2.min.css">
  <!-- jQuery -->
  <script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>

  <link rel="stylesheet" href="<?= base_url() . '/plugins/chart.js/Chart.min.css' ?>">
  <script src=" <?= base_url() . '/plugins/chart.js/Chart.bundle.min.js' ?>">
  </script>

</head>

<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url(); ?>/index3.html" class="brand-link">
        <img src="<?= base_url(); ?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">APP GUDANG</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= base_url(); ?>/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <!-- <a href="#" class="d-block"><?= session()->get('namauser'); ?></a> -->
            <a href="#" class="d-block"><?= session()->namauser; ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <?php if (session()->idlevel == 1) : ?>
              <li class="nav-header">Master</li>
              <li class="nav-item">
                <a href="<?= site_url('kategori/index'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-tasks text-primary"></i>
                  <p class="text">Kategori</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= site_url('satuan/index'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-angle-double-right text-warning"></i>
                  <p class="text">Satuan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= site_url('barang/index'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-tasks text-danger"></i>
                  <p class="text">Barang</p>
                </a>
              </li>
              <li class="nav-header">Transaksi</li>
              <li class="nav-item">
                <a href="<?= site_url('barangmasuk/data'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-arrow-circle-down text-primary"></i>
                  <p class="text">Barang Masuk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= site_url('barangkeluar/data'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-arrow-circle-up text-warning"></i>
                  <p class="text">Barang Keluar</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= site_url('laporan/index'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-file text-warning"></i>
                  <p class="text">Laporan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= site_url('utility/index'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-file text-warning"></i>
                  <p class="text">Utility</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if (session()->idlevel == 2) : ?>
              <li class="nav-item">
                <a href="<?= site_url('barangkeluar/data'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-arrow-circle-up text-warning"></i>
                  <p class="text">Barang Keluar</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if (session()->idlevel == 3) : ?>
              <li class="nav-header">Transaksi</li>
              <li class="nav-item">
                <a href="<?= site_url('barangmasuk/data'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-arrow-circle-down text-primary"></i>
                  <p class="text">Barang Masuk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= site_url('barangkeluar/data'); ?>" class="nav-link">
                  <i class="nav-icon fa fa-arrow-circle-up text-warning"></i>
                  <p class="text">Barang Keluar</p>
                </a>
              </li>
            <?php endif; ?>

            <li class="nav-item">
              <a href="#" class="nav-link" onclick="logout();">
                <i class="nav-icon fa fa-sign-out-alt text-primary"></i>
                <p class="text">Log Out</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>
                <?= $this->renderSection('judul'); ?>
              </h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <?= $this->renderSection('subJudul'); ?>
            </h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <?= $this->renderSection('isi'); ?>
          </div>
          <!-- /.card-body -->

          <!-- /.card-footer-->
        </div>
        <!-- /.card -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0
      </div>
      <strong>Copyright &copy; FandiAziz
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->


  <!-- Bootstrap 4 -->
  <script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables  & Plugins -->
  <!-- <script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/jszip/jszip.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="<?= base_url(); ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="<?= base_url(); ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script> -->
  <!-- AdminLTE App -->
  <script src="<?= base_url(); ?>/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= base_url(); ?>/dist/js/demo.js"></script>
  <!-- sweetalert -->
  <script src="<?= base_url(); ?>/sweeetalert2/dist/sweetalert2.all.min.js"></script>
  <!-- select2 -->
  <script src="<?= base_url(); ?>/plugins/select2/js/select2.min.js" type="text/javascript"></script>

  <script>
    function logout() {
      Swal.fire({
        title: 'Log Out',
        text: 'Apakah anda yakin ingin logout ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Log Out !',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location = ('/login/keluar');
        }
      });
    }
    // $(function() {
    //   $("#example1").DataTable({
    //     "responsive": true,
    //     "lengthChange": false,
    //     "autoWidth": false,
    //     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    //   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    //   $('#example2').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": false,
    //     "ordering": true,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    //   });
    //   $('.select2').select2();
    // });
  </script>
</body>


</html>