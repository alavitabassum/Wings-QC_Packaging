<?php

   include('config.php');


    $sql = "SELECT supplier_name FROM tbl_fullfillment_pickuplist ";
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