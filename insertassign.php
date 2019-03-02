<?php

require_once('config.php');
$response = array(); 

 
 $executive_name = $_POST['executive_name'];
 $executive_code = $_POST['executive_code'];
 $order_count = $_POST['order_count'];
 $merchant_code = $_POST['merchant_code'];
 $assigned_by = $_POST['assigned_by'];
 $created_at = $_POST['created_at'];
 $merchant_name = $_POST['merchant_name'];
 $phone_no = $_POST['phone_no'];
 $p_m_name = $_POST['p_m_name'];
 $p_m_address = $_POST['p_m_address'];
 $complete_status = $_POST['complete_status'];
 $product_name = $_POST['product_name'];
 $api_order_id = $_POST['api_order_id'];
 $demo = $_POST['demo'];
 $pick_from_merchant_status= $_POST['pick_from_merchant_status'];
 $received_from_HQ_status = $_POST['received_from_HQ_status'];

 
 $Sql_Query = "insert into insertassign (executive_name,executive_code,order_count,merchant_code,assigned_by,created_at,merchant_name,phone_no,p_m_name,p_m_address,complete_status,product_name,api_order_id,demo,pick_from_merchant_status,received_from_HQ_status) values ('$executive_name','$executive_code','$order_count','$merchant_code','$assigned_by','$created_at','$merchant_name','$phone_no','$p_m_name','$p_m_address','$complete_status','$product_name','$api_order_id','$demo','$pick_from_merchant_status','$received_from_HQ_status')";



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