        <?php   
            $ordermarkSQL="select * from tbl_order_mark";
            $ordermarkResult =mysqli_query($conn, $ordermarkSQL);
            $ordermarkRow = mysqli_fetch_array($ordermarkResult);
            $rowPerPage = 50;
            $totalPage = ceil($total_rows/$rowPerPage);
        ?>

        <div style="clear: both; margin-left: 1%">
            <?php if ($total_rows > 0) {?>
            <div id="pagination_controls">
                <?php if ($searchText !=''){ echo '<p>Please press search again to retrieve all orders</p>';} else {?>
                <form action="" method="post">
                    <button type="submit" name="firstbtn" onclick="request_page(1)" <?php if ($pageNo == 1){echo "disabled";}?>>|&lt;</button>
                    <button type="submit" name="prevbtn" onclick="request_page(2)" <?php if ($pageNo == 1){echo "disabled";}?>>&lt;</button>
                    <p style="display: inline-block">Page <?php echo $pageNo;?> of <?php echo $totalPage ;?></p>
                    <button type="submit" name="nextbtn" onclick="request_page(3)" <?php if ($pageNo == $totalPage){echo "disabled";}?>>&gt;</button>
                    <button type="submit" name="lastbtn" onclick="request_page(4)" <?php if ($pageNo == $totalPage){echo "disabled";}?>>&gt;|</button>
                    <input id="pageCount" type="hidden" name="pageCount" value="<?php echo $pageNo;?>">
                    <input id="rowCount" type="hidden" name="rowCount" value="<?php echo $rowPerPage;?>">
                </form>
                <?php }?>
            </div>
            <?php }?>
            <div style="width: 650px">
                <table style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <tr style="font: 13px 'paperfly bold'">
                        <td style="width: 100px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Order ID</label>
                        </td>
                        <td style="width: 120px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Merchant Ref:</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Pick</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>DP 1</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>DP 2</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
                            <label>Cash</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
                            <label>Ret</label>
                        </td>
                        <td style="width: 60px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
                            <label>Partial</label>
                        </td>
                        <td style="width: 60px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
                            <label>On Hold</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Close</label>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="orderDiv" style="overflow-y: auto; height: 400px; margin: 1px; border-bottom: 1px solid #16469E; width: 655px">
                <table id="orderTracker" style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                <?php foreach ($orderResult as $orderRow){?>
                        <?php if ($orderRow['districtId'] == 0){ 
                                $findMer = $orderRow['merchantCode'];
                                $merDistrictSQL ="select districtid from tbl_merchant_info where merchantCode ='$findMer'";
                                $merDistrictResult = mysqli_query($conn, $merDistrictSQL);
                                $merDistrictRow = mysqli_fetch_array($merDistrictResult);
                                    if ($merDistrictRow['districtid'] != $orderRow['customerDistrict']){
                                        $diffDist = 1;
                                    } else {
                                        $diffDist = 0;
                                    }
                                    } else {
                                    if ($orderRow['districtId'] != $orderRow['customerDistrict']){
                                        $diffDist = 1;
                                    } else {
                                        $diffDist = 0;
                                    }
                                }
                                ?>
                     <tr <?php
                             if ($orderRow['Cash'] == 'Y' and $orderRow['close'] == 'Y'){
                                 echo "class='cash'";
                             }
                             if ($orderRow['Ret'] == 'Y' and $orderRow['close'] == 'Y'){
                                 echo "class='return'";
                             }
                             if ($orderRow['partial'] == 'Y' and $orderRow['close'] == 'Y'){
                                 echo "class='partial'";
                             }
                             if ($orderRow['accRem'] != ''){
                                 echo "class='accident'";
                             }
                             if ( $orderRow['close'] != 'Y'){
                                 echo "class='ud'";
                             }
                         ?> style="height: 35px; background-color: <?php if (intval($orderRow['CashAmt']) != round($orderRow['packagePrice']) and $orderRow['Cash'] == 'Y'){echo "yellow";} if ($orderRow['orderType'] == 'smartPick'){echo "#d6c7c2";}?>; <?php if ($diffDist ==1){?>border-top: 2px dotted red; border-left: 1px solid red; border-right: 1px solid red; border-bottom: 2px dotted red; <?php }?>  font: 11px 'paperfly roman'">
                        <td style="width: 100px; border-right: 1px solid #16469E">
                            <label id="<?php echo $orderRow['orderid'];?>" style="font: 10px 'paperfly roman'" title="Click for more detail of this order ID" class="js-open-modal" data-modal-id="popup" onclick="<?php echo "return showOrderDetail('".$orderRow['orderid']."')"?>"><?php echo $orderRow['orderid'];?></label>
                            <?php if ($orderRow['pickMerchantName'] !=''){
                                $pickFrom = $orderRow['pickMerchantName'];
                                $pickAddress = $orderRow['pickMerchantAddress']." , ".$orderRow['picThana'];
                                $pickPhone = $orderRow['pickupMerchantPhone'];
                            } else {
                                $pickFrom = $orderRow['merchantName'];
                                $pickAddress = $orderRow['address']." , ".$orderRow['thanaName'];
                                $pickPhone = $orderRow['contactNumber'];                                
                            }
                            if($orderRow['orderType'] == 'smartPick'){
                                $smpickFrom = $orderRow['pickMerchantName'];
                                $smpickAddress = $orderRow['pickMerchantAddress']." , ".$orderRow['picThana'];
                                $smpickPhone = $orderRow['pickupMerchantPhone'];
                                $pickFrom = $orderRow['merchantName'];
                                $pickAddress = $orderRow['address']." , ".$orderRow['thanaName'];
                                $pickPhone = $orderRow['contactNumber'];                                                                       
                            }                            
                            ?>
                            <label id="<?php echo "orderinf".$orderRow['orderid'];?>" hidden><?php if ($orderRow['orderType'] == 'smartPick') {echo "Order ID : ".$orderRow['orderid']."\nOrder Time : ".date('h:i:s a', strtotime($orderRow['creation_date']))."\n\n ---Pick-up Detail---\nPickup from : ".$smpickFrom."\nPickup address : ".$smpickAddress."\nphone : ".$smpickPhone."\n\n ---Package Detail---\nPackage Option : ".$orderRow['productSizeWeight']."\nDelivery Option : ".$orderRow['deliveryOption']."\nProduct Breif : ".$orderRow['productBrief']."\nPackage Price : ".num_to_format(round($orderRow['packagePrice']))."\n\n ---Merchant Details---\nName : ".$pickFrom."\nAddress : ".$pickAddress."\nPhone : ".$pickPhone;}else{ echo "<b>Order ID : </b>".$orderRow['orderid']."<br><b>Order Time : </b>".date('h:i:s a', strtotime($orderRow['creation_date']))."<br><br> <b>---Pick-up Detail---</b><br><b>Merchant : </b>".$orderRow['merchantName']."<br><b>Pickup from : </b>".$pickFrom."<br><b>Pickup address : </b>".$pickAddress."<br><b>phone : </b>".$pickPhone."<br><br> <b>---Package Detail---</b><br><b>Package Option : </b>".$orderRow['productSizeWeight']."<br><b>Delivery Option : </b>".$orderRow['deliveryOption']."<br><b>Product Breif : </b>".$orderRow['productBrief']."<br><b>Package Price : </b>".num_to_format(round($orderRow['packagePrice']))."<br><br> <b>---Customer Details---</b><br><b>Name : </b>".$orderRow['custname']."<br><b>Address : </b>".$orderRow['custaddress']." , ".$orderRow['CustomerTh']."<br><b>Phone : </b>".$orderRow['custphone'];}?></label>
                        </td>
                        <td style="width: 120px; border-right: 1px solid #16469E">
                            <label style="<?php  if ($orderRow['pickMerchantName'] != ''){echo "color:red;";} ?> font: 11px 'paperfly roman'"><?php echo $orderRow['merOrderRef'];?></label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E">
                            <?php if ($orderRow['pickPointEmp'] !='' and $orderRow['Pick'] == '') {?>
                                <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php if ($orderRow['orderType'] == 'smartPick'){ echo "return SmartPickUpdate('".$orderRow['orderid']."')"; } else { echo "return PickUpdate('".$orderRow['orderid']."')";}?>"></button>
                            <?php } else {?>
                                <?php if ($orderRow['Pick'] !='') {?>
                                    <?php if($orderRow['orderType'] == 'smartPick'){?>
                                        <label id="<?php echo "pickmsg".$orderRow['orderid'];?>" hidden><?php echo $orderRow['smartPickComment'];?></label>     
                                        <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return smartPickMsg('".$orderRow['orderid']."')"?>" ></button>
                                    <?php } else {?>
                                        <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled ></button>
                                    <?php }?>
                                <?php }else {?>
                                    <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E">
                            <?php if ($orderRow['Pick'] =='Y' and $orderRow['DP1'] !='Y') {?>
                                <button id="dp1<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return DP1Update('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['DP1'] =='Y'){?>
                                    <button id="dp1<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="dp1<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if($orderRow['Shtl'] =='Y' and $orderRow['DP2'] !='Y'){?>
                                <button id="DP2<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return DP2Update('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['DP2'] =='Y'){?>
                                    <button id="DP2<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="DP2<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 50px">
                            <label id="<?php echo "orgPrice".$orderRow['orderid'];?>" hidden><?php echo $orderRow['packagePrice'];?></label>
                            <label id="<?php echo "cashType".$orderRow['orderid'];?>" hidden><?php echo $orderRow['cashType'];?></label>
                            <label id="<?php echo "cashAmt".$orderRow['orderid'];?>" hidden><?php echo num_to_format(round($orderRow['CashAmt']));?></label>
                            <label id="<?php echo "cashComment".$orderRow['orderid'];?>" hidden><?php echo $orderRow['cashComment'];?></label>
                            <label id="<?php echo "cashTime".$orderRow['orderid'];?>" hidden><?php echo date("d-M-y H:i", strtotime($orderRow['CashTime']));?></label>
                            <?php if($orderRow['PickDrop'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){?>
                                <button id="Cash<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CashUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['Cash'] =='Y'){?>
                                    <button id="Cash<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return cashmsg('".$orderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <button id="Cash<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 50px">
                            <label id="<?php echo "retmsg1".$orderRow['orderid'];?>" hidden><?php  echo $orderRow['retReason'];?></label>
                            <label id="<?php echo "retmsg2".$orderRow['orderid'];?>" hidden><?php echo $orderRow['retRem'];?></label>
                            <label id="<?php echo "retmsg3".$orderRow['orderid'];?>" hidden><?php echo date("d-M-y H:i", strtotime($orderRow['RetTime']));?></label>
                            <?php if($orderRow['PickDrop'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){?>
                                <button id="Ret<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000"></button>
                            <?php }else{?>
                                <?php if($orderRow['Ret'] =='Y'){?>
                                    <button id="Ret<?php echo $orderRow['orderid'];?>" style="background-color: red; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return showRetmsg('".$orderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <button id="Ret<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 60px">
                            <label id="<?php echo "deliveredQty".$orderRow['orderid'];?>" hidden><?php echo $orderRow['partialReceive'];?></label>
                            <label id="<?php echo "returnedQty".$orderRow['orderid'];?>" hidden><?php echo $orderRow['partialReturn'];?></label>
                            <label id="<?php echo "partialReason".$orderRow['orderid'];?>" hidden><?php echo $orderRow['partialReason'];?></label>
                            <label id="<?php echo "partialTime".$orderRow['orderid'];?>" hidden><?php echo date("d-M-y H:i", strtotime($orderRow['partialTime']));?></label>
                            <?php if($orderRow['PickDrop'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){?>
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
                            <input id="onHold<?php echo $orderRow['orderid'];?>" style="display: none; width: 25px" type="text" name="onHoldSchedule" value="<?php echo $orderRow['onHoldSchedule'];?>">
                            <?php if($orderRow['PickDrop'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y' and $orderRow['Rea'] !='Y'){?>
                                <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return holdUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['Rea'] =='Y'){
                                    if ($orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){
                                ?>
                                        <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: yellow; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return holdUpdate('".$orderRow['orderid']."')"?>"><?php echo substr($orderRow['onHoldSchedule'], 8, 2);?></button>
                                    <?php } else {?>
                                        <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: yellow; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($orderRow['onHoldSchedule'], 8, 2);?></button>
                                <?php }}else{?>
                                    <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                            <?php }?>
                            <?php }?>
                        </td>
                        <td style="width: 50px">
                            <?php if($orderRow['DropDP2'] =='Y' and $orderRow['close'] !='Y'){?>
                                <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CloseUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['close'] =='Y'){?>
                                    <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                    </tr>
                <?php }?>
                </table>
            </div>
        </div>
        <!-- The Modal for smart Pick input and message -->
        <div id="messageModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div id="modal-content"  class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'" id="headerID">Alert</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <p id="msg1" style="color: #16469E; text-align: center; font: 13px 'paperfly roman'"></p>
                    <br><label id="cmt">Comments</label>
                    <input id="smartPick" type="text" name="smartPick" style="height: 25px; font: 13px 'paperfly roman'">
                    <input type="hidden" id="orderid" value="">
                    <p id="msg2" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <p id="msg3" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <p id="msg4" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <p id="msg5" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <p id="msg6" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <p id="msg7" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <button id="btnSave" class="btn btn-primary">Save</button>
                    <button id="btnCancel" class="btn btn-default">Cancel</button>
                </div>
            </div>
        </div>
    <script type="text/javascript">
        function smartPickMsg(ord)
        {
            var isMobile = false;
            var ua = navigator.userAgent;
            var checker = {
                iphone: ua.match(/(iPhone|iPod|iPad)/),
                blackberry: ua.match(/BlackBerry/),
                android: ua.match(/Android/)
            };
            if (checker.android)
            {
                isMobile = true;
            }
            else if (checker.iphone)
            {
                isMobile = true;
            }
            else if (checker.blackberry)
            {
                isMobile = true;
            }
            else
            {
                isMobile = false;
            }
            var screenWidth = screen.width;
            var modalWidth = $("#modal-content").width();
            var leftVal = (screenWidth - modalWidth)/2; 
            if (isMobile)
            {
                $("#modal-content").css("margin-left", leftVal);
            }
            var messageModal = document.getElementById('messageModal');
            var span = document.getElementsByClassName("close")[0];
            document.getElementById('smartPick').style.display = "none";
            document.getElementById('btnSave').style.display = "none";
            document.getElementById('btnCancel').style.display = "none";
            messageModal.style.display = "block";
            span.onclick = function ()
            {
                messageModal.style.display = "none";
            }
            $('#orderid').val(ord);
            $('#msg2').html($('#pickmsg' + ord).html());
        }


        function showRetmsg(ord)
        {
            var isMobile = false;
            var ua = navigator.userAgent;
            var checker = {
                iphone: ua.match(/(iPhone|iPod|iPad)/),
                blackberry: ua.match(/BlackBerry/),
                android: ua.match(/Android/)
            };
            if (checker.android)
            {
                isMobile = true;
            }
            else if (checker.iphone)
            {
                isMobile = true;
            }
            else if (checker.blackberry)
            {
                isMobile = true;
            }
            else
            {
                isMobile = false;
            }
            var screenWidth = screen.width;
            var modalWidth = $("#modal-content").width();
            var leftVal = (screenWidth - modalWidth)/2; 
            if (isMobile)
            {
                $("#modal-content").css("margin-left", leftVal);
            }
            //alert(document.getElementById("retmsg" + ord).innerHTML);
            var messageModal = document.getElementById('messageModal');
            var span = document.getElementsByClassName("close")[0];
            document.getElementById('smartPick').style.display = "none";
            document.getElementById('btnSave').style.display = "none";
            document.getElementById('btnCancel').style.display = "none";
            messageModal.style.display = "block";
            span.onclick = function ()
            {
                messageModal.style.display = "none";
            }
            $('#cmt').hide();
            $('#smartPick').hide();
            $('#msg1').html('<b>ORDER ID: </b>'+ord);
            if ($('#retmsg1' + ord).html() != ''){
                $('#msg2').html('<b>Return Reason: </b>'+$('#retmsg1' + ord).html());
            } else {
                 $('#msg2').html('');
            }
            if ($('#retmsg2' + ord).html() != ''){
                $('#msg3').html('<b>Return Remarks: </b>'+$('#retmsg2' + ord).html());
            } else {
                 $('#msg3').html('');
            }
            if ($('#retmsg3' + ord).html() != ''){
                $('#msg4').html('<b>Return Time: </b>'+$('#retmsg3' + ord).html());
            } else {
                 $('#msg4').html('');
            }
             $('#msg5').html('');
             $('#msg6').html('');     
            $('#msg7').html('');                        
        }
        function cashmsg(ord)
        {
            var isMobile = false;
            var ua = navigator.userAgent;
            var checker = {
                iphone: ua.match(/(iPhone|iPod|iPad)/),
                blackberry: ua.match(/BlackBerry/),
                android: ua.match(/Android/)
            };
            if (checker.android)
            {
                isMobile = true;
            }
            else if (checker.iphone)
            {
                isMobile = true;
            }
            else if (checker.blackberry)
            {
                isMobile = true;
            }
            else
            {
                isMobile = false;
            }
            var screenWidth = screen.width;
            var modalWidth = $("#modal-content").width();
            var leftVal = (screenWidth - modalWidth)/2; 
            if (isMobile)
            {
                $("#modal-content").css("margin-left", leftVal);
            }
            //alert(document.getElementById("retmsg" + ord).innerHTML);
            var messageModal = document.getElementById('messageModal');
            var span = document.getElementsByClassName("close")[0];
            document.getElementById('smartPick').style.display = "none";
            document.getElementById('btnSave').style.display = "none";
            document.getElementById('btnCancel').style.display = "none";
            messageModal.style.display = "block";
            span.onclick = function ()
            {
                messageModal.style.display = "none";
            }
            $('#cmt').hide();
            $('#smartPick').hide();
            $('#msg1').html('<b>ORDER ID: </b>'+ord);
            if ($('#cashType' + ord).html() != ''){
                $('#msg2').html('<b>Transaction Type: </b>'+$('#cashType' + ord).html());
            } else {
                 $('#msg2').html('');
            }
            if ($('#orgPrice' + ord).html() != ''){
                $('#msg3').html('<b>Package Price: </b>'+$('#orgPrice' + ord).html());
            } else {
                 $('#msg3').html('');
            }
            if ($('#cashAmt' + ord).html() != ''){
                $('#msg4').html('<b>Collection Amount: </b>'+$('#cashAmt' + ord).html());
            } else {
                 $('#msg4').html('');
            }
            if ($('#cashComment' + ord).html() != ''){
                $('#msg5').html('<b>Remarks: </b>'+$('#cashComment' + ord).html());
            } else {
                 $('#msg5').html('');
            } 
            if ($('#cashTime' + ord).html() != ''){
                $('#msg6').html('<b>Collection Time: </b>'+$('#cashTime' + ord).html());
            } else {
                 $('#msg6').html('');
            }
            $('#msg7').html('');
        }
        function partialmsg(ord)
        {
            var isMobile = false;
            var ua = navigator.userAgent;
            var checker = {
                iphone: ua.match(/(iPhone|iPod|iPad)/),
                blackberry: ua.match(/BlackBerry/),
                android: ua.match(/Android/)
            };
            if (checker.android)
            {
                isMobile = true;
            }
            else if (checker.iphone)
            {
                isMobile = true;
            }
            else if (checker.blackberry)
            {
                isMobile = true;
            }
            else
            {
                isMobile = false;
            }
            var screenWidth = screen.width;
            var modalWidth = $("#modal-content").width();
            var leftVal = (screenWidth - modalWidth)/2; 
            if (isMobile)
            {
                $("#modal-content").css("margin-left", leftVal);
            }
            //alert(document.getElementById("retmsg" + ord).innerHTML);
            var messageModal = document.getElementById('messageModal');
            var span = document.getElementsByClassName("close")[0];
            document.getElementById('smartPick').style.display = "none";
            document.getElementById('btnSave').style.display = "none";
            document.getElementById('btnCancel').style.display = "none";
            messageModal.style.display = "block";
            span.onclick = function ()
            {
                messageModal.style.display = "none";
            }
            $('#cmt').hide();
            $('#smartPick').hide();
            $('#msg1').html('<b>ORDER ID: </b>'+ord);
            if ($('#cashType' + ord).html() != ''){
                $('#msg2').html('<b>Transaction Type: </b>'+$('#cashType' + ord).html());
            } else {
                 $('#msg2').html('');
            }
            if ($('#orgPrice' + ord).html() != ''){
                $('#msg3').html('<b>Package Price: </b>'+$('#orgPrice' + ord).html()+'   ||   '+'<b>Collection Amount: </b>'+$('#cashAmt' + ord).html());
            } else {
                 $('#msg3').html('');
            }
            if ($('#deliveredQty' + ord).html() != ''){
                $('#msg4').html('<b>Delivered Qty: </b>'+$('#deliveredQty' + ord).html());
            } else {
                 $('#msg4').html('');
            }
            if ($('#returnedQty' + ord).html() != ''){
                $('#msg5').html('<b>Returned Qty: </b>'+$('#returnedQty' + ord).html());
            } else {
                 $('#msg5').html('');
            } 
            if ($('#partialReason' + ord).html() != ''){
                $('#msg6').html('<b>Reason: </b>'+$('#partialReason' + ord).html());
            } else {
                 $('#msg6').html('');
            }
            if ($('#partialTime' + ord).html() != ''){
                $('#msg7').html('<b>Partial Time: </b>'+$('#partialTime' + ord).html());
            } else {
                 $('#msg7').html('');
            }
        }
        function showOrderDetail(ord)
        {
            //document.getElementById("orderdetailtext").innerHTML = document.getElementById("orderinf" + ord).innerHTML;
            //alert(document.getElementById("orderinf" + ord).innerHTML);
            var orderDetail = document.getElementById("orderinf" + ord).innerHTML;
            var isMobile = false;
            var ua = navigator.userAgent;
            var checker = {
                iphone: ua.match(/(iPhone|iPod|iPad)/),
                blackberry: ua.match(/BlackBerry/),
                android: ua.match(/Android/)
            };
            if (checker.android)
            {
                isMobile = true;
            }
            else if (checker.iphone)
            {
                isMobile = true;
            }
            else if (checker.blackberry)
            {
                isMobile = true;
            }
            else
            {
                isMobile = false;
            }
            var screenWidth = screen.width;
            var modalWidth = $("#modal-content").width();
            var leftVal = (screenWidth - modalWidth)/2; 
            if (isMobile)
            {
                $("#messageModal").css("text-align", "left");
                $("#messageModal").css("vertical-align", "middle");
            }
            //alert(document.getElementById("retmsg" + ord).innerHTML);
            var messageModal = document.getElementById('messageModal');
            var span = document.getElementsByClassName("close")[0];
            document.getElementById('smartPick').style.display = "none";
            document.getElementById('btnSave').style.display = "none";
            document.getElementById('btnCancel').style.display = "none";
            messageModal.style.display = "block";
            span.onclick = function ()
            {
                messageModal.style.display = "none";
                $('#headerID').html('Alert');
            }
            $('#cmt').hide();
            $('#smartPick').hide();
            //$('#msg1').html('<b>ORDER ID: </b>'+ord);
            $('#msg2').html('');
            $('#msg3').html('');
            $('#msg4').html('');
            $('#msg5').html('');
            $('#msg6').html('');
            $('#msg7').html('');
            $('#msg1').css('text-align','left');
            $('#msg1').html(orderDetail);
            $('#headerID').html('Order Detail');
        }
        function request_page(pn)
        {
            switch (pn)
            {
                case 1:
                    var pageNoInput = document.getElementById("pageCount").value;
                    document.getElementById("pageCount").value = 1;
                    break;
                case 2:
                    var pageNoInput = document.getElementById("pageCount").value;
                    if (parseInt(pageNoInput) !=1)
                    {
                        document.getElementById("pageCount").value = parseInt(pageNoInput) - 1;                        
                    }
                    break;
                case 3:
                    var pageNoInput = document.getElementById("pageCount").value;
                    document.getElementById("pageCount").value = parseInt(pageNoInput) + 1;
                    break;
                case 4:
                    var pageNoInput = document.getElementById("pageCount").value;
                    document.getElementById("pageCount").value = <?php echo $totalPage; ?>;
                    break;
            }
        }
    </script>
    </body>
</html>
