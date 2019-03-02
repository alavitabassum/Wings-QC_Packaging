<?php
    include('session.php');
    include('header.php');

    include('config.php');
    include('num_format.php');  
    $orderViewPriv =0;
    if ($user_check !='admin'){
        $desigCheckSQL ="Select desigid from tbl_employee_info where empCode = '$user_code'";
        $desigCheckResult = mysqli_query($conn, $desigCheckSQL);
        $desigRow = mysqli_fetch_array($desigCheckResult);
        if ($desigRow['desigid']=='1' or $desigRow['desigid']=='2' or $desigRow['desigid']=='3' or $desigRow['desigid']=='4' or $desigRow['desigid']=='9' or $desigRow['desigid']=='10'){
            $orderViewPriv = 1;
        }
        if ($desigRow['desigid']=='6' or $desigRow['desigid']=='8'){
            $orderViewPriv = 2;
        }
        if ($desigRow['desigid']=='5'){
            $orderViewPriv = 3;
        }
        if ($desigRow['desigid']=='7'){
            $orderViewPriv = 4;
        }                    
    }
    if ($user_check =='admin'){
       $orderViewPriv = 1;
    }

    $rowCount = 50;
    if ((!isset($_POST['firstbtn'])) and (!isset($_POST['prevbtn'])) and (!isset($_POST['nextbtn'])) and (!isset($_POST['lastbtn']))){
        $pageNo = 1;
        $offsetCount = 0;
    } else {
        $pageCount = trim($_POST['pageCount']);
        $rowCount = trim($_POST['rowCount']);
        $pageNo = $pageCount;
        $offsetCount = ($pageNo - 1) * $rowCount;
    }
    $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) order by orderid, v_merchant_info.merchantName";    
    $orderCntquery = mysqli_query($conn, $orderSQL);
    $unassaignedOrder = 0;
    foreach ($orderCntquery as $unorderRow){
        if($unorderRow['pickPointEmp'] == NULL){
            $unassaignedOrder++;
        }
    }
   
    if (!isset($_POST['searchOrder'])){
        if ($user_type == 'Merchant'){
            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) and tbl_order_details.merchantCode='$user_code' order by orderid, v_merchant_info.merchantName";
            $orderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) and tbl_order_details.merchantCode='$user_code' order by orderid, v_merchant_info.merchantName";
            $orderResult = mysqli_query($conn, $orderSQL);
            $orderCntquery = mysqli_query($conn, $orderCntSql);
            $orderCntrow = mysqli_fetch_row($orderCntquery);
            $total_rows = $orderCntrow[0];
        } else {
            switch ($orderViewPriv) {
                case 1:
                    //echo "All Orders";
                    $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) order by orderid, v_merchant_info.merchantName";    
                    $orderCntSql = "SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) order by orderid, v_merchant_info.merchantName";  
                    $orderCntquery = mysqli_query($conn, $orderCntSql);
                    $orderCntrow = mysqli_fetch_row($orderCntquery);
                    $total_rows = $orderCntrow[0];
                    break;
                case 2:
                    //echo "All Orders having his assigned point number in Pick-up or Drop";
                    $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                    and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";    
                    $orderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                    and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName"; 
                    $orderCntquery = mysqli_query($conn, $orderCntSql);
                    $orderCntrow = mysqli_fetch_row($orderCntquery);
                    $total_rows = $orderCntrow[0];
                    break;
                case 3:
                    //echo "All Orders assigned to his name in Column 3 or 8";

                    //For Pick before drop executive
                    $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                    and pickPointEmp ='$user_code' and (DP1 != 'Y' or DP1 is null) order by orderid, v_merchant_info.merchantName";    
                    $orderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                    and pickPointEmp ='$user_code' and (DP1 != 'Y' or DP1 is null)";  
                    $orderCntquery = mysqli_query($conn, $orderCntSql);
                    $orderCntrow = mysqli_fetch_row($orderCntquery);
                    $total_rows = $orderCntrow[0];

                    //From drop executive to DP2
                    $dropOrderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                    and dropPointEmp ='$user_code' and DP2 = 'Y' and (Cash != 'Y' or Cash is null) and (Ret != 'Y' or Ret is null) and (NoShow != 'Y' or NoShow is null) order by orderid, v_merchant_info.merchantName";    
                    $dropOrderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                    and dropPointEmp ='$user_code' and DP2 = 'Y' and (Cash != 'Y' or Cash is null) and (Ret != 'Y' or Ret is null) and (NoShow != 'Y' or NoShow is null)";
                    $dropOrderResult = mysqli_query($conn, $dropOrderSQL);                                      
                    $orderCntquery = mysqli_query($conn, $dropOrderCntSql);
                    $dropOrderCntrow = mysqli_fetch_row($orderCntquery);
                    $total_drop_rows = $dropOrderCntrow[0];

                    break;
                case 4:
                    //echo "All Orders from his Merchants";
                    $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                    and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";    
                    $orderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                    and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";   
                    $orderCntquery = mysqli_query($conn, $orderCntSql);
                    $orderCntrow = mysqli_fetch_row($orderCntquery);
                    $total_rows = $orderCntrow[0];
                    break;
                default:
                    echo "No Orders";
            }
            $orderResult = mysqli_query($conn, $orderSQL);
        }
    } else {
        $searchText = trim($_POST['searchText']);
        if ($searchText !=''){
            if ($user_type == 'Merchant'){
                $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') and tbl_order_details.merchantCode='$user_code' order by orderid, v_merchant_info.merchantName";
                $orderResult = mysqli_query($conn, $orderSQL); 
            }else {
                switch ($orderViewPriv) {
                    case 1:
                        //echo "All Orders";
                        $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                        break;
                    case 2:
                        //echo "All Orders having his assigned point number in Pick-up or Drop";
                        $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                        break;
                    case 3:
                        //echo "All Orders assigned to his name in Column 3 or 8";
                        $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                        break;
                    case 4:
                        //echo "All Orders from his Merchants";
                        $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                        break;
                    default:
                        echo "No Orders";
                }
                //$orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                 //               FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";
                $orderResult = mysqli_query($conn, $orderSQL);                            
            }
        } else {
            if ($user_type == 'Merchant'){
                $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) and tbl_order_details.merchantCode='$user_code' order by orderid, v_merchant_info.merchantName";
                $orderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) and tbl_order_details.merchantCode='$user_code' order by orderid, v_merchant_info.merchantName";
                $orderResult = mysqli_query($conn, $orderSQL);
                $orderCntquery = mysqli_query($conn, $orderCntSql);
                $orderCntrow = mysqli_fetch_row($orderCntquery);
                $total_rows = $orderCntrow[0];
            } else {
                switch ($orderViewPriv) {
                    case 1:
                        //echo "All Orders";
                        $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) order by orderid, v_merchant_info.merchantName";    
                        $orderCntSql = "SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) order by orderid, v_merchant_info.merchantName";  
                        $orderCntquery = mysqli_query($conn, $orderCntSql);
                        $orderCntrow = mysqli_fetch_row($orderCntquery);
                        $total_rows = $orderCntrow[0];
                        break;
                    case 2:
                        //echo "All Orders having his assigned point number in Pick-up or Drop";
                        $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";    
                        $orderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName"; 
                        $orderCntquery = mysqli_query($conn, $orderCntSql);
                        $orderCntrow = mysqli_fetch_row($orderCntquery);
                        $total_rows = $orderCntrow[0];
                        break;
                    case 3:
                        //echo "All Orders assigned to his name in Column 3 or 8";

                        //For Pick before drop executive
                        $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and pickPointEmp ='$user_code' and (DP1 != 'Y' or DP1 is null) order by orderid, v_merchant_info.merchantName";    
                        $orderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and pickPointEmp ='$user_code' and (DP1 != 'Y' or DP1 is null)";  
                        $orderCntquery = mysqli_query($conn, $orderCntSql);
                        $orderCntrow = mysqli_fetch_row($orderCntquery);
                        $total_rows = $orderCntrow[0];

                        //From drop executive to DP2
                        $dropOrderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and dropPointEmp ='$user_code' and DP2 = 'Y' and (Cash != 'Y' or Cash is null) and (Ret != 'Y' or Ret is null) and (NoShow != 'Y' or NoShow is null) order by orderid, v_merchant_info.merchantName";    
                        $dropOrderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and dropPointEmp ='$user_code' and DP2 = 'Y' and (Cash != 'Y' or Cash is null) and (Ret != 'Y' or Ret is null) and (NoShow != 'Y' or NoShow is null)";
                        $dropOrderResult = mysqli_query($conn, $dropOrderSQL);                                      
                        $orderCntquery = mysqli_query($conn, $dropOrderCntSql);
                        $dropOrderCntrow = mysqli_fetch_row($orderCntquery);
                        $total_drop_rows = $dropOrderCntrow[0];

                        break;
                    case 4:
                        //echo "All Orders from his Merchants";
                        $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";    
                        $orderCntSql ="SELECT COUNT(1) FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) 
                                        and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";   
                        $orderCntquery = mysqli_query($conn, $orderCntSql);
                        $orderCntrow = mysqli_fetch_row($orderCntquery);
                        $total_rows = $orderCntrow[0];
                        break;
                    default:
                        echo "No Orders";
                }
                //$orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                //                FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) order by orderid, v_merchant_info.merchantName";    
                $orderResult = mysqli_query($conn, $orderSQL);
            }
        }
    }
    $trckerPrivSQL = "Select level from tbl_order_tracker where user_id ='$user_id_chk'";
    $trackerPrivResult = mysqli_query($conn, $trckerPrivSQL);
    $trackerRow = mysqli_fetch_array($trackerPrivResult);
    if ($user_id_chk == '1' or $trackerRow['level'] == 'power_user'){
        include('tracker_header.php');
        include('power_user_tracker.php');
    }
    if ($trackerRow['level'] == 'level4'){
        include('tracker_header.php');
        include('level4_tracker.php');
    }
    if ($trackerRow['level'] == 'level3'){
        //include('tracker_header.php');
        include('tracker_level3_header.php');
        include('level3_tracker.php');
    }
    if ($trackerRow['level'] == 'level2'){
        include('tracker_header.php');
        include('level2_tracker.php');
    }
    if ($trackerRow['level'] == 'level1'){
        include('tracker_header.php');
        include('level1_tracker.php');
    }
    mysqli_close($conn);
?>
    </body>
</html>

 