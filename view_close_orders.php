<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and closedOrders = 'Y'"));
    if ($userPrivCheckRow['closedOrders'] != 'Y'){
        exit();
    }
    $orderViewPriv =0;
    if ($user_check !='admin'){
        $desigCheckSQL ="Select desigid from tbl_employee_info where empCode = '$user_code'";
        $desigCheckResult = mysqli_query($conn, $desigCheckSQL);
        $desigRow = mysqli_fetch_array($desigCheckResult);
        if ($desigRow['desigid']=='1' or $desigRow['desigid']=='2' or $desigRow['desigid']=='3' or $desigRow['desigid']=='4'or $desigRow['desigid']=='9' or $desigRow['desigid']=='10' or $desigRow['desigid']=='11'){
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
    if (!isset($_POST['searchOrder'])){

    } else {
        $startDate = date("Y-m-d", strtotime(trim($_POST['startDate'])));
        $startDate = mysqli_real_escape_string($conn, $startDate);
        $endDate = date("Y-m-d", strtotime(trim($_POST['endDate'])));
        $endDate = mysqli_real_escape_string($conn, $endDate);
        $searchText = trim($_POST['searchText']);
        $searchText = mysqli_real_escape_string($conn, $searchText);
        $showMerchant = trim($_POST['showMerchant']);
        $merchantList = trim($_POST['merchantList']);
        $showDropPoint = trim($_POST['showDropPoint']);
        $pointList = trim($_POST['pointList']);
        $dropExec = trim($_POST['dropExec']);
        $dropExecList = trim($_POST['dropExecList']);
        if ($searchText !=''){
            if ($user_type == 'Merchant'){
                $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') and tbl_order_details.merchantCode='$user_code' order by orderid, v_merchant_info.merchantName";
                $orderResult = mysqli_query($conn, $orderSQL); 
                //$rptString = "startDate=".$startDate."&endDate=".$endDate."&merchantCode=".$user_code."&searchText=".$searchText;                           
            }else {
                switch ($orderViewPriv) {
                    case 1:
                        //echo "All Orders";
                        if ($showMerchant !=''){
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and (orderDate between '$startDate' and '$endDate')  and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                            //$rptString = "startDate=".$startDate."&endDate=".$endDate."&merchantCode=".$user_code."&searchText=".$searchText;
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate')  and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate')  and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')  and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')  and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')  and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')  and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate')  and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                
                            //$rptString = "startDate=".$startDate."&endDate=".$endDate."&merchantCode=&searchText=".$searchText;
                        }
                        break; 
                    case 2:
                        //echo "All Orders having his assigned point number in Pick-up or Drop";
                        if ($showMerchant !=''){
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant != '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate')
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                
                        }
                        break;
                    case 3:
                        //echo "All Orders assigned to his name in Column 3 or 8";
                        if ($showMerchant !=''){
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant != '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate')
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                
                        }
                        break;
                    case 4:
                        //echo "All Orders from his Merchants";
                        if ($showMerchant !=''){
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and (orderDate between '$startDate' and '$endDate')
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate')
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate')
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant != '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate')
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate')
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                                
                        }
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
                                FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and tbl_order_details.merchantCode='$user_code' order by orderid, v_merchant_info.merchantName";
                $orderResult = mysqli_query($conn, $orderSQL);
            } else {
                switch ($orderViewPriv) {
                    case 1:
                        //echo "All Orders";
                        if ($showMerchant !=''){
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and tbl_order_details.merchantCode='$merchantList' order by orderid, v_merchant_info.merchantName";    
                        } 
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and tbl_order_details.dropPointCode='$pointList' order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointEmp='$dropExecList' order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') and tbl_order_details.dropPointEmp='$dropExecList' order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') order by orderid, v_merchant_info.merchantName";                                
                        }
                        break;
                    case 2:
                        //echo "All Orders having his assigned point number in Pick-up or Drop";
                        if ($showMerchant !=''){
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";                                
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";                                
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";                                                                                        
                        }                         
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointCode in (select pointCode from tbl_employee_point where empCode='$user_code') or dropPointCode in (select pointCode from tbl_employee_point where empCode='$user_code')) order by orderid, v_merchant_info.merchantName";                                
                        }
                        break;
                    case 3:
                        //echo "All Orders assigned to his name in Column 3 or 8";
                        if ($showMerchant !=''){
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') order by orderid, v_merchant_info.merchantName";    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') order by orderid, v_merchant_info.merchantName";                                
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant != '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') 
                                            and (pickPointEmp ='$user_code' or dropPointEmp ='$user_code') order by orderid, v_merchant_info.merchantName";                                
                        }
                        break;
                    case 4:
                        //echo "All Orders from his Merchants";
                        if ($showMerchant !=''){
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and (orderDate between '$startDate' and '$endDate') 
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate') 
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";                                
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and (orderDate between '$startDate' and '$endDate') 
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";                                                            
                        }
                        if($showMerchant != '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";                                                                                        
                        }
                        if($showMerchant != '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.merchantCode='$merchantList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint != '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointCode='$pointList' and tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec != '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where tbl_order_details.dropPointEmp='$dropExecList' and (orderDate between '$startDate' and '$endDate') 
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";                                                                                                                    
                        }
                        if($showMerchant == '' && $showDropPoint == '' && $dropExec == '') {
                            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                            FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (orderDate between '$startDate' and '$endDate') 
                                            and tbl_order_details.merchantCode in (Select merchantCode from tbl_merchant_info where empCode = '$user_code') order by orderid, v_merchant_info.merchantName";                                
                        }
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
        include('close_tracker.php');
        include('power_user_tracker.php');
        ?>
<!--
        <iframe id="closeReport" style="margin-left: 15px; display: none" src='Report-Close-Orders?<?php //echo $rptString;?>' width='1200' height='450'></iframe>
-->
        <?php
    }
    if ($trackerRow['level'] == 'level4'){
        include('close_tracker.php');
        include('power_user_tracker.php');
    }
    if ($trackerRow['level'] == 'level3'){
        include('close_tracker.php');
        include('level3_tracker.php');
    }
    if ($trackerRow['level'] == 'level2'){
        include('close_tracker.php');
        include('level2_tracker.php');
    }
    if ($trackerRow['level'] == 'level1'){
        include('close_tracker.php');
        include('level1_tracker.php');
    }
    mysqli_close($conn);
?>
    </body>
</html>

 