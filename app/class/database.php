<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'reseller_madu';

$conn = mysqli_connect($host,$username,$password,$database);

if (!$conn) {
	echo "Connect error :\n" . mysqli_connect_error($conn);
}
?>