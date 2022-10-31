<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Halaman Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>/dist/css/adminlte.min.css">
  <!-- sweetalert -->
  <link rel="stylesheet" href="<?= base_url(); ?>/sweeetalert2/dist/sweetalert2.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="<?= base_url(); ?>/"><b>Silahkan Login</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <?= form_open('login/cekUser'); ?>
        <?= csrf_field(); ?>
        <div class="input-group mb-3">
          <input type="text" class="form-control <?= (session()->getFlashdata('errorIdUser')) ? 'is-invalid' : ''; ?>" placeholder="Inputkan ID User" name="iduser" value="<?= old('iduser') ? old('iduser') : ''; ?>" autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          <div class="invalid-feedback">
            <?= session()->getFlashdata('errorIdUser'); ?>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control <?= (session()->getFlashdata('errorPassword')) ? 'is-invalid' : ''; ?>" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <div class="invalid-feedback">
            <?= session()->getFlashdata('errorPassword'); ?>
          </div>
        </div>
        <div class="input-group mb-3">
          <button type="submit" class="btn btn-block btn-success">Login</button>
        </div>
        <?= form_close(); ?>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url(); ?>/dist/js/adminlte.min.js"></script>
  <!-- sweetalert -->
  <script src="<?= base_url(); ?>/sweeetalert2/dist/sweetalert2.all.min.js"></script>

  <?php if (session()->getFlashdata('pesanGagalLogin')) : ?>
    <script>
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
      });

      setTimeout(function() {
        Toast.fire({
          icon: 'warning',
          title: "Silahkan login terlebih dahulu !",
        });
      }, 500);
    </script>
  <?php endif; ?>

</body>

</html>