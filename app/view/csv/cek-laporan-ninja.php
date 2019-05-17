<?php
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="'.date('Ymd-His').'-cek-laporan-ninja.csv";');

require('app/class/database.php');

$sql = "SELECT no_resi
        FROM kasir_mst
        JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
        JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
        JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
        JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi
        WHERE no_resi != '' AND tgl_transaksi between '$startDate' AND '$endDate 23:59:00'";
$query = mysqli_query($conn,$sql);
$total = mysqli_num_rows($query);
if ($total == 0) {
  $dataResult[] = array('Data Kosong');
} else {
  $dataResult = array();
  while ($row = mysqli_fetch_array($query)) {
    $dataResult[] = array($row['no_resi']);
  }
}

$f = fopen('php://output', 'w');
foreach ($dataResult as $line) {
  fputcsv($f, $line);
}
?>