<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.date('Y-m-d - H-i').' - reminder.xlsx"');

require('app/class/database.php');

$fp = fopen('php://output', 'w');
// $data[] = array(
//   'No Transaksi', 'Shipper order ID', 'name', 'add 1', 'add 2', 'Telp', 'COD', 'Insurance', 'Email', 'Instruksi', 'Size'
// );

$sql = "SELECT id_reminder, ms_reminder.no_transaksi AS 'no_transaksi', k.no_resi AS 'no_resi', k.grand_total AS 'harga', c.alamat AS 'alamat',
ms_reminder.id_customer, nama_customer, ms_reminder.nomor_wa AS 'wa', 
p.nama AS produk, tgl_beli, tgl_reminder, tipe
FROM ms_reminder
JOIN ms_product AS p ON ms_reminder.id_produk=p.id_product
      JOIN kasir_mst AS k ON ms_reminder.no_transaksi=k.no_transaksi
      LEFT JOIN ms_customer AS c ON ms_reminder.id_customer=c.id_customer
					AND tgl_reminder BETWEEN '$startDate' AND '$endDate 23:50:00'";
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
$sheet->setCellValue('A1', 'nama');
$sheet->setCellValue('B1', 'resi');
$sheet->setCellValue('C1', 'harga');
$sheet->setCellValue('D1', 'alamat');
$sheet->setCellValue('E1', 'wa');

for ($i=0; $i < count($result); $i++) { 
  $no = $i+2;
  $sheet->setCellValue('A'.$no, $result[$i]['nama_customer']);
  $sheet->setCellValue('B'.$no, $result[$i]['no_resi']);
  $sheet->setCellValue('C'.$no, $result[$i]['harga']);
  $sheet->setCellValue('D'.$no, $result[$i]['alamat']);
  $sheet->setCellValue('E'.$no, $result[$i]['wa']);
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

?>