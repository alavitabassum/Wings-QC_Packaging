<?php
    include('session.php');
    include('header.php');
    include('config.php');
    include('num_format.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_report` WHERE user_id = $user_id_chk and bankDeposit = 'Y'"));
    if ($userPrivCheckRow['bankDeposit'] != 'Y'){
        exit();
    }
?>
        <div style="clear: both; margin-left: 2%">
            <form name="bankDep" action="" method="post">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Enter Bank Deposit Date</p>
                <input style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="startDate" value="<?php if (isset($_POST['submit'])) { echo date("d-m-Y", strtotime(trim($_POST['startDate']))); } else { echo date("d-m-Y");}?>" onfocus="displayCalendar(document.bankDep.startDate,'dd-mm-yyyy',this)" required> 
                <span style="color: #16469E; font: 11px 'paperfly roman'">To</span>
                <input style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="endDate" value="<?php if (isset($_POST['submit'])) { echo date("d-m-Y", strtotime(trim($_POST['endDate']))); } else { echo date("d-m-Y");}?>" onfocus="displayCalendar(document.bankDep.endDate,'dd-mm-yyyy',this)"required> 
                <input type="submit" name="submit" value="Show" class="btn btn-primary">
            </form>
        </div>
<?php
if (isset($_POST['submit'])){
    $startDate = date("Y-m-d", strtotime(trim($_POST['startDate'])));
    $startDate = mysqli_real_escape_string($conn, $startDate);
    $endDate = date("Y-m-d", strtotime(trim($_POST['endDate'])));
    $endDate = mysqli_real_escape_string($conn, $endDate);
    $pointOrdCntSQL = "SELECT dropPointCode, pointName, count(orderid)as orderCnt, round(sum(CashAmt)) as totAmt FROM `tbl_order_details` left join tbl_point_info on tbl_order_details.dropPointCode = tbl_point_info.pointCode where bank = 'Y' and (DATE_FORMAT(bankTime,'%Y-%m-%d') between '$startDate' and '$endDate') group by dropPointCode order by dropPointCode";
    $pointOrdCntResult = mysqli_query($conn, $pointOrdCntSQL);
    $bankCloseSQL = "Select * from tbl_order_details where bank = 'Y' and (DATE_FORMAT(bankTime,'%Y-%m-%d') between '$startDate' and '$endDate') order by dropPointCode, orderDate";
    $bankCloseResult = mysqli_query($conn, $bankCloseSQL);
?>
        <div style="clear: both; margin-left: 1%">
        <p id="summarry" style="color: #16469E; font: 13px 'paperfly roman'"><u>Summary for <?php if ($startDate !='') { echo "<b>".date("d-m-Y", strtotime($startDate))."</b> To <b>".date("d-m-Y", strtotime($endDate))."</b>";} else {echo "Point-wise Bank Deposits";}?></u></p>
            <table style="margin-bottom: 5px">
                <thead style="color: #16469E; font: 13px 'paperfly roman'">
                    <tr>
                        <th style="width: 130px">Point Code & Name</th>
                        <th style="width: 100px">&nbsp;&nbsp;No of Orders&nbsp;&nbsp;</th>
                        <th style="width: 150px; text-align: right">&nbsp;&nbsp;Bank Deposits in BDT&nbsp;&nbsp;</th>
                        <th>Show/Hide Details</th>
                    </tr>
                </thead>
                <tbody style="color: #16469E; font: 12px 'paperfly roman'">
                    <?php foreach ($pointOrdCntResult as $pointOrdCntRow) {?>
                    <tr id="<?php echo $pointOrdCntRow['dropPointCode'];?>">
                        <td><?php echo $pointOrdCntRow['dropPointCode']." - ".$pointOrdCntRow['pointName'];?></td>
                        <td style="text-align: right; width: 80px"><?php echo num_to_format(round($pointOrdCntRow['orderCnt']));?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right"><?php echo num_to_format(round($pointOrdCntRow['totAmt']));?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td style="text-align: center"><input type="checkbox" id="<?php echo $pointOrdCntRow['dropPointCode'];?>"></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <table id="ordDetail" class='table table-hover'>
                <tr style='background-color:#dad8d8; li'>
                    <th>Order ID</th>
                    <th>Merchant Reference</th> 
                    <th>Price</th>
                    <th>Collected Amount</th>
                    <th>Cash Comment</th>
                    <th>Bank</th>
                    <th>Close</th>
                </tr>
                <?php
                    foreach ($bankCloseResult as $bankCloseRow){
                        ?>
                            <tr id="<?php echo $bankCloseRow['orderid'];?>" class="<?php echo $bankCloseRow['dropPointCode']; ?>">
                                <td><?php echo $bankCloseRow['orderid'];?></td>
                                <td><?php echo $bankCloseRow['merOrderRef'];?></td>
                                <td><?php echo $bankCloseRow['packagePrice'];?></td>
                                <td><?php echo $bankCloseRow['CashAmt'];?></td>
                                <td><?php echo $bankCloseRow['cashComment'];?></td>
                                <td><button style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button></td>
                                <td>
                                    <?php if ($bankCloseRow['close'] == 'Y') {?>
                                        <button style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                    <?php } else {?>
                                        <button id="close<?php echo $bankCloseRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CloseUpdate('".$bankCloseRow['orderid']."')"?>"></button>
                                    <?php }?>
                                </td>
                            </tr>
                        <?php
                    }
                ?>
        </table>
        </div>
<?php }?>
        <script>
            <?php foreach ($pointOrdCntResult as $pointOrdCntRow) {?>
                $("#ordDetail tr.<?php echo $pointOrdCntRow['dropPointCode'];?>").hide();
                $("#<?php echo $pointOrdCntRow['dropPointCode'];?>").change(function(){
                    $("#ordDetail tr.<?php echo $pointOrdCntRow['dropPointCode'];?>").toggle(this.checked); 
                });

            <?php }?>

                       
            function CloseUpdate(ord)
            {
                //
                var pickempid = '';
                var orderid = ord;
                var updateFlag = 'close';
                $.ajax({
                    type: "POST",
                    url: "trackerupdate",
                    data:
                        {
                            data: pickempid,
                            order: orderid,
                            flag: updateFlag
                        },
                    success: function (msg)
                    {
                        if (msg == 'success')
                        {
                            $("#close" + ord).css("background-color", "green");
                            $("#close" + ord).attr("disabled", true);
                        } else
                        {
                            alert("Unable to close order");
                        }
                    }
                });
                //
            }
        </script>
    </body>
</html>

 