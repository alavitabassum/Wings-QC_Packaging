<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $pointSQL = "select pointCode, pointName from tbl_point_info";
    $pointResult = mysqli_query($conn, $pointSQL);
    $depositLocationSQL = "SELECT atmLocationID, tbl_bank_info.bankName, locationName, address, tbl_district_info.districtName FROM `tbl_atm_locations` left join tbl_bank_info on tbl_bank_info.bankID = tbl_atm_locations.bankID left join tbl_district_info on tbl_district_info.districtId = tbl_atm_locations.districtID";
    $depostiLocationResult = mysqli_query($conn, $depositLocationSQL);
    $depositBySQL = "select * from tbl_employee_info";
    $depoitByResult = mysqli_query($conn, $depositBySQL);
    $dp2BatchResult = mysqli_query($conn, "select distinct DropDP2By, dropDP2Batch from tbl_order_details where bank is null and (cash = 'Y'or partial='Y') and DropDP2By is not null and close is null order by dropDP2Batch desc");
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and bankCollection = 'Y'"));
    if ($userPrivCheckRow['bankCollection'] != 'Y'){
        exit();
    }
    $DP2UserResult = mysqli_query($conn, "select distinct DropDP2By from tbl_order_details where bank = 'Y' and DropDP2By !='admin' order by DropDP2By ");
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Collection at Bank</p>
                <div class="row" style="margin: 0">
                    <div class="col-sm-4">
                        <div style="width: 400px; height: 115px; border-radius: 5px; padding: 5px; border-color: #00AEEF; border-style: ridge">
                            <div class="form-group">
                                <label for="pointCode" style="font-size: large">Point Name :</label>
                                <select id="pointCode" name="pointCode" style="width: 100%">
                                    <?php foreach ($pointResult as $pointRow) {?>
                                        <option value="<?php echo $pointRow['pointCode'];?>"><?php echo $pointRow['pointCode']." - ".$pointRow['pointName'];?></option>
                                    <?php }?>
                                </select>
                                <br><br>
                                <input type="submit" name="showPoint" value="Show List" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="width: 400px; height: 115px; border-radius: 5px; padding: 5px; border-color: #00AEEF; border-style: ridge">
                            <div class="form-group">
                                <label for="dp2BatchID" style="font-size: large">Batch ID :</label>
                                <select id="dp2BarchID" name="dp2BarchID" style="width: 100%">
                                    <?php foreach ($dp2BatchResult as $dp2BatchRow) {?>
                                        <option value="<?php echo $dp2BatchRow['dropDP2Batch'].','.$dp2BatchRow['DropDP2By'];?>"><?php echo $dp2BatchRow['DropDP2By']." - ".$dp2BatchRow['dropDP2Batch'];?></option>
                                    <?php }?>
                                </select>
                                <br><br>
                                <input type="submit" name="showBatch" value="Show List" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="width: 400px; height: 115px; border-radius: 5px; padding: 5px; border-color: #00AEEF; border-style: ridge">
                                <div class="col-sm-6">
                                    <label for="dp2BatchBy" style="font-size: large">Batch By</label>
                                    <select id="dp2BatchBy" name="dp2BatchBy" style="width: 100%">
                                        <?php foreach ($DP2UserResult as $DP2UserRow) {?>
                                            <option value="<?php echo $DP2UserRow['DropDP2By'];?>"><?php echo $DP2UserRow['DropDP2By'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Batch No</label>
                                    <input type="text" id="batchNo" name="batchNo" class="form-control input-sm">
                                </div>
                                <div class="col-sm-2">
                                    <input type="submit" name="showBatchHistory" value="Show Batch History" class="btn btn-info">
                                </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
                if(isset($_POST['showBatchHistory'])){
                    $DropDP2By = trim($_POST['dp2BatchBy']);
                    $dropDP2Batch = trim($_POST['batchNo']);
                    $orderListSQL = "select orderid, merOrderRef, packagePrice, CashAmt, concat(DropDP2By,' - ',dropDP2Batch) as batchNo, dropDP2depositSlip, depositComment, dropDP2Comments, concat(tbl_employee_info.empName,' - ',tbl_order_details.depositedBy) as depositedBy from tbl_order_details left join tbl_employee_info on tbl_order_details.depositedBy = tbl_employee_info.empCode where DropDP2By ='$DropDP2By' and dropDP2Batch = '$dropDP2Batch'";
                    $orderListResult = mysqli_query($conn, $orderListSQL);
                    ?>
                    <?php
                        $totalOrder = 0;
                        $totalAmount = 0; 
                        foreach ($orderListResult as $orderListRow){
                            $totalOrder++ ;
                            $totalAmount = $totalAmount + $orderListRow['CashAmt'];
                        }
                    ?>
                    <div style="border-radius: 5px; padding: 5px; margin-right: 20px; border-color: #00AEEF; border-style: ridge">
                        <hr>
                        <p>Orders Selected :<span id="ordersCount" style="width: 100px; margin-right: 100px"><?php echo $totalOrder;?></span>Total Amount : <span id="totalAmount" style="width: 100px; margin-right: 100px"><?php echo $totalAmount;?></span></p>
                        <hr>
                        <table class="table table-basic" id="bankCollectionList">
                            <tr style="background-color: #16469E; color: white">
                                <th>Order ID</th>
                                <th>Merchant Ref</th>
                                <th>Package Price</th>
                                <th>Collection</th>
                                <th>Batch No</th>
                                <th>Deposit Slip</th>
                                <th>Deposit By</th>
                                <th>Comments</th>
                                <th>Line Comments</th>
                            </tr>

                            <?php foreach ($orderListResult as $orderListRow){?>
                                <tr id="tr<?php echo $orderListRow['orderid'];?>">
                                    <td><?php echo $orderListRow['orderid'];?></td>
                                    <td><?php echo $orderListRow['merOrderRef'];?></td>
                                    <td><?php echo $orderListRow['packagePrice'];?></td>
                                    <td id="cash<?php echo $orderListRow['orderid'];?>"><?php echo $orderListRow['CashAmt']; ?></td>
                                    <td><?php echo $orderListRow['batchNo'];?></td>
                                    <td><?php echo $orderListRow['dropDP2depositSlip'];?></td>
                                    <td><?php echo $orderListRow['depositedBy'];?></td>
                                    <td><?php echo $orderListRow['depositComment'];?></td>
                                    <td><?php echo $orderListRow['dropDP2Comments'];?></td>
                                </tr>
                            <?php }?>
                        </table>
                    </div>
            <?php }?>
            
                <?php if (isset($_POST['showPoint'])) {
                    $pointCode = trim($_POST['pointCode']);
                    $orderListSQL = "select orderid, merOrderRef, packagePrice, CashAmt, concat(DropDP2By,' - ',dropDP2Batch) as batchNo, dropDP2depositSlip, depositComment, dropDP2Comments, concat(tbl_employee_info.empName,' - ',tbl_order_details.depositedBy) as depositedBy from tbl_order_details left join tbl_employee_info on tbl_order_details.depositedBy = tbl_employee_info.empCode where dropPointCode ='$pointCode' and (cash = 'Y'or partial='Y') and DropDP2 = 'Y' and bank is null and close is null order by dropDP2Batch";
                    $orderListResult = mysqli_query($conn, $orderListSQL);
                ?>
                <div style="border-radius: 5px; padding: 5px; margin-right: 20px; border-color: #00AEEF; border-style: ridge">
                    <hr>
                    <p>Orders Selected :<span id="ordersCount" style="width: 100px; margin-right: 100px">0</span>Total Amount : <span id="totalAmount" style="width: 100px; margin-right: 100px">0</span><span style="font: 15px 'paperfly roman'"><?php echo "Point Code : ".$pointCode;?></span></p>
                    <hr>
                    <table class="table table-basic" id="bankCollectionList">
                        <tr style="background-color: #16469E; color: white">
                            <th>Order ID</th>
                            <th>Merchant Ref</th>
                            <th>Package Price</th>
                            <th>Collection</th>
                            <th><input type="checkbox" id="chkCollection" value="NA"></th>
                            <th>Batch No</th>
                            <th>Deposit Slip</th>
                            <th>Deposit By</th>
                            <th>Comments</th>
                            <th>Line Comments</th>
                        </tr>

                        <?php foreach ($orderListResult as $orderListRow){?>
                            <tr id="tr<?php echo $orderListRow['orderid'];?>">
                                <td><?php echo $orderListRow['orderid'];?></td>
                                <td><?php echo $orderListRow['merOrderRef'];?></td>
                                <td><?php echo $orderListRow['packagePrice'];?></td>
                                <td id="cash<?php echo $orderListRow['orderid'];?>"><?php echo $orderListRow['CashAmt']; ?></td>
                                <td><input type="checkbox" name="chk" value="<?php echo $orderListRow['orderid'];?>"></td>
                                <td><?php echo $orderListRow['batchNo'];?></td>
                                <td><?php echo $orderListRow['dropDP2depositSlip'];?></td>
                                <td><?php echo $orderListRow['depositedBy'];?></td>
                                <td><?php echo $orderListRow['depositComment'];?></td>
                                <td><?php echo $orderListRow['dropDP2Comments'];?></td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
                <?php }?>

                <?php if (isset($_POST['showBatch'])) {
                    $batchValue = $_POST['dp2BarchID'];
                    $array = explode(',', $batchValue);
                    $batchID = $array [0];
                    $usrName = $array [1];
                    $orderListSQL = "select orderid, merOrderRef, packagePrice, CashAmt, concat(DropDP2By,' - ',dropDP2Batch) as batchNo, dropDP2depositSlip, depositComment, dropDP2Comments, concat(tbl_employee_info.empName,' - ',tbl_order_details.depositedBy) as depositedBy from tbl_order_details left join tbl_employee_info on tbl_order_details.depositedBy = tbl_employee_info.empCode where dropDP2Batch ='$batchID' and DropDP2By = '$usrName' and (cash = 'Y'or partial='Y') and DropDP2 = 'Y' and bank is null and close is null order by dropDP2Batch";
                    $orderListResult = mysqli_query($conn, $orderListSQL);
                ?>
                <div style="border-radius: 5px; padding: 5px; margin-right: 20px; border-color: #00AEEF; border-style: ridge">
                    <hr>
                    <p>Orders Selected :<span id="ordersCount" style="width: 100px; margin-right: 100px">0</span>Total Amount : <span id="totalAmount" style="width: 100px; margin-right: 100px">0</span><span style="font: 15px 'paperfly roman'"><?php echo "Point Code : ".$pointCode;?></span></p>
                    <hr>
                    <table class="table table-basic" id="bankCollectionList">
                        <tr style="background-color: #16469E; color: white">
                            <th>Order ID</th>
                            <th>Merchant Ref</th>
                            <th>Package Price</th>
                            <th>Collection</th>
                            <th><input type="checkbox" id="chkCollection" value="NA"></th>
                            <th>Batch No</th>
                            <th>Deposit Slip</th>
                            <th>Deposit By</th>
                            <th>Comments</th>
                            <th>Line Comments</th>
                        </tr>

                        <?php foreach ($orderListResult as $orderListRow){?>
                            <tr id="tr<?php echo $orderListRow['orderid'];?>">
                                <td><?php echo $orderListRow['orderid'];?></td>
                                <td><?php echo $orderListRow['merOrderRef'];?></td>
                                <td><?php echo $orderListRow['packagePrice'];?></td>
                                <td id="cash<?php echo $orderListRow['orderid'];?>"><?php echo $orderListRow['CashAmt']; ?></td>
                                <td><input type="checkbox" name="chk" value="<?php echo $orderListRow['orderid'];?>"></td>
                                <td><?php echo $orderListRow['batchNo'];?></td>
                                <td><?php echo $orderListRow['dropDP2depositSlip'];?></td>
                                <td><?php echo $orderListRow['depositedBy'];?></td>
                                <td><?php echo $orderListRow['depositComment'];?></td>
                                <td><?php echo $orderListRow['dropDP2Comments'];?></td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
                <?php }?>
            
            <div style="width: 500px; border-radius: 5px; padding: 20px;  border-color: #00AEEF; border-style: ridge; margin-top: 10px">
                <form method="post" action="" name="depositFrm">
                    <table style="width: 100%">
                        <tr>
                            <td><label>Deposit Date</label></td>
                            <td><input style="height: 25px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="text" name="depositDate" value="<?php echo date("d-m-Y");?>" onfocus="displayCalendar(document.depositFrm.depositDate,'dd-mm-yyyy',this)" required> </td>
                        </tr>
                        <tr>
                            <td><label>Deposit Amount</label></td>
                            <td><input style="height: 25px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="text" name="depositAmount" value="0" onkeyup="return isNumberKey(this)" onblur="return isDefault(this)" required></td>
                        </tr>
                        <tr>
                            <td><label>Deposit Location</label></td>
                            <td style="height: 33px"><select style="height: 25px; width: 100%; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" id="depositLocation" name="depositLocation">
                                <?php foreach($depostiLocationResult as $depositLocationRow){?>
                                    <option value="<?php echo $depositLocationRow['atmLocationID'];?>"><?php echo $depositLocationRow['locationName']." - ".$depositLocationRow['districtName'];?></option>
                                <?php }?>
                            </select></td>
                        </tr>
                        <tr>
                            <td><label>Deposit By</label></td>
                            <td style="height: 33px"><select style="height: 25px; width: 100%; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" id="depositBy" name="depositBy">
                                <?php foreach($depoitByResult as $depositByRow){?>
                                    <option value="<?php echo $depositByRow['empCode'];?>"><?php echo $depositByRow['empName'];?></option>
                                <?php }?>
                            </select></td>
                        </tr>
                        <tr>
                            <td><label>Document Reference</label></td>
                            <td><input style="height: 25px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="text" name="documentRef"></td>
                        </tr>
                        <tr>
                            <td><label>Comments</label></td>
                            <td><input style="height: 25px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="text" name="comments"></td>
                        </tr>
                    </table>
                    <br>
                    <input class="btn btn-primary" type="submit" name="acceptDeposit" value="ACCEPT">
                    <textarea rows="6" id="selectedOrders" name="selectedOrders" hidden></textarea>
                </form>
                <?php if (isset($_POST['acceptDeposit'])){
                    $depositDate = date("Y-m-d", strtotime(trim($_POST['depositDate'])));
                    $depositAmount = trim($_POST['depositAmount']);
                    $depositLocation = trim($_POST['depositLocation']);
                    $depositBy = trim($_POST['depositBy']);
                    $documentRef= trim($_POST['documentRef']);
                    $documentRef = mysqli_real_escape_string($conn, $documentRef);
                    $comments = trim($_POST['comments']);
                    $comments = mysqli_real_escape_string($conn, $comments);
                    $selectedOrders = trim($_POST['selectedOrders']);
                    $depositInsertSQL = "Insert into tbl_bank_deposit(depositDate, depositAmount, depositLocation, depositBy, documentRef, comments, creationDate, createdBy) values('$depositDate', '$depositAmount', '$depositLocation', '$depositBy', '$documentRef', '$comments', NOW() + INTERVAL 6 HOUR, '$user_check')";
                    if (!mysqli_query($conn,$depositInsertSQL)){
                        $error ="Insert Error : " . mysqli_error($conn);
                        ?>
                        <p id="depositAlert" style="color: red"><?php echo $error;?></p>
                        <?php
                    } else {
                        $depositID = mysqli_insert_id($conn);
                        $updateBankStatusSQL = "update tbl_order_details set bank='Y', 	bankTime= NOW() + INTERVAL 6 HOUR, bankBy = '$user_check', 	depositID='$depositID' where orderid in ($selectedOrders)";
                        if (!mysqli_query($conn,$updateBankStatusSQL)){
                            $error ="Tracker Update Error : " . mysqli_error($conn);
                            ?>
                            <p id="depositAlert" style="color: red"><?php echo $error;?></p>
                            <?php
                        } else { 
                            ?>
                            <p id="depositAlert" style="color: green">Deposit accepted successfully. <?php echo " Deposit ID : ". $depositID;?></p>    
                            <?php
                        }
                    }
                }?>
            </div>
        </div>
        <script>
            $(document).ready(function ()
            {
                $('#bankCollectionList').bdt({
                    showSearchForm: 1,
                    showEntriesPerPageField: 1
                });

                $('form[name=depositFrm]').submit(function ()
                {
                    if (parseInt($('#ordersCount').html()) < 0)
                    {
                        alert('No order selected');
                        return false;
                    } else
                    {
                        if (parseInt($('input[name=depositAmount]').val()) != parseInt($('#totalAmount').html()))
                        {
                            alert("Deposit amount does not match with selected orders amount");
                            return false;
                        } else
                        {
                            return true;
                        }
                    }
                });

            });
            $(window).load(function ()
            {
                $('#pointCode').select2();
                $('#depositLocation').select2();
                $('#depositBy').select2();
                $('#dp2BarchID').select2();
                $('#dp2BatchBy').select2();
            });

            var cnt = 0;
            var cashAmt = 0;
            $("input[name=chk]").click(function ()
            {
                if ($(this).is(":checked"))
                {
                    cnt = cnt + 1;
                    $('#ordersCount').html(cnt);
                    cashAmt = parseInt($('#totalAmount').html()) + parseInt($('#cash' + ($(this).val())).html());
                    $('#totalAmount').html(cashAmt);
                    $('#tr' + $(this).val()).css('background-color', 'rgb(221, 255, 221)');
                } else
                {
                    cnt = cnt - 1;
                    $('#ordersCount').html(cnt);
                    cashAmt = parseInt($('#totalAmount').html()) - parseInt($('#cash' + ($(this).val())).html());
                    $('#totalAmount').html(cashAmt);
                    $('#tr' + $(this).val()).css('background-color', 'white');
                }
                var selectedOrders = [];
                $("input[name=chk]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        selectedOrders.push("'" + $(this).val() + "'");
                    }
                });
                $('#selectedOrders').val(selectedOrders.toString());
            });

            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }

            function isDefault(inpt)
            {
                if (inpt.value == '')
                {
                    inpt.value = 0;
                }
            }
            $('#chkCollection').click(function ()
            {
                var allChecked = $(this);
                $("#bankCollectionList input[name=chk]").each(function ()
                {
                    $(this).prop("checked", allChecked.is(':checked'));
                    if ($(this).is(":checked"))
                    {
                        cnt = cnt + 1;
                        $('#ordersCount').html(cnt);
                        cashAmt = parseInt($('#totalAmount').html()) + parseInt($('#cash' + ($(this).val())).html());
                        $('#totalAmount').html(cashAmt);
                        $('#tr' + $(this).val()).css('background-color', 'rgb(221, 255, 221)');
                    } else
                    {
                        cnt = 0;
                        $('#ordersCount').html('0');
                        cashAmt = 0;
                        $('#totalAmount').html('0');
                        $('#tr' + $(this).val()).css('background-color', 'white');
                    }
                    var selectedOrders = [];
                    $("input[name=chk]").each(function ()
                    {
                        if ($(this).is(":checked"))
                        {
                            selectedOrders.push("'" + $(this).val() + "'");
                        }
                    });
                    $('#selectedOrders').val(selectedOrders.toString());
                })
            });
        </script>
    </body>
</html>
