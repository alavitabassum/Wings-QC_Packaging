<?php
    if (isset($_GET['xxCode'])){
        include('config.php');
        $beftnID = trim($_GET['xxCode']);
        // Original PHP code by Chirp Internet: www.chirp.com.au
        // Please acknowledge use of this code by including this header.

        function cleanData(&$str)
        {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }

        // filename for download
        $filename = "BEFTN_List_" . date('Ymd') . ".xls";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;
        $sql = "select 	ReceiverID as '	Receiver ID', ReceiverName as 'Receiver Name', ReceiverAccountNumber as 'Receiver Account Number', tbl_merchant_info.bankName, tbl_merchant_info.branch, CrDr, ReceiverShortCode as 'Receiver Short Code', Amount, TransCode as 'Trans Code', SECCode as 'SEC Code' from v_beftnExport left join tbl_merchant_info on v_beftnExport.receiverID = tbl_merchant_info.merchantCode where beftnID = $beftnID";
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