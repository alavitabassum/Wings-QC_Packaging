<?php
    if (isset($_POST['chequeLog'])){
        include('session.php');
        if($user_check !=''){
            include('config.php');
            $startDate = date("Y-m-d", strtotime(trim($_POST['startDate'])));
            $endDate = date("Y-m-d", strtotime(trim($_POST['endDate'])));
            // Original PHP code by Chirp Internet: www.chirp.com.au
            // Please acknowledge use of this code by including this header.

            function cleanData(&$str)
            {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
            }

            // filename for download
            $filename = "Cheque_Print_Log_" . date('Ymd') . ".xls";

            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");

            $flag = false;
            $sql ="SELECT tbl_merchant_info.merchantName,  payTo as 'Pay To', paidAmt as 'Paid Amount', chequeNo as 'Cheque No', tbl_bank_info.bankName as 'Bank Name', payReason as 'Payment Reason', invNum as 'Invoice Number', chequeFor as 'Cheque For', chequeType as 'Cheque Type', DATE_FORMAT(tbl_cheque_print.creationDate,'%d-%b-%Y %h:%i %p') as 'Creation Date', tbl_cheque_print.createdBy as 'Created By' from tbl_cheque_print left join tbl_bank_info on tbl_cheque_print.bankID = tbl_bank_info.bankID left join tbl_merchant_info on substring(tbl_cheque_print.invNum, 12,8) = tbl_merchant_info.merchantCode where DATE_FORMAT(tbl_cheque_print.creationDate,'%Y-%m-%d') between '$startDate' and '$endDate'";

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
        } else {
            header("location: login");
        }
    }
?>