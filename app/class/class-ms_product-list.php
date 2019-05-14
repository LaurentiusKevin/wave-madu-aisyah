<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$data["data"] = array();
$sql = "SELECT * FROM ms_product WHERE aktif='1'";
$query = mysqli_query($conn,$sql);

while ($row = mysqli_fetch_assoc($query)) {
	$data["data"][] = array_map("utf8_encode", $row);
}

// print_r($data);
echo json_encode($data);
?>