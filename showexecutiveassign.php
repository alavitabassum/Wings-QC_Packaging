<?php
   require_once('config.php');
   $response = array(); 
   $executive_name = $_POST['executive_name'];
    $created_at = $_POST['created_at'];


   $sql = "SELECT * FROM insertassign WHERE executive_name = '$executive_name' AND created_at = '$created_at'";
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