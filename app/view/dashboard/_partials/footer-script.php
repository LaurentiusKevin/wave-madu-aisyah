<script src="assets/dashboard/vendor/jquery/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="assets/dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="assets/dashboard/js/front.js"></script>
<script>
  function userLogout()
  {
    $.ajax({
      type: "POST",
      url: "app/class/class-session_destroy.php",
      success: function (response) {
        console.log(response);
        var resultData = JSON.parse(response);
        switch (resultData.status) {
          case 'success':
            window.location = '/reseller-madu';
            break;
          
          case 'failed':
            alert('Gagal Logout. Silahkan coba lagi atau hubungi WAVE SOLUSI INDONESIA');
            break;
        }
      }
    });
  }
</script>

<?php include_once('app/js/js-'.$page.'.html') ?>