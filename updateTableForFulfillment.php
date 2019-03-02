<?php

require_once('config.php');
$response = array(); 

 
    $merchant_code = $_POST['merchant_code'];
    $p_m_name = $_POST['p_m_name'];
    $scan_count = $_POST['scan_count'];
    $picked_qty = $_POST['picked_qty'];
    $updated_by = $_POST['updated_by'];
    $updated_at = $_POST['updated_at'];
    $created_at = $_POST['created_at'];
    $api_order_id = $_POST['api_order_id'];
    $pick_from_merchant_status = $_POST['pick_from_merchant_status'];

 


/* $Sql_Query = "insert into insertassign (executive_name,executive_code,order_count,merchant_code,assigned_by,created_at,api_order_id) values ('$executive_name','$executive_code',$order_count','$merchant_code','$assigned_by','$created_at','$updated_by','$updated_at','$scan_count')";*/

 // = "insert into barcode_factory (merchant_code,barcodeNumber,state,updated_by,updated_at) values ('$merchant_code,$barcodeNumber,$state,$updated_by,$updated_at)";

 $Sql_Query =  "UPDATE insertassign SET scan_count='$scan_count',picked_qty='$picked_qty', updated_by='$updated_by', updated_at='$updated_at', pick_from_merchant_status = '$pick_from_merchant_status' WHERE merchant_code='$merchant_code' AND p_m_name = '$p_m_name' AND created_at = '$created_at' AND api_order_id = '$api_order_id'";

 if(mysqli_query($conn,$Sql_Query)){
 
 $response['error'] = false; 
 echo json_encode($response);
 
 }
 else{
 
$response['error'] = true; 
// printf(error)
echo json_encode($response);
 
 }
 mysqli_close($conn);
?>

