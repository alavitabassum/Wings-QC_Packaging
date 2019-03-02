<?php
include('config.php');

if(isset($_POST['get_merchantCode'])){

        $merchantCode = $_POST['get_merchantCode'];
   
        $findsql="SELECT * FROM `tbl_order_details` where `Shtl` IS NULL AND `merchantCode` = '$merchantCode' ";
        $findresult = mysqli_query($conn, $findsql);
        echo '<table id="orderPullTable" class="table table-hover packagingTable">';
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
                                    echo '<td style="width: 150px;">'.$orderRow['merOrderRef'].'</td>';
                                    echo '<td  style="width: 350px;">'.$orderRow['productBrief'].'</td>';
                                    echo '<td style="width: 100px;">'.$orderRow['pkgQty_ff'].'</td>';
                                    echo '<td style="width: 100px;"><button type="button" id="btnPrintpkg"  class="btn btn-primary scan_pkg" data-id="'.$orderRow['orderid'].'" data-qty="'.$orderRow['pkgQty_ff'].'" name="scan" value="Scan" > Scan</button></td>';
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



if(isset($_POST['texthidden'])){
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
                    // echo 'Flag updated';

                    $countOrders = "SELECT COUNT(productBarcode) FROM `tbl_packaging_records_fulfillment` WHERE `orderID`='$orderid'";
                    $findcount = mysqli_query($conn, $countOrders);
                    $orderCntrow = mysqli_fetch_row($findcount);
                    $total_rows_total = $orderCntrow[0];

                    if (mysqli_num_rows($findcount)>0) {
                    
                    echo "$total_rows_total";

                    $updateSQL2 = "UPDATE `tbl_order_details` SET `pkgQty_ff`='$total_rows_total' WHERE `orderid`='$orderid'";
                    $updateCount = mysqli_query($conn, $updateSQL2);
                    } 
                    else{
                        echo "No result";
                    }
            
                 }
             
         } 
        }
 
 

//get all product for selected orderID
if(isset($_POST['get_pkgOID'])){

    $packagedOID = $_POST['get_pkgOID'];

    $findsql="SELECT * FROM `tbl_packaging_records_fulfillment` where  `orderID` = '$packagedOID' ";
    $findresult = mysqli_query($conn, $findsql);
    echo '<table id="orderPullTable" class="table table-hover packagingTable">';
                        echo '<thead>
                            <tr>
                            <th>Order ID</th>
                            <th>Packed Products</th>
                            <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>';
                        foreach($findresult as $orderRow){
                                echo'<tr>'; 
                                echo '<td>'.$orderRow['orderID'].'</td>';
                                echo '<td style="width: 150px;">'.$orderRow['productBarcode'].'</td>';
                                echo '<td style="width: 100px;"><button type="button" id="btnDltpkg"  class="btn-dlt" data-id="'.$orderRow['id'].'" data-barcode="' . $orderRow['productBarcode'] .'" name="removeProduct" value="delete" >Delete</button></td>';
                                echo '</tr>';
                        }
                    echo '</tbody></table>';
    exit;
}



    if(isset($_POST['get_packageOrderID'])){


    $packagedOrderID = $_POST['get_packageOrderID'];
    $packagedProductBarcode = $_POST['get_packageProductBarcode'];
    $OID = $_POST['getOrderID'];


    $dltSQL = "DELETE FROM `tbl_packaging_records_fulfillment` WHERE id = '$packagedOrderID'" ;
    $dltresult = mysqli_query($conn, $dltSQL);
    
    if(! $dltresult ) {
       die('Could not delete data: ' . mysql_error());
    }else{
       // echo "Deleted data successfully\n";

       $RemoveUpdateSQL = "UPDATE `tbl_child_barcode_fulfillment` SET `Packaged`='N' WHERE `childBarcode`='$packagedProductBarcode'";
       $RemoveUpdateResult = mysqli_query($conn, $RemoveUpdateSQL);

       if(! $RemoveUpdateResult ) {
        die('Could not update data: ' . mysql_error());
     }else{

        //after delete count prodcuts for the orderid
        $countOrdersLatest = "SELECT COUNT(productBarcode) FROM `tbl_packaging_records_fulfillment` WHERE `orderID`='$OID'";
        $findcountlatest = mysqli_query($conn, $countOrdersLatest);
        $productCnt = mysqli_fetch_row($findcountlatest);
        $total_products = $productCnt[0];

        if (mysqli_num_rows($findcountlatest)>0) {
        
        echo "$total_products";

        $updatePC = "UPDATE `tbl_order_details` SET `pkgQty_ff`='$total_products' WHERE `orderid`='$OID'";
        $updateCount = mysqli_query($conn, $updatePC);
        } 
        else{
            echo "No result";
        }

     }
       

    }
    
   

}


?>

