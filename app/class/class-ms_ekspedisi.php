<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$case = $_POST['case'];
if (isset($_POST['id'])) {
  $id = $_POST['id'];
}

switch ($case) {
  case 'list_data':
    $data["data"] = array();
    $sql = "SELECT * FROM ms_ekspedisi WHERE aktif='1'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;
  
  case 'edit_data':
    $sql = "SELECT * FROM ms_ekspedisi WHERE id_ekspedisi='$id'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;

  case 'delete_data':
    $data['nama'] = $_POST['nama'];
    $sql = "UPDATE ms_ekspedisi SET aktif='0' WHERE id_ekspedisi='$id'";
    if (mysqli_query($conn,$sql)) {
      $data['status'] = 'success';
    } else {
      $data['status'] = 'failed';
      $data['error'] = mysqli_error($conn);
    }
    echo json_encode($data);
    break;
}


?>