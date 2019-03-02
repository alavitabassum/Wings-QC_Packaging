<?php
    include('session.php');
    include('config.php');
    include('num_format.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and cashPending = 'Y'"));
    if ($userPrivCheckRow['cashPending'] != 'Y'){
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

        $cashPendingListResult = mysqli_query($conn, "select orderid, orderDate, tbl_merchant_info.merchantName, DATE_FORMAT(CashTime, '%Y-%m-%d') as cashDate, DATE_FORMAT(partialTime, '%Y-%m-%d') as partialDate, CashBy, CashAmt, partial, DropDP2, DropDP2By from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where dropPointCode = '$pointCode' and (Cash = 'Y' or partial = 'Y') and bank is null and close is null order by orderDate");

        $pointInfoRow = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_point_info where pointCode = '$pointCode'"));

        $orderCount = 0;
        $cashAmount = 0;
        $lineCount = 0;

        foreach($cashPendingListResult as $cashPendingListRow){
            $orderCount++;
            $cashAmount = $cashAmount + $cashPendingListRow['CashAmt'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Pending Order Details</title>
        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <font size="3">
                <div class="row" style="margin-top: 20px">
                    <div class="col-sm-2">
                        <label>Point Code: <?php echo $pointCode;?></label>
                    </div>
                    <div class="col-sm-3">
                        <label>Point Name: <?php echo $pointInfoRow['pointName'];?></label>
                    </div>
                    <div class="col-sm-2">
                        <label>Order Count: <?php echo num_to_format(round($orderCount, 0));?></label>
                    </div>
                    <div class="col-sm-3">
                        <label>Cash Amount: <?php echo num_to_format(round($cashAmount, 0));?></label>
                    </div>
                </div>
            </font>
            <font size="1">
                <table class="table table-hover">
                    <thead><tr><th>Sl No</th><th>Order ID</th><th>Order Date</th><th>Merchant Name</th><th>Cash Date</th><th>Cash By</th><th style="text-align: right">Cash Amount</th><th>Partial</th><th>DP2</th><th>DP2 By</th></tr></thead>
                    <tbody>
                        <?php foreach($cashPendingListResult as $cashPendingListRow){ $lineCount++;?>
                        <tr <?php if($cashPendingListRow['DropDP2'] != 'Y'){echo 'style="color: red"';}?>>
                            <td><?php echo $lineCount;?></td>
                            <td><?php echo $cashPendingListRow['orderid'];?></td>
                            <td><?php echo date('d-M-Y', strtotime($cashPendingListRow['orderDate']));?></td>
                            <td><?php echo $cashPendingListRow['merchantName'];?></td>
                            <?php if($cashPendingListRow['partial'] == 'Y'){?>
                                <td><?php echo date('d-M-Y', strtotime($cashPendingListRow['partialDate']));?></td>
                            <?php } else {?>
                                <td><?php echo date('d-M-Y', strtotime($cashPendingListRow['cashDate']));?></td>
                            <?php }?>
                            <td><?php echo $cashPendingListRow['CashBy'];?></td>
                            <td style="text-align: right"><?php echo num_to_format(round($cashPendingListRow['CashAmt'], 0)) ;?></td>
                            <td><?php echo $cashPendingListRow['partial'];?></td>
                            <td><?php echo $cashPendingListRow['DropDP2'];?></td>
                            <td><?php echo $cashPendingListRow['DropDP2By'];?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </font>
        </div>
    </body>
</html>
