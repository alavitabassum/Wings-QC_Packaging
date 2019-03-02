<?php
    if(isset($_POST['get_pointCode'])){
        include('config.php');
        $pointCode = $_POST['get_pointCode'];
        $findsql="SELECT pointCode FROM tbl_inventory_info WHERE id='$pointCode' ";
        debug($findsql);
        $findresult = mysqli_query($conn, $findsql);
        echo "<input></input>";
        foreach ($findresult as $row){
      
            echo " <input value=".$row['pointCode']." id='pointCodeshow' type='text' name='pointCode' >";

        }
        exit;
    }
?>