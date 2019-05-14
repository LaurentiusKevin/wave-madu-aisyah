<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$case = $_POST['case'];
if (isset($_POST['id'])) {
  $id = $_POST['id'];
}
$data = array();

switch ($case) {
  case 'list':
    $sql = "SELECT * FROM ms_product WHERE aktif='1'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;
  case 'edit_product':
    $sql = "SELECT * FROM ms_product WHERE id_product = '$id'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_array($query)) {
      $data['id_product'] = $row['id_product'];
      $data['nama'] = $row['nama'];
      $data['harga'] = $row['harga'];
      $data['potongan_harga'] = $row['potongan_harga'];
      $data['reminder'] = $row['reminder'];
      $data['berat'] = $row['berat'];
    }
    echo json_encode($data);
    break;

  case 'delete_product':
    $data['name'] = $_POST['name'];
    $sql = "UPDATE ms_product SET aktif='0' WHERE id_product='$id'";
    if (mysqli_query($conn,$sql)) {
      $data['status'] = 'success';
    } else {
      $data['status'] = 'failed';
    }
    echo json_encode($data);
    break;
}

?>