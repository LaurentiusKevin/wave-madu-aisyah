<!DOCTYPE html>
<html>
  <head>
    <?php include_once('_partials/head.html') ?>
  </head>
  <body>
    <!-- navbar-->
    <header class="header">
      <?php include_once('_partials/navbar.php') ?>
    </header>
    <!-- /navbar -->

    <!-- sidebar -->
    <div class="d-flex align-items-stretch">
      <div id="sidebarasd" class="sidebar py-3">
        <?php echo $_SESSION['sidebar'] ?>
        <?php //include_once('_partials/sidebar.php') ?>
      </div>
      <div class="page-holder w-100 d-flex flex-wrap">
        <div class="container-fluid px-xl-5">
          <?php include_once('vw-'.$page.'.html') ?>
        </div>
        <footer class="footer bg-white shadow align-self-end py-3 px-xl-5 w-100">
          <?php include_once('_partials/footer.php') ?>
        </footer>
      </div>
    </div>

    <!-- JavaScript files-->
    <?php include_once('_partials/footer-script.php') ?>
  </body>
</html>