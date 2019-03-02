<?php
    include('session.php');
    if ($user_check!=''){
        include('config.php');
        // Original PHP code by Chirp Internet: www.chirp.com.au
        // Please acknowledge use of this code by including this header.

        function cleanData(&$str)
        {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }

        // filename for download
        $filename = "Missed_SLA_" . date('Ymd') . ".xls";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;
             $sql ="SELECT tbl_order_details.orderid AS 'Order ID', tbl_employee_info.empName AS 'Pickup Executive', drop_employee.empName as 'Drop Executive', tbl_order_details.merchantCode as 'Merchant Code',     tbl_merchant_info.merchantName as 'Merchant Name', tbl_merchant_info.address as 'Merchant Address', tbl_merchant_info.contactNumber as 'Merchant Phone', tbl_thana_info.thanaName as 'Merchant Thana Name', tbl_district_info.districtName as 'Merchant District Name', merOrderRef as 'Merchant Order Reference', orderDate as 'Order Date', pickMerchantName as 'Pickup Merchant Name', pickMerchantAddress as 'Pickup Merhcant Address', pick_thana.thanaName as 'Thana Name', pick_district.districtName as 'District Name', pickupMerchantPhone as 'Pickup Merchant Phone', productSizeWeight as 'Package Option', deliveryOption as 'Delivery Option', productBrief as 'Product Breif' , packagePrice as 'Package Price', custname as 'Customer Name', custaddress as 'Customer Address', cust_thana.thanaName as 'Customer Thana Name' , cust_district.districtName as 'Customr District Name', custphone as 'Customer Phone', CashAmt as 'Cash Amount', DATE_FORMAT(CashTime,'%d-%b-%y %H:%i') as 'Cash Time', cashComment as 'Cash Comment', tbl_order_details.Ret as 'Return', DATE_FORMAT(RetTime,'%d-%b-%y %H:%i') as 'Return Time', retReason as 'Return Reason', retRem as 'Return Remarks', partial, onHoldSchedule as 'On Hold' , tbl_order_details.close, accRem as 'Accident', tbl_order_details.destination, tbl_order_details.charge, tbl_order_details.cod,  DATE_FORMAT(tbl_order_details.creation_date,'%d-%b-%Y %h:%i %p') as 'Creation Date', tbl_order_details.created_by as 'Created By', DATE_FORMAT(tbl_order_details.update_date,'%d-%b-%Y %h:%i%p') as 'Update Date', tbl_order_details.updated_by as 'Updated By'  
FROM ((((((tbl_order_details
LEFT JOIN tbl_employee_info ON tbl_order_details.pickPointEmp = tbl_employee_info.empCode) left join tbl_employee_info as drop_employee on tbl_order_details.dropPointEmp = drop_employee.empCode) LEFT JOIN ((tbl_merchant_info LEFT JOIN tbl_thana_info ON tbl_merchant_info.thanaid = tbl_thana_info.thanaId) LEFT JOIN tbl_district_info ON tbl_merchant_info.districtid = tbl_district_info.districtId) ON tbl_order_details.merchantCode = tbl_merchant_info.merchantCode) left join tbl_thana_info as pick_thana on tbl_order_details.thanaId = pick_thana.thanaId) left join tbl_district_info as pick_district on tbl_order_details.districtId = pick_district.districtId) left join tbl_thana_info as cust_thana on tbl_order_details.customerThana = cust_thana.thanaId) left join tbl_district_info as cust_district on tbl_order_details.customerDistrict = cust_district.districtId WHERE DATE(orderDate) < SUBDATE(CURDATE(), INTERVAL 1 DAY) and close is null and Cash is null and Ret is null and partial is null and destination = 'local' order by dropPointCode";

        $result = mysqli_query($conn, $sql);
        foreach ($result as $row){
      
  
        //while(false !== ($row = pg_fetch_assoc($result))) {
        if(!$flag) {
            // display field/column names as first row
            echo implode("\t", array_keys($row)) . "\r\n";
            $flag = true;
        }
        array_walk($row, 'cleanData');
        echo implode("\t", array_values($row)) . "\r\n";
        //}
        }
        mysqli_close($conn);
        exit;
        
    }
?>