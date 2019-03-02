<?php
    include('header.php');
    include('num_format.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and cashPending = 'Y'"));
    if ($userPrivCheckRow['cashPending'] != 'Y'){
        exit();
    }


    $cashPendingResult = mysqli_query($conn, "select pointCode, pointName, bankPendingCount, bankAmt, pendingCount, CashAmt from tbl_point_info left join (select dropPointCode, count(1) as bankPendingCount, sum(CashAmt) as bankAmt from tbl_order_details where (Cash = 'Y' or partial = 'Y') and DropDP2 = 'Y' and bank is null and close is null group by dropPointCode) as bankPending on tbl_point_info.pointCode = bankPending.dropPointCode left join (select dropPointCode, count(1) as pendingCount, sum(CashAmt) as CashAmt from tbl_order_details where (Cash = 'Y' or partial = 'Y') and DropDP2 is null and bank is null and close is null group by dropPointCode) as dp2Pending on tbl_point_info.pointCode = dp2Pending.dropPointCode order by tbl_point_info.pointCode");
    $bankOrderCount = 0;
    $bankAmount = 0;
    $dp2OrderCount = 0;
    $dp2Amount = 0;
    function encryptor($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'shLitu';
        $secret_iv = '12Litu34';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
    	    //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
?>

        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Pending Cash Collection Summary</p>
            <div class="col-sm-12">
                <div class="row" style="margin-top: 15px">
                    <div class="col-sm-12">
                        <p id="alrt" style="color: blue; text-align: center"></p>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px">
                    <?php
                        foreach($cashPendingResult as $cashPendingRow){
                            $bankOrderCount = $bankOrderCount + $cashPendingRow['bankPendingCount']; 
                            $bankAmount = $bankAmount + $cashPendingRow['bankAmt'];                            
                            $dp2OrderCount = $dp2OrderCount + $cashPendingRow['pendingCount']; 
                            $dp2Amount = $dp2Amount + $cashPendingRow['CashAmt'];                            
                        }
                    ?>                    
                    <div class="col-sm-3">
                        <label><b>Bank Pending Count :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo num_to_format(round($bankOrderCount, 0));?></label>
                    </div>
                    <div class="col-sm-3">
                        <label><b>Bank Pending Amount :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo num_to_format(round($bankAmount, 0));?></label>
                    </div>
                    <div class="col-sm-3">
                        <label><b>DP2 Pending Count :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo num_to_format(round($dp2OrderCount, 0));?></label>
                    </div>
                    <div class="col-sm-3">
                        <label><b>DP2 Pending Amount :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo num_to_format(round($dp2Amount, 0));?></label>
                    </div>
                </div>
                <?php
                    $orderCount = 0;
                    $cashAmount = 0;                    
                ?>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" id="cashPendingTable">
                            <thead><tr><th>Point</th><th style="text-align: right">Bank Pending Count</th><th style="text-align: right">Bank Pending Amount</th><th style="text-align: right">DP2 Pending Count</th><th style="text-align: right">DP2 Pending Amount</th></tr></thead>
                            <tbody>
                                <?php 
                                    foreach($cashPendingResult as $cashPendingRow){
                                        if($cashPendingRow['bankPendingCount'] > 0 or $cashPendingRow['pendingCount'] > 0){
                                        $pointCode = urlencode(encryptor('encrypt', $cashPendingRow['pointCode']));
                                ?>
                                <tr>
                                    <td><a href="collection-pending-detail?xxCode=<?php echo $pointCode;?>" target="_blank"><?php echo $cashPendingRow['pointCode'].'-'.$cashPendingRow['pointName'];?></a></td>
                                    <td style="text-align: right"><?php echo $cashPendingRow['bankPendingCount'];?></td>
                                    <td style="text-align: right"><?php echo num_to_format(round($cashPendingRow['bankAmt'], 0));?></td>
                                    <td style="text-align: right"><?php echo $cashPendingRow['pendingCount'];?></td>
                                    <td style="text-align: right"><?php echo num_to_format(round($cashPendingRow['CashAmt'], 0));?></td>
                                </tr>
                                <?php
                                        } 
                                    }
                                ?>
                                <tr style="font-weight: 800">
                                    <td>Total</td><td style="text-align: right"><?php echo num_to_format(round($bankOrderCount,0));?></td><td style="text-align: right"><?php echo num_to_format(round($bankAmount,0));?></td><td style="text-align: right"><?php echo num_to_format(round($dp2OrderCount,0));?></td><td style="text-align: right"><?php echo num_to_format(round($dp2Amount,0));?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('select').select2();
        </script>
    </body>
</html>
