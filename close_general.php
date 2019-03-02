        <?php
            include('header.php');
            include('num_format.php');
            $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where DropDP2 = 'Y' and (bank = 'Y' or Ret = 'Y' or retcp1 = 'Y') and (close != 'Y' or close is null) order by orderid, v_merchant_info.merchantName";
            $orderResult = mysqli_query($conn, $orderSQL);            
            $totalRow = 0;
            foreach ($orderResult as $orderRow){
                $totalRow++;
            }
            $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and close_order = 'Y'"));
            if ($userPrivCheckRow['close_order'] != 'Y'){
                exit();
            }
        ?>

        <div style="clear: both; margin-left: 1%">
        <p id="ordercount" style="color: #16469E; font: 13px 'paperfly roman'">Order Count : <span style="font: 13px 'paperfly roman'"><u><?php echo $totalRow;?></u></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="altermsg" style="font: 13px 'paperfly roman'"></span></p>
            <input id="usercode" type="hidden" value="<?php echo $user_code ;?>">
            <form action="" method="post">
            <table border="0" style="margin-left: 1px">
                <tr>
                    <td style="padding-left: 1px; width: 190px">
                        <label style="background-color: #16469E; color: white; font: 20px 'paperfly roman'">General Close</label>
                    </td>
                    <td><label style="margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Drop Point&nbsp;&nbsp;<img id="img5" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 5)"/></label></td>
                    <td><label style="margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Pick-up Merchant&nbsp;&nbsp;<img id="img6" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 6)"/></label></td>
                </tr>
            </table>
            </form>
            <input id="col2sort" type="hidden" name="col2sort" value="1">
            <div style="width: 1030px">
                <table style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <thead>
                    <tr style="font: 13px 'paperfly bold'">
                        <td style="width: 100px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Order ID&nbsp;&nbsp;<img id="img1" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 1)"/></label>
                        </td>
                        <td style="width: 100px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Merchant Ref.</label>
                        </td>
                        <td style="width: 90px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Price&nbsp;&nbsp;&nbsp;<img id="img2" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 2)"/></label>
                        </td>
                        <td style="width: 150px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Collection Amount</label>
                        </td>
                        <td style="width: 150px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Cash Comment</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
                            <label>Cash</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
                            <label>Ret</label>
                        </td>
                        <td style="width: 70px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
                            <label>Partial</label>
                        </td>
                        <td style="width: 60px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
                            <label>On Hold</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>DP 2</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Bank</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>CP1</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Close</label>
                        </td>
                    </tr>
                    </thead>
                </table>
            </div>
            <div style="overflow-y: auto; height: 400px; margin: 1px; border-bottom: 1px solid #16469E; width: 1037px">
                <table id="orderTracker" style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <tbody>
                <?php foreach ($orderResult as $orderRow){?>
                     <tr style="height: 30px; background-color: <?php if ($orderRow['CashAmt'] != $orderRow['packagePrice'] and $orderRow['Cash'] == 'Y'){echo "yellow";}?>; font: 11px 'paperfly roman'">
                        <td id = "1" style="width: 100px; <?php if ($orderRow['packagePrice'] > 2999) {?> border: 3px solid red <?php } else {?> border-right: 1px solid #16469E<?php }?>">
                            <label id="<?php echo $orderRow['orderid'];?>" style="<?php if ($orderRow['deliveryOption'] == 'express'){ ?>background-color: blue; color: white;<?php }?> <?php if ($orderRow['productSizeWeight'] == 'large' or $orderRow['productSizeWeight'] == 'special'){ ?>color: #FF0000; font: 10px 'paperfly bold'<?php } else {?> font: 10px 'paperfly roman'<?php }?>" title="Click for more detail of this order ID" class="js-open-modal" data-modal-id="popup" onclick="<?php echo "return showOrderDetail('".$orderRow['orderid']."')"?>"><?php echo $orderRow['orderid'];?></label>
                            <?php if ($orderRow['pickMerchantName'] !=''){
                                $pickFrom = $orderRow['pickMerchantName'];
                                $pickAddress = $orderRow['pickMerchantAddress']." , ".$orderRow['picThana'];
                                $pickPhone = $orderRow['pickupMerchantPhone'];
                            } else {
                                $pickFrom = $orderRow['merchantName'];
                                $pickAddress = $orderRow['address']." , ".$orderRow['thanaName'];
                                $pickPhone = $orderRow['contactNumber'];                                
                            }?>
                            <label id="<?php echo "orderinf".$orderRow['orderid'];?>" hidden><?php echo "Order ID : ".$orderRow['orderid']."\n ---Pick-up Detail---\nMerchant : ".$orderRow['merchantName']."\nPickup from : ".$pickFrom."\nPickup address : ".$pickAddress."\nphone : ".$pickPhone."\n ---Package Detail---\nPackage Option : ".$orderRow['productSizeWeight']."\nDelivery Option : ".$orderRow['deliveryOption']."\nProduct Breif : ".$orderRow['productBrief']."\nPackage Price : ".$orderRow['packagePrice']."\n ---Customer Details---\nName : ".$orderRow['custname']."\nAddress : ".$orderRow['custaddress']." , ".$orderRow['CustomerTh']."\nPhone : ".$orderRow['custphone'];?></label>
                        </td>
                        <td style="width: 100px; border-right: 1px solid #16469E">
                            <label style="font: 11px 'paperfly roman'"><?php echo $orderRow['merOrderRef'];?></label>
                        </td>
                        <td id = "2" style="width: 90px; border-right: 1px solid #16469E; text-align: right">
                            <label style="font: 11px 'paperfly roman'"><?php echo num_to_format(round($orderRow['packagePrice']));?>&nbsp;&nbsp;&nbsp;</label>
                        </td>
                        <td id = "3" style="width: 150px; border-right: 1px solid #16469E; text-align: right">
                            <label style="font: 11px 'paperfly roman'"><?php echo num_to_format(round($orderRow['CashAmt']));?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        </td>
                        <td id = "3" style="width: 150px; border-right: 1px solid #16469E; text-align: right">
                            <label style="font: 11px 'paperfly roman'"><?php echo $orderRow['cashComment'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 50px">
                            <?php if($orderRow['Cust'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){?>
                                <button id="Cash<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CashUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['Cash'] =='Y'){?>
                                    <button id="Cash<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="Cash<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 50px">
                            <label id="<?php echo "retmsg".$orderRow['orderid'];?>" hidden><?php echo $orderRow['retRem'];?></label>
                            <?php if($orderRow['Cust'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){?>
                                <button id="Ret<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000"></button>
                            <?php }else{?>
                                <?php if($orderRow['Ret'] =='Y'){?>
                                    <button id="Ret<?php echo $orderRow['orderid'];?>" style="background-color: red; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return showRetmsg('".$orderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <button id="Ret<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 70px">
                            <?php if($orderRow['Cust'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){?>
                                <button id="partial<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return partialUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['partial'] =='Y'){?>
                                    <button id="partial<?php echo $orderRow['orderid'];?>" style="background-color: #ff6a00; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return partialmsg('".$orderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <button id="partial<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 60px">
                            <?php if($orderRow['Cust'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y' and $orderRow['Rea'] !='Y'){?>
                                <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000"></button>
                            <?php }else{?>
                                <?php if($orderRow['Rea'] =='Y'){
                                    if ($orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){
                                ?>
                                        <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: yellow; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000"><?php echo substr($orderRow['onHoldSchedule'], 0, 2);?></button>
                                    <?php } else {?>
                                        <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: yellow; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($orderRow['onHoldSchedule'], 0, 2);?></button>
                                <?php }}else{?>
                                    <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                            <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if(($orderRow['Cash'] =='Y' and $orderRow['DropDP2'] !='Y') or ($orderRow['Ret'] =='Y' and $orderRow['DropDP2'] !='Y') or ($orderRow['partial'] =='Y' and $orderRow['DropDP2'] !='Y')){?>
                                <button id="DropDP2<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return DropDP2Update('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['DropDP2'] =='Y'){?>
                                    <button id="DropDP2<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="DropDP2<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if($orderRow['DropDP2'] =='Y' and $orderRow['bank'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){?>
                                <button id="bank<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return BankUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['bank'] =='Y'){?>
                                    <button id="bank<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="bank<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                         <td style="border-right: 1px solid #16469E; width: 50px">
                             <?php if ($orderRow['Ret'] == 'Y' and $orderRow['DropDP2'] == 'Y' and $orderRow['retcp1'] != 'Y'){?>
                                <button id="retcp1<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return retcp1Update('".$orderRow['orderid']."')"?>"></button>                                
                             <?php } else {?>
                                <button id="retcp1<?php echo $orderRow['orderid'];?>" style="background-color: <?php if($orderRow['retcp1'] == 'Y'){echo 'red';}else{echo '#dbd9d9';} ?>; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>                                
                             <?php }?>
                         </td>
                        <td style="width: 50px">
                            <?php if($orderRow['partial'] !='Y'){?>
                                <?php if(($orderRow['bank'] =='Y' and $orderRow['close'] !='Y') or ($orderRow['retcp1'] =='Y' and $orderRow['close'] !='Y')){?>
                                    <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CloseUpdate('".$orderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <?php if($orderRow['close'] =='Y'){?>
                                        <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                    <?php }else{?>
                                        <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                    <?php }?>
                                <?php }?>
                            <?php } else {?>
                                <?php if($orderRow['retcp1'] =='Y' and $orderRow['close'] !='Y'){?>
                                    <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CloseUpdate('".$orderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <?php if($orderRow['close'] =='Y'){?>
                                        <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                    <?php }else{?>
                                        <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </td>
                         <td id="5" style="display: none"><?php echo $orderRow['dropPointCode']?></td>
                         <td id="6" style="display: none"><?php if ($orderRow['pickMerchantName'] !=''){ echo $orderRow['pickMerchantName'];} else { echo $orderRow['merchantName'];}?></td>
                    </tr>
                <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    <script type="text/javascript">
        function showOrderDetail(ord)
        {
            //document.getElementById("orderdetailtext").innerHTML = document.getElementById("orderinf" + ord).innerHTML;
            alert(document.getElementById("orderinf" + ord).innerHTML);
        }
        function showRetmsg(ord)
        {
            alert(document.getElementById("retmsg" + ord).innerHTML);
        }
        function CloseUpdate(ord)
        {
            var val = '';
            $.ajax({
                type: 'post',
                url: 'closePriv',
                data: {
                    userCheck: val
                },
                success: function (response)
                {
                    if (response == 'empTrue')
                    {
                        //
                        var pickempid = 'NotRequired';
                        var orderid = ord;
                        var updateFlag = 'close';
                        //alert(pickempid +":"+orderid+":"+updateFlag);
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
                                    $("#altermsg").css("color", "green");
                                    $("#altermsg").text(ord + " Close order successful");
                                } else
                                {
                                    
                                    $("#altermsg").css("color", "red");
                                    $("#altermsg").text("Unable to update status");
                                }
                            }
                        });
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
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    break;
                case 2:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    break;
                case 5:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    break;
                case 6:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    break;
            }
        }

    </script>