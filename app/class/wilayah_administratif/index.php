<?php
include_once('../database.php');

$file = fopen('provinces.csv','r');
echo "------ PROVINSI ------";
while (! feof($file)) {
  $row = fgetcsv($file);
  $sql = 'INSERT INTO wilayah_provinsi(id_provinsi, nama) VALUES ("'.$row[0].'","'.$row[1].'")';
  if($query = mysqli_query($conn,$sql)) {
    echo $row[0].' - '.$row[1];
  }
}
fclose($file);

$file = fopen('regencies.csv','r');
echo "------ KOTA ------";
while (! feof($file)) {
  $row = fgetcsv($file);
  $sql = 'INSERT INTO wilayah_kota(id_kota, id_provinsi, nama) VALUES ("'.$row[0].'","'.$row[1].'","'.$row[2].'")';
  if($query = mysqli_query($conn,$sql)) {
    echo $row[0].' - '.$row[1].' - '.$row[2];
  }
}
fclose($file);

$file = fopen('districts.csv','r');
echo "------ KECAMATAN ------";
while (! feof($file)) {
  $row = fgetcsv($file);
  $sql = 'INSERT INTO wilayah_kecamatan(id_kecamatan, id_kota, nama) VALUES ("'.$row[0].'","'.$row[1].'","'.$row[2].'")';
  if($query = mysqli_query($conn,$sql)) {
    echo $row[0].' - '.$row[1].' - '.$row[2];
  }
}
fclose($file);

$file = fopen('villages.csv','r');
echo "------ KELURAHAN ------";
while (! feof($file)) {
  $row = fgetcsv($file);
  $sql = 'INSERT INTO wilayah_kelurahan(id_kelurahan, id_kecamatan, nama) VALUES ("'.$row[0].'","'.$row[1].'","'.$row[2].'")';
  if($query = mysqli_query($conn,$sql)) {
    echo $row[0].' - '.$row[1].' - '.$row[2];
  }
}
fclose($file);
?>