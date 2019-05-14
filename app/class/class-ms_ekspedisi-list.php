<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$data["data"] = array();
$sql = 'SELECT id_ekspedisi,nama FROM ms_ekspedisi';
$query = mysqli_query($conn,$sql);

while ($row = mysqli_fetch_assoc($query)) {
	$data["data"][] = array_map("utf8_encode", $row);
}

echo json_encode($data);
?>