<?php
// error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Jakarta");
require('database.php');

$case = $_POST['case'];

switch ($case) {
  case 'list':
    $sql = "SELECT no_transaksi, DATE_FORMAT(tgl_transaksi,'%d %M %Y, Pk %H:%i:%s') AS tgl_transaksi, 
            DATE_FORMAT(tgl_kirim,'%d %M %Y, Pk %H:%i:%s') AS tgl_kirim,
            cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi,
            reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,
            FORMAT(grand_total,2) AS grand_total, no_resi
            FROM kasir_mst
            JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
            JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
            JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
            JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
            WHERE status_kirim IN ('1','2')";
    $query = mysqli_query($conn,$sql);
    $total = mysqli_num_rows($query);
    if ($total < 1) {
      $result["data"] = '';
    } else {
      while ($row = mysqli_fetch_assoc($query)) {
        $result["data"][] = array_map("utf8_encode", $row);
      }
    }
    echo json_encode($result);
    break;
  
  case 'update-status-pengiriman':
    require('plugin/simple_html_dom.php');
    $html = file_get_html("https://berdu.id/cek-resi?courier=ninja&code=NVIDMDAIS190420014&secret=RUzROx");
    $headlines = $html -> find("div[class=cekresi_table view div]")[0];

    echo $headlines;
    break;

  default:
    # code...
    break;
}
?>