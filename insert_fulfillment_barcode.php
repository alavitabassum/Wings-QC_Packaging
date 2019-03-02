<?php

require_once('config.php');
$response = array(); 

 
 $merchant_code = $_POST['merchant_code'];
 $sub_merchant_name = $_POST['sub_merchant_name'];
 $barcodeNumber = $_POST['barcodeNumber'];
 $state = $_POST['state'];
 $updated_by = $_POST['updated_by'];
 $updated_at = $_POST['updated_at'];
 $order_id = $_POST['order_id'];
 $picked_qty = $_POST['picked_qty'];
 
 $Sql_Query = "INSERT INTO tbl_barcode_factory_fulfillment (merchant_code,sub_merchant_name, barcodeNumber,state,updated_by,updated_at,order_id,picked_qty) VALUES ('$merchant_code','$sub_merchant_name','$barcodeNumber','$state','$updated_by','$updated_at','$order_id','$picked_qty')
    ON DUPLICATE KEY UPDATE picked_qty='$picked_qty';";

 if(mysqli_query($conn,$Sql_Query)){
 
 $response['error'] = false; 
 echo json_encode($response);
 
 }
 else{
 
$response['error'] = true; 
$error = mysqli_error($conn);
print_r($error);
echo json_encode($response);
 
 }
 mysqli_close($conn);
?>




