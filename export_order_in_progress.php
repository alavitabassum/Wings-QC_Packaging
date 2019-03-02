<?php
    if (isset($_GET['invNum'])){
        include('config.php');
        $invNum = trim($_GET['invNum']);


        include('session.php');
        include('config.php');
        // Original PHP code by Chirp Internet: www.chirp.com.au
        // Please acknowledge use of this code by including this header.
        if($user_check == ''){
            header("location: main");
        }

        function cleanData(&$str)
        {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }

        // filename for download
        $filename = "Orders_InProgress_" . date('Ymd') . ".xls";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;
        $sql ="select invNum as 'Invoice Number', orderid as 'Order ID' , merOrderRef as 'Merchant Order Ref.', productSizeWeight as 'Package Option', deliveryOption as 'Delivery Option', packagePrice as 'Price', customerDistrict, tbl_district_info.districtName as 'District Name' FROM tbl_order_progress left join tbl_district_info on tbl_order_progress.customerDistrict = tbl_district_info.districtId where invNum = '$invNum' order by orderDate";
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