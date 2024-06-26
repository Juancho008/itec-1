<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo TITULO_SISTEMA ?></title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo RUTA_URL ?>/css/login.css">
</head>

<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 login-section-wrapper">
          <div class="brand-wrapper">
            <img src="<?php echo RUTA_URL ?>/img/logo-generico.png" alt="logo" class="logo">
          </div>
          <h1><?php echo TITULO_SISTEMA ?></h1>
          <div class="login-wrapper my-auto">
            <h3 class="login-title">Ingresar al sistema</h3>
            <form action="<?php echo RUTA_URL ?>/Acceso/ingresar" method="post">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="usuario" id="email" class="form-control" placeholder="Ingrese su usuario">
              </div>
              <div class="form-group mb-4">
                <label for="password">Password</label>
                <input type="password" name="clave" id="password" class="form-control" placeholder="Ingrese su clave">
              </div>
              <input id="login" class="btn btn-block login-btn" type="submit" value="Ingresar">
            </form>
            <!-- <a href="#!" class="forgot-password-link">Forgot password?</a>
            <p class="login-wrapper-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p> -->
          </div>
        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="<?php echo RUTA_URL ?>/img/login-imagen.jpg" alt="login image" class="login-img">
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>