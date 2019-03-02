<?php


require_once('config.php');
$response = array(); 

 
 $merchant_code = $_POST['merchant_code'];
 $sub_merchant_name = $_POST['sub_merchant_name'];
 $barcodeNumber = $_POST['barcodeNumber'];
 $state = $_POST['state'];
 $updated_by = $_POST['updated_by'];
 $updated_at = $_POST['updated_at'];
 
 $Sql_Query = "INSERT INTO barcode_factory (merchant_code,sub_merchant_name, barcodeNumber,state,updated_by,updated_at) VALUES ('$merchant_code','$sub_merchant_name','$barcodeNumber','$state','$updated_by','$updated_at')";

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




