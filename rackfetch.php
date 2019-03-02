<?php
    if(isset($_POST['get_rackid'])){
        include('config.php');
        $rackName = $_POST['get_rackid'];
        $findsql="SELECT * FROM tbl_inventory_racks WHERE lineCode='$rackName' ";
        $findresult = mysqli_query($conn, $findsql);
        echo "<option>Select Rack</option>";
        foreach ($findresult as $row){
            /* $var1 =$row['id'];  */
           /*  echo "<option data-pointCode=".$row['pointCode']." value=".$var1.">".$row['rackName']."</option>"; */
            echo '<option data-rackName="' . $row['rackName'] .'"  data-lineName="' . $row['lineName'] .'"  data-invName="' . $row['inventoryName'] .'"  data-pointCode="' . $row['pointCode'] . '" value="' . $row['id'] . '">' . $row['rackName'] . '</option>';
        }
        exit;
    }
?>

