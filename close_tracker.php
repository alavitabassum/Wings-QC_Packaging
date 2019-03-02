        <?php
            include('num_format.php');
            $totalRow = 0;
            $totalPrice = 0;
            $totalCash = 0;
            $CashAmount = 0;
            $CollectionAmount = 0;
            $totalReturn = 0 ;
            $ReturnPrice = 0;
            $totalpartial = 0;
            $partialPrice = 0;
            $totalAccident = 0;
            $AccidentPrice =0;
            $totclose =0;
            $ClosePrice =0;
            foreach ($orderResult as $orderRow){
                $totalRow++;
                $totalPrice = $totalPrice + $orderRow['packagePrice'];
                if ($orderRow['Cash'] == 'Y' and $orderRow['accRem'] == NULL and $orderRow['close'] == 'Y'){
                    $totalCash++;
                    $CashAmount = $CashAmount + $orderRow['packagePrice'];
                    $CollectionAmount = $CollectionAmount + $orderRow['CashAmt'];
                }
                if ($orderRow['Ret'] == 'Y' and $orderRow['accRem'] == NULL and $orderRow['close'] == 'Y'){
                    $totalReturn++;
                    $ReturnPrice = $ReturnPrice + $orderRow['packagePrice'];
                }
                if ($orderRow['partial'] == 'Y' and $orderRow['accRem'] == NULL and $orderRow['close'] == 'Y') {
                    $totalpartial++;
                    $partialPrice = $partialPrice + $orderRow['packagePrice'];
                }
                if ($orderRow['accRem'] != '') {
                    $totalAccident++;
                    $AccidentPrice = $AccidentPrice + $orderRow['packagePrice'];
                }
                if ($orderRow['close'] == 'Y') {
                    $totclose++;
                    $ClosePrice = $ClosePrice + $orderRow['packagePrice'];
                }
            }
        ?>
