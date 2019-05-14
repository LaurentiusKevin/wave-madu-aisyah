<?php
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
require('database.php');

$filename = $_FILES['qqfile']['name'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);
// $newFile = 'upload/'.date('Y-m-d_His').'.'.$ext;
$tmpName = $_FILES['qqfile']['tmp_name'];

// if (!isset($_FILES['qqfile']['tmp_name'])) {
//   $data = array(
//     'success' => false,
//     'error' => error_get_last()
//   );
// } else {
//   $data = array(
//     'success' => true,
//     'error' => error_get_last()
//   );
// }

// if (move_uploaded_file($tmpName, $newFile)) {
//   $data = array(
//     'success' => true,
//     'error' => error_get_last()
//   );
// } else {
//   $data = array(
//     'success' => false,
//     'error' => error_get_last()
//   );
// }

require('plugin/PHPSpreadsheet/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();

switch ($ext) {
  case 'xls':
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
    break;
    
  case 'xlsx':
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    break;
}

$spreadsheet = $reader->load($tmpName);
$worksheet = $spreadsheet->getActiveSheet();
$excel = $worksheet->toArray();

foreach ($excel[0] as $key => $value) {
  switch ($value) {
    case 'No Transaksi':
      $iTransaksi = $key;
      break;
    
    case 'Tracking Id':
      $iTracking = $key;
      break;
  }
}

$success = 0;
for ($i=1; $i < count($excel); $i++) { 
  $noTransaksi = $excel[$i][$iTransaksi];
  $trackingID = $excel[$i][$iTracking];
  $sql = "UPDATE kasir_mst SET no_resi='$trackingID' WHERE no_transaksi='$noTransaksi' AND status_kirim IN ('1','2')";
  if (mysqli_query($conn,$sql)) {
    $success++;
  }
}
$totalRow = count($excel);
$updated = $success+1;

if ($totalRow == $updated) {
  $data = array(
    'success' => true,
    'excel' => $excel,
    'kolom_no_transaksi' => $iTransaksi,
    'kolom_tracking' => $iTracking
  );
} else {
  $data = array(
    'success' => false,
    'error' => error_get_last(),
    'excel' => $excel
  );
}

echo json_encode($data);
?>