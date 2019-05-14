<?php
include_once('database.php');

$id = $_POST['case'];
$nama = $_POST['input_nama'];
$harga = $_POST['input_harga'];
$potongan = $_POST['input_potongan'];
$reminder = $_POST['input_reminder'];
$berat = $_POST['input_berat'];
$data['nama'] = $nama;

if ($id == 'new') {
  $sql = "INSERT INTO ms_product(nama, harga, potongan_harga, reminder, berat)
          VALUES ('$nama','$harga','$potongan','$reminder','$berat')";
  if (mysqli_query($conn,$sql)) {
    $data['status'] = 'success';
    echo json_encode($data);
  } else {
    $data['status'] = 'failed';
    $data['error'] = mysqli_error($conn);
    echo json_encode($data);
  }
} else {
  $sql = "UPDATE ms_product
          SET nama='$nama',harga='$harga',potongan_harga='$potongan',reminder='$reminder',berat='$berat' 
          WHERE id_product='$id'";
  if (mysqli_query($conn,$sql)) {
    $data['status'] = 'success';
    echo json_encode($data);
  } else {
    $data['status'] = 'failed';
    $data['error'] = mysqli_error($conn);
    echo json_encode($data);
  }
}
?>