<?php
    if(isset($_POST['get_lineid'])){
        include('config.php');
        $lineName = $_POST['get_lineid'];
        $findsql="SELECT * FROM tbl_inventory_lines WHERE inventoryCode='$lineName' ";
        $findresult = mysqli_query($conn, $findsql);
        echo "<option>Select Line</option>";
        foreach ($findresult as $row){
            /* $var1 =$row['id'];    */         
            /* echo "<option data-pointCode=".$row['pointCode']." value=".$var1.">".$row['lineName']."</option>"; */
              echo '<option data-lineName="' . $row['lineName'] .'"  data-invName="' . $row['inventoryName'] .'"  data-pointCode="' . $row['pointCode'] . '" value="' . $row['id'] . '">' . $row['lineName'] . '</option>';
        }
        exit;
    }
?>

