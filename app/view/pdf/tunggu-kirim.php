<?php
date_default_timezone_set("Asia/Jakarta");
require('app/class/database.php');

if ($id == 'all') {
  $startDate = $data[3];
  $endDate = $data[4];
  $sql = "SELECT no_transaksi, tgl_transaksi, 
        cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi,
        reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,grand_total
        FROM kasir_mst
        JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
        JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
        JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
        JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
        WHERE tgl_transaksi BETWEEN '$startDate' AND '$endDate 23:30:00'";
} else {
  $noTransaksi = explode('-',$id);
  $strTransaksi = implode("','",$noTransaksi);
  $sql = "SELECT no_transaksi, tgl_transaksi, 
        cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi,
        reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,grand_total
        FROM kasir_mst
        JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
        JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
        JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
        JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
        WHERE no_transaksi IN ('$strTransaksi')";
}

$data = array();

$query = mysqli_query($conn,$sql);
$i = 0;
while ($row = mysqli_fetch_array($query)) {
  array_push($data,
    array(
      'no_transaksi' => $row['no_transaksi'],
      'customer' => $row['customer'],
      'tgl_transaksi' => date('H:i:s  d/m/Y', strtotime($row['tgl_transaksi']))
    )
  );
  $i++;
}
// print_r($data);

$tran = '';
$i = 1;

foreach ($data as $k => $v) {
  $tran .= '<tr>
              <td>'.$i.'.</td>
              <td>'.$data[$k]['no_transaksi'].'</td>
              <td>'.$data[$k]['customer'].'</td>
              <td>'.$data[$k]['tgl_transaksi'].'</td>
            </tr>';
  $i++;
}

// echo '<pre>';
// print_r($tran);
// echo '</pre>';

// echo '<table>';
// echo $tran;
// echo '</table>';

$html = '
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Per Barang</title>
  <style>
  #transaksi table {
    border-collapse: collapse;
    width: 100%;
  }
  
  #transaksi th, td {
    text-align: left;
    padding: 3px;
  }
  
  #transaksi tr:nth-child(even){background-color: #f2f2f2}
  
  #transaksi th {
    background-color: #191966;
    color: #ffffff;
    text-align: center;
  }
  </style>
</head>';

$html .= '
<body>
  <table id="transaksi" width="100%">
    <tr>
      <th width="5%" align="center">No</th>
      <th width="15%">#Invoice</th>
      <th width="60%">Nama Customer</th>
      <th width="20%">Tanggal Transaksi</th>
    </tr>
    '.$tran.'
  </table>
</body>
</html>
';

require_once 'assets/pdf/vendor/autoload.php';
// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf([
  'mode' => 'utf-8',
  'format' => 'A4',
  'default_font' => '',
  'margin_top' => 35,
  'margin_bottom' => 20,
  'margin_left' => 5,
  'margin_right' => 5,
  'margin_header' => 5,
  'margin_footer' => 5
  ]);

// Create Header & Footer
$mpdf->SetHTMLHeader('
<table width="100%">
  <tr>
    <td width="15%" rowspan="3">LOGO</td>
    <td width="45%"><strong>PROSES PENGIRIMAN</strong></td>
    <td width="40%" align="right"><strong>RESELLER MADU</strong></td>
  </tr>
  <tr>
    <td width="45%">Form: '.$form.'</td>
    <td width="40%" align="right">Jl. Mintojiwo Timur No. 35</td>
  </tr>
  <tr>
    <td width="45%">Tercetak: '.date('H:i:s - d M Y').'</td>
    <td width="40%" align="right"></td>
  </tr>
</table>');
$mpdf->SetHTMLFooter('
<table width="100%">
  <tr>
    <td width="33%">{DATE j-m-Y}</td>
    <td width="33%" align="center">{PAGENO}/{nbpg}</td>
    <td width="33%" style="text-align: right;">WAVE Solusi Indonesia</td>
  </tr>
  <tr style="background-color: lightgrey;">
    <td colspan="3" align="center">nota ini tidak membutuhkan tanda tangan karena tercetak secara otomatis melalui sistem.</td>
  </tr>
</table>');

// Write some HTML code:
$mpdf->WriteHTML($html);

// Output a PDF file directly to the browser
$mpdf->Output();
?>