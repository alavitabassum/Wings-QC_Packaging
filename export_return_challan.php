<?php
    include('session.php');
    include('num_format.php');
    include('number_to_word.php');
    include('config.php');
    if ($user_check!=''){
        if(isset($_GET['xxCode'])){
            $retInv = trim($_GET['xxCode']);

            $challanDetailSQL = "SELECT tbl_order_details.merchantCode, v_merchant.merchantCode, v_merchant.merchantName, v_merchant.address, v_merchant.thanaName, v_merchant.districtName, v_merchant.companyPhone, orderid, merOrderRef, retInv, retInvDate, CONCAT(retReason,' ',retRem) as retReason, retChallanBy, IF(RetTime is not null, DATE_FORMAT(RetTime,'%d-%b-%Y'),DATE_FORMAT(partialTime,'%d-%b-%Y')) as retDate, v_employee.empName, v_employee.designation, v_employee.contactNumber FROM `tbl_order_details` left join (SELECT merchantCode, merchantName, address, tbl_thana_info.thanaName, tbl_district_info.districtName, companyPhone FROM tbl_merchant_info left join tbl_thana_info on tbl_merchant_info.thanaid = tbl_thana_info.thanaId left join tbl_district_info on tbl_merchant_info.districtid = tbl_district_info.districtId WHERE merchantCode = (select distinct merchantCode from tbl_order_details where retInv = '$retInv')) as v_merchant on tbl_order_details.merchantCode = v_merchant.merchantCode left join (SELECT tbl_user_info.userName, tbl_employee_info.empName, tbl_designation_info.Name as designation, tbl_employee_info.contactNumber, tbl_user_info.merchEmpCode FROM tbl_user_info left join tbl_employee_info on tbl_user_info.merchEmpCode = tbl_employee_info.empCode left join tbl_designation_info on tbl_employee_info.desigid = tbl_designation_info.desigid WHERE userName = (select distinct retChallanBy from tbl_order_details WHERE retInv = '$retInv') ) as v_employee on tbl_order_details.retChallanBy = v_employee.userName WHERE retInv = '$retInv'";
            $challanDetailResult = mysqli_query($conn, $challanDetailSQL);
            $challanHeaderRow = mysqli_fetch_array($challanDetailResult);  

            $output = '';

            $output .= '<table class="table">';
                $output .= '<thead><tr><th colspan=6 style="font-size: 25px">Return Orders Delivery Challan</th></tr></thead>';
                $output .= '<thead><tr><th style="text-align: left">Challan Date</th><th colspan=5 style="text-align: left">'.date('d-M-Y',strtotime($challanHeaderRow['retInvDate'])).'</th></tr></thead>';
                $output .= '<thead><tr><th style="text-align: left">Challan No</th><th colspan=5 style="text-align: left">'.$challanHeaderRow['retInv'].'</th></tr></thead>';
                $output .= '<thead><tr><th style="text-align: left">Merchant Name</th><th colspan=5 style="text-align: left">'.$challanHeaderRow['merchantName'].'</th></tr></thead>';
                $output .= '<thead><tr><th style="text-align: left">Address</th><th colspan=5 style="text-align: left">'.$challanHeaderRow['address'].','.$challanHeaderRow['thanaName'].','.$challanHeaderRow['districtName'].'</th></tr></thead>';
                $output .= '<thead><tr><th style="text-align: left">Phone</th><th colspan=5 style="text-align: left">'.$challanHeaderRow['companyPhone'].'</th></tr></thead>';

                $output .= '<thead><tr><th style="background-color: #59B899;color: #F4F5F8">SL.</th>
                <th style="background-color: #59B899;color: #F4F5F8">Order ID</th>
                <th style="background-color: #59B899;color: #F4F5F8">Merchant Order Ref.</th>
                <th style="background-color: #59B899;color: #F4F5F8">Return Date</th>
                <th style="background-color: #59B899;color: #F4F5F8">Return Reason</th>
                <th style="background-color: #59B899;color: #F4F5F8">Remarks</th></tr></thead>';
                $output .= '<tbody>';

                $lineCount = 1;

                foreach($challanDetailResult as $challanDetailRow){
                    $output .= '<tr>';
                    $output .= '<td>'.$lineCount.'</td>';
                    $output .= '<td>'.$challanDetailRow['orderid'].'</td>';
                    $output .= '<td>'.$challanDetailRow['merOrderRef'].'</td>';
                    $output .= '<td>'.$challanDetailRow['retDate'].'</td>';
                    $output .= '<td>'.$challanDetailRow['retReason'].'</td>';
                    $output .= '<td></td>';
                    $lineCount++;
                    $output .= '</tr>';
                }
                $lineCount = $lineCount - 1;
                
                $output .= '<tr><td colspan=6></td></tr>';
                $output .= '<tr><td colspan=2 style="background-color: #F0F0F0; font-size: 20px"><b>Total Return Orders</b></td><td colspan=4 style="text-align: left; background-color: #F0F0F0; font-size: 20px"><b>'.$lineCount.'</b></td></tr>';

                $output .= '</tbody>';
            $output .= '</table>';

            header('Content-Type: application/xls');
            header('Content-Disposition: attachment; filename='.$retInv.'.xls');
            echo $output;                                          
        }
    }        
?>