<script>
    $(document).ready(function ()
    {
        $('tr.ud').hide();
    });
    function ordType(cri)
    {

        switch (cri)
        {
            case 1:
                $('tr.accident').show();
                $('tr.partial').show();
                $('tr.return').show();
                $('tr.cash').show();
                $('tr.ud').hide();
                $('#allorders').css("background-color", "yellow");
                $('#cashorders').css("background-color", "white");
                $('#returnorders').css("background-color", "white");
                $('#partialorders').css("background-color", "white");
                $('#accidentorders').css("background-color", "white");
                $('#pendingorders').css("background-color", "white");
                $('#ordersReceived').css("background-color", "white");
                document.getElementById("receivedorders").checked = false;
                document.getElementById("pending").checked = false;
                document.getElementById("totorders").checked = true;
                document.getElementById("delivery").checked = false;
                document.getElementById("return").checked = false;
                document.getElementById("partial").checked = false;
                document.getElementById("accident").checked = false;
                break;
            case 2:
                $('tr.accident').hide();
                $('tr.partial').hide();
                $('tr.return').hide();
                $('tr.cash').show();
                $('tr.ud').hide();
                $('#allorders').css("background-color", "white");
                $('#cashorders').css("background-color", "yellow");
                $('#returnorders').css("background-color", "white");
                $('#partialorders').css("background-color", "white");
                $('#accidentorders').css("background-color", "white");
                $('#pendingorders').css("background-color", "white");
                $('#ordersReceived').css("background-color", "white");
                document.getElementById("receivedorders").checked = false;
                document.getElementById("pending").checked = false;
                document.getElementById("totorders").checked = false;
                document.getElementById("delivery").checked = true;
                document.getElementById("return").checked = false;
                document.getElementById("partial").checked = false;
                document.getElementById("accident").checked = false;
                break;
            case 3:
                $('tr.accident').hide();
                $('tr.partial').hide();
                $('tr.return').show();
                $('tr.cash').hide();
                $('tr.ud').hide();
                $('#allorders').css("background-color", "white");
                $('#cashorders').css("background-color", "white");
                $('#returnorders').css("background-color", "yellow");
                $('#partialorders').css("background-color", "white");
                $('#accidentorders').css("background-color", "white");
                $('#pendingorders').css("background-color", "white");
                $('#ordersReceived').css("background-color", "white");
                document.getElementById("receivedorders").checked = false;
                document.getElementById("pending").checked = false;
                document.getElementById("totorders").checked = false;
                document.getElementById("delivery").checked = false;
                document.getElementById("return").checked = true;
                document.getElementById("partial").checked = false;
                document.getElementById("accident").checked = false;
                break;
            case 4:
                $('tr.accident').hide();
                $('tr.partial').show();
                $('tr.return').hide();
                $('tr.cash').hide();
                $('tr.ud').hide();
                $('#allorders').css("background-color", "white");
                $('#cashorders').css("background-color", "white");
                $('#returnorders').css("background-color", "white");
                $('#partialorders').css("background-color", "yellow");
                $('#accidentorders').css("background-color", "white");
                $('#pendingorders').css("background-color", "white");
                $('#ordersReceived').css("background-color", "white");
                document.getElementById("receivedorders").checked = false;
                document.getElementById("pending").checked = false;
                document.getElementById("delivery").checked = false;
                document.getElementById("return").checked = false;
                document.getElementById("totorders").checked = false;
                document.getElementById("partial").checked = true;
                document.getElementById("accident").checked = false;
                break;
            case 5:
                $('tr.accident').show();
                $('tr.partial').hide();
                $('tr.return').hide();
                $('tr.cash').hide();
                $('tr.ud').hide();
                $('#allorders').css("background-color", "white");
                $('#cashorders').css("background-color", "white");
                $('#returnorders').css("background-color", "white");
                $('#partialorders').css("background-color", "white");
                $('#accidentorders').css("background-color", "yellow");
                $('#pendingorders').css("background-color", "white");
                $('#ordersReceived').css("background-color", "white");
                document.getElementById("receivedorders").checked = false;
                document.getElementById("pending").checked = false;
                document.getElementById("delivery").checked = false;
                document.getElementById("return").checked = false;
                document.getElementById("totorders").checked = false;
                document.getElementById("partial").checked = false;
                document.getElementById("accident").checked = true;
                break;
            case 6:
                $('tr.accident').show();
                $('tr.partial').show();
                $('tr.return').show();
                $('tr.cash').show();
                $('tr.ud').show();
                $('#allorders').css("background-color", "white");
                $('#cashorders').css("background-color", "white");
                $('#returnorders').css("background-color", "white");
                $('#partialorders').css("background-color", "white");
                $('#accidentorders').css("background-color", "white");
                $('#pendingorders').css("background-color", "white");
                $('#ordersReceived').css("background-color", "yellow");
                document.getElementById("receivedorders").checked = true;
                document.getElementById("pending").checked = false;
                document.getElementById("delivery").checked = false;
                document.getElementById("return").checked = false;
                document.getElementById("totorders").checked = false;
                document.getElementById("partial").checked = false;
                document.getElementById("accident").checked = false;
                break;
            case 7:
                $('tr.accident').hide();
                $('tr.partial').hide();
                $('tr.return').hide();
                $('tr.cash').hide();
                $('tr.ud').show();
                $('#allorders').css("background-color", "white");
                $('#cashorders').css("background-color", "white");
                $('#returnorders').css("background-color", "white");
                $('#partialorders').css("background-color", "white");
                $('#accidentorders').css("background-color", "white");
                $('#pendingorders').css("background-color", "yellow");
                $('#ordersReceived').css("background-color", "white");
                document.getElementById("receivedorders").checked = false;
                document.getElementById("pending").checked = true;
                document.getElementById("delivery").checked = false;
                document.getElementById("return").checked = false;
                document.getElementById("totorders").checked = false;
                document.getElementById("partial").checked = false;
                document.getElementById("accident").checked = false;
                break;
        }
    }
    function viewReport()
    {

        if (document.getElementById("orderDiv").style.display == 'none')
        {
            document.getElementById("orderDiv").style.display = '';
            document.getElementById("closeReport").style.display = 'none';
            $("#reportToggle").css("background-color", "#efeeee");
            //document.getElementById("reportToggle").style.background-color = 'yellow';
        } else
        {
            document.getElementById("orderDiv").style.display = 'none';
            document.getElementById("closeReport").style.display = '';
            $("#reportToggle").css("background-color", "yellow");
        }
    }
