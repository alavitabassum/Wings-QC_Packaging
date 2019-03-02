<?php
    include('header.php');
    include('num_format.php');
    $merchantsql = "SELECT distinct tbl_order_details.merchantCode, tbl_merchant_info.merchantName FROM tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode WHERE invNum IS NULL and close ='Y' and orderType in ('Merchant', 'Other_Merchant')";
    $merchantresult = mysqli_query($conn,$merchantsql);
    $merchantrow = mysqli_fetch_array($merchantresult);
    $smartPickCnt = 0;
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and invoices = 'Y'"));
    if ($userPrivCheckRow['invoices'] != 'Y'){
        exit();
    }
?>

        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Create New Invoice</p>
                <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                    <div id="invContainer" class="container-fluid">
                        <div class="row" style="margin-top: 15px">
                            <div class="col-sm-4">
                                <div id="generalOrders" style="border-style: solid; border-color: #00AEEF; border-radius: 5px; margin: 0px; padding-left: 20px; padding-right: 2px; max-width: 500px; height: auto">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p style="color: #16469E; font: 20px 'paperfly roman'">s</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p id="alrtGeneral" style="color: red; font: 15px 'paperfly roman'">&nbsp;</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p>Merchant</p>
                                            <select id ="merchantCode" name="merchantCode" data-placeholder="Select Merchant............." style="width: 100%; height: 28px">
                                                <?php if ($user_type == 'Merchant'){
                                                    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                                    $merchantrow = mysqli_fetch_array($merchantresult);
                                                    echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";     
                                                } else {?>
                                                    <option></option>
                                                    <?php
                                        
                                                        //foreach ($merchantresult as $merchantrow){
                                                        //    $generalCnt = $generalCnt + 1;
                                                        //    echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";
                                                        //}
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-sm-6">
                                            <input type="submit" class="btn btn-primary" name="genOrders" value="Generate Invoice">
                                        </div>
                                        <div class="col-sm-6" style="text-align: right">
                                            <input type="button" class="btn btn-info" name="showOrders" value="Preview Invoice" onclick="prevInv()">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-sm-12">
                                            <p id="pendingGeneral" style="color: red; font: 15px 'paperfly roman'">Pending Invoices :<span id="invCount"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="monthlyInvoices" style="border-style: solid; border-color: #00AEEF; border-radius: 5px; margin: 0px; padding-left: 20px; padding-right: 2px; max-width: 500px; height: auto">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p style="color: #16469E; font: 20px 'paperfly roman'">Monthly Invoices</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p id="alrtMonth" style="color: red; font: 15px 'paperfly roman'">&nbsp;</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <p>Merchant</p>
                                            <select id ="monthlyMerchant" name="monthlyMerchant" data-placeholder="Select Merchant............." style="width: 100%; height: 28px">
                                                <?php if ($user_type == 'Merchant'){
                                                    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                                    $merchantrow = mysqli_fetch_array($merchantresult);
                                                    echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";     
                                                } else {?>
                                                    <option></option>
                                                    <?php
                                                        $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where monthlyInvoice = 'Y'";
                                                        $merchantresult = mysqli_query($conn,$merchantsql);                                        
                                                        foreach ($merchantresult as $merchantrow){
                                                            $monthlyCnt = $monthlyCnt + 1;
                                                            echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <p>Month</p>
                                            <select id="invMonth" name="invMonth" style="width: 100%">
                                                <option value="01" <?php if(date('m') == '02'){echo 'selected';}?>>January</option>
                                                <option value="02" <?php if(date('m') == '03'){echo 'selected';}?>>February</option>
                                                <option value="03" <?php if(date('m') == '04'){echo 'selected';}?>>March</option>
                                                <option value="04" <?php if(date('m') == '05'){echo 'selected';}?>>April</option>
                                                <option value="05" <?php if(date('m') == '06'){echo 'selected';}?>>May</option>
                                                <option value="06" <?php if(date('m') == '07'){echo 'selected';}?>>June</option>
                                                <option value="07" <?php if(date('m') == '08'){echo 'selected';}?>>July</option>
                                                <option value="08" <?php if(date('m') == '09'){echo 'selected';}?>>August</option>
                                                <option value="09" <?php if(date('m') == '10'){echo 'selected';}?>>September</option>
                                                <option value="10" <?php if(date('m') == '11'){echo 'selected';}?>>October</option>
                                                <option value="11" <?php if(date('m') == '12'){echo 'selected';}?>>November</option>
                                                <option value="12" <?php if(date('m') == '01'){echo 'selected';}?>>December</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <p>Year</p>
                                            <?php $yearResult = mysqli_query($conn, "SELECT YEAR(orderDate) AS orderDate FROM `tbl_order_details` GROUP BY YEAR(orderDate) DESC");?>
                                            <select id="invYear" name="invYear" style="width: 100%">
                                                <?php foreach($yearResult as $yearRow){?>
                                                <option value="<?php echo $yearRow['orderDate'];?>"><?php echo $yearRow['orderDate'];?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-sm-6">
                                            <input type="submit" class="btn btn-primary" name="monthlyOrders" value="Generate Invoice">
                                        </div>
                                        <div class="col-sm-6" style="text-align: right">
                                            <input type="button" class="btn btn-info" name="monthlyShowOrders" value="Preview Invoice" onclick="monthlyPrevInv()">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-sm-12">
                                            <p id="pendingGeneral" style="color: red; font: 15px 'paperfly roman'">Entitled Merchant Count : <?php echo $monthlyCnt;?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div id="smartPickOrders" style="border-style: solid; border-color: #00AEEF; border-radius: 5px; margin: 0px; padding-left: 20px; padding-right: 2px; max-width: 500px; height: auto">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <p style="color: #16469E; font: 20px 'paperfly roman'">SmartPick Invoices</p>
                                        </div>
                                        <div class="col-sm-5">
                                            <p id="alrtSmart" style="color: red; font: 15px 'paperfly roman'">&nbsp;</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p>Merchant</p>
                                            <select id ="smartPickMerchant" name="smartPickMerchant" data-placeholder="Select Merchant............." style="width: 100%; height: 28px">
                                                <?php if ($user_type == 'Merchant'){
                                                    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                                    $merchantrow = mysqli_fetch_array($merchantresult);
                                                    echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";     
                                                } else {?>
                                                    <option></option>
                                                    <?php
                                                        $merchantsql = "SELECT distinct tbl_order_details.merchantCode, tbl_merchant_info.merchantName FROM tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode WHERE invNum IS NULL and close ='Y' and orderType ='smartPick'";
                                                        $merchantresult = mysqli_query($conn,$merchantsql);                                        
                                                        foreach ($merchantresult as $merchantrow){
                                                            $smartPickCnt = $smartPickCnt + 1;
                                                            echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-sm-6">
                                            <input type="submit" class="btn btn-primary" name="smartPickgenOrders" value="Generate Invoice">
                                        </div>
                                        <div class="col-sm-6" style="text-align: right">
                                            <input type="button" class="btn btn-info" name="smartPickshowOrders" value="Preview Invoice" onclick="smartPickprevInv()">
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-sm-12">
                                            <p id="pendingGeneral" style="color: red; font: 15px 'paperfly roman'">Pending Invoices : <?php echo $smartPickCnt;?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div style="width:  1200px; height: 30px; clear: both">
                    </div>
                    <div class="row" style="margin: 0px">
                        <div class="col-sm-12">
                            <p id="alrt"></p>
                        </div>
                    </div>
                    <div class="row" style="margin: 0px">
                        <div class="col-sm-2">
                            <label style="font: 15px 'paperfly roman'">Discount/Adjustment</label>
                            <input type="text" id="disc" name="disc" style="height: 25px" onkeyup="isNumberKey(this)">
                        </div>
                    </div>    
                    <div class="row" style="margin: 0px">
                        <div class="col-sm-4">
                        <label style="font: 15px 'paperfly roman'">Invoice Message (maximum 6 lines)</label>
                        <textarea class="form-control" rows="6" id="comment" name="comment">
Any dispute must be notified in written within 15 days from the date of this invoice. 
This is an electronic statement, does not require any signature </textarea>
                        </div>
                    </div>    
                </form>
                <input type="hidden" id="invMerchantCode" value =""/>
                <input type="hidden" id="invNumber" value =""/>
            <div style="width: 700px; float: left">
        </div>
    </body>
            <?php
                if (isset($_POST['genOrders'])){
                    $merchantCode = trim($_POST['merchantCode']);
                    if ($merchantCode ==''){
                        echo "<script> document.getElementById('alrtGeneral').innerHTML = 'Please select a merchant' ;</script>";
                    } else {
                    $discAdj = trim($_POST['disc']);
                    $invComment = $_POST['comment'];
                    $invComment = mysqli_real_escape_string($conn, $invComment);
                    $invDate = date('d-m-Y', strtotime('+6 hours'));

                    //invoice table population

                    //select max seq for merchant 
                    $maxSeqSQL = "select max(invSeq) as maxSeq from tbl_invoice where merchantCode = '$merchantCode' and invType='general'";
                    $maxSeqResult = mysqli_query($conn, $maxSeqSQL);
                    $maxSeqRow = mysqli_fetch_array($maxSeqResult);
                    $maxSeq = $maxSeqRow['maxSeq'] + 1;

                    //invoice number generation
                    $invNum = $invDate."-".$merchantCode."-".$maxSeq;

                    echo '<script>$("#invMerchantCode").val("'.$merchantCode.'"); $("#invNumber").val("'.$invNum.'");</script>';

                    //invoice date for in MySQL format 
                    $invDateINS = date('Y-m-d', strtotime('+6 hours'));

                    //invoice period identification
                    $invPeriodSQL = "SELECT statementDate, returnCharge from tbl_merchant_info where merchantCode = '$merchantCode'";
                    $invPeriodResult = mysqli_query($conn, $invPeriodSQL);
                    $invPeriodRow = mysqli_fetch_array($invPeriodResult);
                    $startDate = $invPeriodRow['statementDate'];

                    //Statement End Date
                    $invEnddate = new DateTime(date('Y-m-d', strtotime('+6 hours')));
                    $invEnddate->sub(new DateInterval('P1D'));
                    $invEnddate = date_format($invEnddate, 'Y-m-d');

                    //Statement Date 
                    $statementDate = date('Y-m-d', strtotime('+6 hours'));

                    //Return Charge indicator
                    $returnCharge = $invPeriodRow['returnCharge'];

                    //Return Charge update if N marked

                    if($returnCharge == 'N'){
                        $returnChargeUpdate = mysqli_query($conn, "update tbl_order_details set charge = 0 where merchantCode = '$merchantCode' and Ret = 'Y' and close = 'Y' and invNum IS NULL");
                    }

                    //insert into invoice table 
                    $invInsertSQL ="Insert into tbl_invoice (inv_date, merchantCode, invSeq, invType, invNum, minDate, maxDate, Discount, Message, inv_user) values ('$invDateINS', '$merchantCode', '$maxSeq', 'general', '$invNum', '$startDate', '$invEnddate', '$discAdj', '$invComment', '$user_code')";
                    if (!mysqli_query($conn,$invInsertSQL))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger' style='clear: both'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                                exit();
                            } else {
                                //insert into invoice details
                                $invDetailInsSQL = "insert into tbl_invoice_details (invNum, destination, productSizeWeight, deliveryOption, TotalOrder, cash, Ret, partial, deliveryCharge, cashCollection, CashCoD) select '".$invNum."', destination, productSizeWeight, deliveryOption, count(orderid) as TotalOrder, count(cash) as cash, count(Ret) as 'Return', count(partial) as partial, sum(charge) as deliveryCharge, sum(CashAmt) as cashCollection, IF(sum(CashAmt)=0, 0, sum(CashAmt)-sum(charge)) * (cod/100) as CashCoD from tbl_order_details where merchantCode = '$merchantCode' and close = 'Y' and invNum IS NULL and (DATE_FORMAT(closeTime,'%Y-%m-%d') between '$startDate' and '$invEnddate') and orderType in ('Merchant', 'Other_Merchant') group by destination, productSizeWeight, deliveryOption";
                                if (!mysqli_query($conn,$invDetailInsSQL))
                                        {
                                            $error ="Insert Error : " . mysqli_error($conn);
                                            echo "<div class='alert alert-danger' style='clear: both'>";
                                                echo "<strong>Error!</strong>".$error; 
                                            echo "</div>";
                                        }
                            
                                //update tbl_order_details
                                $invUpdateSQL = "update tbl_order_details set invNum = '$invNum' where merchantCode ='$merchantCode' and close = 'Y' and invNum IS NULL and (DATE_FORMAT(closeTime,'%Y-%m-%d') between '$startDate' and '$invEnddate') and orderType in ('Merchant', 'Other_Merchant')";         
                                if (!mysqli_query($conn,$invUpdateSQL))
                                        {
                                            $error ="Update Error : " . mysqli_error($conn);
                                            echo "<div class='alert alert-danger' style='clear: both'>";
                                                echo "<strong>Error!</strong>".$error; 
                                            echo "</div>";
                                        }
                                $invStDateSQL = "update tbl_merchant_info set statementDate = '$statementDate' where merchantCode ='$merchantCode'";         
                                if (!mysqli_query($conn,$invStDateSQL))
                                        {
                                            $error ="Update Error : " . mysqli_error($conn);
                                            echo "<div class='alert alert-danger' style='clear: both'>";
                                                echo "<strong>Error!</strong>".$error; 
                                            echo "</div>";
                                        }
                                //insert into invoice details
                                //$orderProgInsSQL = "insert into tbl_order_progress (invNum, destination, productSizeWeight, deliveryOption, TotalOrder, cash, Ret, partial, deliveryCharge, cashCollection, CashCoD) select '".$invNum."', destination, productSizeWeight, deliveryOption, count(orderid) as TotalOrder, count(cash) as cash, count(Ret) as 'Return', count(partial) as partial, sum(charge) as deliveryCharge, sum(CashAmt) as cashCollection, sum(((CashAmt-charge) * cod)/100) as CashCoD from tbl_order_details where merchantCode = '$merchantCode' and close IS NULL and invNum IS NULL group by destination, productSizeWeight, deliveryOption";
                                $orderProgInsSQL = "Insert Into tbl_order_progress (invNum, orderid, orderDate, merOrderRef, merchantCode, productSizeWeight, deliveryOption, packagePrice, customerDistrict) select '".$invNum."', orderid, orderDate, merOrderRef, merchantCode, productSizeWeight, deliveryOption, packagePrice, customerDistrict from tbl_order_details where merchantCode = '$merchantCode' and close IS NULL and invNum IS NULL and orderDate <'$statementDate' and orderType in ('Merchant', 'Other_Merchant')";
                                if (!mysqli_query($conn,$orderProgInsSQL))
                                        {
                                            $error ="Insert Error : " . mysqli_error($conn);
                                            echo "<div class='alert alert-danger' style='clear: both'>";
                                                echo "<strong>Error!</strong>".$error; 
                                            echo "</div>";
                                        }

                                echo "<div class='alert alert-success' style='clear: both'>";
    //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                    echo "Invoice ".$invNum." generated successfully ";
                                    echo "<script>window.open('old_invoice.php?xxCode=".$invNum."', '_blank');</script>";
                                    //echo "<script>window.open('print_cheque.php?xxCode=".$invNum."', '_blank');</script>";
                                echo "</div>";
                                            
                            }
                        //echo "<meta http-equiv='refresh' content='0'>";
                    }
                }

                if(isset($_POST['monthlyOrders'])){
                    $merchantCode = trim($_POST['monthlyMerchant']);
                    if ($merchantCode ==''){
                        echo "<script> document.getElementById('alrtMonth').innerHTML = 'Select merchant' ;</script>";
                    } else {
                    $discAdj = trim($_POST['disc']);
                    $invComment = $_POST['comment'];
                    $invComment = mysqli_real_escape_string($conn, $invComment);
                    $invMonth = $_POST['invMonth'];
                    $invYear = $_POST['invYear'];

                    $invPeriod = $invYear.'-'.$invMonth.'-01'; 

                    
                    $invStartDate = $invYear.'-'.$invMonth.'-02';
                    $invMonthEnd = date("Y-m-t", strtotime($invStartDate));
                    $invEndDate = date('Y-m-d',strtotime($invMonthEnd . "+1 days"));

                    //Vat applicable or not
                    $merchantVatRow = mysqli_fetch_array(mysqli_query($conn, "select vat from tbl_merchant_info where merchantCode ='$merchantCode'"));
                    $vat = $merchantVatRow['vat'];


                    //invoice table population

                    //select max seq for merchant 
                    $maxSeqSQL = "select max(monthInvSeq) as maxSeq from tbl_invoice where merchantCode = '$merchantCode'";
                    $maxSeqResult = mysqli_query($conn, $maxSeqSQL);
                    $maxSeqRow = mysqli_fetch_array($maxSeqResult);
                    $maxSeq = $maxSeqRow['maxSeq'] + 1;

                    $statmentDate = date('Y-m-d', strtotime('+6 hours'));


                    //invoice number generation
                    $invNum = 'MCH'.$invMonth.$invYear."-".$merchantCode."-".$maxSeq;


                    //echo '<script>$("#invMerchantCode").val("'.$merchantCode.'"); $("#invNumber").val("'.$invNum.'");</script>';

                    $invExistanceCheckResult = mysqli_query($conn, "select * from tbl_invoice where invMonth = '$invPeriod' and merchantCode = '$merchantCode'");

                    $numForInv =  mysqli_query($conn, "select invNum from tbl_invoice where merchantCode = '$merchantCode' and inv_date between '$invStartDate' and '$invEndDate' ");
                    $invNumbers = '';
                    $rowNum = 1;
                    foreach($numForInv as $invs){
                        if($rowNum != mysqli_num_rows($numForInv)){
                            $invNumbers = $invNumbers."'".$invs['invNum']."',";    
                        } else {
                            $invNumbers = $invNumbers."'".$invs['invNum']."'";
                        }
                      $rowNum++;  
                    }
                    if(mysqli_num_rows($invExistanceCheckResult) > 0){
                       echo "<script> document.getElementById('alrtMonth').innerHTML = 'Invoice already generated' ;</script>"; 
                    } else {
                        $updateInvoiceSQL = "update tbl_invoice set monthInvSeq = '$maxSeq', monthInvNum = '$invNum', monthDiscount = '$discAdj', vat = '$vat', monthMessage = '$invComment', invMonth ='$invPeriod', statementDate = '$statmentDate', monthInvUser = '$user_code' where invNum in ($invNumbers)";
                        if(!mysqli_query($conn, $updateInvoiceSQL)){
                            echo "<script> document.getElementById('alrtMonth').innerHTML = 'Error '".mysqli_error($conn)." ;</script>"; 
                        } else {
                            echo '<script>window.open("Invoice-Monthly?xxCode='.$invNum.'", "_blank");</script>';
                        }
                    }

                    }                    
                }

                if (isset($_POST['smartPickgenOrders'])){
                    $merchantCode = trim($_POST['smartPickMerchant']);
                    if ($merchantCode ==''){
                        echo "<script> document.getElementById('alrtSmart').innerHTML = 'Please select a merchant' ;</script>";
                        //echo "<script> alert('Please select a merchant'); </script>";
                    } else {
                    $discAdj = trim($_POST['disc']);
                    $invComment = $_POST['comment'];
                    $invComment = mysqli_real_escape_string($conn, $invComment);
                    $invDate = date('d-m-Y');

                    //invoice table population

                    //select max seq for merchant 
                    $maxSeqSQL = "select max(invSeq) as maxSeq from tbl_invoice where merchantCode = '$merchantCode' and invType='smartPick'";
                    $maxSeqResult = mysqli_query($conn, $maxSeqSQL);
                    $maxSeqRow = mysqli_fetch_array($maxSeqResult);
                    $maxSeq = $maxSeqRow['maxSeq'] + 1;

                    //invoice number generation
                    $invNum = "S-".$invDate."-".$merchantCode."-".$maxSeq;

                    //invoice date for in MySQL format 
                    $invDateINS = date('Y-m-d');

                    //invoice period identification
                    $invPeriodSQL = "SELECT smartStatementDate from tbl_merchant_info where merchantCode = '$merchantCode'";
                    $invPeriodResult = mysqli_query($conn, $invPeriodSQL);
                    $invPeriodRow = mysqli_fetch_array($invPeriodResult);
                    $startDate = $invPeriodRow['smartStatementDate'];

                    //Statement End Date
                    $invEnddate = new DateTime(date('Y-m-d', strtotime('+6 hour')));
                    $invEnddate->sub(new DateInterval('P1D'));
                    $invEnddate = date_format($invEnddate, 'Y-m-d');

                    //Statement Date 
                    $statementDate = date('Y-m-d', strtotime('+6 hour'));

                    //insert into invoice table 
                    $invInsertSQL ="Insert into tbl_invoice (inv_date, merchantCode, invSeq, invType, invNum, minDate, maxDate, Discount, Message, inv_user) values ('$invDateINS', '$merchantCode', '$maxSeq', 'smartPick', '$invNum', '$startDate', '$invEnddate', '$discAdj', '$invComment', '$user_code')";
                    if (!mysqli_query($conn,$invInsertSQL))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger' style='clear: both'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                                exit();
                            } else {
                                //insert into invoice details
                                $invDetailInsSQL = "insert into tbl_invoice_details (invNum, destination, productSizeWeight, deliveryOption, TotalOrder, cash, Ret, partial, deliveryCharge, cashCollection, CashCoD) select '".$invNum."', destination, productSizeWeight, deliveryOption, count(orderid) as TotalOrder, count(cash) as cash, count(Ret) as 'Return', count(partial) as partial, sum(charge) as deliveryCharge, sum(CashAmt) as cashCollection, IF(sum(CashAmt)=0, 0, sum(CashAmt)-sum(charge)) * (cod/100) as CashCoD from tbl_order_details where merchantCode = '$merchantCode' and close = 'Y' and invNum IS NULL and (DATE_FORMAT(closeTime,'%Y-%m-%d') between '$startDate' and '$invEnddate') and orderType ='smartPick' group by destination, productSizeWeight, deliveryOption";
                                if (!mysqli_query($conn,$invDetailInsSQL))
                                        {
                                            $error ="Insert Error : " . mysqli_error($conn);
                                            echo "<div class='alert alert-danger' style='clear: both'>";
                                                echo "<strong>Error!</strong>".$error; 
                                            echo "</div>";
                                        }
                            
                                //update tbl_order_details
                                $invUpdateSQL = "update tbl_order_details set invNum = '$invNum' where merchantCode ='$merchantCode' and close = 'Y' and invNum IS NULL and (DATE_FORMAT(closeTime,'%Y-%m-%d') between '$startDate' and '$invEnddate') and orderType ='smartPick'";         
                                if (!mysqli_query($conn,$invUpdateSQL))
                                        {
                                            $error ="Update Error : " . mysqli_error($conn);
                                            echo "<div class='alert alert-danger' style='clear: both'>";
                                                echo "<strong>Error!</strong>".$error; 
                                            echo "</div>";
                                        }
                                $invStDateSQL = "update tbl_merchant_info set smartStatementDate = '$statementDate' where merchantCode ='$merchantCode'";         
                                if (!mysqli_query($conn,$invStDateSQL))
                                        {
                                            $error ="Update Error : " . mysqli_error($conn);
                                            echo "<div class='alert alert-danger' style='clear: both'>";
                                                echo "<strong>Error!</strong>".$error; 
                                            echo "</div>";
                                        }
                                //insert into invoice details
                                //$orderProgInsSQL = "insert into tbl_order_progress (invNum, destination, productSizeWeight, deliveryOption, TotalOrder, cash, Ret, partial, deliveryCharge, cashCollection, CashCoD) select '".$invNum."', destination, productSizeWeight, deliveryOption, count(orderid) as TotalOrder, count(cash) as cash, count(Ret) as 'Return', count(partial) as partial, sum(charge) as deliveryCharge, sum(CashAmt) as cashCollection, sum(((CashAmt-charge) * cod)/100) as CashCoD from tbl_order_details where merchantCode = '$merchantCode' and close IS NULL and invNum IS NULL group by destination, productSizeWeight, deliveryOption";
                                $orderProgInsSQL = "Insert Into tbl_order_progress (invNum, orderid, orderDate, merOrderRef, merchantCode, productSizeWeight, deliveryOption, packagePrice, customerDistrict) select '".$invNum."', orderid, orderDate, merOrderRef, merchantCode, productSizeWeight, deliveryOption, packagePrice, customerDistrict from tbl_order_details where merchantCode = '$merchantCode' and close IS NULL and invNum IS NULL and orderDate <'$statementDate' and orderType ='smartPick'";
                                if (!mysqli_query($conn,$orderProgInsSQL))
                                        {
                                            $error ="Insert Error : " . mysqli_error($conn);
                                            echo "<div class='alert alert-danger' style='clear: both'>";
                                                echo "<strong>Error!</strong>".$error; 
                                            echo "</div>";
                                        }

                                echo "<div class='alert alert-success' style='clear: both'>";
    //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                    echo "Invoice ".$invNum." generated successfully ";
                                    echo "<script>window.open('old_invoice_smart.php?xxCode=".$invNum."', '_blank'); </script>";
                                    //echo "<script>window.open('print_cheque.php?xxCode=".$invNum."', '_blank');</script>";
                                echo "</div>";
                                            
                            }
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                }
            ?>
    <script type="text/javascript">
        $(window).load(function ()
        {
            $('#merchantCode').select2();
            $('#smartPickMerchant').select2();
            $('#monthlyMerchant').select2();
            $('#invMonth').select2();
            $('#invYear').select2();
            merchantList();
            invoiceCount();
            sendMessageMerchant();
        });
        function isNumberKey(inpt)
        {
            var regex = /[^0-9.-]/g;
            inpt.value = inpt.value.replace(regex, "");
        }
        function merchantList()
        {
            $.ajax(
            {
                type: 'post',
                url: 'toupdateorders',
                data:
                {
                    get_orderid: 'na',
                    flagreq: 'merchantInvoiceList'
                },
                success: function (response)
                {
                    $('#merchantCode').html('');
                    $('#merchantCode').html(response);
                }
            })
        }
        function invoiceCount()
        {
            $.ajax(
            {
                type: 'post',
                url: 'toupdateorders',
                data:
                {
                    get_orderid: 'na',
                    flagreq: 'invoiceCount'
                },
                success: function (response)
                {
                    $('#invCount').html('');
                    $('#invCount').html(response);
                }
            })
        }
        function sendMessageMerchant()
        {
            var merchant = $('#invMerchantCode').val();
            var inv = $('#invNumber').val();
            if (merchant != '')
            {
                $.ajax(
                {
                    type: 'post',
                    url: 'sendMessage',
                    data:
                    {
                        rootData: merchant,
                        flagreq: 'invoiceMessage'
                    },
                    success: function (response)
                    {
                        data = JSON.parse(response);
                        for (rst in data)
                        {
                            var status = data[rst][0].status;
                            var mobile = data[rst][0].destination;
                            if (status == 0)
                            {
                                $('#alrt').html('');
                                $('#alrt').append("<p id=\"sendOutput\" style=\"color: green\">SMS sent to " + mobile + " successfully</p>");
                                $.ajax(
                                {
                                    type: 'post',
                                    url: 'sendMessage',
                                    data:
                                    {
                                        rootData: inv,
                                        status: status,
                                        phone: mobile,
                                        flagreq: 'smsStatus'
                                    },
                                    success: function (response2)
                                    {
                                        $('#alrt').html('');
                                        $('#alrt').append("<p id=\"sendOutput\" style=\"color: orange\">SMS Log recorded successfully</p>");
                                    }
                                })
                            } else
                            {
                                $('#alrt').html('');
                                $('#alrt').append("<p id=\"sendOutput\" style=\"color: red\">SMS to " + mobile + " failed</p>");
                                $.ajax(
                                {
                                    type: 'post',
                                    url: 'sendMessage',
                                    data:
                                    {
                                        rootData: inv,
                                        status: status,
                                        phone: mobile,
                                        flagreq: 'smsStatus'
                                    },
                                    success: function (response2)
                                    {
                                        $('#alrt').html('');
                                        $('#alrt').append("<p id=\"sendOutput\" style=\"color: red\">Invalid mobile no!!! Failed to log</p>");
                                    }
                                })
                            }
                        }
                    }
                })
            }
        }
        function prevInv()
        {
            var element = document.getElementById("merchantCode");
            var merchant = element.options[element.selectedIndex].value;
            var disc_adj = document.getElementById("disc").value;
            var lines = $('textarea').val().split('\n');
            for (var i = 0; i < 6; i++)
            {
                if (i == 0)
                {
                    var invLine1 = lines[i];
                    if (!invLine1) { invLine1 = ' '; }
                }
                if (i == 1)
                {
                    var invLine2 = lines[i];
                    if (!invLine2) { invLine2 = ' '; }
                }
                if (i == 2)
                {
                    var invLine3 = lines[i];
                    if (!invLine3) { invLine3 = ' '; }
                }
                if (i == 3)
                {
                    var invLine4 = lines[i];
                    if (!invLine4) { invLine4 = ' '; }
                }
                if (i == 4)
                {
                    var invLine5 = lines[i];
                    if (!invLine5) { invLine5 = ' '; }
                }
                if (i == 5)
                {
                    var invLine6 = lines[i];
                    if (!invLine6) { invLine6 = ' '; }
                }
            }
            if (merchant == '')
            {
                $('#alrtGeneral').html('Please select a merchant');
            } else
            {
                window.open("invoice_view.php?xxCode=" + merchant + "&disc=" + disc_adj + "&invC1=" + invLine1 + "&invC2=" + invLine2 + "&invC3=" + invLine3 + "&invC4=" + invLine4 + "&invC5=" + invLine5 + "&invC6=" + invLine6, "_blank");
            }

        }

        function smartPickprevInv()
        {
            var element = document.getElementById("smartPickMerchant");
            var merchant = element.options[element.selectedIndex].value;
            var disc_adj = document.getElementById("disc").value;
            var lines = $('textarea').val().split('\n');
            for (var i = 0; i < 6; i++)
            {
                if (i == 0)
                {
                    var invLine1 = lines[i];
                    if (!invLine1) { invLine1 = ' '; }
                }
                if (i == 1)
                {
                    var invLine2 = lines[i];
                    if (!invLine2) { invLine2 = ' '; }
                }
                if (i == 2)
                {
                    var invLine3 = lines[i];
                    if (!invLine3) { invLine3 = ' '; }
                }
                if (i == 3)
                {
                    var invLine4 = lines[i];
                    if (!invLine4) { invLine4 = ' '; }
                }
                if (i == 4)
                {
                    var invLine5 = lines[i];
                    if (!invLine5) { invLine5 = ' '; }
                }
                if (i == 5)
                {
                    var invLine6 = lines[i];
                    if (!invLine6) { invLine6 = ' '; }
                }
            }

            if (merchant == '')
            {
                $('#alrtSmart').html('Please select a merchant');
            } else
            {
                window.open("invoice_view_smart.php?xxCode=" + merchant + "&disc=" + disc_adj + "&invC1=" + invLine1 + "&invC2=" + invLine2 + "&invC3=" + invLine3 + "&invC4=" + invLine4 + "&invC5=" + invLine5 + "&invC6=" + invLine6, "_blank");
            }
        }
        function monthlyPrevInv()
        {
            var element = document.getElementById("monthlyMerchant");
            var merchant = element.options[element.selectedIndex].value;
            var disc_adj = document.getElementById("disc").value;
            var invMonth = $('#invMonth').val();
            var invYear = $('#invYear').val();
            var lines = $('textarea').val().split('\n');
            for (var i = 0; i < 6; i++)
            {
                if (i == 0)
                {
                    var invLine1 = lines[i];
                    if (!invLine1) { invLine1 = ' '; }
                }
                if (i == 1)
                {
                    var invLine2 = lines[i];
                    if (!invLine2) { invLine2 = ' '; }
                }
                if (i == 2)
                {
                    var invLine3 = lines[i];
                    if (!invLine3) { invLine3 = ' '; }
                }
                if (i == 3)
                {
                    var invLine4 = lines[i];
                    if (!invLine4) { invLine4 = ' '; }
                }
                if (i == 4)
                {
                    var invLine5 = lines[i];
                    if (!invLine5) { invLine5 = ' '; }
                }
                if (i == 5)
                {
                    var invLine6 = lines[i];
                    if (!invLine6) { invLine6 = ' '; }
                }
            }

            if (merchant == '')
            {
                $('#alrtMonth').html('Please select a merchant');
            } else
            {
                window.open("Monthly-Invoice?xxCode=" + merchant + "&disc=" + disc_adj + "&invC1=" + invLine1 + "&invC2=" + invLine2 + "&invC3=" + invLine3 + "&invC4=" + invLine4 + "&invC5=" + invLine5 + "&invC6=" + invLine6 + "&invMonth=" + invMonth + "&invYear=" + invYear, "_blank");
            }
        }            
    </script>
</html>
