
<?php

require_once('config.php');
$response = array();


   $merchant_code = $_POST['merchant_code'];
   $p_m_name = $_POST['p_m_name'];
   $scan_count = $_POST['scan_count'];
   $updated_by = $_POST['updated_by'];
   $updated_at = $_POST['updated_at'];
   $created_at = $_POST['created_at'];


// = "insert into barcode_factory (merchant_code,barcodeNumber,state,updated_by,updated_at) values ('$merchant_code,$barcodeNumber,$state,$updated_by,$updated_at)";

$Sql_Query =  "UPDATE insertassign SET scan_count='$scan_count', updated_by='$updated_by', updated_at='$updated_at' WHERE merchant_code='$merchant_code' AND p_m_name = '$p_m_name' AND created_at = '$created_at'";

if(mysqli_query($conn,$Sql_Query)){

$response['error'] = false;
echo json_encode($response);

}
else{

$response['error'] = true;
echo json_encode($response);

}
mysqli_close($conn);
?>
