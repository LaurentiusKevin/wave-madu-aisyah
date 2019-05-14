<?php
include_once('database.php');

$id = $_POST['set_id'];
$nama = $_POST['input_nama'];
$alamat = $_POST['input_alamat'];
$telp = $_POST['input_telp'];
$nama_pic = $_POST['input_nama_pic'];
$telp_pic = $_POST['input_telp_pic'];
$email_pic = $_POST['input_email_pic'];
$data['nama'] = $nama;

// JIKA BARU
if ($id == 'new') {
  $sql = "INSERT INTO ms_ekspedisi(nama, alamat, telp, nama_pic, telp_pic, email_pic) 
          VALUES ('$nama','$alamat','$telp','$nama_pic','$telp_pic','$email_pic')";
  if (mysqli_query($conn,$sql)) {
    $data['status'] = 'success';
  } else {
    $data['status'] = 'failed';
  }
  echo json_encode($data);
} else {
  // JIKA UPDATE
  $sql = "UPDATE ms_ekspedisi 
          SET nama='$nama',alamat='$alamat',telp='$telp',nama_pic='$nama_pic',telp_pic='$telp_pic',email_pic='$email_pic'
          WHERE id_ekspedisi='$id'";
  if (mysqli_query($conn,$sql)) {
    $data['status'] = 'success';
  } else {
    $data['status'] = 'failed';
  }
  echo json_encode($data);
}
?>