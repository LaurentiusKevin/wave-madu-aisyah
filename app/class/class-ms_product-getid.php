<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$data = array();
$id = $_POST['id'];
$sql = 'SELECT * FROM ms_product WHERE id_product="'.$id.'"';
$query = mysqli_query($conn,$sql);

while ($row = mysqli_fetch_assoc($query)) {
  $data = array_map("utf8_encode", $row);
}

echo json_encode($data);
?>