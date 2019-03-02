<?php

   include('config.php');
 $created_at= $_POST['created_at'];


    $sql = "SELECT main_merchant,supplier_name,supplier_phone,supplier_address,product_name,product_id,COUNT(product_quantity) as sum,created_at FROM tbl_fullfillment_pickuplist WHERE created_at = '$created_at' group by product_id";
       $result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));

       if(mysqli_num_rows($result) > 0){
           //create an array
           $merArray = array();
           while($row = mysqli_fetch_assoc($result))
           {
               $merArray[] = $row;
           }
           
           echo json_encode(array('summary' =>$merArray));
         
           //close the db connection
           mysqli_close($conn);
       } else {
           $response['error'] = true; 
 echo json_encode($response);
       }
       //echo json_encode($merArray);
?>