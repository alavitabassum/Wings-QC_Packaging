<?php
    include('session.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and returnReport = 'Y'"));
    if ($userPrivCheckRow['returnReport'] != 'Y'){
        exit();
    }
    function encryptor($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'shLitu';
        $secret_iv = '12Litu34';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
    	    //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }    
    if(isset($_GET['xxCode'])){

        $pointCode = encryptor('decrypt', $_GET['xxCode']);

        $merchantCode = $_GET['yyCode'];
        $poinNameResult = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_point_info where pointCode = '$pointCode'"));

        $slaPeriod = $_GET['slCode'];
        $cpCode = $_GET['cpCode'];
        if($cpCode == 0){
            if($slaPeriod == 0 and $merchantCode == '0'){
                $returnResult = mysqli_query($conn, "select orderDate, orderid, tbl_merchant_info.merchantName, merOrderRef, DATE_FORMAT(RetTime, '%Y-%m-%d') as returnDate, RetBy, retReason from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where Ret = 'Y' and DropDP2 is null and close is null and dropPointCode = '$pointCode' order by RetTime");
            }

            if($slaPeriod == 0 and $merchantCode != '0'){
                $returnResult = mysqli_query($conn, "select orderDate, orderid, tbl_merchant_info.merchantName, merOrderRef, DATE_FORMAT(RetTime, '%Y-%m-%d') as returnDate, RetBy, retReason from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where Ret = 'Y' and DropDP2 is null and close is null and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchantCode' order by RetTime");
            }

            if($slaPeriod != 0 and $merchantCode != '0'){
                $returnResult = mysqli_query($conn, "select orderDate, orderid, tbl_merchant_info.merchantName, merOrderRef, DATE_FORMAT(RetTime, '%Y-%m-%d') as returnDate, RetBy, retReason from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where Ret = 'Y' and DropDP2 is null and close is null and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchantCode' and DATE_FORMAT(retTime, '%Y-%m-%d') < DATE_SUB(CURDATE(), INTERVAL $slaPeriod DAY) order by RetTime");
            }

            if($slaPeriod != 0 and $merchantCode == '0'){
                $returnResult = mysqli_query($conn, "select orderDate, orderid, tbl_merchant_info.merchantName, merOrderRef, DATE_FORMAT(RetTime, '%Y-%m-%d') as returnDate, RetBy, retReason from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where Ret = 'Y' and DropDP2 is null and close is null and dropPointCode = '$pointCode' and DATE_FORMAT(retTime, '%Y-%m-%d') < DATE_SUB(CURDATE(), INTERVAL $slaPeriod DAY) order by RetTime");
            }            
        }

        if($cpCode == 1){
            if($slaPeriod == 0 and $merchantCode == '0'){
                $returnResult = mysqli_query($conn, "select orderDate, orderid, tbl_merchant_info.merchantName, merOrderRef, DATE_FORMAT(RetTime, '%Y-%m-%d') as returnDate, RetBy, retReason, DATE_FORMAT(DropDP2Time, '%Y-%m-%d') as dropDP2Date from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where Ret = 'Y' and DropDP2 = 'Y' and retcp1 is null and close is null and dropPointCode = '$pointCode' order by RetTime");
            }

            if($slaPeriod == 0 and $merchantCode != '0'){
                $returnResult = mysqli_query($conn, "select orderDate, orderid, tbl_merchant_info.merchantName, merOrderRef, DATE_FORMAT(RetTime, '%Y-%m-%d') as returnDate, RetBy, retReason, DATE_FORMAT(DropDP2Time, '%Y-%m-%d') as dropDP2Date from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where Ret = 'Y' and DropDP2 = 'Y' and retcp1 is null and close is null and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchantCode' order by RetTime");
            }

            if($slaPeriod != 0 and $merchantCode != '0'){
                $returnResult = mysqli_query($conn, "select orderDate, orderid, tbl_merchant_info.merchantName, merOrderRef, DATE_FORMAT(RetTime, '%Y-%m-%d') as returnDate, RetBy, retReason, DATE_FORMAT(DropDP2Time, '%Y-%m-%d') as dropDP2Date from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where Ret = 'Y' and DropDP2 = 'Y' and retcp1 is null and close is null and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchantCode' and DATE_FORMAT(retTime, '%Y-%m-%d') < DATE_SUB(CURDATE(), INTERVAL $slaPeriod DAY) order by RetTime");
            }

            if($slaPeriod != 0 and $merchantCode == '0'){
                $returnResult = mysqli_query($conn, "select orderDate, orderid, tbl_merchant_info.merchantName, merOrderRef, DATE_FORMAT(RetTime, '%Y-%m-%d') as returnDate, RetBy, retReason, DATE_FORMAT(DropDP2Time, '%Y-%m-%d') as dropDP2Date from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where Ret = 'Y' and DropDP2 = 'Y' and retcp1 is null and close is null and dropPointCode = '$pointCode' and DATE_FORMAT(retTime, '%Y-%m-%d') < DATE_SUB(CURDATE(), INTERVAL $slaPeriod DAY) order by RetTime");
            }            
        }

        
        
        
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Pending Return Order Details</title>
        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <font size="3">
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-12">
                    <?php
                        if($cpCode == 0){
                    ?>
                    <label style="color: blue">DP2 Pendings</label>
                    <?php
                        }
                    ?>
                    <?php
                        if($cpCode == 1){
                    ?>
                    <label style="color: blue">CP Pendings</label>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-2">
                    <label>Point Code: <?php echo $pointCode;?></label>
                </div>
                <div class="col-sm-4">
                    <label>Point Name: <?php echo $poinNameResult['pointName'];?></label>
                </div>
                <div class="col-sm-6">
                    <label>Total Return Pendings: <?php echo mysqli_num_rows($returnResult);?></label>
                </div>
            </div>
            </font>
            <font size="1">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Order ID</th>
                        <th>Merchant Name</th>
                        <th>Merchant Ref</th>
                        <th>Return Date</th>
                        <th>Return By</th>
                        <th>Return Reason</th>
                        <?php
                            if($cpCode == 1){
                        ?>
                        <th>DP2 Date</th>
                        <?php
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($returnResult as $pointOrdersRow){?>
                    <?php
                        $orderDate = date('d-M-Y', strtotime($pointOrdersRow['orderDate']));
                        $returnDate = date('d-M-Y', strtotime($pointOrdersRow['returnDate']));
                        if($cpCode == 1){
                            $dp2Date = date('d-M-Y', strtotime($pointOrdersRow['dropDP2Date']));
                        }
                    ?>
                    <tr>
                        <td><?php echo $orderDate;?></td>
                        <td><?php echo $pointOrdersRow['orderid'];?></td>
                        <td><?php echo $pointOrdersRow['merchantName'];?></td>
                        <td><?php echo $pointOrdersRow['merOrderRef'];?></td>
                        <td><?php echo $returnDate;?></td>
                        <td><?php echo $pointOrdersRow['RetBy'];?></td>
                        <td><?php echo $pointOrdersRow['retReason'];?></td>
                        <?php
                            if($cpCode == 1){
                        ?>
                        <td><?php echo $dp2Date;?></td>
                        <?php
                            }
                        ?>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
                </font>
        </div>
    </body>
</html>
<?php
    }
?>
