<?php
    include('session.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and point_executive = 'Y'"));
    if ($userPrivCheckRow['point_executive'] != 'Y'){
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

        $poinNameResult = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_point_info where pointCode = '$pointCode'"));

        $slaPeriod = $poinNameResult['sla'];

        if($_GET['yyCode'] !=''){
            $merchant = encryptor('decrypt', $_GET['yyCode']);
            $pointOrdersResult = mysqli_query($conn, "SELECT orderid, orderDate, merOrderRef, tbl_point_info.region, tbl_merchant_info.merchantName, IFNULL(tbl_employee_info.empName,'') as dropExecutive, tbl_thana_info.thanaName, tbl_district_info.districtName, IFNULL(Cash,'') as Cash, IFNULL(Ret,'') as Ret, IFNULL(partial,'') as partial, IFNULL(Rea,'') as Rea, IFNULL(onHoldSchedule,'') as onHoldSchedule, destination FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode left join tbl_thana_info on tbl_order_details.customerThana = tbl_thana_info.thanaid left join tbl_district_info on tbl_order_details.customerDistrict = tbl_district_info.districtid left join tbl_point_info on tbl_order_details.dropPointCode = tbl_point_info.pointCode WHERE tbl_order_details.merchantCode = '$merchant' and dropPointCode = '$pointCode' and Shtl ='Y' and dropDP2 is null and close is null order by orderDate");
        } else {
            $pointOrdersResult = mysqli_query($conn, "SELECT orderid, orderDate, merOrderRef, tbl_point_info.region, tbl_merchant_info.merchantName, IFNULL(tbl_employee_info.empName,'') as dropExecutive, tbl_thana_info.thanaName, tbl_district_info.districtName, IFNULL(Cash,'') as Cash, IFNULL(Ret,'') as Ret, IFNULL(partial,'') as partial, IFNULL(Rea,'') as Rea, IFNULL(onHoldSchedule,'') as onHoldSchedule, destination FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode left join tbl_thana_info on tbl_order_details.customerThana = tbl_thana_info.thanaid left join tbl_district_info on tbl_order_details.customerDistrict = tbl_district_info.districtid left join tbl_point_info on tbl_order_details.dropPointCode = tbl_point_info.pointCode WHERE dropPointCode = '$pointCode' and Shtl ='Y' and dropDP2 is null and close is null order by orderDate");    
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
                <div class="col-sm-4">
                    <label>Point Name: <?php echo $poinNameResult['pointName'];?></label>
                </div>
                <div class="col-sm-6">
                    <label>Pending Orders: <?php echo mysqli_num_rows($pointOrdersResult);?></label>
                </div>
            </div>
            </font>
            <font size="1">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Merchant Reference</th>
                        <th>Merchant Name</th>
                        <th>Drop Executive</th>
                        <th>Thana</th>
                        <th>District</th>
                        <th>Cash</th>
                        <th>Return</th>
                        <th>Partial</th>
                        <th>On Hold</th>
                        <th>On Hold Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pointOrdersResult as $pointOrdersRow){?>
                    <?php
                        if($pointOrdersRow['onHoldSchedule']==''){
                            $onHoldSchedule = '';
                        } else {
                            $onHoldSchedule = date('d-M-Y', strtotime($pointOrdersRow['onHoldSchedule']));
                        }
                        $curDate = date("Y-m-d");
                        $orderDate = $pointOrdersRow['orderDate'];
                        $todayDate = strtotime($curDate);
                        $orderRecDate = strtotime($orderDate);
                        $diff= ($todayDate-$orderRecDate)/86400;

                        if($diff > $slaPeriod and ($pointOrdersRow['Cash'] == '' and $pointOrdersRow['Ret'] == '' and $pointOrdersRow['partial'] == '')){
                            echo '<tr style="background-color: rgba(255, 0, 0, 0.15)">';
                        } else {
                            echo '<tr>';
                        }
                        //if((($diff > 1 && $pointOrdersRow['destination'] == 'local') || ($diff > 3 && $pointOrdersRow['destination'] == 'interDistrict')) and ($pointOrdersRow['Cash'] == '' and $pointOrdersRow['Ret'] == '' and $pointOrdersRow['partial'] == '')){
                        //    echo '<tr style="background-color: rgba(255, 0, 0, 0.15)">';
                        //} else {
                        //    if(($diff > 3 && $pointOrdersRow['region'] == 'Dhaka Outer') and ($pointOrdersRow['Cash'] == '' and $pointOrdersRow['Ret'] == '' and $pointOrdersRow['partial'] == '')){
                        //        echo '<tr style="background-color: rgba(255, 0, 0, 0.15)">';
                        //    } else {
                        //        echo '<tr>';
                        //    }
                        //}
                    ?>
                    
                        <td><?php echo $pointOrdersRow['orderid'];?></td>
                        <td><?php echo $pointOrdersRow['merOrderRef'];?></td>
                        <td><?php echo $pointOrdersRow['merchantName'];?></td>
                        <td><?php echo $pointOrdersRow['dropExecutive'];?></td>
                        <td><?php echo $pointOrdersRow['thanaName'];?></td>
                        <td><?php echo $pointOrdersRow['districtName'];?></td>
                        <td><?php echo $pointOrdersRow['Cash'];?></td>
                        <td><?php echo $pointOrdersRow['Ret'];?></td>
                        <td><?php echo $pointOrdersRow['partial'];?></td>
                        <td><?php echo $pointOrdersRow['Rea'];?></td>
                        <td><?php echo $onHoldSchedule;?></td>
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
