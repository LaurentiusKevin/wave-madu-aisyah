<?php
session_start();
$url = str_replace('/reseller-madu','',$_SERVER['REQUEST_URI']);


if ($url == '/') {
	$page = 'overview';
	require('app/view/home/vw-home.html');
} else {
	if (!isset($_SESSION['username'])) {
		require('app/view/login/layout.php');
	} else {
		$url = substr($url,1);
		$data = explode('/',$url);
		$total = count($data);
		switch ($data[0]) {
			case 'laporan':
				$page = $data[0];
				$form = $data[1];
				$startDate = $data[2];
				$endDate = $data[3];
				$seller = $data[4];
				$produk = $data[5];
				switch ($form) {
					case 'cek-laporan-ninja':
						require('app/view/csv/layout.php');
						break;
					
					default:
						require('app/view/pdf/layout.php');
						break;
				}
				break;

			case 'cetak':
				$page = $data[0];
				$form = $data[1];
				$id = $data[2];
				require('app/view/pdf/layout.php');
				break;

			case 'export':
				$page = $data[0];
				$form = $data[1];
				if (isset($data[2])) { $startDate = $data[2]; }
				if (isset($data[3])) { $endDate = $data[3]; }
				if (isset($data[4])) { $id = $data[4]; }
				require('app/view/csv/layout.php');
				break;

			case 'admin':
				$page = 'overview';
				require('app/view/dashboard/layout.php');

				break;

			default:
				$page = $data[0];
				if (file_exists('app/view/dashboard/vw-'.$data[0].'.html')) {
					require('app/view/dashboard/layout.php');
				} else {
					require('app/view/404.html');
				}

				break;
		}
	}
}
?>
