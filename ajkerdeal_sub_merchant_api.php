<?php
   require_once('config.php');
   $response = array(); 
   mysqli_set_charset($conn, "utf8");
  
  /*$sql= "SELECT tbl_pickup_merchant_info.pickMerchantName,tbl_pickup_merchant_info.pickMerchantAddress,tbl_pickup_merchant_info.phone1,tbl_api_orders.merOrderRef,tbl_api_orders.apiOrderID
    FROM tbl_api_orders
    LEFT JOIN tbl_pickup_merchant_info ON tbl_api_orders.pickUpMerchantID = tbl_pickup_merchant_info.pickMerchantID
    WHERE tbl_api_orders.processed =  'N'
    AND tbl_api_orders.merchantCode =  'M-1-0262'
    AND pickUpMerchantID >0";*/
    $sql = "select b.pickMerchantName,b.pickMerchantAddress,b.phone1,s.merOrderRef,s.apiOrderID,c.merchantName,s.creationDate
    from tbl_api_orders s, tbl_pickup_merchant_info b, tbl_merchant_info c
    where b.pickMerchantID = s.pickUpMerchantID and s.merchantCode = c.merchantCode AND s.processed= 'N' AND s.merchantCode = 'M-1-0262'
    and s.pickUpMerchantID>0";

       $result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));

       if(mysqli_num_rows($result) > 0){
           //create an array
           $merArray = array();
           while($row = mysqli_fetch_assoc($result))
           {
               $merArray[] = $row;
           }
             
           
             echo json_encode(array('summary' => $merArray));

         
           //close the db connection
           mysqli_close($conn);
       } else {
      
           echo 'failure';
       }
       //echo json_encode($merArray);
?>