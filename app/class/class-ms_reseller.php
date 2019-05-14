<?php
header('Content-Type: text/html; charset=utf-8');
include_once('database.php');

$case = $_POST['case'];
if (isset($_POST['id'])) {
  $id = $_POST['id'];
}

switch ($case) {
  case 'list':
    $sql = "SELECT reseller.id_reseller,reseller.nama,reseller.alamat,sub.nama AS 'subreseller_dari',reseller.no_hp, if(reseller.id_header_reseller=reseller.id_reseller,'form_reseller','form_subreseller') AS 'option' 
    FROM ms_reseller as reseller
    JOIN ms_reseller as sub
    ON reseller.id_header_reseller = sub.id_reseller
    WHERE reseller.aktif = '1'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data["data"][] = array_map("utf8_encode", $row);
    }
    echo json_encode($data);
    break;

  case 'edit_reseller':
    $sql = "SELECT nama,alamat,no_hp
            FROM ms_reseller
            WHERE id_reseller='$id'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data['nama'] = $row['nama'];
      $data['alamat'] = $row['alamat'];
      $data['no_hp'] = $row['no_hp'];
    }
    echo json_encode($data);
    // echo $sql;
    break;

  case 'edit_subreseller':
    $reseller = '<option value="">Pilih ...</option>';
    $sql = "SELECT id_reseller,nama
            FROM ms_reseller
            WHERE id_reseller=id_header_reseller";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_array($query)) {
      $reseller .= '<option value="'.$row['id_reseller'].'">'.$row['nama'].'</option>';
    }
    $data['reseller'] = $reseller;
    $sql = "SELECT nama,alamat,no_hp,id_header_reseller
            FROM ms_reseller
            WHERE id_reseller='$id'";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($query)) {
      $data['nama'] = $row['nama'];
      $data['alamat'] = $row['alamat'];
      $data['no_hp'] = $row['no_hp'];
      $data['id_header_reseller'] = $row['id_header_reseller'];
    }
    echo json_encode($data);
    break;

  case 'list_reseller':
    $data = '<option value="">Pilih ...</option>';
    $sql = "SELECT id_reseller,nama
            FROM ms_reseller
            WHERE id_reseller=id_header_reseller";
    $query = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_array($query)) {
      $data .= '<option value="'.$row['id_reseller'].'">'.$row['nama'].'</option>';
    }
    echo $data;
    break;

  case 'submit_reseller':
    $nama = $_POST['input_nama_reseller'];
    $alamat = $_POST['input_alamat_reseller'];
    $idHeaderReseller = '';
    $noHp = $_POST['input_telp_reseller'];
    $data['nama'] = $nama;
    if ($id == 'new') {
      $sql = "INSERT INTO ms_reseller (nama,alamat,id_header_reseller,no_hp) 
              VALUES ('$nama','$alamat','$idHeaderReseller','$noHp')";
      if (mysqli_query($conn,$sql)) {
        $result = mysqli_insert_id($conn);
        $sql = "UPDATE ms_reseller SET id_header_reseller='$result' WHERE id_reseller='$result'";
        if (mysqli_query($conn,$sql)) {
          $data['status'] = 'success';
        } else {
          $data['status'] = 'failed';
          $data['error'] = mysqli_error($conn);
        }
      } else {
        $data['status'] = 'failed';
        $data['error'] = mysqli_error($conn);
      }
    } else {
      $sql = "UPDATE ms_reseller SET nama='$nama',alamat='$alamat',no_hp='$noHp' WHERE id_reseller='$id'";
      if (mysqli_query($conn,$sql)) {
        $data['status'] = 'success';
      } else {
        $data['status'] = 'failed';
        $data['error'] = mysqli_error($conn);
      }
    }
    echo json_encode($data);
    break;

  case 'submit_subreseller':
    $nama = $_POST['input_nama_subreseller'];
    $alamat = $_POST['input_alamat_subreseller'];
    $idHeaderReseller = $_POST['input_reseller'];
    $noHp = $_POST['input_telp_subreseller'];
    $data['nama'] = $nama;
    if ($id == 'new') {
      $sql = "INSERT INTO ms_reseller (nama,alamat,id_header_reseller,no_hp) 
              VALUES ('$nama','$alamat','$idHeaderReseller','$noHp')";
    } elseif ($id !== 'new') {
      $sql = "UPDATE ms_reseller SET nama='$nama',alamat='$alamat',no_hp='$noHp' WHERE id_reseller='$id'";
    }
    if (mysqli_query($conn,$sql)) {
      $data['status'] = 'success';
    } else {
      $data['status'] = 'failed';
      $data['error'] = mysqli_error($conn);
    }
    echo json_encode($data);
    break;

  case 'delete_data':
    $data['nama'] = $_POST['nama'];
    $sql = "UPDATE ms_reseller SET aktif='0' WHERE id_reseller='$id'";
    if (mysqli_query($conn,$sql)) {
      $data['status'] = 'success';
      $data['error'] = mysqli_error($conn);
    } else {
      $data['status'] = 'failed';
      $data['error'] = mysqli_error($conn);
    }
    echo json_encode($data);
    break;
}

?>