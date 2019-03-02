<?php
    include('session.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and retOrder = 'Y'"));
    if ($userPrivCheckRow['retOrder'] != 'Y'){
        exit();
    }
    $merchantListSQL = "select distinct tbl_order_details.merchantCode, tbl_merchant_info.merchantName, retInv, DATE_FORMAT(retInvDate, '%d-%b-%Y') as retInvDate from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where cpRetStatus = 'S' order by retInvDate";
    $merchantListResult = mysqli_query($conn, $merchantListSQL);

    $output = '';
        $output .= '<table id="returnPendingTable" class="table table-hover">';
        $output .= '<thead><tr><th colspan=3 style="font-size: 30px"><b>Pending Return Invoice List</b></th></tr></thead>';
        $output .= '<thead><tr><th>Merchant Code</th><th>Mechant Name</th><th>Return Invoice No</th></tr></thead>';
        $output .='<tbody>';
        foreach ($merchantListResult as $merchantListRow){
            $output .='<tr>';
                $output .='<td>'.$merchantListRow['merchantCode'].'</td>';
                $output .='<td>'.$merchantListRow['merchantName'].'</td>';
                $output .='<td>'.$merchantListRow['retInv'].'</td>';
            $output .='</tr>';
        }
        $output .='</tbody></table>';
        mysqli_close($conn);



        // filename for download
        $filename = "Return_Accept_Pendings_" . date('Ymd') . ".xls";

        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');
        echo $output;
        exit;
?>



