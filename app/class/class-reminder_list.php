<?php
session_start();
// error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
require('database.php');

if (isset($_POST['case'])) { $case = $_POST['case']; }
if (isset($_POST['id'])) { $id = $_POST['id']; }

switch ($case) {
  case 'list':
    $sql = "SELECT id_reminder, no_transaksi, id_customer, nama_customer, nomor_wa, p.nama AS produk, tgl_beli, tgl_reminder, tipe
            FROM ms_reminder
            JOIN ms_product AS p ON ms_reminder.id_produk=p.id_product
            WHERE ms_reminder.aktif='1'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $result["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($result);
    break;

  case 'produk':
    $sql = "SELECT * FROM ms_product WHERE aktif='1'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $result['data'][] = array_map("utf8_encode", $row);
    }
    echo json_encode($result);
    break;

  case 'edit':
    $sql = "SELECT id_reminder, no_transaksi, id_customer, nama_customer, nomor_wa,
            ms_reminder.id_produk, p.nama AS product, tgl_beli, tgl_reminder, tipe
            FROM ms_reminder
            JOIN ms_product AS p ON ms_reminder.id_produk=p.id_product
            WHERE ms_reminder.id_reminder = '$id'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $result = array_map("utf8_encode", $row);
    }
    echo json_encode($result);
    // echo mysqli_error($conn);
    break;

  case 'delete':
    $sql = "UPDATE ms_reminder SET aktif='0' WHERE id_reminder='$id'";
    if (mysqli_query($conn,$sql)) {
      $result['status'] = 'success';
    } else {
      $result['status'] = 'failed';
      $result['sql_error'] = mysqli_error($conn);
    }
    echo json_encode($result);
    break;

  case 'submit':
    $nama = $_POST['input_nama'];
    $nomorWA = $_POST['input_nomor'];
    $idProduk = $_POST['input_produk'];
    $tglReminder = $_POST['tgl_reminder'];
    $tipe = $_POST['input_tipe'];
    if ($id == 'new') {
      $sql = "INSERT INTO ms_reminder (nama_customer, nomor_wa, id_produk, tgl_reminder, tipe)
              VALUES ('$nama', '$nomorWA', '$idProduk', '$tglReminder', '$tipe')
              ";
      if (mysqli_query($conn,$sql)) {
        $result['status'] = 'success';
        $result['nama'] = $nama;
        $result['tgl_reminder'] = $tglReminder;
      } else {
        $result['status'] = 'failed';
        $result['sql_error'] = mysqli_error($conn);
        $result['nama'] = $nama;
        $result['tgl_reminder'] = $tglReminder;
      }
    } else {
      $sql = "UPDATE ms_reminder SET nomor_wa='$nomorWA', id_produk='$idProduk', tgl_reminder='$tglReminder', tipe='$tipe'  WHERE id_reminder='$id'";
      if (mysqli_query($conn,$sql)) {
        $result['status'] = 'success';
        $result['nama'] = $nama;
        $result['tgl_reminder'] = $tglReminder;
      } else {
        $result['status'] = 'failed';
        $result['sql_error'] = mysqli_error($conn);
        $result['nama'] = $nama;
        $result['tgl_reminder'] = $tglReminder;
      }
    }
    echo json_encode($result);
    break;
  
  default:
    # code...
    break;
}
?>