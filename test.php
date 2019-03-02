<?php
    include('config2.php');

    $merchantCode = 'M-1-0414';

    $orderListSQL = "select orderid, merOrderRef, pick, pickTime, DP1, DP1Time, DP2, DP2Time, cust, custTime, cash, cashTime, ret, retTime as returnTime, partial, partialTime, Rea as onHold, onHoldSchedule from tbl_order_details where merchantCode = '$merchantCode' and close is null and orderid != '270618-0800-A1-A6'";

    echo $orderListSQL.'<br>';
    $orderListResult = mysqli_query($conn, $orderListSQL) or die ("Error searching orders information".mysqli_error($conn));
    $ordersArray = array();
    while($orderListRow = mysqli_fetch_assoc($orderListResult)){
        $ordersArray[] = $orderListRow;
    }
    echo json_encode($ordersArray, JSON_HEX_APOS);
?>