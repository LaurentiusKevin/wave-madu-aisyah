<?php
if (file_exists('app/view/csv/'.$form.'.php')) {
  require($form.'.php');
} else {
  require('app/view/404.html');
}
?>