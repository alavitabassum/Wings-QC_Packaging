<?php

require_once('config.php');
$response = array(); 

 
 
 $merchant_code = $_POST['merchant_code'];
 $executive_code = $_POST['executive_code'];


 


/* $Sql_Query = "insert into insertassign (executive_name,executive_code,order_count,merchant_code,assigned_by,created_at) values ('$executive_name','$executive_code',$order_count','$merchant_code','$assigned_by','$created_at','$updated_by','$updated_at','$scan_count')";*/

 $Sql_Query = "DELETE FROM insertassign WHERE merchant_code = '$merchant_code' AND executive_code = '$executive_code'";



 if(mysqli_query($conn,$Sql_Query)){
 
/* $response['error'] = false; 
 echo json_encode($response);*/
 echo "deleted";
 
 }
 else{
 
$response['error'] = true; 
echo json_encode($response);
 
 }
 mysqli_close($conn);
?>