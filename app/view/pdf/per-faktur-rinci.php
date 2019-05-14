<?php
require('app/class/database.php');

// echo $startDate.' || ';
// echo $endDate.' || ';
// echo $seller.' || ';
// echo $produk;
$data = array();

$sql = "SELECT no_transaksi, tgl_transaksi, 
        cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi,
        reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,grand_total
        FROM kasir_mst
        JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
        JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
        JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
        JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
        WHERE tgl_transaksi between '$startDate' AND '$endDate 23:59:00'";
$query = mysqli_query($conn,$sql);
$i = 0;
while ($row = mysqli_fetch_array($query)) {
  array_push($data,
    array(
      'detail' => array(
        'no_transaksi' => $row['no_transaksi'],
        'tgl_transaksi' => date('d/m/Y H:i',strtotime($row['tgl_transaksi'])),
        'customer' => $row['customer'],
        'reseller' => $row['reseller'],
        'subreseller' => $row['subreseller'],
        'ekspedisi' => $row['ekspedisi'],
        'grand_total' => $row['grand_total']
      ),
      'transaksi' => array()
    )
  );
  $i++;
}

foreach ($data as $key => $value) {
  $noTransaksi = $data[$key]['detail']['no_transaksi'];
  $sql = "SELECT no_transaksi, product.nama, kasir_trn.harga, kasir_trn.potongan_harga, jumlah, sub_harga
          FROM kasir_trn
          JOIN ms_product AS product ON kasir_trn.id_product = product.id_product
          WHERE no_transaksi='$noTransaksi'";
  $query = mysqli_query($conn,$sql);
  while ($row = mysqli_fetch_array($query)) {
    array_push($data[$key]['transaksi'], 
      array(
        'nama' => $row['nama'],
        'harga' => $row['harga'],
        'potongan_harga' => $row['potongan_harga'],
        'jumlah' => $row['jumlah'],
        'sub_harga' => $row['sub_harga']
        )
    );
  }
}

// echo '<pre>';
// print_r($data);
// echo '</pre>';

$count = 1;
$countProd = 1;
$tran = '';
foreach ($data as $i => $v) {
  # border-style: none none dotted none;
  $tran .= '<table>
              <tr class="detail">
                <td width="5%" align="center">'.$count.'.</td>
                <td width="30%">No. Tran. : '.$data[$i]['detail']['no_transaksi'].'</td>
                <td width="65%">Tanggal : '.$data[$i]['detail']['tgl_transaksi'].'</td>
              </tr>
            </table>';
  foreach ($data[$i]['transaksi'] as $key => $value) {
    $harga = number_format($data[$i]['transaksi'][$key]['harga'],2,',','.');
    $potongan = number_format($data[$i]['transaksi'][$key]['potongan_harga'],2,',','.');
    $subHarga = number_format($data[$i]['transaksi'][$key]['sub_harga'],2,',','.');
    $tran .= '<table style="border-top: 1pt dotted black;">
                <tr>
                  <td width="5%"></td>
                  <td width="5%">'.$countProd.'.</td>
                  <td width="25%">'.$data[$i]['transaksi'][$key]['nama'].'</td>
                  <td width="20%">'.$harga.'</td>
                  <td width="20%">'.$potongan.'</td>
                  <td width="5%">'.$data[$i]['transaksi'][$key]['jumlah'].'</td>
                  <td width="20%">'.$subHarga.'</td>
                </tr>
              </table>';
    $countProd++;
  }
  $grandTotal = number_format($data[$i]['detail']['grand_total'],2,',','.');
  $tran .= '<table class="detail-bottom" style="border-top: 1pt dotted black; border-bottom: 1pt solid black;">
              <tr>
                <td width="5%"></td>
                <td width="10%">Sales</td>
                <td width="20%">: '.$data[$i]['detail']['subreseller'].'</td>
                <td width="25%"></td>
                <td width="20%"></td>
                <td width="20%"></td>
              </tr>
              <tr>
                <td width="5%"></td>
                <td width="10%">Cust</td>
                <td width="20%">: '.$data[$i]['detail']['customer'].'</td>
                <td width="25%"></td>
                <td width="20%">Grand Total</td>
                <td width="20%">: '.$grandTotal.'</td>
              </tr>
            </table>';
  // if (($count % 8) == 0) {
  //   $tran .= '<pagebreak>';
  // }
  $count++;
  $countProd = 1;
}
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
  <title>Document</title>
  <style>
  table
  {
    border-collapse: collapse;
    width: 100%;
  }
  body
  {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 12px;
  }
  .detail td
  {
    border-top: 1pt solid black;
  }
  .detail-bottom tr:nth-child(even)
  {
    border-bottom: 1pt solid black;
  }
  </style>
</head>';

$html .= '
<body>
  <table style="border-top: 1pt solid black;">
    <tr>
      <th width="5%">No</th>
      <th width="30%">Produk</th>
      <th width="20%">Harga</th>
      <th width="20%">Potongan Harga</th>
      <th width="5%">Qty</th>
      <th width="20%">Sub Harga</th>
    </tr>
  </table>
  '.$tran.'
</body>
</html>
';

require_once 'assets/pdf/vendor/autoload.php';
// Create an instance of the class:
$mpdf = new \Mpdf\Mpdf([
  'mode' => 'utf-8',
  'format' => 'A4',
  'default_font' => '',
  'margin_top' => 25,
  'margin_bottom' => 20,
  'margin_left' => 5,
  'margin_right' => 5,
  'margin_header' => 5,
  'margin_footer' => 5
  ]);

// Create Header & Footer
$mpdf->SetHTMLHeader('
<table>
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
    <td width="45%">PER FAKTUR RINCI</td>
    <td width="40%" align="right"></td>
  </tr>
</table>');
$mpdf->SetHTMLFooter('
<table>
  <tr>
    <td width="33%">{DATE j-m-Y}</td>
    <td width="33%" align="center">{PAGENO}/{nbpg}</td>
    <td width="33%" style="text-align: right;">RESELLER MADU</td>
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