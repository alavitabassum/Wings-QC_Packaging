<?php
    include('config.php');
    include('session.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and printInvoices = 'Y'"));
    if ($userPrivCheckRow['printInvoices'] != 'Y'){
        header("location: login");
    }    
    if (isset($_GET['invNum'])){
        $invNum = trim($_GET['invNum']);

        function cleanData(&$str)
        {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }

        // filename for download
        $filename = "Itemized_Order_" . date('Ymd') . ".xls";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;
        $sql ="select '$invNum' as 'Invoice Number', orderid as 'Order ID', merOrderRef as 'Merchant Order Ref.', productSizeWeight as 'Package Option', deliveryOption as 'Delivery Option', packagePrice as 'Product Price', CashAmt as 'Collection', tbl_district_info.districtName as 'District Name', charge as 'Charge', case when Cash = 'Y' then 'Success' when Ret = 'Y' then 'Return' when partial = 'Y' then 'partial' end as deliveryStatus, case when Cash = 'Y' then cashComment when Ret = 'Y' then CONCAT_WS('',retReason ,' ',retRem ) when partial = 'Y' then partialReason when orderType='smartPick' then smartPickComment end as comment from tbl_order_details left join tbl_district_info on tbl_order_details.customerDistrict = tbl_district_info.districtId where invNum = '$invNum' order by orderDate, deliveryStatus desc";
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