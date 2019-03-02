<?php

require_once('config.php');
$response = array(); 

    $picked_qty = $_POST['picked_qty'];
    $barcodeNumber = $_POST['barcodeNumber'];

 $Sql_Query =  "UPDATE tbl_barcode_factory_fulfillment SET picked_qty='$picked_qty' WHERE barcodeNumber='$barcodeNumber'";

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

