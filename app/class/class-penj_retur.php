<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$page = $_POST['p'];

switch ($page) {
  case 'get_transaction':
    if (isset($_POST['m']) && isset($_POST['y'])) {
      $monthNow = $_POST['m'];
      $yearNow = $_POST['y'];
    } else {
      $monthNow = date('n');
      $yearNow = date('Y');
    }

    $data["data"] = array() ;
    $sql = 'SELECT no_transaksi, tgl_transaksi, cust.nama AS "id_customer", res.nama AS "id_reseller", subres.nama AS "id_subreseller", expe.nama AS "id_ekspedisi", grand_total
    FROM kasir_mst
    INNER JOIN ms_customer AS cust ON kasir_mst.id_customer = cust.id_customer
    INNER JOIN ms_reseller AS res ON kasir_mst.id_reseller = res.id_reseller
    INNER JOIN ms_reseller AS subres ON kasir_mst.id_subreseller = subres.id_reseller
    INNER JOIN ms_ekspedisi AS expe ON kasir_mst.id_ekspedisi = expe.id_ekspedisi
    WHERE YEAR(tgl_transaksi) = '.$yearNow.' AND MONTH(tgl_transaksi) = '.$monthNow;
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $row['action'] = '<button class="btn btn-primary btn-sm" onclick="returProduk(\''.$row['no_transaksi'].'\')"><i class="fas fa-edit"></i></button>';
      $data["data"][] = array_map("utf8_encode", $row);
    }
    
    echo json_encode($data);
    break;
  
  case 'filtered_transaction':
    $monthNow = $_POST['m'];
    $yearNow = $_POST['y'];
    $data = array() ;
    $sql = 'SELECT no_transaksi, tgl_transaksi, cust.nama AS "id_customer", res.nama AS "id_reseller", subres.nama AS "id_subreseller", expe.nama AS "id_ekspedisi", subtotal, grand_total
    FROM kasir_mst
    INNER JOIN ms_customer AS cust ON kasir_mst.id_customer = cust.id_customer
    INNER JOIN ms_reseller AS res ON kasir_mst.id_reseller = res.id_reseller
    INNER JOIN ms_reseller AS subres ON kasir_mst.id_subreseller = subres.id_reseller
    INNER JOIN ms_ekspedisi AS expe ON kasir_mst.id_ekspedisi = expe.id_ekspedisi
    WHERE YEAR(tgl_transaksi) = '.$yearNow.' AND MONTH(tgl_transaksi) = '.$monthNow;
    $query = mysqli_query($conn,$sql);
    $total = mysqli_num_rows($query);
    if ($total == 0) {
      echo 'empty';
    } else {
      while ($row = mysqli_fetch_assoc($query)) {
        $row['action'] = '<button class="btn btn-primary btn-sm" onclick="returProduk(\''.$row['no_transaksi'].'\')"><i class="fas fa-edit"></i></button>';
        $data[] = array_map("utf8_encode", $row);
      }
      echo json_encode($data);
    }
    break;

  case 'get_transaction_id':
    $noTransaksi = $_POST['id'];

    // MASTER
    $sql = 'SELECT no_transaksi, tgl_transaksi, cust.nama AS "id_customer", res.nama AS "id_reseller", subres.nama AS "id_subreseller", expe.nama AS "id_ekspedisi", grand_total
    FROM kasir_mst
    INNER JOIN ms_customer AS cust ON kasir_mst.id_customer = cust.id_customer
    INNER JOIN ms_reseller AS res ON kasir_mst.id_reseller = res.id_reseller
    INNER JOIN ms_reseller AS subres ON kasir_mst.id_subreseller = subres.id_reseller
    INNER JOIN ms_ekspedisi AS expe ON kasir_mst.id_ekspedisi = expe.id_ekspedisi
    WHERE no_transaksi="'.$noTransaksi.'"';
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data['master'] = array_map("utf8_encode", $row);
    }

    // TRANSACTION
    $counterRow = "0";
    $counter = 0;
    $sql = 'SELECT kasir_trn.id_product AS "id_product", prod.nama AS "produk", kasir_trn.harga, kasir_trn.potongan_harga,jumlah, sub_harga
    FROM kasir_trn
    INNER JOIN ms_product AS prod ON kasir_trn.id_product = prod.id_product
    WHERE no_transaksi = "'.$noTransaksi.'" AND sub_harga > 0';
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $sqlQty = 'SELECT jumlah FROM kasir_trn WHERE no_transaksi = "'.$noTransaksi.'" AND id_product = "'.$row['id_product'].'" AND sub_harga < 0';
      $queryQty = mysqli_query($conn,$sqlQty);
      $totalQty = mysqli_num_rows($queryQty);
      if ($totalQty > 0) {
        while ($rowQty = mysqli_fetch_array($queryQty)) {
          $row['jumlah'] = $row['jumlah'] - $rowQty['jumlah'];
        }
      }
      $counter++;
      $produk = $row['produk'];
      $opsi = $row['potongan_harga'];
      $harga = $row['harga'];
      $qty = $row['jumlah'];
      $subHarga = $row['sub_harga'];
      
      $row['produk'] = '<input type="text" class="form-control form-control-sm" value="'.$row['id_product'].'" id="produk_'.$counter.'" hidden>'.$produk;
      $row['opsi'] = '<input type="text" class="form-control form-control-sm" value="'.$opsi.'" id="opsi_'.$counter.'" readonly>';
      $row['harga'] = '<input type="text" class="form-control form-control-sm" value="'.$harga.'" id="harga_'.$counter.'" readonly>';
      $row['jumlah'] = '<input type="number" class="form-control form-control-sm" min="0" max="'.$qty.'" value="0" id="jumlah_'.$counter.'" onchange="updateSubTotal(\''.$counter.'\')">';
      $row['sub_harga'] = '<input type="text" class="form-control form-control-sm" id="subharga_'.$counter.'" readonly>';

      if ($qty > 0) {
        $data['transaction'][] = array_map("utf8_encode", $row);
        $counterRow .= ",".$counter;
      }
      
    }
    $data['counter_row'] = $counterRow;
    $data['error'] = mysqli_error($conn);
    echo json_encode($data);
    break;

  case 'submit':
    $counter = 0;
    // INSERT TRN
    $noTransaksi = $_POST['noTrans'];
    $produk = $_POST['produk'];
    $opsi = $_POST['opsi'];
    $harga = $_POST['harga'];
    $qty = $_POST['qty'];
    $total = $_POST['total'];
    for ($i=0; $i < count($produk); $i++) { 
      $sql = 'INSERT INTO kasir_trn(no_transaksi, id_product, opsi, harga, jumlah, sub_harga) VALUES ("'.$noTransaksi.'","'.$produk[$i].'","'.$opsi[$i].'","'.$harga[$i].'","'.$qty[$i].'","'.$total[$i].'")';
      if (mysqli_query($conn,$sql)) {
        $counter++;
      } else {
        echo "failed<br>".mysqli_error($conn);
        break;
      }
    }
    if ($counter == count($produk)) {
      $data['status'] = 'success';
      $data['no_transaksi'] = $noTransaksi;
    } else {
      $data['status'] = 'gagal menyimpan transaksi';
    }
    echo json_encode($data);
    break;
}
?>