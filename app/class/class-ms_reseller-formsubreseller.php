<?php
include_once('database.php');

$data = '<option value="">Pilih ...</option>';
$sql = 'SELECT * FROM ms_reseller WHERE id_header_reseller = ""';
$query = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_array($query)) {
  $data .= '<option value="'.$row['id_reseller'].'">'.$row['nama'].'</option>';
}

echo $data;
?>