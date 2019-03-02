<?php
    if (isset($_POST['submit'])){
        include('config.php');
        include('session.php');
        $startDate = date("Y-m-d", strtotime(trim($_POST['startDate'])));
        $startDate = mysqli_real_escape_string($conn, $startDate);
        $endDate = date("Y-m-d", strtotime(trim($_POST['endDate'])));
        $endDate = mysqli_real_escape_string($conn, $endDate);
        $showMerchant = trim($_POST['showMerchant']);
        $merchantList = trim($_POST['merchantList']);
        // Original PHP code by Chirp Internet: www.chirp.com.au
        // Please acknowledge use of this code by including this header.
        $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and exportToExcel = 'Y'"));
        if ($userPrivCheckRow['exportToExcel'] != 'Y'){
            exit();
        }
        $flag = false;
        if ($showMerchant !='' && $user_type == 'Merchant'){

$sql ="SELECT tbl_order_details.orderid, tbl_merchant_info.merchantName, merOrderRef as 'Merchant Order', orderDate, packagePrice, custname, custaddress, cust_thana.thanaName as 'custthana' , cust_district.districtName as 'custdist', custphone, CashAmt, DATE_FORMAT(CashTime,'%d-%b-%y %H:%i') as 'CashTime', cashComment, tbl_order_details.Ret as 'Return', DATE_FORMAT(RetTime,'%d-%b-%y %H:%i') as 'RetTime', retReason, retRem, partial, DATE_FORMAT(partialTime, '%Y-%m-%d %H:%i') as partialTime, partialReason, onHoldSchedule, onHoldReason  
FROM ((((((tbl_order_details
LEFT JOIN tbl_employee_info ON tbl_order_details.pickPointEmp = tbl_employee_info.empCode) left join tbl_employee_info as drop_employee on tbl_order_details.dropPointEmp = drop_employee.empCode) LEFT JOIN ((tbl_merchant_info LEFT JOIN tbl_thana_info ON tbl_merchant_info.thanaid = tbl_thana_info.thanaId) LEFT JOIN tbl_district_info ON tbl_merchant_info.districtid = tbl_district_info.districtId) ON tbl_order_details.merchantCode = tbl_merchant_info.merchantCode) left join tbl_thana_info as pick_thana on tbl_order_details.thanaId = pick_thana.thanaId) left join tbl_district_info as pick_district on tbl_order_details.districtId = pick_district.districtId) left join tbl_thana_info as cust_thana on tbl_order_details.customerThana = cust_thana.thanaId) left join tbl_district_info as cust_district on tbl_order_details.customerDistrict = cust_district.districtId where orderDate between '$startDate' and '$endDate' and tbl_order_details.merchantCode='$merchantList'";
            $result = mysqli_query($conn, $sql);
            $output = '';

            $output .= '<table id="orderOutputTable" class="table table-hover">';
            $output .= '<thead><tr><th>Order ID</th><th>Merchant Name</th><th>Merchant Order ID</th><th>Order Date</th><th>Package Price</th><th>Customer Name</th><th>Customer Address</th><th>Customer Thana Name</th><th>Customer District Name</th><th>Customer Phone</th><th>Cash Amount</th><th>Cash Time</th><th>Cash Comment</th><th>Return</th><th>Return Time</th><th>Return Reason</th><th>Return Remarks</th><th>Partial</th><th>Partial Time</th><th>Partial Remarks</th><th>On Hold</th><th>On Hold Reason</th></tr></thead>';
            $output .='<tbody>';
            foreach ($result as $row){
                $output .='<tr>';
                    $output .='<td>'.$row['orderid'].'</td>';
                    $output .='<td>'.$row['merchantName'].'</td>';
                    $output .='<td>'.$row['Merchant Order'].'</td>';
                    $output .='<td>'.$row['orderDate'].'</td>';
                    $output .='<td>'.$row['packagePrice'].'</td>';
                    $output .='<td>'.$row['custname'].'</td>';
                    $output .='<td>'.$row['custaddress'].'</td>';
                    $output .='<td>'.$row['custthana'].'</td>';
                    $output .='<td>'.$row['custdist'].'</td>';
                    $output .='<td>'.$row['custphone'].'</td>';
                    $output .='<td>'.$row['CashAmt'].'</td>';
                    $output .='<td>'.$row['CashTime'].'</td>';
                    $output .='<td>'.$row['cashComment'].'</td>';
                    $output .='<td>'.$row['Return'].'</td>';
                    $output .='<td>'.$row['RetTime'].'</td>';
                    $output .='<td>'.$row['retReason'].'</td>';
                    $output .='<td>'.$row['retRem'].'</td>';
                    $output .='<td>'.$row['partial'].'</td>';
                    $output .='<td>'.$row['partialTime'].'</td>';
                    $output .='<td>'.$row['partialReason'].'</td>';
                    $output .='<td>'.$row['onHoldSchedule'].'</td>';
                    $output .='<td>'.$row['onHoldReason'].'</td>';
                $output .='</tr>';
            }
            $output .='</tbody></table>';
        } else {
            if($showMerchant !=''){
                $sql ="SELECT tbl_order_details.orderid, barcode,tbl_order_details.dropPointCode, tbl_employee_info.empName as pickEx, drop_employee.empName as dropEx, tbl_order_details.ShtlBy, tbl_order_details.merchantCode,     tbl_merchant_info.merchantName, tbl_merchant_info.address, tbl_merchant_info.contactNumber, tbl_thana_info.thanaName as merThana, tbl_district_info.districtName as 'merDist', merOrderRef as 'merOrder', orderDate, pickMerchantName, pickMerchantAddress, pick_thana.thanaName as 'pickThana', pick_district.districtName as 'pickDist', pickupMerchantPhone, productSizeWeight, deliveryOption, productBrief, packagePrice, custname, custaddress, cust_thana.thanaName as 'custthana' , cust_district.districtName as 'custdist', custphone, CashAmt, DATE_FORMAT(CashTime,'%d-%b-%y %H:%i') as 'CashTime', cashComment, tbl_order_details.Ret as 'Return', DATE_FORMAT(RetTime,'%d-%b-%y %H:%i') as 'RetTime', retReason, retRem, partial, DATE_FORMAT(partialTime, '%Y-%m-%d %H:%i') as partialTime, partialReason, onHoldSchedule, onHoldReason, retcp1 as CP1, DATE_FORMAT(retcp1Time,'%d-%b-%y %H:%i') as 'CP1Time', retcp1By as CP1By, tbl_order_details.close, accRem, tbl_order_details.destination, tbl_order_details.charge, tbl_order_details.cod, IF(tbl_order_details.CashAmt > 0, ROUND((((tbl_order_details.CashAmt - tbl_order_details.charge) * tbl_order_details.cod)/100),2), 0) as 'CoDAmount', case when Cash = 'Y' then DATEDIFF(DATE_FORMAT(CashTime, '%Y-%m-%d'), orderDate) when Ret = 'Y' then DATEDIFF(DATE_FORMAT(RetTime, '%Y-%m-%d'), orderDate) when partial = 'Y' then DATEDIFF(DATE_FORMAT(partialTime, '%Y-%m-%d'), orderDate) end as deliveryDuration, DATE_FORMAT(tbl_order_details.creation_date,'%d-%b-%Y %h:%i %p') as 'CreationDate', tbl_order_details.created_by as 'CreatedBy', DATE_FORMAT(tbl_order_details.update_date,'%d-%b-%Y %h:%i%p') as 'UpdateDate', tbl_order_details.updated_by as 'UpdatedBy'  
    FROM ((((((tbl_order_details
    LEFT JOIN tbl_employee_info ON tbl_order_details.pickPointEmp = tbl_employee_info.empCode) left join tbl_employee_info as drop_employee on tbl_order_details.dropPointEmp = drop_employee.empCode) LEFT JOIN ((tbl_merchant_info LEFT JOIN tbl_thana_info ON tbl_merchant_info.thanaid = tbl_thana_info.thanaId) LEFT JOIN tbl_district_info ON tbl_merchant_info.districtid = tbl_district_info.districtId) ON tbl_order_details.merchantCode = tbl_merchant_info.merchantCode) left join tbl_thana_info as pick_thana on tbl_order_details.thanaId = pick_thana.thanaId) left join tbl_district_info as pick_district on tbl_order_details.districtId = pick_district.districtId) left join tbl_thana_info as cust_thana on tbl_order_details.customerThana = cust_thana.thanaId) left join tbl_district_info as cust_district on tbl_order_details.customerDistrict = cust_district.districtId where orderDate between '$startDate' and '$endDate' and tbl_order_details.merchantCode='$merchantList'";

                $result = mysqli_query($conn, $sql);
                $output = '';

                $output .= '<table id="orderOutputTable" class="table table-hover">';
                $output .= '<thead><tr><th>Order ID</th><th>barcode</th><th>Drop Point</th><th>Pickup Executive</th><th>Drop Executive</th><th>Shuttle By</th><th>Merchant Code</th><th>Merchant Name</th><th>Merchant Address</th><th>Merchant Phone</th><th>Merchant Thana Name</th><th>Merchant District Name</th><th>Merchant Order Reference</th><th>Order Date</th><th>Pickup Merchant Name</th><th>Pickup Merhcant Address</th><th>Thana Name</th><th>District Name</th><th>Pickup Merchant Phone</th><th>Package Option</th><th>Delivery Option</th><th>Product Breif</th><th>Package Price</th><th>Customer Name</th><th>Customer Address</th><th>Customer Thana Name</th><th>Customr District Name</th><th>Customer Phone</th><th>Cash Amount</th><th>Cash Time</th><th>Cash Comment</th><th>Return</th><th>Return Time</th><th>Return Reason</th><th>Return Remarks</th><th>Partial</th><th>Partial Time</th><th>Partial Remarks</th><th>On Hold</th><th>On Hold Reason</th><th>Return CP1</th><th>CP1 Time</th><th>Return CP1 By</th><th>Close</th><th>Accident</th><th>Destination</th><th>Charge</th><th>CoD</th><th>CoD Amount</th><th>Delivery Duration</th><th>Creation Date</th><th>Created By</th><th>Update Date</th><th>Updated By</th></tr></thead>';
                $output .='<tbody>';
                foreach ($result as $row){
                    $output .='<tr>';
                        $output .='<td>'.$row['orderid'].'</td>';
                        $output .='<td>'.$row['barcode'].'</td>';
                        $output .='<td>'.$row['dropPointCode'].'</td>';
                        $output .='<td>'.$row['pickEx'].'</td>';
                        $output .='<td>'.$row['dropEx'].'</td>';
                        $output .='<td>'.$row['ShtlBy'].'</td>';
                        $output .='<td>'.$row['merchantCode'].'</td>';
                        $output .='<td>'.$row['merchantName'].'</td>';
                        $output .='<td>'.$row['address'].'</td>';
                        $output .='<td>'.$row['contactNumber'].'</td>';
                        $output .='<td>'.$row['merThana'].'</td>';
                        $output .='<td>'.$row['merDist'].'</td>';
                        $output .='<td>'.$row['merOrder'].'</td>';
                        $output .='<td>'.$row['orderDate'].'</td>';
                        $output .='<td>'.$row['pickMerchantName'].'</td>';
                        $output .='<td>'.$row['pickMerchantAddress'].'</td>';
                        $output .='<td>'.$row['pickThana'].'</td>';
                        $output .='<td>'.$row['pickDist'].'</td>';
                        $output .='<td>'.$row['pickupMerchantPhone'].'</td>';
                        $output .='<td>'.$row['productSizeWeight'].'</td>';
                        $output .='<td>'.$row['deliveryOption'].'</td>';
                        $output .='<td>'.$row['productBrief'].'</td>';
                        $output .='<td>'.$row['packagePrice'].'</td>';
                        $output .='<td>'.$row['custname'].'</td>';
                        $output .='<td>'.$row['custaddress'].'</td>';
                        $output .='<td>'.$row['custthana'].'</td>';
                        $output .='<td>'.$row['custdist'].'</td>';
                        $output .='<td>'.$row['custphone'].'</td>';
                        $output .='<td>'.$row['CashAmt'].'</td>';
                        $output .='<td>'.$row['CashTime'].'</td>';
                        $output .='<td>'.$row['cashComment'].'</td>';
                        $output .='<td>'.$row['Return'].'</td>';
                        $output .='<td>'.$row['RetTime'].'</td>';
                        $output .='<td>'.$row['retReason'].'</td>';
                        $output .='<td>'.$row['retRem'].'</td>';
                        $output .='<td>'.$row['partial'].'</td>';
                        $output .='<td>'.$row['partialTime'].'</td>';
                        $output .='<td>'.$row['partialReason'].'</td>';
                        $output .='<td>'.$row['onHoldSchedule'].'</td>';
                        $output .='<td>'.$row['onHoldReason'].'</td>';
                        $output .='<td>'.$row['CP1'].'</td>';
                        $output .='<td>'.$row['CP1Time'].'</td>';
                        $output .='<td>'.$row['CP1By'].'</td>';
                        $output .='<td>'.$row['close'].'</td>';
                        $output .='<td>'.$row['accRem'].'</td>';
                        $output .='<td>'.$row['destination'].'</td>';
                        $output .='<td>'.$row['charge'].'</td>';
                        $output .='<td>'.$row['cod'].'</td>';
                        $output .='<td>'.$row['CoDAmount'].'</td>';
                        $output .='<td>'.$row['deliveryDuration'].'</td>';
                        $output .='<td>'.$row['CreationDate'].'</td>';
                        $output .='<td>'.$row['CreatedBy'].'</td>';
                        $output .='<td>'.$row['UpdateDate'].'</td>';
                        $output .='<td>'.$row['UpdatedBy'].'</td>';
                    $output .='</tr>';
                }
                $output .='</tbody></table>';                
            } else {
                $sql ="SELECT tbl_order_details.orderid, barcode,tbl_order_details.dropPointCode, tbl_employee_info.empName as pickEx, drop_employee.empName as dropEx, tbl_order_details.ShtlBy, tbl_order_details.merchantCode,     tbl_merchant_info.merchantName, tbl_merchant_info.address, tbl_merchant_info.contactNumber, tbl_thana_info.thanaName as merThana, tbl_district_info.districtName as 'merDist', merOrderRef as 'merOrder', orderDate, pickMerchantName, pickMerchantAddress, pick_thana.thanaName as 'pickThana', pick_district.districtName as 'pickDist', pickupMerchantPhone, productSizeWeight, deliveryOption, productBrief, packagePrice, custname, custaddress, cust_thana.thanaName as 'custthana' , cust_district.districtName as 'custdist', custphone, CashAmt, DATE_FORMAT(CashTime,'%d-%b-%y %H:%i') as 'CashTime', cashComment, tbl_order_details.Ret as 'Return', DATE_FORMAT(RetTime,'%d-%b-%y %H:%i') as 'RetTime', retReason, retRem, partial, DATE_FORMAT(partialTime, '%Y-%m-%d %H:%i') as partialTime, partialReason, onHoldSchedule, onHoldReason, retcp1 as CP1, DATE_FORMAT(retcp1Time,'%d-%b-%y %H:%i') as 'CP1Time', retcp1By as CP1By, tbl_order_details.close, accRem, tbl_order_details.destination, tbl_order_details.charge, tbl_order_details.cod, IF(tbl_order_details.CashAmt > 0, ROUND((((tbl_order_details.CashAmt - tbl_order_details.charge) * tbl_order_details.cod)/100),2), 0) as 'CoDAmount', case when Cash = 'Y' then DATEDIFF(DATE_FORMAT(CashTime, '%Y-%m-%d'), orderDate) when Ret = 'Y' then DATEDIFF(DATE_FORMAT(RetTime, '%Y-%m-%d'), orderDate) when partial = 'Y' then DATEDIFF(DATE_FORMAT(partialTime, '%Y-%m-%d'), orderDate) end as deliveryDuration, DATE_FORMAT(tbl_order_details.creation_date,'%d-%b-%Y %h:%i %p') as 'CreationDate', tbl_order_details.created_by as 'CreatedBy', DATE_FORMAT(tbl_order_details.update_date,'%d-%b-%Y %h:%i%p') as 'UpdateDate', tbl_order_details.updated_by as 'UpdatedBy'  
    FROM ((((((tbl_order_details
    LEFT JOIN tbl_employee_info ON tbl_order_details.pickPointEmp = tbl_employee_info.empCode) left join tbl_employee_info as drop_employee on tbl_order_details.dropPointEmp = drop_employee.empCode) LEFT JOIN ((tbl_merchant_info LEFT JOIN tbl_thana_info ON tbl_merchant_info.thanaid = tbl_thana_info.thanaId) LEFT JOIN tbl_district_info ON tbl_merchant_info.districtid = tbl_district_info.districtId) ON tbl_order_details.merchantCode = tbl_merchant_info.merchantCode) left join tbl_thana_info as pick_thana on tbl_order_details.thanaId = pick_thana.thanaId) left join tbl_district_info as pick_district on tbl_order_details.districtId = pick_district.districtId) left join tbl_thana_info as cust_thana on tbl_order_details.customerThana = cust_thana.thanaId) left join tbl_district_info as cust_district on tbl_order_details.customerDistrict = cust_district.districtId where orderDate between '$startDate' and '$endDate'";

                $result = mysqli_query($conn, $sql);
                $output = '';

                $output .= '<table id="orderOutputTable" class="table table-hover">';
                $output .= '<thead><tr><th>Order ID</th><th>barcode</th><th>Drop Point</th><th>Pickup Executive</th><th>Drop Executive</th><th>Shuttle By</th><th>Merchant Code</th><th>Merchant Name</th><th>Merchant Address</th><th>Merchant Phone</th><th>Merchant Thana Name</th><th>Merchant District Name</th><th>Merchant Order Reference</th><th>Order Date</th><th>Pickup Merchant Name</th><th>Pickup Merhcant Address</th><th>Thana Name</th><th>District Name</th><th>Pickup Merchant Phone</th><th>Package Option</th><th>Delivery Option</th><th>Product Breif</th><th>Package Price</th><th>Customer Name</th><th>Customer Address</th><th>Customer Thana Name</th><th>Customr District Name</th><th>Customer Phone</th><th>Cash Amount</th><th>Cash Time</th><th>Cash Comment</th><th>Return</th><th>Return Time</th><th>Return Reason</th><th>Return Remarks</th><th>Partial</th><th>Partial Time</th><th>Partial Remarks</th><th>On Hold</th><th>On Hold Reason</th><th>Return CP1</th><th>CP1 Time</th><th>Return CP1 By</th><th>Close</th><th>Accident</th><th>Destination</th><th>Charge</th><th>CoD</th><th>CoD Amount</th><th>Delivery Duration</th><th>Creation Date</th><th>Created By</th><th>Update Date</th><th>Updated By</th></tr></thead>';
                $output .='<tbody>';
                foreach ($result as $row){
                    $output .='<tr>';
                        $output .='<td>'.$row['orderid'].'</td>';
                        $output .='<td>'.$row['barcode'].'</td>';
                        $output .='<td>'.$row['dropPointCode'].'</td>';
                        $output .='<td>'.$row['pickEx'].'</td>';
                        $output .='<td>'.$row['dropEx'].'</td>';
                        $output .='<td>'.$row['ShtlBy'].'</td>';
                        $output .='<td>'.$row['merchantCode'].'</td>';
                        $output .='<td>'.$row['merchantName'].'</td>';
                        $output .='<td>'.$row['address'].'</td>';
                        $output .='<td>'.$row['contactNumber'].'</td>';
                        $output .='<td>'.$row['merThana'].'</td>';
                        $output .='<td>'.$row['merDist'].'</td>';
                        $output .='<td>'.$row['merOrder'].'</td>';
                        $output .='<td>'.$row['orderDate'].'</td>';
                        $output .='<td>'.$row['pickMerchantName'].'</td>';
                        $output .='<td>'.$row['pickMerchantAddress'].'</td>';
                        $output .='<td>'.$row['pickThana'].'</td>';
                        $output .='<td>'.$row['pickDist'].'</td>';
                        $output .='<td>'.$row['pickupMerchantPhone'].'</td>';
                        $output .='<td>'.$row['productSizeWeight'].'</td>';
                        $output .='<td>'.$row['deliveryOption'].'</td>';
                        $output .='<td>'.$row['productBrief'].'</td>';
                        $output .='<td>'.$row['packagePrice'].'</td>';
                        $output .='<td>'.$row['custname'].'</td>';
                        $output .='<td>'.$row['custaddress'].'</td>';
                        $output .='<td>'.$row['custthana'].'</td>';
                        $output .='<td>'.$row['custdist'].'</td>';
                        $output .='<td>'.$row['custphone'].'</td>';
                        $output .='<td>'.$row['CashAmt'].'</td>';
                        $output .='<td>'.$row['CashTime'].'</td>';
                        $output .='<td>'.$row['cashComment'].'</td>';
                        $output .='<td>'.$row['Return'].'</td>';
                        $output .='<td>'.$row['RetTime'].'</td>';
                        $output .='<td>'.$row['retReason'].'</td>';
                        $output .='<td>'.$row['retRem'].'</td>';
                        $output .='<td>'.$row['partial'].'</td>';
                        $output .='<td>'.$row['partialTime'].'</td>';
                        $output .='<td>'.$row['partialReason'].'</td>';
                        $output .='<td>'.$row['onHoldSchedule'].'</td>';
                        $output .='<td>'.$row['onHoldReason'].'</td>';
                        $output .='<td>'.$row['CP1'].'</td>';
                        $output .='<td>'.$row['CP1Time'].'</td>';
                        $output .='<td>'.$row['CP1By'].'</td>';
                        $output .='<td>'.$row['close'].'</td>';
                        $output .='<td>'.$row['accRem'].'</td>';
                        $output .='<td>'.$row['destination'].'</td>';
                        $output .='<td>'.$row['charge'].'</td>';
                        $output .='<td>'.$row['cod'].'</td>';
                        $output .='<td>'.$row['CoDAmount'].'</td>';
                        $output .='<td>'.$row['deliveryDuration'].'</td>';
                        $output .='<td>'.$row['CreationDate'].'</td>';
                        $output .='<td>'.$row['CreatedBy'].'</td>';
                        $output .='<td>'.$row['UpdateDate'].'</td>';
                        $output .='<td>'.$row['UpdatedBy'].'</td>';
                    $output .='</tr>';
                }
                $output .='</tbody></table>';                
            }
        }

        mysqli_close($conn);



        // filename for download
        $filename = "Order_information_" . date('Ymd') . ".xls";

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');
        echo $output;
        
    }
?>