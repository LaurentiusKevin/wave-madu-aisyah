<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.date('Y-m-d - H-i').' - reminder.xlsx"');

require('app/class/database.php');

$fp = fopen('php://output', 'w');
// $data[] = array(
//   'No Transaksi', 'Shipper order ID', 'name', 'add 1', 'add 2', 'Telp', 'COD', 'Insurance', 'Email', 'Instruksi', 'Size'
// );

$sql = "SELECT id_reminder, no_transaksi, 
					id_customer, nama_customer, nomor_wa, 
					p.nama AS produk, tgl_beli, tgl_reminder, tipe
				FROM ms_reminder
				JOIN ms_product AS p ON ms_reminder.id_produk=p.id_product
				WHERE ms_reminder.aktif='1' 
					AND tgl_reminder BETWEEN '$startDate' AND '$endDate'";
$query = mysqli_query($conn,$sql);
$i = 1;
for ($result = array (); 
		$row = mysqli_fetch_assoc($query); 
		$result[] = $row);

require 'app/class/plugin/PHPSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

// SET PROPERTIES
$spreadsheet->getProperties()
    ->setCreator("WAVE SOLUSI INDONESIA")
    ->setLastModifiedBy("WAVE SOLUSI INDONESIA")
    ->setTitle("Sistem Informasi Kasir Online")
    ->setSubject("Reminder WhatsApp")
    ->setDescription("Reminder WhatsApp");
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'id_reminder');
$sheet->setCellValue('B1', 'no_transaksi');
$sheet->setCellValue('C1', 'id_customer');
$sheet->setCellValue('D1', 'nama_customer');
$sheet->setCellValue('E1', 'nomor_wa');
$sheet->setCellValue('F1', 'produk');
$sheet->setCellValue('G1', 'tgl_beli');
$sheet->setCellValue('H1', 'tgl_reminder');

for ($i=0; $i < count($result); $i++) { 
  $no = $i+2;
  $sheet->setCellValue('A'.$no, $result[$i]['id_reminder']);
  $sheet->setCellValue('B'.$no, $result[$i]['no_transaksi']);
  $sheet->setCellValue('C'.$no, $result[$i]['id_customer']);
  $sheet->setCellValue('D'.$no, $result[$i]['nama_customer']);
  $sheet->setCellValue('E'.$no, $result[$i]['nomor_wa']);
  $sheet->setCellValue('F'.$no, $result[$i]['produk']);
  $sheet->setCellValue('G'.$no, $result[$i]['tgl_beli']);
  $sheet->setCellValue('H'.$no, $result[$i]['tgl_reminder']);
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

?>