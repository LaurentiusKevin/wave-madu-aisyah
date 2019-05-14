<?php
// Require composer autoload
include_once('app/class/database.php');
include_once('app/class/terbilang.php');

$sql = "SELECT no_transaksi, tgl_transaksi, 
cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi,
reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,grand_total
FROM kasir_mst
JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
WHERE no_transaksi='$id'";
$query = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_assoc($query)) {
  $result['no'] = $row['no_transaksi'];
  $result['tanggal'] = date('d M Y', strtotime($row['tgl_transaksi']));
  $result['customer'] = $row['customer'];
  $result['alamat'] = $row['alamat'];
  $result['kecamatan'] = $row['kecamatan'];
  $result['kota'] = $row['kota'];
  $result['provinsi'] = $row['provinsi'];
  $result['reseller'] = $row['reseller'];
  $result['subreseller'] = $row['subreseller'];
  $result['ekspedisi'] = $row['ekspedisi'];
  $result['total'] = number_format($row['grand_total'],2,',','.');
  $terbilang = terbilang($row['grand_total']);
}

$tran = '';
$number = 1;
$sql = "SELECT p.nama AS 'produk',kasir_trn.harga AS 'harga',kasir_trn.potongan_harga AS 'potongan',jumlah,sub_harga
FROM kasir_trn
JOIN ms_product AS p ON kasir_trn.id_product=p.id_product
WHERE no_transaksi='$id'";
$query = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_assoc($query)) {
  $tran .= '<tr>
    <td align="center">'.$number.'.</td>
    <td>'.$row['produk'].'</td>
    <td align="right">'.number_format($row['harga'],2,',','.').'</td>
    <td align="right">'.number_format($row['potongan'],2,',','.').'</td>
    <td align="center">'.$row['jumlah'].'</td>
    <td align="right">'.number_format($row['sub_harga'],2,',','.').'</td>
  </tr>';
  $number++;
}
// echo "<pre>";
// print_r($result);
// echo "</pre>";

$html = '
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style>
  body
  {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
  }
  td
  {
    vertical-align: top;
    text-align: left;
  }
  .brand
  {
    text-align: right;
    font-weight: bold;
  }
  .title
  {
    font-size: 14px;
  }
  .detail
  {
    font-size: 13px;
  }
  .table
  {
    padding-top: 20px;
  }
  table.transaction
  {
    /* font-family: arial, sans-serif; */
    border-collapse: collapse;
    width: 100%;
  }
  table.transaction tr td
  {
    border: 2px solid #dddddd;
    text-align: left;
    padding: 8px;
  }
  </style>
</head>';

$html .= '
<body>
  <table width="100%">
    <tr>
      <td width="30%">
        <table>
          <tr>
            <td colspan="3">
              <strong>INVOICE</strong>
            </td>
          </tr>
          <tr class="detail">
            <td>Number</td>
            <td>:</td>
            <td>'.$result['no'].'</td>
          </tr>
          <tr class="detail">
            <td>Date</td>
            <td>:</td>
            <td>'.$result['tanggal'].'</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <table class="table" width="100%">
    <tr>
      <td width="50%">
        <table>
          <tr>
            <td colspan="3">
              <strong>CUSTOMER DETAIL</strong>
            </td>
          </tr>
          <tr class="detail">
            <td>Nama</td>
            <td>:</td>
            <td>'.$result['customer'].'</td>
          </tr>
          <tr class="detail">
            <td>Alamat</td>
            <td>:</td>
            <td>'.$result['alamat'].'</td>
          </tr>
          <tr class="detail">
            <td>Kecamatan</td>
            <td>:</td>
            <td>'.$result['kecamatan'].'</td>
          </tr>
          <tr class="detail">
            <td>Kota/Kabupaten</td>
            <td>:</td>
            <td>'.$result['kota'].'</td>
          </tr>
          <tr class="detail">
            <td>Provinsi</td>
            <td>:</td>
            <td>'.$result['provinsi'].'</td>
          </tr>
        </table>
      </td>
      <td width="5%"></td>
      <td width="45%">
        <table>
          <tr>
            <td colspan="3">
              <strong>PAYMENT DETAIL</strong>
            </td>
          </tr>
          <tr class="detail">
            <td>Kasir</td>
            <td>:</td>
            <td>Admin</td>
          </tr>
          <tr class="detail">
            <td>Reseller</td>
            <td>:</td>
            <td>'.$result['reseller'].'</td>
          </tr>
          <tr class="detail">
            <td>Sub Reseller</td>
            <td>:</td>
            <td>'.$result['subreseller'].'</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <p><strong>PURCHASE DETAIL</strong></p>
  <table class="transaction">
    <tr>
      <td width="5%">No</td>
      <td width="30%">Produk</td>
      <td width="20%">Harga</td>
      <td width="20%">Potongan</td>
      <td width="5%">Qty</td>
      <td width="20%">Sub Harga</td>
    </tr>
    '.$tran.'
    <tr style="background-color: dodgerblue;">
      <td colspan="3">Harga sudah termasuk PPN (10%)</td>
      <td colspan="2"><strong>Grand Total</strong></td>
      <td align="right"><strong>'.$result['total'].'</strong></td>
    </tr>
    <tr style="background-color: lightgrey;">
      <td colspan="6">Terbilang : <span style="font-style:italic;">'.$terbilang.' Rupiah</span></td>
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
  'default_font' => '',
  'margin_top' => 10,
  'margin_bottom' => 10,
  'margin_left' => 5,
  'margin_right' => 5,
  'margin_header' => 5,
  'margin_footer' => 5
  ]);

// Create Header & Footer
$mpdf->SetHTMLHeader('<div style="text-align: right; font-weight: bold;">
RESELLER MADU
</div>');
$mpdf->SetHTMLFooter('
<table width="100%">
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