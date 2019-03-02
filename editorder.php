<?php
    include('session.php');
    include('header.php');
    include('config.php');
    if (isset($_POST['updatesubmit'])) {
        $orderId =  trim($_POST['orderId']);
        $merchantid = trim($_POST['merchantid']);
        $orderDate = date("Y-m-d", strtotime(trim($_POST['orderDate'])));
        $productdetials = trim($_POST['productdetails']);
        $productdetials = mysqli_real_escape_string($conn, $productdetials);
        $size = trim($_POST['size']);
        $size = mysqli_real_escape_string($conn, $size);
        $pweight = trim($_POST['weight']);
        $pprice = trim($_POST['productPrice']);
        $dcharge = trim($_POST['deliveryCharge']);
        $custname = trim($_POST['customerName']);
        $custname = mysqli_real_escape_string($conn, $custname);
        $custaddress = trim($_POST['customerAddress']);
        $custaddress = mysqli_real_escape_string($conn, $custaddress);
        $thanaId = trim($_POST['thanaId']);
        $districtId = trim($_POST['districtId']);
        $custphone = trim($_POST['phone']);
        $custphone = mysqli_real_escape_string($conn, $custphone);
        $updatesql = "UPDATE  tbl_order_details SET merchant_id='$merchantid' ,orderDate='$orderDate' ,productDetails='$productdetials' ,
        size='$size' ,weight='$pweight' ,productPrice='$pprice'  ,deliveryCharge='$dcharge', customerName='$custname', customerAddress='$custaddress', 
        thanaId='$thanaId', districtId='$districtId', phone='$custphone', update_date = NOW() + INTERVAL 6 HOUR, updated_by='$user_check' where orderId='$orderId'";
        if (!mysqli_query($conn,$updatesql))
                {
                    $error ="Update Error : " . mysqli_error($conn);
                    echo "<div class='alert alert-danger'>";
                        echo "<strong>Error!</strong>".$error; 
                    echo "</div>";
                } else {
                        echo "<div class='alert alert-success'>";
    //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                            echo "Order updated successfully.";
                        echo "</div>";
            }
    }
    if (isset($_POST['deletesubmit'])) {
        $orderId =  trim($_POST['orderId']);
        $deletesql = "delete from tbl_order_details where orderId='$orderId'";
        if (!mysqli_query($conn,$deletesql))
                {
                    $error ="Delete Error : " . mysqli_error($conn);
                    echo "<div class='alert alert-danger'>";
                        echo "<strong>Error!</strong>".$error; 
                    echo "</div>";
                } else {
                        echo "<div class='alert alert-success'>";
    //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                            echo "Order Deleted successfully.";
                        echo "</div>";
            }
    }
    mysqli_close($conn);
    echo "<a href='editorders' class='btn btn-info' role='button'>Back to Edit Orders</a>";
?>

