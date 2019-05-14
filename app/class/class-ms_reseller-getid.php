<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$data = array();
$id = $_POST['id'];
$sql = 'SELECT * FROM ms_reseller WHERE id_reseller="'.$id.'"';
$query = mysqli_query($conn,$sql);

while ($row = mysqli_fetch_assoc($query)) {
  $data = array_map("utf8_encode", $row);
}

$container = '';
if ($data['id_header_reseller'] != '') {
  $sql = 'SELECT * FROM ms_reseller WHERE id_header_reseller=""';
  $query = mysqli_query($conn,$sql);
  while ($row = mysqli_fetch_array($query)) {
    if ($row['id_reseller'] == $data['id_header_reseller']) {
      $container .= '<option value="'.$row['id_reseller'].'" selected>'.$row['nama'].'</option>';
    } else {
      $container .= '<option value="'.$row['id_header_reseller'].'">'.$row['nama'].'</option>';
    }
  }
  $data['id_header_reseller'] = $container;
}

echo json_encode($data);
?>