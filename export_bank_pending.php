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
        $filename = "Bank_Pending_" . date('Ymd') . ".xls";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;
             $sql ="SELECT orderid as 'Order ID', CashAmt as Collection FROM `tbl_order_details` WHERE (Cash = 'Y' and bank is null and close is null) or (partial = 'Y' and bank is null and close is null) having CAST(CashAmt as UNSIGNED) > 0 order by  dropPointCode, orderDate";

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