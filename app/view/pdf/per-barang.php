<?php
require('app/class/database.php');

// echo $startDate.' || ';
// echo $endDate.' || ';
// echo $seller.' || ';
// echo $produk;
$data = array();

if ($produk == 'all' && $seller == 'all') {
  $sql = "SELECT kasir_trn.no_transaksi,s.nama AS seller,tanggal,username AS kasir,
          product.nama AS product,kasir_trn.harga,kasir_trn.potongan_harga,jumlah,sub_harga
          FROM kasir_trn
          JOIN ms_product AS product ON kasir_trn.id_product=product.id_product
          JOIN kasir_mst AS mst ON kasir_trn.no_transaksi=mst.no_transaksi
          JOIN ms_reseller AS s ON mst.id_subreseller=s.id_reseller
          WHERE tanggal BETWEEN '$startDate' AND '$endDate 23:59:00'";
} elseif($produk != 'all' && $seller == 'all') {
  $sql = "SELECT kasir_trn.no_transaksi,s.nama AS seller,tanggal,username AS kasir,
          product.nama AS product,kasir_trn.harga,kasir_trn.potongan_harga,jumlah,sub_harga
          FROM kasir_trn
          JOIN ms_product AS product ON kasir_trn.id_product=product.id_product
          JOIN kasir_mst AS mst ON kasir_trn.no_transaksi=mst.no_transaksi
          JOIN ms_reseller AS s ON mst.id_subreseller=s.id_reseller
          WHERE tanggal BETWEEN '$startDate' AND '$endDate 23:59:00' AND kasir_trn.id_product='$produk'";
} elseif($produk == 'all' && $seller != 'all') {
  $sql = "SELECT kasir_trn.no_transaksi,s.nama AS seller,tanggal,username AS kasir,
          product.nama AS product,kasir_trn.harga,kasir_trn.potongan_harga,jumlah,sub_harga
          FROM kasir_trn
          JOIN ms_product AS product ON kasir_trn.id_product=product.id_product
          JOIN kasir_mst AS mst ON kasir_trn.no_transaksi=mst.no_transaksi
          JOIN ms_reseller AS s ON mst.id_subreseller=s.id_reseller
          WHERE tanggal BETWEEN '$startDate' AND '$endDate 23:59:00' AND mst.id_subreseller='$seller'";
} else {
  $sql = "SELECT kasir_trn.no_transaksi,s.nama AS seller,tanggal,username AS kasir,
          product.nama AS product,kasir_trn.harga,kasir_trn.potongan_harga,jumlah,sub_harga
          FROM kasir_trn
          JOIN ms_product AS product ON kasir_trn.id_product=product.id_product
          JOIN kasir_mst AS mst ON kasir_trn.no_transaksi=mst.no_transaksi
          JOIN ms_reseller AS s ON mst.id_subreseller=s.id_reseller
          WHERE tanggal BETWEEN '$startDate' AND '$endDate 23:59:00' AND kasir_trn.id_product='$produk' AND mst.id_subreseller='$seller'";
}
$query = mysqli_query($conn,$sql);
$i = 0;
while ($row = mysqli_fetch_array($query)) {
  array_push($data,
    array(
      'no_transaksi' => $row['no_transaksi'],
      'seller' => $row['seller'],
      'tanggal' => date('H:i - d/m/Y',strtotime($row['tanggal'])),
      'kasir' => $row['kasir'],
      'product' => $row['product'],
      'harga' => number_format($row['harga'],2,',','.'),
      'potongan_harga' => number_format($row['potongan_harga'],2,',','.'),
      'jumlah' => $row['jumlah'],
      'sub_harga' => $row['sub_harga']
    )
  );
  $i++;
}

$tran = '';
$total = 0;

foreach ($data as $key => $value) {
  $tran .= '<tr>
              <td>'.$data[$key]['no_transaksi'].'</td>
              <td>'.$data[$key]['seller'].'</td>
              <td>'.$data[$key]['tanggal'].'</td>
              <td>'.$data[$key]['kasir'].'</td>
              <td>'.$data[$key]['product'].'</td>
              <td align="right">'.$data[$key]['harga'].'</td>
              <td align="right">'.$data[$key]['potongan_harga'].'</td>
              <td align="center">'.$data[$key]['jumlah'].'</td>
              <td align="right">'.number_format($data[$key]['sub_harga'],2,',','.').'</td>
            </tr>';
  $total += $data[$key]['sub_harga'];
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
    background-color: #e6e6e6;
    text-align: center;
  }
  </style>
</head>';

$html .= '
<body>
  <table id="transaksi" width="100%">
    <tr>
      <th width="10%">#</th>
      <th width="15%">seller</th>
      <th width="14%">tanggal</th>
      <th width="6%">kasir</th>
      <th width="15%">product</th>
      <th width="10%">harga</th>
      <th width="10%">potongan</th>
      <th width="5%">qty</th>
      <th width="10%">sub harga</th>
    </tr>
    '.$tran.'
    <tr>
      <th colspan="6"></th>
      <th>total</th>
      <th colspan="2">'.number_format($total,2,',','.').'</th>
    </tr>
  </table>
</body>
</html>
';

require_once 'assets/pdf/vendor/autoload.php';
// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf([
  'mode' => 'utf-8',
  'format' => 'A4',
  'orientation' => 'L',
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
    <td width="45%"><strong>LAPORAN PENJUALAN</strong></td>
    <td width="40%" align="right"><strong>RESELLER MADU</strong></td>
  </tr>
  <tr>
    <td width="45%">Laporan: '.$form.', Range: '.$startDate.' - '.$endDate.'</td>
    <td width="40%" align="right">Jl. Mintojiwo Timur No. 35</td>
  </tr>
  <tr>
    <td width="45%">PER BARANG</td>
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