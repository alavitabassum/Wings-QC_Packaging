<?php
include('config.php');

if(isset($_POST['get_merchantCode'])){

        $merchantCode = $_POST['get_merchantCode'];
   
        $findsql="SELECT * FROM `tbl_order_details` where `Shtl` IS NULL AND `merchantCode` = '$merchantCode' ";
        $findresult = mysqli_query($conn, $findsql);
        echo '<table id="orderPullTable" class="table table-hover">';
                            echo '<thead>
                                <tr>
                                <th>Order ID</th>
                                <th>Merchant Ref</th>
                                <th>Product Brief</th>
                                <th>Product Quantity</th>
                                <th>Scan</th>
                                </tr>
                                </thead>
                                <tbody>';
                            foreach($findresult as $orderRow){
                                    echo'<tr>'; 
                                    echo '<td>'.$orderRow['orderid'].'</td>';
                                    echo '<td>'.$orderRow['merOrderRef'].'</td>';
                                    echo '<td>'.$orderRow['productBrief'].'</td>';
                                    echo '<td>'.$orderRow['pkg_prod_qty'].'</td>';
                                    echo '<td><button type="button" id="btnPrintpkg"  class="btn btn-primary scan_pkg" data-id="'.$orderRow['orderid'].'" data-qty="'.$orderRow['pkg_prod_qty'].'" name="scan" value="Scan" > Scan</button></td>';
                                    echo '</tr>';
                            }
                        echo '</tbody></table>';
        exit;
    }


    
    if(isset($_POST['get_barcode'])){

        $orderid = $_POST['get_order_id'];
        $barcode = $_POST['get_barcode'];
        $flag = $_POST['flag'];

        $findsql2="SELECT `childBarcode` FROM `tbl_child_barcode_fulfillment` where `childBarcode` = '$barcode' ";
        $findresult = mysqli_query($conn, $findsql2);

        if (mysqli_num_rows($findresult)>0) {
        
        echo" Product found";
        } 
        else{
            echo "Product not found!";
        }

        exit;
     }   




        $orderid = mysqli_real_escape_string($conn, $_POST["order_id"]);
        $barcode= mysqli_real_escape_string($conn, $_POST["cp_barcode"]);
 

            //Insert statement
               $insertSQL = "INSERT INTO `tbl_packaging_records_fulfillment`(`orderID`, `productBarcode`) VALUES ('$orderid', '$barcode')";
           
 
            if(!mysqli_query($conn, $insertSQL)){
 
             echo "Error: ".mysqli_error($conn);
 
         } else {
 
                 $updateSQL = "UPDATE `tbl_child_barcode_fulfillment` SET `Packaged`='Y' WHERE `childBarcode`='$barcode'";
                     //Update statement
                 if(!mysqli_query($conn, $updateSQL)){
                     echo 'error '.mysqli_error($conn);
                 } else {
                     echo 'Flag updated';
                 }
             
         } 
 
 


         /* 
         if(isset($_POST['get_CountPerOrder'])){
            $CountPerOrder = $_POST['get_CountPerOrder'];
            $orderid = $_POST['get_orderid'];

            $updateOrderSQL = "UPDATE `tbl_order_details` SET  `pkg_prod_qty`='$CountPerOrder' WHERE `orderid`= '$orderid'";

            if(!mysqli_query($conn, $updateOrderSQL)){
                echo 'error '.mysqli_error($conn) + $CountPerOrder +$orderid;
            } else{
                echo 'Order Count Updated';
            }
        */
/* 
    $orderid = mysqli_real_escape_string($conn, $_POST["order_id"]);
    $cp_barcode = mysqli_real_escape_string($conn, $_POST["cp_barcode"]);
    
    $sql = "INSERT INTO tbl_child_barcode_fulfillment (childBarcode, parentBarcode, occupiedSpace, c_bin, c_rack, c_line, c_inv, c_point)
    VALUES ('$barcode_c', '$barcode_p','$child_prod_vol', '$c_bin', '$c_rack', '$c_line', '$c_inv', '$c_point')"; */

/* 
    if (mysqli_affected_rows($conn)) {
        return true;
    } */


?>



