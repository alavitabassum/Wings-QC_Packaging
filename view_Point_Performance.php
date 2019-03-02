<?php
    include('session.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and point_executive = 'Y'"));
    if ($userPrivCheckRow['delivery_performance'] != 'Y'){
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

        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        $poinNameResult = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_point_info where pointCode = '$pointCode'"));

        $pointOrderSQL = "SELECT tbl_order_details.dropPointEmp, tbl_employee_info.empName, totStat.totCount, count(1) as cashCount, returnStat.retCount, partialStat.partialCount FROM tbl_order_details 
left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode

left join (SELECT tbl_order_details.dropPointEmp, tbl_employee_info.empName, count(1) as retCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode 
WHERE tbl_order_details.dropPointCode = '$pointCode' and Ret = 'Y' and DATE_FORMAT(RetTime, '%Y-%m-%d') BETWEEN '$startDate' and '$endDate' group by tbl_order_details.dropPointEmp, tbl_employee_info.empName) as returnStat on tbl_order_details.dropPointEmp = returnStat.dropPointEmp

left join (SELECT tbl_order_details.dropPointEmp, tbl_employee_info.empName, count(1) as totCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode 
WHERE tbl_order_details.dropPointCode = '$pointCode' and DATE_FORMAT(dropAssignTime, '%Y-%m-%d') BETWEEN '$startDate' and '$endDate' group by tbl_order_details.dropPointEmp, tbl_employee_info.empName) as totStat on tbl_order_details.dropPointEmp = totStat.dropPointEmp

left join (SELECT tbl_order_details.dropPointEmp, tbl_employee_info.empName, count(1) as partialCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode 
WHERE tbl_order_details.dropPointCode = '$pointCode' and partial = 'Y' and DATE_FORMAT(partialTime, '%Y-%m-%d') BETWEEN '$startDate' and '$endDate' group by tbl_order_details.dropPointEmp, tbl_employee_info.empName) as partialStat on tbl_order_details.dropPointEmp = partialStat.dropPointEmp

WHERE tbl_order_details.dropPointCode = '$pointCode' and Cash = 'Y' and DATE_FORMAT(CashTime, '%Y-%m-%d') BETWEEN '$startDate' and '$endDate' group by tbl_order_details.dropPointEmp, tbl_employee_info.empName";

    $pointOrdersResult = mysqli_query($conn, $pointOrderSQL);

    $totCount = 0 ;
    $totalCashCount = 0;
    $returnCount = 0;
    $partialCount = 0;

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Executives Delivery Performance</title>
        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <font size="2">
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-2">
                    <label>Point Code: <?php echo $pointCode;?></label>
                </div>
                <div class="col-sm-2">
                    <label>Point Name: <?php echo $poinNameResult['pointName'];?></label>
                </div>
                <div class="col-sm-2">
                </div>
                <div class="col-sm-4">
                    <label>Date: <?php echo date('d-M-Y',strtotime($startDate));?> To <?php echo date('d-M-Y', strtotime($endDate));?></label>
                </div>
                <div class="col-sm-2">
                    <label>Employee Count: <?php echo mysqli_num_rows($pointOrdersResult);?></label>
                </div>
            </div>
            </font>
            <font size="1">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        <th>Total Assigned</th>
                        <th>Cash</th>
                        <th>Ret</th>
                        <th>Partial</th>
                        <th>Cash %</th>
                        <th>Return %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pointOrdersResult as $pointOrdersRow){?>
                    <?php
                        $cashPercent = round(($pointOrdersRow['cashCount']/$pointOrdersRow['totCount'])*100,0); 
                        $deliveryPercent = round((($pointOrdersRow['retCount'])/$pointOrdersRow['totCount'])*100,0); 
                    ?>
                    <tr>
                        <td><?php echo $pointOrdersRow['dropPointEmp'];?></td>
                        <td><?php echo $pointOrdersRow['empName'];?></td>
                        <td><?php echo $pointOrdersRow['totCount'];?></td>
                        <td><?php echo $pointOrdersRow['cashCount'];?></td>
                        <td><?php echo $pointOrdersRow['retCount'];?></td>
                        <td><?php echo $pointOrdersRow['partialCount'];?></td>
                        <td><?php echo $cashPercent;?></td>
                        <td><?php echo $deliveryPercent;?></td>
                    </tr>
                    <?php
                        $totCount = $totCount + $pointOrdersRow['totCount'];
                        $totalCashCount = $totalCashCount + $pointOrdersRow['cashCount'];
                        $returnCount = $returnCount + $pointOrdersRow['retCount'];
                        $partialCount = $partialCount + $pointOrdersRow['partialCount'];
                        $totalSuccess = round(($totalCashCount/$totCount)*100,0);
                        $totalDelivered = round((($totalCashCount + $returnCount + $partialCount)/$totCount)*100,0);
                        }
                    ?>
                    <tr style="font-weight: 800"><td colspan="2">Total</td><td><?php echo $totCount;?></td><td><?php echo $totalCashCount;?></td><td><?php echo $returnCount;?></td><td><?php echo $partialCount;?></td><td><?php echo $totalSuccess;?></td><td><?php echo $totalDelivered;?></td></tr>
                </tbody>
            </table>
                </font>
        </div>
    </body>
</html>
<?php
    }
?>
