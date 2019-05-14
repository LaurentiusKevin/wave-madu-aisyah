<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

if (isset($_POST['case'])) {
  $case = $_POST['case'];
} else {
  $case = 'get_all';
}
if (isset($_POST['id'])) {
  $id = $_POST['id'];
}

switch ($case) {
  case 'get_customer':
    // $container = '<option>Pilih ...</option>';
    $sql = "SELECT * FROM ms_customer WHERE aktif='1'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      // $container .= '<option value="'.$row['id_customer'].'">'.$row['nama'].'</option>';
      $data["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;

  case 'get_reseller':
    $sql = "SELECT * FROM ms_reseller
            WHERE id_header_reseller=id_reseller";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;

  case 'get_subreseller':
    $sql = "SELECT * FROM ms_reseller
            WHERE id_header_reseller='$id'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;

  case 'get_ekspedisi':
    $sql = "SELECT * FROM ms_ekspedisi
            WHERE aktif='1'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;

  case 'nama_produk':
    $data = '<option>Pilih ...</option>';
    $sql = 'SELECT id_product,nama FROM ms_product';
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data .= '<option value="'.$row['id_product'].'">'.$row['nama'].'</option>';
    }
    echo $data;
    break;

  case 'get_harga':
    $sql = "SELECT harga,potongan_harga FROM ms_product WHERE id_product='$id'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_array($query)) {
      $data['harga'] = $row['harga'];
      $data['potongan_harga'] = $row['potongan_harga'];
    }
    echo json_encode($data);
    break;

  case 'submit_data':
    $tglBeli = date('Y-m-d H:i:s');
    $yearmonth = date('ym');
    $idNumber = str_pad('1','4','0', STR_PAD_LEFT);
    $sql = "SELECT no_transaksi FROM kasir_mst WHERE no_transaksi LIKE '$yearmonth%' ORDER BY no_transaksi DESC LIMIT 1";
    $query = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($query);
    if (!$row) {
      $noTransaksi = $yearmonth.'K'.$idNumber;
    } else {
      $container = $row['no_transaksi'];
      $replace = substr($container,5);
      // $removeZero = str_replace('0','',$replace);
      $idNumber = ((int) $replace) + 1;
      $noTransaksi = $yearmonth.'K'.str_pad($idNumber,'4','0', STR_PAD_LEFT);
    }
    $data['no_transaksi'] = $noTransaksi;
    $data['customer'] = $_POST['input_customer'];
    $idCustomer = $_POST['input_customer'];
    $sql = "SELECT * FROM ms_customer WHERE id_customer='".$data['customer']."'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_array($query)) {
      $namaCustomer = $row['nama'];
      $noWa = $row['nomor_wa'];
    }
    $data['reseller'] = $_POST['input_reseller'];
    $data['subreseller'] = $_POST['input_subreseller'];
    $data['ekspedisi'] = $_POST['input_ekspedisi'];
    $data['total_harga'] = $_POST['total_harga'];
    $data['produk'] = $_POST['produk'];
    // print_r($data);
    // print_r($data['produk']['\'nama\'']);
    // INSERT MST
    $sql = "INSERT INTO kasir_mst(no_transaksi,id_customer,id_reseller,id_subreseller,id_ekspedisi,grand_total) 
            VALUES ('".$data['no_transaksi']."','".$data['customer']."','".$data['reseller']."','".$data['subreseller']."','".$data['ekspedisi']."','".str_replace(',','',$data['total_harga'])."')";
    if (mysqli_query($conn,$sql)) {
      // INSERT TRN
      $counter = 0;
      for ($i=0; $i < count($data['produk']['\'nama\'']); $i++) { 
        $sql = "SELECT reminder FROM ms_product WHERE id_product='".$data['produk']['\'nama\''][$i]."'";
        $query = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($query)) {
          $reminder = $row['reminder']*$data['produk']['\'qty\''][$i];
        }
        $days = "+".$reminder." days";
        $tglReminder = date('Y-m-d', strtotime($days,strtotime($tglBeli)));
        $sql = "INSERT INTO kasir_trn(no_transaksi,username,id_product,harga,potongan_harga,jumlah,sub_harga) 
                VALUES ('".$data['no_transaksi']."',
                '".$_SESSION['username']."',
                '".$data['produk']['\'nama\''][$i]."',
                '".str_replace(',','',$data['produk']['\'harga\''][$i])."',
                '".str_replace(',','',$data['produk']['\'potongan_harga\''][$i])."',
                '".$data['produk']['\'qty\''][$i]."',
                '".str_replace(',','',$data['produk']['\'total\''][$i])."')";
        $sqlReminder = "INSERT INTO ms_reminder(no_transaksi,id_customer,nama_customer,nomor_wa,
                        id_produk,tgl_beli,tgl_reminder,tipe) 
                        VALUES ('$noTransaksi','".$data['customer']."','$namaCustomer','$noWa',
                        '".$data['produk']['\'nama\''][$i]."',
                        '$tglBeli','$tglReminder','hot')";
        if (mysqli_query($conn,$sql) && mysqli_query($conn,$sqlReminder)) {
          $counter++;
        }
      }

      if ($counter == count($data['produk']['\'nama\''])) {
        $result['no_transaksi'] = $noTransaksi;
        $result['status'] = "success";
      } else {
        $result['status'] = "failed MST";
        $result['error'] = mysqli_error($conn);
      }
    } else {
      $result['status'] = "failed".$noTransaksi;
      $result['error'] = mysqli_error($conn);
    }
    echo json_encode($result);
    break;
}
?>