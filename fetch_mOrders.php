<?php
    if(isset($_POST['get_merchantId'])){
        include('config.php');
        $mName = $_POST['get_merchantId'];
        $findsql= "SELECT * FROM `tbl_order_details` where `merchantCode` = '$mName' ";
        $findresult = mysqli_query($conn, $findsql);
        echo "<td></td>";
        foreach ($findresult as $row){
            /* $var1 =$row['id'];  */
           /*  echo "<option data-pointCode=".$row['pointCode']." value=".$var1.">".$row['rackName']."</option>"; */
            echo '<td  name="orderid">' . $row['orderid'] . '</option>';
            echo '<td  name="mRef">' . $row['merOrderRef'] . '</option>';
        }
        exit;
    }
?>

