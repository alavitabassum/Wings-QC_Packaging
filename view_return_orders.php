<?php
    include('header.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and retOrder = 'Y'"));
    if ($userPrivCheckRow['retOrder'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Return Orders Management</p>
            <div class="container-fluid" style="margin-left: 15px; font: 15px 'paperfly roman'">
                <div class="row">
                    <div class="col-sm-6">
                        <?php
                            $retCountSQL = "select count(1) as retCount from tbl_order_details where (Ret = 'Y' or partial = 'Y') and retcp1 is null and close is null";
                            $retCountResult = mysqli_query($conn, $retCountSQL) or die("Error: unable count retrun list".mysqli_error($conn));                            
                            $retCountRow = mysqli_fetch_array($retCountResult);
                        ?>
                        <label for="returnList" style="font-size: 130%; margin-top: 20px"><b>Return List Pending for CP&nbsp;(&nbsp;</b><b style="color: red"><?php echo $retCountRow['retCount'];?></b><b>&nbsp;)</b></label>
                        <hr>
                        <?php
                            $returnListSQL = "select orderid, tbl_merchant_info.merchantName, IF(Ret ='Y', DATE_FORMAT(RetTime, '%d-%M-%Y'),DATE_FORMAT(partialTime, '%d-%M-%Y')) as retDate from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where (Ret = 'Y' or partial = 'Y') and retcp1 is null and close is null order by orderDate";
                            $returnListResult = mysqli_query($conn, $returnListSQL) or die("Error: unable select retrun list".mysqli_error($conn));
                        ?>
                        <table id="returnTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Merchant Name</th>
                                    <th>Return Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($returnListResult as $returnListRow){
                                ?>
                                <tr>
                                    <td><?php echo $returnListRow['orderid'];?></td>
                                    <td><?php echo $returnListRow['merchantName'];?></td>
                                    <td><?php echo $returnListRow['retDate'];?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <label for="returnList" style="font-size: 130%; margin-top: 20px"><b>Monthly Return Order Stack</b></label>
                        <hr>
                        <?php
                            $returnCountSQL = "select count(1) as retOrders, MONTHNAME(RetTime) as RetMonth, YEAR(RetTime) as RetYear, v_orders.totOrders, v_orders.orderMonth, v_orders.orderYear from tbl_order_details left join (select count(1) as totOrders, MONTHNAME(orderDate) as orderMonth, YEAR(orderDate) as orderYear  from tbl_order_details group by MONTH(orderDate), YEAR(orderDate) order by YEAR(orderDate) desc, MONTH(orderDate) desc LIMIT 6) as v_orders on MONTHNAME(tbl_order_details.RetTime) = v_orders.orderMonth where Ret = 'Y' group by MONTH(RetTime), YEAR(RetTime) order by YEAR(RetTime) desc, MONTH(RetTime) desc LIMIT 6";
                            $returnCountResult = mysqli_query($conn, $returnCountSQL);      
                            $retArray = array();
                            while($row =mysqli_fetch_assoc($returnCountResult))
                            {
                                $retArray[] = $row;
                            }
                            $count = count($retArray);
                            $monthOrder = 6;
                            $strDpoints ='';
                            for ($c=0; $c < $count; $c++) {
                                $monthOrder = $monthOrder -1; 
                                if($monthOrder != 0){
                                    $strDpoints = $strDpoints.'{y: '.$retArray[$monthOrder]['retOrders'].', label: "'.$retArray[$monthOrder]['RetMonth'].' - '.round((($retArray[$monthOrder]['retOrders']/$retArray[$monthOrder]['totOrders'])*100),0).'%" },';     
                                } else {
                                    $strDpoints = $strDpoints.'{y: '.$retArray[$monthOrder]['retOrders'].', label: "'.$retArray[$monthOrder]['RetMonth'].' - '.round((($retArray[$monthOrder]['retOrders']/$retArray[$monthOrder]['totOrders'])*100),0).'%" }';
                                }
                            }
                            $strDpoints = "[".$strDpoints."]";     
                        ?>
                        <div id="chartContainer" style="height: 300px; width: 100%;">
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="cpReturnAcceptList" style="font-size: 130%; margin-top: 20px"><b>Return Challan List for Acceptance</b><span id="acceptanceCount" style="color:  red"></span></span></label>
                                <hr>
                                <form action="" method="post" enctype="multipart/form-data" style="margin-left: 10px">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <?php
                                                $merchantListSQL = "select distinct tbl_order_details.merchantCode, tbl_merchant_info.merchantName, retInv, DATE_FORMAT(retInvDate, '%d-%b-%Y') as retInvDate from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where cpRetStatus = 'S' order by retInvDate";
                                                $merchantListResult = mysqli_query($conn, $merchantListSQL);
                                                $challanCount = 0;
                                            ?>
                                            <select id="challanList" name="challanList" style="width:  100%">
                                                <?php foreach($merchantListResult as $merchantListRow){?>
                                                <option value="<?php echo $merchantListRow['retInv'];?>"><?php echo $merchantListRow['merchantName'].' : '.$merchantListRow['retInv'];?></option>
                                                <?php
                                                    $challanCount++; }
                                                    echo '<script>';
                                                    echo "$('#acceptanceCount').html('  (Pending Count: ".$challanCount.")');";
                                                    echo '</script>';
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="file" name="fileToUpload" id="fileToUpload" required>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px">
                                        <div class="col-sm-2">
                                            <label for="comments">Comments :</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" id="remarks" name="remarks" class="form-control">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="submit" class="btn btn-primary" name="accept" value="Accept">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-success" id="exportPendingList">Export Pending List</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <script>
                                $('#exportPendingList').click(function () {
                                    window.open("Export-Return_Invoice-List","_blank");
                                })
                            </script>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php
                                        if(isset($_POST['accept'])){
                                            $challanNo = $_POST['challanList'];
                                            $remarks = $_POST['remarks'];
                                            $remarks = mysqli_real_escape_string($conn, $remarks);
                                            $files = @$_FILES["fileToUpload"];
                                            $uploadOk = 1;
                                            $fullpath = $_SERVER[DOCUMENT_ROOT]."/returnOrder/".$files["name"];
                                            $fileType = pathinfo($fullpath,PATHINFO_EXTENSION);
                                            if($fileType != "jpg" && $fileType != "pdf" && $fileType != "jpeg" && $fileType != "gif" ) {
                                                echo "<div class='alert alert-danger' style='margin-left: 20px'>";
                                                    echo "<strong>Sorry, only JPG, JPEG, PDF & GIF files are allowed.</strong>"; 
                                                echo "</div>";
                                                $uploadOk = 0;
                                            } else {
                                                $uploadOk = 1;
                                            }
                                            //if($files["name"] != ''){
                                            //    $uploadOk = 1;
                                            //}
                                            if ($uploadOk == 0) {
                                                echo "<div class='alert alert-danger' style='margin-left: 20px'>";
                                                    echo "<strong>Sorry, your file was not uploaded.</strong>"; 
                                                echo "</div>";
                                            } else {
                                                $fileName = basename( $_FILES["fileToUpload"]["name"]);
                                                $fileDupCheckSQL = "select retInvFileName from tbl_order_details where retInvFileName = '$fileName'";
                                                if(!mysqli_query($conn, $fileDupCheckSQL)){
                                                    echo "<div class='alert alert-danger' style='margin-left: 20px'>";
                                                        echo "<strong>Error : checking duplicate file name".mysqli_error($conn)."</strong>"; 
                                                    echo "</div>";
                                                } else {
                                                    $fileDupCheckResult = mysqli_query($conn, $fileDupCheckSQL);
                                                    if (mysqli_num_rows($fileDupCheckResult) > 0) {
                                                        echo "<div class='alert alert-danger' style='margin-left: 20px'>";
                                                            echo "<strong>Error : ".$fileName." already exists</strong>"; 
                                                        echo "</div>";
                                                    } else {
                                                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $fullpath)) {
                                                            if (file_exists($fullpath)) {
                                                                $updateFileNameSQL = "update tbl_order_details set retInvFileName = '$fileName', cpRetStatus = 'A', retAcceptRem = '$remarks', retAcceptDate = NOW() + INTERVAL 6 HOUR, update_date = NOW() + INTERVAL 6 HOUR, updated_by = '$user_check' where retInv = '$challanNo'";
                                                                if(!mysqli_query($conn, $updateFileNameSQL)){
                                                                    echo "<div class='alert alert-danger' style='margin-left: 20px'>";
                                                                        echo "<strong>Error : unable to update file name".mysqli_error($conn)."</strong>"; 
                                                                    echo "</div>";                                                                    
                                                                } else {
                                                                    echo "<div class='alert alert-success' style='margin-left: 20px'>";
                                                                        echo "The file ".$fileName. " has been uploaded and Accepted. ";
                                                                    echo "</div>";
                                                                    echo "<meta http-equiv='refresh' content='2'>";
                                                                }
                                                            }
                                                        } else {
                                                            echo "<div class='alert alert-danger' style='margin-left: 20px'>";
                                                                echo "<strong>Sorry, there was an error uploading and accepting your file.</strong>"; 
                                                            echo "</div>";
                                                        }                                                        
                                                    }
                                                }
                                            }                                    
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="cpReturnAcceptList" style="font-size: 130%; margin-top: 20px"><b>Return Challan Log</b></label>
                                <hr>
                            </div>
                            
                        </div>
                        
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-2">
                                <label for="merchantList">Merchant List</label>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                    $merchantSQL = "select distinct tbl_order_details.merchantCode, tbl_merchant_info.merchantName from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where retInv is not null and cpRetStatus = 'A'";
                                    $merchantResult = mysqli_query($conn, $merchantSQL);
                                ?>
                                <select id="merchantList" data-placeholder="Select merchant......" style="width: 100%">
                                    <option></option>
                                    <?php foreach($merchantResult as $merchantRow){?>
                                    <option value="<?php echo $merchantRow['merchantCode'];?>"><?php echo $merchantRow['merchantName'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="button" id="showChallanList" class="btn btn-info" value="Show Challan List" onclick="showChallanList()">
                            </div>
                        </div>
                        <div class="row" style="margin-top:  10px">
                            <div class="col-sm-2">
                                <label for="challanToView">Challan List</label>
                            </div>
                            <div class="col-sm-10">
                                <select id="challanToView" data-placeholder="Select Challan No. ........." style="width:  100%">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-2">
                            </div>
                            <div class="col-sm-6" >
                                <input type="button" id="origChallan" class="btn btn-info" value="view Orginal Challan" onclick="veiwOrigChallan()">
                            </div>
                            <div class="col-sm-4">
                                <input type="button" id="acceptedChallan" class="btn btn-success" value="view Accepted Challan" onclick="veiwAcceptedChallan()">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="cpReturnAcceptList" style="font-size: 130%; margin-top: 20px"><b>Search Return Orders</b></label>
                                <hr>
                            </div>
                            
                        </div>
                        
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-3">
                                <label for="orderidMerRef">Order ID / Merchant Ref.</label>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="searchReturn" class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <input type="button" id="btnSearchReturn" class="btn btn-info" value="Search">
                            </div>
                        </div>
                        <hr>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-12">
                                <table id="searchList" class="table table-striped">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="cpReturnList" style="font-size: 130%; margin-top: 20px"><b>CP Return List for Challan</b><span id="cpMerchant" style="color: red"></span></label>
                        <hr>
                        <div class="row" >
                            <div class="col-sm-1">
                                <label for="merchantCode">Merchant</label>
                            </div>
                            <div class="col-sm-7">
                                <?php
                                    $cpMerchantSQL = "select distinct tbl_order_details.merchantCode, tbl_merchant_info.merchantName from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where retcp1 = 'Y' and cpRetStatus = 'R' ";
                                    $cpMerchantResult = mysqli_query($conn, $cpMerchantSQL);
                                ?>
                                <select id="cpMerchantList" style="width: 100%">
                                    <option></option>
                                    <?php
                                        $cpMerchantCount = 0;
                                        foreach($cpMerchantResult as $cpMerchantRow){
                                    ?>
                                    <option value="<?php echo $cpMerchantRow['merchantCode'];?>"><?php echo $cpMerchantRow['merchantName'];?></option>
                                    
                                    <?php
                                        $cpMerchantCount++; }
                                        echo '<script>';
                                        echo "$('#cpMerchant').html('  (Pending Count: ".$cpMerchantCount.")');";
                                        echo '</script>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="cpOrderList" class="btn btn-info" style="margin-left: -3px">Show</button>
                            </div>
                        </div>
                        <table id="cpReturnTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Merchant Ref.</th>
                                    <th>Merchant Name</th>
                                    <th>CP Return Date</th>
                                    <th><input type="checkbox" id="checkCpRet"><button id="generateChallan" class="btn btn-primary" style="margin-left: 10px" onclick="genrateChallan()">Challan</button></th>
                                </tr>
                            </thead>
                            <tbody id="cpReturnOrders">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                    $pendingCountSQL = "select distinct tbl_order_details.merchantCode, tbl_merchant_info.merchantName, retInv, DATE_FORMAT(retInvDate, '%d-%b-%Y') as retInvDate from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where cpRetStatus = 'S' order by retInvDate";
                                    $pendingCountResult = mysqli_query($conn, $pendingCountSQL);
                                    $cnt = 0;
                                    foreach($pendingCountResult as $pendingCountRow){
                                        $cnt++;
                                    }
                                ?>
                                <label for="challanList" style="font-size: 130%">Challan List ( <span style="color: red">Pending Count : <span id="pendingCount"><?php echo $cnt;?></span></span> )</label>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-sm-8">
                                <?php
                                    $merchantListSQL = "select distinct tbl_order_details.merchantCode, tbl_merchant_info.merchantName, retInv, DATE_FORMAT(retInvDate, '%d-%b-%Y') as retInvDate from tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where cpRetStatus = 'S' order by retInvDate";
                                    $merchantListResult = mysqli_query($conn, $merchantListSQL);
                                ?>
                                <select id="challanListInv" style="width:  100%">
                                    <?php foreach($merchantListResult as $merchantListRow){?>
                                    <option value="<?php echo $merchantListRow['retInv'];?>"><?php echo $merchantListRow['merchantName'].' : '.$merchantListRow['retInv'];?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="button" id="btnShowInvoice" class="btn btn-info" value="Show Challan" onclick="showInvoiceList()">
                            </div>
                            <div class="col-sm-2">
                                <input type="button" id="btnExportChallan" class="btn btn-success" value="Export Challan" onclick="exportChallan()">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="dialog" title="Alert" style="text-align: center">
            <p id="dialogAlert"></p>
        </div>
        <script type="text/javascript">
            window.onload = function () {
                var chart = new CanvasJS.Chart("chartContainer",
                {
                    title:{
                    text: "Monthly Return Orders"    
                    },
                    exportEnabled: true,
                    animationEnabled: true,
                    axisY: {
                    title: "No of Orders"
                    },
                    legend: {
                    verticalAlign: "bottom",
                    horizontalAlign: "center"
                    },
                    theme: "theme2",
                    data: [

                    {        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "Last 6 months count",
                    dataPoints: <?php echo $strDpoints;?>
                    }   
                    ]
                });

                chart.render();
            }
            function exportChallan(){
                var challanNo = $('#challanListInv').val();
                //alert(challanNo);
                window.open("Export-Return-Challan?xxCode=" + challanNo, "_blank");
            }
            function commonAlert(v)
            {
                $('#dialog').html('');
                $("#dialog").append("<p id=\"dialogAlert\"></p>");
                $('#dialogAlert').html(v);
                $("#dialog").append("<button type=\"button\" id=\"btnOK\" class=\"btn btn-default\" onclick=\"btnGenOK()\">&nbsp;OK&nbsp;&nbsp;</button>");
                $("#dialog").dialog({ modal: true });
            }
            function btnGenOK()
            {
                $('#dialog').dialog("close");
            }
            function genrateChallan()
            {
                var invVal = [];
                $("input[type=checkbox]").each(function ()
                {
                    if ($(this).is(":checked"))
                    {
                        invVal.push("'" + $(this).val() + "'");
                    }
                });
                var flag = 'cpReturnInvoice';
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: invVal.toString(),
                        flagreq: flag
                    },
                    success: function (response)
                    {
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            commonAlert('Challan Generated Successfully');
                            window.location.reload();
                        } else
                        {
                            commonAlert(response);
                        }
                    }
                });
            }
        $(window).load(function ()
        {
            $('select').select2();
        });
        function showInvoiceList(){
            var challanNo = $('#challanListInv').val();
            //alert(challanNo);
            window.open("Return-Challan?xxCode=" + challanNo, "_blank");
        }
        function showChallanList(){
            var merchantCode = $('#merchantList').val();
            $.ajax({
                type: 'post',
                url: 'toupdateorders',
                data: {
                    get_orderid: merchantCode,
                    flagreq: 'challanList'
                },
                success: function(response){
                    var str = response;
                    var n = str.search("Error");
                    if (n < 0)
                    {
                        $('#challanToView').html('');
                        $('#challanToView').append(response);
                    } else
                    {
                        commonAlert(response);
                    }
                }
            });
        }
        function veiwOrigChallan(){
            var challanNo = $('#challanToView').val();
            if(challanNo == ''){
                commonAlert('Challan No require');
            } else {
                window.open("Return-Challan?xxCode=" + challanNo, "_blank");
            }
        }
        function veiwAcceptedChallan(){
            var challanNo = $('#challanToView').val();
            if(challanNo == ''){
                commonAlert('Challan No require');
            } else {
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: 'na',
                        challanNo: challanNo,
                        flagreq: 'acceptedChallanList'
                    },
                    success: function(response){
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            window.open("/returnOrder/" + response, "_blank");
                        } else
                        {
                            commonAlert(response);
                        }
                    }
                })
            }            
        }
        $( "#cpOrderList" ).click(function() {
            if($('#cpMerchantList').val() == ''){
                commonAlert('Merchant Name require!!!');
            } else {
                var merchantCode = $('#cpMerchantList').val();
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: 'na',
                        merchantCode: merchantCode,
                        flagreq: 'cpReturnOrderList'
                    },
                    success: function(response){
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            $('#cpReturnOrders').html('');
                            $('#cpReturnOrders').append(response);
                        } else
                        {
                            commonAlert(response);
                        }
                    }
                })
            }
        });
        $('#checkCpRet').click(function(){
            var allChecked = $(this);
            $("#cpReturnTable input[type=checkbox]").each(function() {
                $(this).prop("checked", allChecked.is(':checked'));
            })
        });
        $('#btnSearchReturn').click(function(){
            if($('#searchReturn').val() == ''){
                commonAlert('Order ID or Merchant Ref. require');
            } else {
                var searchID = $('#searchReturn').val();
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: 'na',
                        searchID: searchID,
                        flagreq: 'searchReturn'
                    },
                    success: function(response){
                        var str = response;
                        var n = str.search("Error");
                        if (n < 0)
                        {
                            $('#searchList').html('');
                            $('#searchList').append(response);
                        } else
                        {
                            commonAlert(response);
                        }
                    }
                })
            }
        });
        </script>
    </body>
</html>
