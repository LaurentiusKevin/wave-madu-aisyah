<?php
include_once('database.php');

$id = $_POST['id_reseller'];
$page = $_POST['p'];

switch ($page) {
  case 'reseller':
    if ($id == 'new') {
      $nama = $_POST['input_nama'];
      $alamat = $_POST['input_alamat'];
      $telp = $_POST['input_telp'];
      $sql = 'INSERT INTO ms_reseller(nama, alamat, id_header_reseller, no_hp) VALUES 
              ("'.$nama.'", "'.$alamat.'", "", "'.$telp.'")';
      if (mysqli_query($conn,$sql)) {
        echo "success";
      } else {
        echo "--- failed ---".mysqli_error($conn);
      }
    } else {
      $nama = $_POST['input_nama'];
      $alamat = $_POST['input_alamat'];
      $telp = $_POST['input_telp'];
      $sql = 'UPDATE ms_reseller SET 
              nama="'.$nama.'", 
              alamat="'.$alamat.'", 
              no_hp="'.$telp.'" 
              WHERE id_reseller="'.$id.'"';
      if (mysqli_query($conn,$sql)) {
        echo "success";
      } else {
        echo "--- failed ---".mysqli_error($conn);
      }
    }
    break;
  
  case 'sub_reseller':
    if ($id == 'new') {
      $nama = $_POST['input_nama'];
      $alamat = $_POST['input_alamat'];
      $reseller = $_POST['input_reseller'];
      $telp = $_POST['input_telp'];
      $sql = 'INSERT INTO ms_reseller(nama, alamat, id_header_reseller, no_hp) VALUES 
              ("'.$nama.'", "'.$alamat.'", "'.$reseller.'", "'.$telp.'")';
      if (mysqli_query($conn,$sql)) {
        echo "success";
      } else {
        echo "--- failed ---".mysqli_error($conn);
      }
    } else {
      $nama = $_POST['input_nama'];
      $alamat = $_POST['input_alamat'];
      $reseller = $_POST['input_reseller'];
      $telp = $_POST['input_telp'];
      $sql = 'UPDATE ms_reseller SET 
              nama="'.$nama.'", 
              alamat="'.$alamat.'", 
              id_header_reseller="'.$reseller.'", 
              no_hp="'.$telp.'" 
              WHERE id_reseller="'.$id.'"';
      if (mysqli_query($conn,$sql)) {
        echo "success";
      } else {
        echo "--- failed ---".mysqli_error($conn);
      }
    }
    break;
}
?>