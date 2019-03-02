<?php
    if(isset($_POST['get_binid'])){
        include('config.php');
        $binName = $_POST['get_binid'];
        $findsql="SELECT * FROM tbl_inventory_bins WHERE rackCode='$binName' ";
        $findresult = mysqli_query($conn, $findsql);
        echo "<option>Select Bin</option>";
        foreach ($findresult as $row){
           echo '<option data-binName="' . $row['binName'] .'"  data-rackName="' . $row['rackName'] .'"  data-lineName="' . $row['lineName'] .'"  data-invName="' . $row['inventoryName'] .'"  data-pointCode="' . $row['pointCode'] . '" value="' . $row['id'] . '">' . $row['binName'] . '</option>';
        }
        exit;
    }
?>

