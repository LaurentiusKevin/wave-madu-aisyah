<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Jakarta");
require('database.php');

$case = $_POST['case'];
if (isset($_POST['id'])) { $id = $_POST['id']; }

switch ($case) {
  case 'list':
    $sql = "SELECT no_transaksi,
            DATE_FORMAT(tgl_transaksi,'%d %M %Y, Pk %H:%i:%s') AS tgl_transaksi,
            DATE_FORMAT(tgl_kirim,'%d %M %Y, Pk %H:%i:%s') AS tgl_kirim,
            cust.nama AS customer, cust.alamat, cust.kecamatan, cust.kota, cust.provinsi,
            reseller.nama AS reseller,sub.nama AS subreseller,ekspedisi.nama AS ekspedisi,
            FORMAT(grand_total,2) AS grand_total, no_resi
            FROM kasir_mst
            JOIN ms_customer AS cust ON kasir_mst.id_customer=cust.id_customer
            JOIN ms_reseller AS reseller ON kasir_mst.id_reseller=reseller.id_reseller
            JOIN ms_reseller AS sub ON kasir_mst.id_subreseller=sub.id_reseller
            JOIN ms_ekspedisi AS ekspedisi ON kasir_mst.id_ekspedisi=ekspedisi.id_ekspedisi";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $result["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($result);
    break;

  case 'detail-transaksi':
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
      $data['info_transaksi'] = array(
        'no_transaksi' => $row['no_transaksi'],
        'tgl_transaksi' => $row['tgl_transaksi'],
        'customer' => $row['customer'],
        'alamat' => $row['alamat'],
        'kecamatan' => $row['kecamatan'],
        'kota' => $row['kota'],
        'provinsi' => $row['provinsi'],
        'reseller' => $row['reseller'],
        'subreseller' => $row['subreseller'],
        'ekspedisi' => $row['ekspedisi'],
        'grand_total' => number_format($row["grand_total"],2,",",".")
      );
    }
    $sql = "SELECT p.nama AS 'produk',kasir_trn.harga AS 'harga',kasir_trn.potongan_harga AS 'potongan',jumlah,sub_harga
            FROM kasir_trn
            JOIN ms_product AS p ON kasir_trn.id_product=p.id_product
            WHERE no_transaksi='$id'";
    $query = mysqli_query($conn,$sql);
    $table = '';
    $no = 1;
    while ($row = mysqli_fetch_assoc($query)) {
      $table .= '<tr>
                  <td align="center">'.$no.'.</td>
                  <td>'.$row["produk"].'</td>
                  <td align="right">'.number_format($row["harga"],2,",",".").'</td>
                  <td align="right">'.number_format($row["potongan"],2,",",".").'</td>
                  <td align="center">'.$row["jumlah"].'</td>
                  <td align="right">'.number_format($row["sub_harga"],2,",",".").'</td>
                </tr>';
      $no++;
    }
    $data['detail_transaksi'] = $table;
    echo json_encode($data);
    break;

  default:
    # code...
    break;
}
?>