</script>
        <div style="clear: both; margin-left: 1%">
        <p id="summarry" style="color: #16469E; font: 13px 'paperfly roman'"><u>Summary for <?php if ($startDate !='') { echo "<b>".date("d-m-Y", strtotime($startDate))."</b> To <b>".date("d-m-Y", strtotime($endDate))."</b>";} else {echo "all closed orders";}?></u></p>
            <table style="margin-bottom: 5px">
                <thead style="color: #16469E; font: 13px 'paperfly roman'">
                    <tr>
                        <th>Particulars</th>
                        <th>&nbsp;&nbsp;No of Orders&nbsp;&nbsp;</th>
                        <th style="width: 120px; text-align: right">&nbsp;&nbsp;Amount in BDT&nbsp;&nbsp;</th>
                        <th style="width: 120px; text-align: right">&nbsp;&nbsp;Collection in BDT&nbsp;&nbsp;</th>
                        <th>&nbsp;&nbsp;Show Detail</th>
                    </tr>
                </thead>
                <tbody style="color: #16469E; font: 12px 'paperfly roman'">
                    <tr id="ordersReceived">
                        <td>Total Orders Received</td>
                        <td style="text-align: right"><?php echo $totalRow;?>&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right"><?php echo num_to_format(round($totalPrice));?>&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right">&nbsp;&nbsp;</td>
                        <td style="text-align: center"><input id="receivedorders" type="checkbox" name="TotalOrders" onclick="return ordType(6)"></td>
                    </tr>
                    <tr id="allorders" style="background-color: yellow">
                        <td>Total Closed Orders</td>
                        <td style="text-align: right"><?php echo $totclose;?>&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right"><?php echo num_to_format(round($ClosePrice));?>&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right">&nbsp;&nbsp;</td>
                        <td style="text-align: center"><input id="totorders" type="checkbox" name="TotalOrders" checked onclick="return ordType(1)"></td>
                    </tr>
                    <tr id="cashorders">
                        <td>Successfull Delivery</td>
                        <td style="text-align: right"><?php echo $totalCash;?>&nbsp;&nbsp;</td>
                        <td style="text-align: right"><?php echo num_to_format(round($CashAmount));?>&nbsp;&nbsp;</td>
                        <td style="text-align: right"><?php echo num_to_format(round($CollectionAmount));?>&nbsp;&nbsp;</td>
                        <td style="text-align: center"><input id="delivery" type="checkbox" name="delivery" onclick="return ordType(2)"></td>
                    </tr>
                    <tr id="returnorders">
                        <td>Return</td>
                        <td style="text-align: right"><?php echo $totalReturn;?>&nbsp;&nbsp;</td>
                        <td style="text-align: right"><?php echo num_to_format(round($ReturnPrice));?>&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right">&nbsp;&nbsp;</td>
                        <td style="text-align: center"><input id="return" type="checkbox" name="return" onclick="return ordType(3)"></td>
                    </tr>
                    <tr id="partialorders">
                        <td>Partial</td>
                        <td style="text-align: right"><?php echo $totalpartial;?>&nbsp;&nbsp;</td>
                        <td style="text-align: right"><?php echo num_to_format(round($partialPrice));?>&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right">&nbsp;&nbsp;</td>
                        <td style="text-align: center"><input id="partial" type="checkbox" name="partial" onclick="return ordType(4)"></td>
                    </tr>
                    <tr id="accidentorders">
                        <td>Accidental</td>
                        <td style="text-align: right"><?php echo $totalAccident;?>&nbsp;&nbsp;</td>
                        <td style="text-align: right"><?php echo num_to_format(round($AccidentPrice));?>&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right">&nbsp;&nbsp;</td>
                        <td style="text-align: center"><input id="accident" type="checkbox" name="accident" onclick="return ordType(5)"></td>
                    </tr>
                    <tr id="pendingorders" style="color: red">
                        <td >Live Orders</td>
                        <td style="text-align: right"><?php echo ($totalRow - $totclose);?>&nbsp;&nbsp;</td>
                        <td style="text-align: right"><?php echo num_to_format(round(($totalPrice - $ClosePrice)));?>&nbsp;&nbsp;</td>
                        <td style="width: 80px; text-align: right">&nbsp;&nbsp;</td>
                        <td style="text-align: center"><input id="pending" type="checkbox" name="accident" onclick="return ordType(7)"></td>
                    </tr>
                </tbody>
            </table>
            <form name="trackerCri" action="" method="post" style="margin: auto">
                <input style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="startDate" value="<?php if ($startDate !='') {echo date("d-m-Y", strtotime($startDate));} else {echo date("d-m-Y");}?>" onfocus="displayCalendar(document.trackerCri.startDate,'dd-mm-yyyy',this)" required> 
                <span style="color: #16469E; font: 11px 'paperfly roman'">To</span>
                <input style="height: 25px; margin-top: 10px; width: 80px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'" type="text" name="endDate" value="<?php if ($endDate !='') {echo date("d-m-Y", strtotime($endDate));} else {echo date("d-m-Y");}?>" onfocus="displayCalendar(document.trackerCri.endDate,'dd-mm-yyyy',this)"required> 
                <input  style="height: 25px; width: 200px; margin: auto; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="text" name="searchText" placeholder="Search Criteria">
                <button style="height: 25px; border-style: solid; border-width: 1px; border-color: #0094ff" type="submit" class="btn btn-default" name="searchOrder">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
                <?php if ($user_type != 'Merchant'){?>
                <label style="display: inline; margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Drop Point&nbsp;&nbsp;<img id="img5" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 5)"/></label>
                &nbsp;&nbsp;&nbsp;&nbsp;<input id="showMerchant" type="checkbox" name="showMerchant" style="border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" <?php if ($showMerchant !='') {echo "checked";}?>>
                <select id="merchantList" name="merchantList" style="height: 25px; width: 150px; margin: auto; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'">
                    <?php 
                        $merchantSQL = "Select merchantCode, merchantName from tbl_merchant_info";
                        $merchantResult = mysqli_query($conn, $merchantSQL);
                        foreach ($merchantResult as $merchantRow){
                            ?> 
                            <option value="<?php echo $merchantRow['merchantCode'];?>" <?php if ($merchantRow['merchantCode'] == $merchantList) { echo "selected";}?>><?php echo $merchantRow['merchantName'];?></option>
                            <?php
                        }
                    ?>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;<input id="showDropPoint" type="checkbox" name="showDropPoint" style="border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" <?php if ($showDropPoint !='') {echo "checked";}?>>
                <select id="pointList" name="pointList" style="height: 25px; width: 150px; margin: auto; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'">
                    <?php 
                        $pointSQL = "Select pointCode, pointName from tbl_point_info";
                        $pointResult = mysqli_query($conn, $pointSQL);
                        foreach ($pointResult as $pointRow){
                            ?> 
                            <option value="<?php echo $pointRow['pointCode'];?>" <?php if ($pointRow['pointCode'] == $pointList) { echo "selected";}?>><?php echo $pointRow['pointCode']." - ".$pointRow['pointName'];?></option>
                            <?php
                        }
                    ?>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;<input id="dropExec" type="checkbox" name="dropExec" style="border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" <?php if ($dropExec !='') {echo "checked";}?>>
                <select id="dropExecList" name="dropExecList" style="height: 25px; width: 150px; margin: auto; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff; font: 11px 'paperfly roman'">
                    <?php 
                        $dropExecSQL = "SELECT empCode, empName FROM tbl_employee_info WHERE empCode in (select distinct empCode from tbl_employee_point)";
                        $dropExecResult = mysqli_query($conn, $dropExecSQL);
                        foreach ($dropExecResult as $dropExecRow){
                            ?> 
                            <option value="<?php echo $dropExecRow['empCode'];?>" <?php if ($dropExecRow['empCode'] == $dropExecList) { echo "selected";}?>><?php echo $dropExecRow['empName'];?></option>
                            <?php
                        }
                    ?>
                </select>
                <?php }?>
                <!--
                <button id="reportToggle" type="button" style="background-color: #efeeee; display: inline; height: 25px; border-style: solid; border-width: 1px; border-color: #0094ff; border-radius: 5%; font: 13px 'paperfly roman'" onclick="return viewReport()">Show Report</button>
                -->
            </form>
        </div>