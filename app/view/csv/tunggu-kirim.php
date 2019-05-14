<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.date('Y-m-d - H-i').' - tunggu kirim.xlsx"');

require('app/class/database.php');

$fp = fopen('php://output', 'w');
// $data[] = array(
//   'No Transaksi', 'Shipper order ID', 'name', 'add 1', 'add 2', 'Telp', 'COD', 'Insurance', 'Email', 'Instruksi', 'Size'
// );

if ($data[4] == 'all') {

  $sql = "SELECT no_transaksi, tgl_transaksi, 
          cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi, cust.nomor_wa,
          reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,grand_total
          FROM kasir_mst
          JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
          JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
          JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
          JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
          WHERE tgl_transaksi BETWEEN '$startDate' AND '$endDate 23:30:00'";
} else {
  $noTransaksi = explode('-',$id);
  $no = implode("','",$noTransaksi);
  $sql = "SELECT no_transaksi, tgl_transaksi, 
          cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi, cust.nomor_wa,
          reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,grand_total
          FROM kasir_mst
          JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
          JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
          JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
          JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
          WHERE no_transaksi IN ('$no') AND tgl_transaksi BETWEEN '$startDate' AND '$endDate 23:30:00'";
}
$query = mysqli_query($conn,$sql);

$i = 1;
while ($row = mysqli_fetch_assoc($query)) {
  $result[] = array(
    'no_transaksi' => $row['no_transaksi'],
    'shipper_id' => date('ymd').str_pad($i,3,'0',STR_PAD_LEFT),
    'name' => $row['customer'],
    'add1' => $row['alamat'].', '.$row['kecamatan'].', '.$row['kota'].', '.$row['provinsi'],
    'add2' => '',
    'telp' => $row['nomor_wa'],
    'cod' => $row['grand_total'],
    'insurance' => '',
    'email' => '',
    'instruksi' => 'TOLONG TELFON DULU UNTUK JANJIAN SEBELUM MENGANTAR BIAR GAK BOLAK BALIK',
    'size' => 'S'
  );
  $i++;
}

// print_r($result);

// foreach ($data as $key => $value) {
//   fputcsv($fp, $value);
// }

require 'app/class/plugin/PHPSpreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

// SET PROPERTIES
$spreadsheet->getProperties()
    ->setCreator("WAVE SOLUSI INDONESIA")
    ->setLastModifiedBy("WAVE SOLUSI INDONESIA")
    ->setTitle("Sistem Informasi Kasir Online")
    ->setSubject("Tunggu Kirim")
    ->setDescription("Tunggu Kirim");
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'No Transaksi');
$sheet->setCellValue('B1', 'Shipper order ID');
$sheet->setCellValue('C1', 'name');
$sheet->setCellValue('D1', 'add 1');
$sheet->setCellValue('E1', 'add 2');
$sheet->setCellValue('F1', 'Telp');
$sheet->setCellValue('G1', 'COD');
$sheet->setCellValue('H1', 'Insurance');
$sheet->setCellValue('I1', 'Email');
$sheet->setCellValue('J1', 'Instruksi');
$sheet->setCellValue('K1', 'Size');

for ($i=0; $i < count($result); $i++) { 
  $no = $i+2;
  $sheet->setCellValue('A'.$no, $result[$i]['no_transaksi']);
  $sheet->setCellValue('B'.$no, $result[$i]['shipper_id']);
  $sheet->setCellValue('C'.$no, $result[$i]['name']);
  $sheet->setCellValue('D'.$no, $result[$i]['add1']);
  $sheet->setCellValue('E'.$no, $result[$i]['add2']);
  $sheet->setCellValue('F'.$no, $result[$i]['telp']);
  $sheet->setCellValue('G'.$no, $result[$i]['cod']);
  $sheet->setCellValue('H'.$no, $result[$i]['insurance']);
  $sheet->setCellValue('I'.$no, $result[$i]['email']);
  $sheet->setCellValue('J'.$no, $result[$i]['instruksi']);
  $sheet->setCellValue('K'.$no, $result[$i]['size']);
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

?>