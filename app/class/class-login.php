<?php
session_start();
include_once('database.php');

$inputUsername = $_POST['input_username'];
$inputPassword = hash('sha3-512',$_POST['input_password']);
$result = '';

$sql = "SELECT * FROM ms_user WHERE username='$inputUsername' AND aktif='1'";
$query = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_array($query)) {
  $username = $row['username'];
  $password = $row['password'];
  $namaLengkap = $row['nama_lengkap'];
  $telp = $row['telp'];
}
if ($password == $inputPassword) {
  $result = array(
    'status' => 'success'
  );

  // INSERT USER DATA TO SESSION
  $_SESSION['username'] = $username;
  $_SESSION['nama_lengkap'] = $namaLengkap;
  $_SESSION['telp'] = $telp;
  include_once('class-login_getmenu.php');
  $_SESSION['sidebar'] = $data;

  // SIDEBAR SELECTED
  $sqlMenuSelected = 'SELECT m.id_menu, m.nama, m.link, m.menu_order, mg.target
  FROM ms_permission
  INNER JOIN ms_menu AS m ON ms_permission.id_menu = m.id_menu
  INNER JOIN ms_menugroup AS mg ON m.id_menugroup = mg.id_menugroup
  WHERE ms_permission.username = "$username"';
  $querySelectedMenu = mysqli_query($conn,$sqlMenuSelected);
  while ($row = mysqli_fetch_array($querySelectedMenu)) {
    $_SESSION['menu_selected'][$row['link']] = $row['target'];
  }

} else {
  $result = array(
    'status' => 'failed',
    // 'sql' => $sql
  );
}

echo json_encode($result);
?>