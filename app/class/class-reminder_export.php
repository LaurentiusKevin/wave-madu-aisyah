<?php
session_start();
// error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
require('database.php');

if (isset($_POST['case'])) { $case = $_POST['case']; }
if (isset($_POST['id'])) { $id = $_POST['id']; }

switch ($case) {
  case 'list':
    if (!isset($_POST['start']) && !isset($_POST['end'])) {
      $start = date('Y-m-d');
      $end = date('Y-m-d').' 23:30:00';
    } else {
      $start = $_POST['start'];
      $end = $_POST['end'].' 23:30:00';
    }
    $sql = "SELECT id_reminder, no_transaksi, id_customer, nama_customer, nomor_wa, p.nama AS produk, tgl_beli, tgl_reminder, tipe
            FROM ms_reminder
            JOIN ms_product AS p ON ms_reminder.id_produk=p.id_product
            WHERE ms_reminder.aktif='1' AND tgl_reminder BETWEEN '$start' AND '$end 23:30:00'";
    $query = mysqli_query($conn,$sql);
    $total = mysqli_num_rows($query);
    if ($total == 0) {
      $result["data"] = '';
    } else {
      while ($row = mysqli_fetch_assoc($query)) {
        $result["data"][] = array_map("utf8_encode", $row);
      }
    }
    echo json_encode($result);
    break;

  default:
    # code...
    break;
}
?>
