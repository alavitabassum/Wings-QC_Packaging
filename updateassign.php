<?php

require_once('config.php');

 
  $merchant_code = $_POST['merchant_code'];
  $executive_code = $_POST['executive_code'];
 // $executive_name = $_POST['executive_name'];
  $order_count = $_POST['order_count'];





/* $Sql_Query = "insert into insertassign (executive_name,executive_code,order_count,merchant_code,assigned_by,created_at) values ('$executive_name','$executive_code','$order_count','$merchant_code','$assigned_by','$created_at')";*/

$Sql_Query = "UPDATE insertassign SET order_count='$order_count'  WHERE merchant_code = '$merchant_code' AND executive_code = '$executive_code'";




 
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