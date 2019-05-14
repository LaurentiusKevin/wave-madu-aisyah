<?php
include_once('database.php');

$id = $_POST['set_id'];

$nama = $_POST['input_nama'];
$nomor = $_POST['input_nomor'];
$selectedProvinsi = $_POST['input_provinsi'];
$selectedKota = $_POST['input_kota'];
$selectedKecamatan = $_POST['input_kecamatan'];
$alamat = $_POST['input_alamat'];
if ($id == 'new') {
  $sql = "SELECT wilayah_provinsi.nama AS 'provinsi', wilayah_kota.nama AS 'kota', wilayah_kecamatan.nama AS 'kecamatan' FROM wilayah_provinsi, wilayah_kota, wilayah_kecamatan
  WHERE wilayah_provinsi.id_provinsi = '$selectedProvinsi' AND
  wilayah_kota.id_kota = '$selectedKota' AND
  wilayah_kecamatan.id_kecamatan = '$selectedKecamatan'";
  $query = mysqli_query($conn,$sql);
  while ($row = mysqli_fetch_array($query)) {
    $provinsi = $row['provinsi'];
    $kota = $row['kota'];
    $kecamatan = $row['kecamatan'];
  }
  $sql = "INSERT INTO ms_customer(nama, provinsi, kota, kecamatan, alamat, nomor_wa) VALUES ('$nama','$provinsi','$kota','$kecamatan','$alamat','$nomor')";
  $data['nama'] = $nama;
  if (mysqli_query($conn,$sql)) {
    $data['status'] = 'success';
    echo json_encode($data);
  } else {
    $data['status'] = 'success';
    $data['error'] = mysqli_error($conn);
    echo json_encode($data);
  }
} else {
  $sql = "SELECT wilayah_provinsi.nama AS 'provinsi', wilayah_kota.nama AS 'kota', wilayah_kecamatan.nama AS 'kecamatan' FROM wilayah_provinsi, wilayah_kota, wilayah_kecamatan
  WHERE wilayah_provinsi.id_provinsi = '$selectedProvinsi' AND
  wilayah_kota.id_kota = '$selectedKota' AND
  wilayah_kecamatan.id_kecamatan = '$selectedKecamatan'";
  $query = mysqli_query($conn,$sql);
  while ($row = mysqli_fetch_array($query)) {
    $provinsi = $row['provinsi'];
    $kota = $row['kota'];
    $kecamatan = $row['kecamatan'];
  }
  $sql = "UPDATE ms_customer SET nama='$nama',provinsi='$provinsi',kota='$kota',kecamatan='$kecamatan',alamat='$alamat',nomor_wa='$nomor' WHERE id_customer='$id'";
  $data['nama'] = $nama;
  if (mysqli_query($conn,$sql)) {
    $data['status'] = 'success';
    echo json_encode($data);
  } else {
    $data['status'] = 'success';
    $data['error'] = mysqli_error($conn);
    echo json_encode($data);
  }
}
?>