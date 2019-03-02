<?php
include('session.php');
include('num_format.php');
include('config.php');
$error=''; // Variable To Store Error Message
if (mysqli_connect_errno()){
    $error = "Failed to connect to Database: " .mysqli_connect_errno(). " - ". mysqli_connect_error();
} else {
    if(isset($_GET['xxCode'])){
        $shuttleDate = strtotime($_GET['xxCode']);
        $shuttleEndTime = strtotime($_GET['shuttleEndTime']);
        $shuttleDt = date("Y-m-d H:i", $shuttleDate);
        $shuttleEndTime = date("Y-m-d H:i", $shuttleEndTime);

        $merchantWiseSQL = "Select dropPointCode,  tbl_point_info.pointName, count(1) as orderCount from tbl_order_details left join tbl_point_info on tbl_order_details.dropPointCode = tbl_point_info.pointCode where DATE_FORMAT(ShtlTime, '%Y-%m-%d %H:%i') >= '$shuttleDt' and DATE_FORMAT(ShtlTime, '%Y-%m-%d %H:%i') <= '$shuttleEndTime' and Shtl = 'Y' group by dropPointCode order by tbl_point_info.pointCode";
        $merchantWiseResult = mysqli_query($conn, $merchantWiseSQL) or die ("Error: unable to execute query ".mysqli_error($conn));

        if ($user_type == 'Merchant'){
            $sql = "select * from tbl_merchant_info where merchantCode = '$user_code'";
            $result = mysqli_query($conn, $sql);
            foreach ($result as $row){
                $uName = $row['merchantName'];
            }
        } 
        if ($user_type == 'Employee'){
            $sql = "select * from tbl_employee_info where empCode = '$user_code'";
            $result = mysqli_query($conn, $sql);
            foreach ($result as $row){
                $uName = $row['empName'];
            }
        }
        if ($user_type == 'Administrator'){
            $uName = "Administrator";
        }
    }

if ($user_check!=''){    
    $output = '';
    $output .= '<table>';
    $output .= '<tr style="font-size: 200%; text-align: center"><td colspan =7>Shuttle Report</td></tr>';
    $output .= '<tr style="font-size: 110%"><td>Shuttle Period</td><td>'.date('d-m-Y H:i', strtotime($shuttleDt)).'</td><td style="text-align: center">To</td><td>'.date('d-m-Y H:i',strtotime($shuttleEndTime)).'</td></tr>';
    foreach($merchantWiseResult as $merchantWiseRow){
        $output .= '<tr style="font-size: 130%; font-weight: 800"><td>Point : </td><td>'.$merchantWiseRow['dropPointCode'].' - '.$merchantWiseRow['pointName'].'</td></tr>';   
        $pointCode = $merchantWiseRow['dropPointCode'];

        $shuttlePickSQL = "Select orderid, merOrderRef, tbl_order_details.merchantCode, tbl_merchant_info.merchantName, packagePrice from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where DATE_FORMAT(ShtlTime, '%Y-%m-%d %H:%i') >= '$shuttleDt' and DATE_FORMAT(ShtlTime, '%Y-%m-%d %H:%i') <= '$shuttleEndTime' and dropPointCode = '$pointCode'and Shtl = 'Y'";
        $shuttlePickResult = mysqli_query($conn, $shuttlePickSQL);
        $lineCount = 1;
        $output .= '<tr style="font-weight: 800"><td>SL No.</td><td>Paperfly Order ID</td><td>Merchant Reference</td><td>Merchant Name</td><td>Price</td><td>Received Confirmation</td><td>Remarks</td></tr>';
        foreach($shuttlePickResult as $shuttlePickRow){
            $output .= '<tr><td>'.$lineCount.'</td><td>'.$shuttlePickRow['orderid'].'</td><td>'.$shuttlePickRow['merOrderRef'].'</td><td>'.$shuttlePickRow['merchantName'].'</td><td>'.$shuttlePickRow['packagePrice'].'</td><td></td><td></td></tr>';
            $lineCount++;
        }
        $output .= '<tr><td colspan=7></td></tr>';            
    }
    $output .= '</table>';

    header('Content-Type: application/xls');
    header('Content-Disposition: attachment; filename=shuttleDetail.xls');
    echo $output;
} else {
    header("location:index.php");
}
}
mysqli_close($conn);
?>