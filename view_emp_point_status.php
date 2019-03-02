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

        $startDate = $_GET['startDate'];

        $poinNameResult = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_point_info where pointCode = '$pointCode'"));

        if($_GET['yyCode'] !=''){
            $merchant = encryptor('decrypt', $_GET['yyCode']);
            $pointOrdersResult = mysqli_query($conn, "SELECT distinct tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName, pickData.pickCount, cashData.cashCount, retData.retCount, partialData.partialCount, onHoldData.onHoldCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode 
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as pickCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.dropAssignTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchant' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as pickData on tbl_order_details.dropPointEmp = pickData.dropPointEmp 
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as cashCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.CashTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchant' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as cashData on tbl_order_details.dropPointEmp = cashData.dropPointEmp 
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as retCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.RetTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchant' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as retData on tbl_order_details.dropPointEmp = retData.dropPointEmp 
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as partialCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.partialTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchant' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as partialData on tbl_order_details.dropPointEmp = partialData.dropPointEmp
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as onHoldCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.ReaTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchant' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as onHoldData on tbl_order_details.dropPointEmp = onHoldData.dropPointEmp
WHERE (DATE_FORMAT(tbl_order_details.dropAssignTime,'%Y-%m-%d') = '$startDate' or DATE_FORMAT(tbl_order_details.CashTime,'%Y-%m-%d') = '$startDate' or DATE_FORMAT(tbl_order_details.RetTime,'%Y-%m-%d') = '$startDate' or DATE_FORMAT(tbl_order_details.partialTime,'%Y-%m-%d') = '$startDate') and tbl_order_details.dropPointCode = '$pointCode' and tbl_order_details.merchantCode = '$merchant' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName");
        } else {
            $pointOrdersResult = mysqli_query($conn, "SELECT distinct tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName, pickData.pickCount, cashData.cashCount, retData.retCount, partialData.partialCount, onHoldData.onHoldCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode 
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as pickCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.dropAssignTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as pickData on tbl_order_details.dropPointEmp = pickData.dropPointEmp 
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as cashCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.CashTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as cashData on tbl_order_details.dropPointEmp = cashData.dropPointEmp 
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as retCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.RetTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as retData on tbl_order_details.dropPointEmp = retData.dropPointEmp 
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as partialCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.partialTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as partialData on tbl_order_details.dropPointEmp = partialData.dropPointEmp
left join (SELECT distinct dropPointCode, dropPointEmp, tbl_employee_info.empName, count(1) as onHoldCount FROM tbl_order_details left join tbl_employee_info on tbl_order_details.dropPointEmp = tbl_employee_info.empCode WHERE DATE_FORMAT(tbl_order_details.ReaTime,'%Y-%m-%d') = '$startDate' and dropPointCode = '$pointCode' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName) as onHoldData on tbl_order_details.dropPointEmp = onHoldData.dropPointEmp
WHERE (DATE_FORMAT(tbl_order_details.dropAssignTime,'%Y-%m-%d') = '$startDate' or DATE_FORMAT(tbl_order_details.CashTime,'%Y-%m-%d') = '$startDate' or DATE_FORMAT(tbl_order_details.RetTime,'%Y-%m-%d') = '$startDate' or DATE_FORMAT(tbl_order_details.partialTime,'%Y-%m-%d') = '$startDate') and tbl_order_details.dropPointCode = '$pointCode' group by tbl_order_details.dropPointCode, tbl_order_details.dropPointEmp, tbl_employee_info.empName");    
        }
        
   $cashTotal = 0;
   $retTotal = 0;
   $partialTotal = 0;     
   $totalAssigned = 0;
   $onHoldTotal = 0;
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Executive deliver count</title>
        <script src="js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <font size="3">
            <?php if($_GET['yyCode'] !=''){?>
            <?php
                $merchantRow = mysqli_fetch_array(mysqli_query($conn, "select merchantName from tbl_merchant_info where merchantCode = '$merchant'"));
            ?>
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-4">
                    <label>Merchant Name : <?php echo $merchantRow['merchantName'];?></label>
                </div>
            </div>
            <?php }?>
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-2">
                    <label>Point Code: &nbsp;&nbsp;<?php echo $pointCode;?></label>
                </div>
                <div class="col-sm-3">
                    <label>Point Name: &nbsp;&nbsp;<?php echo $poinNameResult['pointName'];?></label>
                </div>
                <div class="col-sm-3">
                    <label>Employee Count: &nbsp;&nbsp;<?php echo mysqli_num_rows($pointOrdersResult);?></label>
                </div>
                <div class="col-sm-4">
                    <label>Delivery Date: &nbsp;&nbsp;<?php echo date('d-M-Y', strtotime($startDate));?></label>
                </div>
            </div>
            </font>
            <font size="1">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        <th>Order Assigned</th>
                        <th>Cash Count</th>
                        <th>Return Count</th>
                        <th>Partial Count</th>
                        <th>On Hold Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pointOrdersResult as $pointOrdersRow){?>
                    <?php $success = round((($pointOrdersRow['cashCount']/$pointOrdersRow['pickCount'])*100),0);?>
                    <?php $unDelivered = ($pointOrdersRow['pickCount'] - ($pointOrdersRow['cashCount'] + $pointOrdersRow['retCount'] + $pointOrdersRow['partialCount']));?>
                    <tr>
                        <td><?php echo $pointOrdersRow['dropPointEmp'];?></td>
                        <td><?php echo $pointOrdersRow['empName'];?></td>
                        <td><?php echo $pointOrdersRow['pickCount'];?></td>
                        <td><?php echo $pointOrdersRow['cashCount'];?></td>
                        <td><?php echo $pointOrdersRow['retCount'];?></td>
                        <td><?php echo $pointOrdersRow['partialCount'];?></td>
                        <td><?php echo $pointOrdersRow['onHoldCount'];?></td>
                    </tr>
                    <?php
                        $cashTotal = $cashTotal + $pointOrdersRow['cashCount'];
                        $retTotal = $retTotal + $pointOrdersRow['retCount'];
                        $partialTotal = $partialTotal + $pointOrdersRow['partialCount'];
                        $totalAssigned = $totalAssigned + $pointOrdersRow['pickCount'];
                        $onHoldTotal = $onHoldTotal + $pointOrdersRow['onHoldCount'];
                    ?>
                    <?php }?>
                    <tr style="font-weight: 800">
                        <td colspan="2">Total</td>
                        <td><?php echo $totalAssigned;?></td>
                        <td><?php echo $cashTotal;?></td>
                        <td><?php echo $retTotal;?></td>
                        <td><?php echo $partialTotal;?></td>
                        <td><?php echo $onHoldTotal;?></td>
                    </tr>
                </tbody>
            </table>
                </font>
        </div>
    </body>
</html>
<?php
    }
?>
