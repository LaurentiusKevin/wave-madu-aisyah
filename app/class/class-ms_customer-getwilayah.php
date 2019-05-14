<?php
include_once('database.php');

$page = $_POST['p'];
if(isset($_POST['id'])) {
  $id = $_POST['id'];
} else {
  $id = '';
}
$data = '<option selected>Pilih ...</option>';

switch ($page) {
  case 'provinsi':
    $sql = "SELECT * FROM wilayah_provinsi ORDER BY nama";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_array($query)) {
      $data .= '<option value="'.$row['id_provinsi'].'">'.$row['nama'].'</option>';
    }
    break;

  case 'kota':
    $sql = 'SELECT * FROM wilayah_kota WHERE id_provinsi="'.$id.'" ORDER BY nama';
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_array($query)) {
      $data .= '<option value="'.$row['id_kota'].'">'.$row['nama'].'</option>';
    }
    break;

  case 'kecamatan':
    $sql = 'SELECT * FROM wilayah_kecamatan WHERE id_kota="'.$id.'" ORDER BY nama';
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_array($query)) {
      $data .= '<option value="'.$row['id_kecamatan'].'">'.$row['nama'].'</option>';
    }
    break;
}

echo $data;
?>