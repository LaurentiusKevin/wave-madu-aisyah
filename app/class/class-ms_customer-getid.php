<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$data = array();
$id = $_POST['id'];
$sql = 'SELECT * FROM ms_customer WHERE id_customer="'.$id.'"';
$query = mysqli_query($conn,$sql);

while ($row = mysqli_fetch_assoc($query)) {
  $data = array_map("utf8_encode", $row);
}

// GET PROVINSI DATA
$container = '';
$sql = 'SELECT * FROM wilayah_provinsi';
$query = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_array($query)) {
  if ($row['nama'] == $data['provinsi']) {
    $container .= '<option value="'.$row['id_provinsi'].'" selected>'.$row['nama'].'</option>';
    $provinsi = $row['id_provinsi'];
  } else {
    $container .= '<option value="'.$row['id_provinsi'].'">'.$row['nama'].'</option>';
  }
}
$data['provinsi'] = $container;

// GET KOTA DATA
$container = '';
$sql = 'SELECT * FROM wilayah_kota WHERE id_provinsi = "'.$provinsi.'"';
$query = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_array($query)) {
  if ($row['nama'] == $data['kota']) {
    $container .= '<option value="'.$row['id_kota'].'" selected>'.$row['nama'].'</option>';
    $kota = $row['id_kota'];
  } else {
    $container .= '<option value="'.$row['id_kota'].'">'.$row['nama'].'</option>';
  }
}
$data['kota'] = $container;

// GET KECAMATAN DATA
$container = '';
$sql = 'SELECT * FROM wilayah_kecamatan WHERE id_kota = "'.$kota.'"';
$query = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_array($query)) {
  if ($row['nama'] == $data['kecamatan']) {
    $container .= '<option value="'.$row['id_kecamatan'].'" selected>'.$row['nama'].'</option>';
  } else {
    $container .= '<option value="'.$row['id_kecamatan'].'">'.$row['nama'].'</option>';
  }
}
$data['kecamatan'] = $container;

// print $data['provinsi'];
echo json_encode($data);
?>