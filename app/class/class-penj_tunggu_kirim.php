<?php
session_start();
// error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
require('database.php');

if (isset($_POST['case'])) { $case = $_POST['case']; }
if (isset($_POST['id'])) { $id = $_POST['id']; }

switch ($case) {
  case 'list':
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $status = $_POST['status'];
    if ($status == 'all') {
      $sql = "SELECT no_transaksi, DATE_FORMAT(tgl_transaksi,'%d %M %Y, Pk %H:%i:%s') AS tgl_transaksi, 
              cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi,
              reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,
              FORMAT(grand_total,2) AS grand_total, no_resi
              FROM kasir_mst
              JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
              JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
              JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
              JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
              WHERE tgl_transaksi BETWEEN '$startDate' AND '$endDate 23:30:00'";
    } else {
      $sql = "SELECT no_transaksi, DATE_FORMAT(tgl_transaksi,'%d %M %Y, Pk %H:%i:%s') AS tgl_transaksi, 
              cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi,
              reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,
              FORMAT(grand_total,2) AS grand_total, no_resi
              FROM kasir_mst
              JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
              JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
              JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
              JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
              WHERE kasir_mst.status_kirim='$status' AND tgl_transaksi BETWEEN '$startDate' AND '$endDate 23:30:00'";
    }
    $query = mysqli_query($conn,$sql);
    $totalRow = mysqli_num_rows($query);
    if ($totalRow > 0) {
      while ($row = mysqli_fetch_assoc($query)) {
        $result["data"][] = array_map("utf8_encode", $row);
      }
    } else {
      $result["data"] = '';
    }
    
    echo json_encode($result);
    break;

  case 'kirim':
    $noTransaksi = $_POST['noTransaksi'];
    $stringTran = implode("','",$noTransaksi);
    $tglKirim = date('Y-m-d H:i:s');
    $sql = "UPDATE kasir_mst SET status_kirim='1',tgl_kirim='$tglKirim' WHERE no_transaksi IN ('$stringTran')";
    if (mysqli_query($conn,$sql)) {
      $result['status'] = 'success';
      $result['no_transaksi'] = implode('-',$noTransaksi);
    } else {
      $result['status'] = 'failed';
      $result['sql_error'] = mysqli_error($conn);
      $result['no_transaksi'] = implode('-',$noTransaksi);
    }
    echo json_encode($result);
    break;

  case 'kirim-all':
    $status = $_POST['input_status'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $tglKirim = date('Y-m-d H:i:s');
    $sql = "UPDATE kasir_mst SET status_kirim='1', tgl_kirim='$tglKirim' WHERE tgl_transaksi BETWEEN '$startDate' AND '$endDate'";
    if (mysqli_query($conn,$sql)) {
      $result['status'] = 'success';
      // $result['no_transaksi'] = implode('-',$noTransaksi);
    } else {
      $result['status'] = 'failed';
      $result['sql_error'] = mysqli_error($conn);
      // $result['no_transaksi'] = implode('-',$noTransaksi);
    }
    echo json_encode($result);
    break;
    
  default:
    # code...
    break;
}
?>