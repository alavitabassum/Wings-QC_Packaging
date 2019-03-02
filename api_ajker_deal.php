<?php
   require_once('config.php');
   $response = array(); 
  

  /* $sql = "SELECT apiOrderID, merchantCode  FROM `tbl_api_orders` WHERE DATE_FORMAT(creationDate,'%d-%M-%Y')='18-Dec-2018' AND merchantCode = 'M-1-0262' ";*/
 /* SELECT Orders.OrderID, Customers.CustomerName, Orders.OrderDate
  AND merchantCode = 'M-1-0262' AND DATE_FORMAT(creationDate,'%d-%m-%Y')='18-12-2018'"
FROM Orders
INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID;*/
  $sql= "SELECT tbl_pickup_merchant_info.pickMerchantName FROM tbl_api_orders LEFT JOIN tbl_pickup_merchant_info ON tbl_api_orders.pickUpMerchantID = tbl_pickup_merchant_info.pickMerchantID where tbl_api_orders.processed='N' AND tbl_api_orders.merchantCode='M-1-0262' and pickUpMerchantID >0 ";
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
        echo ("dddddd");
           echo 'failure';
       }
       //echo json_encode($merArray);
?>