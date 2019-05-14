<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');
session_start();

$case = $_POST['case'];
$result = array();

switch ($case) {
	case 'list':
		$sql = "SELECT username, nama_lengkap, telp, 
					DATE_FORMAT(date_create,'%d %M %Y, Pk %H:%i:%s') AS 'date_create',
					IF(aktif='1','aktif','disabled') AS 'status' 
				FROM ms_user";
		$query = mysqli_query($conn,$sql);
		while ($row = mysqli_fetch_assoc($query)) {
		$data["data"][] = array_map("utf8_encode", $row);
		}
		echo json_encode($data);
		break;

	case 'menu':
		$username = $_SESSION['username'];
		if ($username != 'admin') {
			$sql = "SELECT g.id_menugroup AS 'groupID',
						g.nama AS 'groupName',
						g.group_order AS 'groupOrder',
						ms_menu.id_menu AS 'menuID',
						ms_menu.nama AS 'menuName',
						ms_menu.menu_order AS 'menuOrder'
					FROM `ms_menu`
					JOIN ms_menugroup AS g ON ms_menu.id_menugroup = g.id_menugroup
					WHERE g.id_menugroup NOT IN ('admin')
					ORDER BY g.group_order ASC, ms_menu.menu_order ASC";
		} else {
			$sql = "SELECT g.id_menugroup AS 'groupID',
					g.nama AS 'groupName',
					g.group_order AS 'groupOrder',
					ms_menu.id_menu AS 'menuID',
					ms_menu.nama AS 'menuName',
					ms_menu.menu_order AS 'menuOrder'
					FROM `ms_menu`
					JOIN ms_menugroup AS g ON ms_menu.id_menugroup = g.id_menugroup
					ORDER BY g.group_order ASC, ms_menu.menu_order ASC";
		}
		$query = mysqli_query($conn,$sql);
		while ($row = mysqli_fetch_array($query)) {
			$groupID = $row['groupOrder'];
			$groupName = $row['groupName'];
			$menuOrder = $row['menuOrder'];
			$menuID = $row['menuID'];
			$menuName = $row['menuName'];
			
			$result[$groupID]['groupName'] = $groupName;
			$result[$groupID]['menu'][$menuOrder]['ID'] = $menuID;
			$result[$groupID]['menu'][$menuOrder]['name'] = $menuName;
		}
		echo json_encode($result);
		break;

	case 'edit':
		$username = $_POST['username'];
		$sql = "SELECT username,nama_lengkap,telp FROM ms_user WHERE username = '$username'";
		$query = mysqli_query($conn,$sql);
		for ($result['info'] = array (); $row = mysqli_fetch_assoc($query); $result['info'][] = $row);

		$sql = "SELECT id_menu FROM ms_permission WHERE username = '$username' AND aktif='1'";
		$query = mysqli_query($conn,$sql);
		for ($result['permission'] = array (); $row = mysqli_fetch_assoc($query); $result['permission'][] = $row);

		echo json_encode($result);
		break;

	case 'new':
		$username = $_POST['input_username'];
		$password = hash('sha3-512',$_POST['input_password']);
		$namalengkap = $_POST['input_namalengkap'];
		$telp = $_POST['input_telp'];
		$menuSelected = $_POST['menu'];
		$counter = 0;

		$sql = "INSERT INTO ms_user (username,password,nama_lengkap,telp)
				VALUES ('$username','$password','$namalengkap','$telp')";
		if (mysqli_query($conn,$sql)) {
			$sql = "SELECT id_menu FROM ms_menu";
			$query = mysqli_query($conn,$sql);
			while ($row = mysqli_fetch_array($query)) {
				$listMenu[] = $row['id_menu'];
			}
			foreach ($listMenu as $key => $value) {
				if (in_array($value, $menuSelected)) {
					$sql = "INSERT INTO ms_permission (username,id_menu,aktif)
							VALUES ('$username','$value','1')";
				} else {
					$sql = "INSERT INTO ms_permission (username,id_menu,aktif)
							VALUES ('$username','$value','0')";
				}
				if (mysqli_query($conn,$sql)) {
					$counter++;
				}
			}
		}
		if ($counter == count($listMenu)) {
			$result = array(
				'status' => 'success'
			);
		} else {
			$result = array(
				'status' => 'failed',
				'mysql_error' => mysqli_error($conn),
				'php_error' => error_log()
			);
		}
		echo json_encode($result);
		break;

	case 'update':
		$username = $_POST['input_username'];
		$namalengkap = $_POST['input_namalengkap'];
		$telp = $_POST['input_telp'];
		$menuSelected = $_POST['menu'];
		$counter = 0;
		if (isset($_POST['input_password'])) {
			$password = hash('sha3-512',$_POST['input_password']);
			$sql = "UPDATE ms_user 
					SET password='$password', 
					nama_lengkap='$namalengkap', 
					telp='$telp' 
					WHERE username='$username' ";
		} else {
			$sql = "UPDATE ms_user 
					SET nama_lengkap='$namalengkap', 
					telp='$telp' 
					WHERE username='$username' ";
		}
		if (mysqli_query($conn,$sql)) {
			$sql = "SELECT id_menu FROM ms_menu";
			$query = mysqli_query($conn,$sql);
			while ($row = mysqli_fetch_array($query)) {
				$listMenu[] = $row['id_menu'];
			}
			$sql = "DELETE FROM ms_permission WHERE username='$username'";
			if (mysqli_query($conn,$sql)) {
				foreach ($listMenu as $key => $value) {
					if (in_array($value, $menuSelected)) {
						$sql = "INSERT INTO ms_permission (username,id_menu,aktif)
								VALUES ('$username','$value','1')";
					} else {
						$sql = "INSERT INTO ms_permission (username,id_menu,aktif)
								VALUES ('$username','$value','0')";
					}
					if (mysqli_query($conn,$sql)) {
						$counter++;
					}
				}
			}
		}
		if ($counter == count($listMenu)) {
			$result = array(
				'status' => 'success'
			);
		} else {
			$result = array(
				'status' => 'success',
				'mysql_error' => mysqli_error($conn),
				'php_error' => error_log()
			);
		}
		echo json_encode($result);
		break;

	case 'delete':
		$username = $_POST['username'];
		$sql = "UPDATE ms_user SET aktif='0' WHERE username='$username'";
		if (mysqli_query($conn,$sql)) {
			$result = array(
				'status' => 'success',
				'username' => $username
			);
		} else {
			$result = array(
				'status' => 'failed',
				'username' => $username
			);
		}
		echo json_encode($result);
		break;
}
?>