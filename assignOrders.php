<?php
    include('session.php');
    include('header.php');
    include('config.php');
    include('num_format.php');
    $pickCount = 0;
    $dropCount = 0;
    $ordermarkSQL="select * from tbl_order_mark";
    $ordermarkResult =mysqli_query($conn, $ordermarkSQL);
    $ordermarkRow = mysqli_fetch_array($ordermarkResult);
    $assignorderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (dropPointEmp = '' or dropPointEmp is null) or (pickPointEmp = '' or pickPointEmp is null) and (close != 'Y' or close is null) order by dropPointCode, orderid, v_merchant_info.merchantName";
    $assignorderResult = mysqli_query($conn, $assignorderSQL);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and assign_order = 'Y'"));
    if ($userPrivCheckRow['assign_order'] != 'Y'){
        exit();
    }

?>
        <div style="margin-left: 15px; width: 98%; clear: both">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Assign Orders - One By One</p>
                <p style="margin-left: 20px; color: #16469E; font: 13px 'paperfly roman'">Pick-up Count: &nbsp;<span id="pickCount" style="color: blue; font-weight: 600"></span><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Drop Count:&nbsp;&nbsp;</span><span id="dropCount" style="color: blue; font-weight: 600"></span> </p>     
                <div class="radio" style="font: 14px 'paperfly roman'">
                    Pick-up Executive&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="op1" type="radio" name="orderType" value="pick" onclick="return showHide()" checked>&nbsp;&nbsp;&nbsp;
                    Drop Executive&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="op2" type="radio" name="orderType" value="drop" onclick="return showHide()">
                    <label style="margin: auto; color: #16469E; display: inline; font: 13px 'paperfly roman'">&nbsp;&nbsp; Drop Point&nbsp;&nbsp;<img id="img7" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 7)"/></label>
                    <p style="display: inline; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="altermsg" style="font: 13px 'paperfly roman'"></span></p>
                </div>
        </div>
        <div style="clear: both; margin-left: 1%">
            <input id="col2sort" type="hidden" name="col2sort" value="1">
            <div style="width: 1205px">
                <table style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <thead>
                    <tr style="font: 13px 'paperfly roman'">
                        <td style="width: 100px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Order ID&nbsp;&nbsp;<img id="img1" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 1)"/></label>
                        </td>
                        <td style="width: 120px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Merchant Ref.&nbsp;<img id="img2" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 2)"/></label>
                        </td>
                        <td style="width: 120px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Package Price&nbsp;<img id="img3" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 3)"/></label>
                        </td>
                        <td style="width: 180px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF" class="pickexec">
                            <label>Pick-up Merchant Name&nbsp;<img id="img4" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 4)"/></label>
                        </td>
                        <td style="width: 200px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF" class="pickexec">
                            <label>Pick-up Merchant Address&nbsp;</label>
                        </td>
                        <td style="width: 153px; border-bottom: 1px solid #16469E; background-color: #00AEEF" class="pickexec">
                            <label>Pick-up Exec&nbsp;&nbsp;&nbsp;&nbsp;<img id="img5" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 5)"/></label>
                        </td>
                        <td style="width: 15px;border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF" class="pickexec"></td>
                        <td style="width: 150px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF" class="dropexec">
                            <label>Customer Name&nbsp;</label>
                        </td>
                        <td style="width: 150px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF" class="dropexec">
                            <label>Customer Address&nbsp;</label>
                        </td>
                        <td style="width: 152px; border-bottom: 1px solid #16469E; background-color: #00AEEF" class="dropexec">
                            <label>Drop Exec&nbsp;&nbsp;&nbsp;&nbsp;<img id="img6" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 6)"/></label>
                        </td>
                        <td style="width: 15px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF" class="dropexec"></td>
                    </tr>
                    </thead>
                </table>
            </div>
            <div id="orderDiv" style="overflow-y: auto; height: 400px; margin: 1px; border-bottom: 1px solid #16469E; width: 1225px">
                <table id="orderTracker" style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <tbody>
                <?php foreach ($assignorderResult as $orderRow){?>
                     <tr <?php echo "id='".$orderRow['orderid']."' ";
                             if ($orderRow['pickPointEmp'] == '' or  $orderRow['pickPointEmp'] == NULL){
                                 echo "class='pick'";
                                 $pickCount++;
                             } else {
                                 echo "class='drop'";
                                 $dropCount++;
                             }
                         ?> style="background-color: <?php if ($orderRow['CashAmt'] != $orderRow['packagePrice'] and $orderRow['Cash'] == 'Y'){echo "yellow";}?>; font: 11px 'paperfly roman'">
                        <td id = "1" style="width: 100px; <?php if ($orderRow['packagePrice'] >= $ordermarkRow['price']) {?> border: 3px solid <?php echo "#".$ordermarkRow['priceColor'];  } else {?> border-right: 1px solid #16469E<?php }?>">
                            <label id="<?php echo $orderRow['orderid'];?>" style="<?php if ($orderRow['deliveryOption'] == 'express'){ ?>background-color: <?php echo "#".$ordermarkRow['delOptionBack']?>; color: <?php echo "#".$ordermarkRow['delOptionFont'].";"; }?> <?php if ($orderRow['productSizeWeight'] == 'large' or $orderRow['productSizeWeight'] == 'special'){ ?>color: <?php echo "#".$ordermarkRow['large'];?>; font: 10px 'paperfly bold'<?php } else {?> font: 10px 'paperfly roman'<?php }?>" title="Click for more detail of this order ID" class="js-open-modal" data-modal-id="popup" onclick="<?php echo "return showOrderDetail('".$orderRow['orderid']."')"?>"><?php echo $orderRow['orderid'];?></label>
                            <?php if ($orderRow['pickMerchantName'] !=''){
                                $pickFrom = $orderRow['pickMerchantName'];
                                $pickAddress = $orderRow['pickMerchantAddress']." , ".$orderRow['picThana'];
                                $pickPhone = $orderRow['pickupMerchantPhone'];
                            } else {
                                $pickFrom = $orderRow['merchantName'];
                                $pickAddress = $orderRow['address']." , ".$orderRow['thanaName'];
                                $pickPhone = $orderRow['contactNumber'];                                
                            }?>
                            <label id="<?php echo "orderinf".$orderRow['orderid'];?>" hidden><?php echo "Order ID : ".$orderRow['orderid']."\n\n ---Pick-up Detail---\nMerchant : ".$orderRow['merchantName']."\nPickup from : ".$pickFrom."\nPickup address : ".$pickAddress."\nphone : ".$pickPhone."\n\n ---Package Detail---\nPackage Option : ".$orderRow['productSizeWeight']."\nDelivery Option : ".$orderRow['deliveryOption']."\nProduct Breif : ".$orderRow['productBrief']."\nPackage Price : ".num_to_format(round($orderRow['packagePrice']))."\n\n ---Customer Details---\nName : ".$orderRow['custname']."\nAddress : ".$orderRow['custaddress']." , ".$orderRow['CustomerTh']."\nPhone : ".$orderRow['custphone'];?></label>
                        </td>
                        <td id = "2" style="width: 120px; border-right: 1px solid #16469E">
                            <label style="font: 11px 'paperfly roman'"><?php echo $orderRow['merOrderRef'];?></label>
                        </td>
                        <td id = "3" style="width: 120px; border-right: 1px solid #16469E; text-align: right">
                            <label style="font: 11px 'paperfly roman'"><?php echo num_to_format(round($orderRow['packagePrice']));?>&nbsp;&nbsp;&nbsp;</label>
                        </td>
                        <td id = "4" style="width: 180px; border-right: 1px solid #16469E" class="pickexec">
                            <label style="font: 11px 'paperfly roman'"><?php echo $orderRow['merchantName'];?></label>
                        </td>                                                 
                        <td style="width: 200px; border-right: 1px solid #16469E" class="pickexec">
                            <label style="font: 11px 'paperfly roman'"><?php echo $pickAddress.", ".$pickPhone;?></label>
                        </td>                                                 
                         <td id = "5" class="pickexec" style="width: 150px">
                            <?php if (substr($orderRow['pickPointEmp'],0,5) !='') {?>
                                <select id="pickemp<?php echo $orderRow['orderid'];?>" name="pickPointEmp" style="width: 150px">
                                    <?php
                                        $empCode = trim($orderRow['pickPointEmp']);
                                        $pickEmpSQL = "Select empCode, empName from tbl_employee_info where empCode='$empCode'";
                                        $pickEmpResult = mysqli_query($conn, $pickEmpSQL);
                                        $pickRow = mysqli_fetch_array($pickEmpResult);
                                    ?>
                                    <option value="<?php echo $pickRow['empCode'];?>"><?php echo $pickRow['empName'];?></option>
                                </select>
                            <?php } else {?>
                            <select id="pickemp<?php echo $orderRow['orderid'];?>" name="pickPointEmp" style="width: 150px">
                                <option></option>
                                <?php
                                    $pointCode =  $orderRow['pickPointCode'];
                                    $picpointSQL = "Select tbl_employee_point.empCode, tbl_employee_info.empName, tbl_employee_point.pointCode 
                                    from tbl_employee_point, tbl_employee_info where tbl_employee_point.empCode = tbl_employee_info.empCode 
                                    and tbl_employee_point.pointCode = '$pointCode' and tbl_employee_info.isActive = 'Y'";
                                    $picpointResult = mysqli_query($conn, $picpointSQL);
                                    foreach ($picpointResult as $picRow){
                                ?>
                                <option value="<?php echo $picRow['empCode'];?>"><?php echo $picRow['empName'];?></option>
                                <?php }?>
                            </select>
                            <?php }?>
                        </td>
                         <td style="width: 15px; margin-left: 1px; border-right: 1px solid #16469E" class="pickexec">
                             <?php if (substr($orderRow['pickPointEmp'],0,5) !='') {?>
                                <button id="assignPic<?php echo $orderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignPick" disabled></button>
                             <?php } else {?>
                                <button id="assignPic<?php echo $orderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignPick" onclick="<?php echo "return assignPicExec('".$orderRow['orderid']."')"?>"></button>
                             <?php }?>
                         </td>
                        <td style="width: 150px; border-right: 1px solid #16469E" class="dropexec">
                            <label style="font: 11px 'paperfly roman'"><?php echo $orderRow['custname'];?></label>
                        </td>     
                        <td style="width: 150px; border-right: 1px solid #16469E" class="dropexec">
                            <label style="font: 11px 'paperfly roman'"><?php echo $orderRow['custaddress']." , ".$orderRow['CustomerTh'].", ".$orderRow['custphone'];?></label>
                        </td>                                                 

                        <td id = "6" class="dropexec" style="width: 150px">
                            <?php if (substr($orderRow['dropPointEmp'],0,5) !='') {?>
                                <select id="dropemp<?php echo $orderRow['orderid'];?>" name="dropPointEmp" style="width: 150px">
                                    <?php
                                        $dropCode = trim($orderRow['dropPointEmp']);
                                        $dropEmpSQL = "Select empCode, empName from tbl_employee_info where empCode='$dropCode' and isActive = 'Y'";
                                        $dropEmpResult = mysqli_query($conn, $dropEmpSQL);
                                        $dropRow = mysqli_fetch_array($dropEmpResult);
                                    ?>
                                    <option value="<?php echo $dropRow['empCode'];?>"><?php echo $dropRow['empName'];?></option>
                                </select>
                            <?php } else {?>
                                <select id="dropemp<?php echo $orderRow['orderid'];?>" name="dropPointEmp" style="width: 150px">
                                    <option></option>
                                    <?php
                                        $dropCode =  $orderRow['dropPointCode'];
                                        $droppointSQL = "Select tbl_employee_point.empCode, tbl_employee_info.empName, tbl_employee_point.pointCode 
                                        from tbl_employee_point, tbl_employee_info where tbl_employee_point.empCode = tbl_employee_info.empCode 
                                        and tbl_employee_point.pointCode = '$dropCode' and tbl_employee_info.isActive = 'Y'";
                                        $droppointResult = mysqli_query($conn, $droppointSQL);
                                        foreach ($droppointResult as $dropRow){
                                    ?>
                                    <option value="<?php echo $dropRow['empCode'];?>"><?php echo $dropRow['empName'];?></option>
                                    <?php }?>
                                </select>
                            <?php }?>
                        </td>
                         <td style="width: 15px; border-right: 1px solid #16469E" class="dropexec">
                                <?php if (substr($orderRow['dropPointEmp'],0,5) !=''){?>
                                    <button id="assignDrop<?php echo $orderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignDrop" disabled></button>            
                                <?php } else {?>
                                    <button id="assignDrop<?php echo $orderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignDrop" onclick="<?php echo "return assignDropExec('".$orderRow['orderid']."')"?>"></button>
                                <?php }?>
                         </td>
                         <td id = "7" style="display: none"><?php echo $orderRow['dropPointCode']?></td>
                         <td id="8" style="display: none"><?php if ($orderRow['pickMerchantName'] !=''){ echo $orderRow['pickMerchantName'];} else { echo $orderRow['merchantName'];}?></td>
                    </tr>
                <?php }
                    echo "<script>
                               document.getElementById('pickCount').innerHTML = '$pickCount';
                               document.getElementById('dropCount').innerHTML = '$dropCount';
                          </script>  
                    ";
                 ?>
                    </tbody>
                </table>
            </div>
        </div>
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $('tr.drop').hide();
            $('td.dropexec').hide();
            $('#orderDiv').css('width', '925');
        });
        function showOrderDetail(ord)
        {
            //document.getElementById("orderdetailtext").innerHTML = document.getElementById("orderinf" + ord).innerHTML;
            alert(document.getElementById("orderinf" + ord).innerHTML);
        }
        function showHide()
        {
            if (document.getElementById("op2").checked == true)
            {
                $('tr.pick').hide();
                $('td.pickexec').hide();
                $('tr.drop').show();
                $('td.dropexec').show();
                $('#orderDiv').css('width', '825');
            } else
            {
                $('tr.pick').show();
                $('td.pickexec').show();
                $('tr.drop').hide();
                $('td.dropexec').hide();
                $('#orderDiv').css('width', '925');
            }
        }
        function assignPicExec(ord)
        {
            var pcount = $("#pickCount").text();
            var dcount = $("#dropCount").text();
            var val = '';
            $.ajax({
                type: 'post',
                url: 'usertovalid',
                data: {
                    userCheck: val
                },
                success: function (response)
                {
                    if (response == 'empTrue')
                    {
                        //
                        var pickempid = ($("#pickemp" + ord).val());
                        var orderid = ord;
                        var updateFlag = 'assignPic';
                        if (pickempid == '')
                        {
                            alert("Please select Pick-up Executive to assign.");
                        } else
                        {
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
                                        $("#altermsg").css("color", "green");
                                        $("#altermsg").text(ord + " Assigned Pick-up Executive successfully");
                                        $("#pick" + ord).attr("disabled", false);
                                        $("#" + ord).switchClass("pick", "drop", 1);
                                        $('tr.drop').hide();
                                        $("#pickCount").text(parseInt(pcount) - 1);
                                        $("#dropCount").text(parseInt(dcount) + 1);
                                    } else
                                    {
                                        $("#altermsg").css("color", "red");
                                        $("#altermsg").text("Unable to update status");
                                    }
                                }
                            });
                        }
                        //
                    } else
                    {
                        alert('You are Unauthorized!!');
                    }
                }
            });
        }

        function assignDropExec(ord)
        {
            var dcount = $("#dropCount").text();
            var val = '';
            $.ajax({
                type: 'post',
                url: 'usertovalid',
                data: {
                    userCheck: val
                },
                success: function (response)
                {
                    if (response == 'empTrue')
                    {
                        //
                        var pickempid = ($("#dropemp" + ord).val());
                        var orderid = ord;
                        var updateFlag = 'assignDrop';
                        if (pickempid == '')
                        {
                            alert("Please select Drop Executive to assign.");
                        } else
                        {
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
                                        $("#altermsg").css("color", "green");
                                        $("#altermsg").text(ord + " Assigned Drop Executive successfully");
                                        $("#" + ord).switchClass("drop", "none", 1);
                                        $('tr.none').hide();
                                        $("#dropCount").text(parseInt(dcount) - 1);
                                    } else
                                    {
                                        $("#altermsg").css("color", "red");
                                        $("#altermsg").text("Unable to update status");
                                    }
                                }
                            });
                        }
                        //
                    } else
                    {
                        alert('You are Unauthorized!!');
                    }
                }
            });
        }

        function sortTable(table, colid)
        {
            var sortOrder = document.getElementById("col2sort").value;

            tbody = table.find('tbody');

            tbody.find('tr').sort(function (a, b)
            {
                if (sortOrder == 1)
                {
                    $("#img" + colid).attr("src", "image/down.png");
                    $("#col2sort").val("2");
                    return $('#' + colid, a).text().localeCompare($('#' + colid, b).text()); // Ascending order
                } else
                {
                    $("#img" + colid).attr("src", "image/up.png");
                    $("#col2sort").val("1");
                    return $('#' + colid, b).text().localeCompare($('#' + colid, a).text()); // Descending order
                }

            }).appendTo(tbody);
            switch (colid)
            {
                case 1:
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    $("#img7").attr("src", "image/updown1.png");
                    break;
                case 2:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    $("#img7").attr("src", "image/updown1.png");
                    break;
                case 3:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    $("#img7").attr("src", "image/updown1.png");
                    break;
                case 4:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    $("#img7").attr("src", "image/updown1.png");
                    break;
                case 5:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    $("#img7").attr("src", "image/updown1.png");
                    break;
                case 6:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img7").attr("src", "image/updown1.png");
                    break;
                case 7:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    break;
            }
        }

    </script>
    </body>
</html>
