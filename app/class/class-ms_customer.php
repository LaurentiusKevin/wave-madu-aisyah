<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$case = $_POST['case'];
if (isset($_POST['id'])) {
  $id = $_POST['id'];
}

switch ($case) {
  case 'list_customer':
    $data["data"] = array();
    $sql = "SELECT * FROM ms_customer WHERE aktif='1'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;


  case 'delete_customer':
    $data['nama'] = $_POST['name'];
    $sql = "UPDATE ms_customer SET aktif='0' WHERE id_customer='$id'";
    if (mysqli_query($conn,$sql)) {
      $data['status'] = 'success';
    } else {
      $data['status'] = 'failed';
    }
    echo json_encode($data);
    break;
}

?>