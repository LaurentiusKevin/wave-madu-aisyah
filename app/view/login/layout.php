<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>RESELLER MADU</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Google fonts - Popppins for copy-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,800">
    <!-- orion icons-->
    <link rel="stylesheet" href="assets/dashboard/css/orionicons.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="assets/dashboard/css/style.red.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="assets/dashboard/css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="assets/dashboard/img/favicon.png?3">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <div class="page-holder d-flex align-items-center">
      <div class="container">
        <div class="row align-items-center py-5">
          <div class="col-5 col-lg-7 mx-auto mb-5 mb-lg-0">
            <div class="pr-lg-5"><img src="assets/dashboard/img/login.svg" alt="" style="image-height: 50%;" class="img-fluid"></div>
          </div>
          <div class="col-lg-5 px-lg-4">
            <h1 class="text-base text-primary text-uppercase mb-4">Reseller Madu</h1>
            <h2 class="mb-4">Login Dashboard</h2>
            <form id="login_form" class="mt-4">
              <div class="form-group mb-4">
                <input type="text" id="input_username" name="input_username" placeholder="Username" class="form-control border-0 shadow form-control-lg" autocomplete="off" autofocus>
              </div>
              <div class="form-group mb-4">
                <input type="password" name="input_password" placeholder="Password" class="form-control border-0 shadow form-control-lg text-violet">
              </div>
              <a href="/" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Go Back</a>
              <button type="submit" class="btn btn-primary float-right shadow px-5">Log in</button>
            </form>
          </div>
        </div>
        <p class="mt-5 mb-0 text-gray-400 text-center">Developed by <a href="http://waveitsolution.com" class="external text-gray-400">WAVE Solusi Indonesia</a></p>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="assets/dashboard/vendor/jquery/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="assets/dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="assets/dashboard/vendor/chart.js/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="assets/dashboard/js/front.js"></script>
    <?php include_once('app/js/js-login.html') ?>
  </body>
</html>