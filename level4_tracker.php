        <?php   
            $ordermarkSQL="select * from tbl_order_mark";
            $ordermarkResult =mysqli_query($conn, $ordermarkSQL);
            $ordermarkRow = mysqli_fetch_array($ordermarkResult);
            $rowPerPage = 50;
            $totalPage = ceil($total_rows/$rowPerPage);
        ?>        

        <div style="clear: both; margin-left: 1%">
            <input id="col2sort" type="hidden" name="col2sort" value="1">
            <div style="width: 1750px">
            <p id="altermsg" style="font: 13px 'paperfly roman'"></p>
            <p style="color: #16469E; font: 15px 'paperfly roman'"> Pick Order Count: <?php echo $total_rows;?> <span style="margin-left: 650px"> Drop Order Count: <?php echo $dropOrdertotal_rows;?></span></p>
            <div style="width: 800px; float: left">
                <table style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <thead>
                    <tr style="font: 13px 'paperfly roman'">
                        <td style="width: 100px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Order ID</label>
                        </td>
                        <td style="width: 100px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Merchant Ref.</label>
                        </td>
                        <td style="width: 150px; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Pick-up Exec</label>
                        </td>
                        <td style="width: 15px;border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF"></td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Pick</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>DP 1</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Shuttle</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>CP1</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Shuttle</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>CP2</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Shuttle</label>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
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
                         ?> style="background-color: <?php if (intval($orderRow['CashAmt']) != round($orderRow['packagePrice']) and $orderRow['Cash'] == 'Y'){echo "yellow";} if ($orderRow['orderType'] == 'smartPick'){echo "#d6c7c2";}?>; <?php if ($diffDist ==1){?>border-top: 2px dotted red; border-left: 1px solid red; border-right: 1px solid red; border-bottom: 2px dotted red; <?php }?>  font: 11px 'paperfly roman'">
                        <td id = "1" style="width: 100px; <?php if ($orderRow['packagePrice'] >= $ordermarkRow['price']) {?> border: 3px solid <?php echo "#".$ordermarkRow['priceColor'];  } else {?> border-right: 1px solid #16469E<?php }?>">
                            <label id="<?php echo $orderRow['orderid'];?>" style="<?php if ($orderRow['deliveryOption'] == 'express'){ ?>background-color: <?php echo "#".$ordermarkRow['delOptionBack']?>; color: <?php echo "#".$ordermarkRow['delOptionFont'].";"; }?> <?php if ($orderRow['productSizeWeight'] == 'large' or $orderRow['productSizeWeight'] == 'special'){ ?>color: <?php echo "#".$ordermarkRow['large'];?>; font: 10px 'paperfly bold'<?php } else {?> font: 10px 'paperfly roman'<?php }?>" title="Click for more detail of this order ID" class="js-open-modal" data-modal-id="popup" onclick="<?php echo "return showOrderDetail('".$orderRow['orderid']."')"?>"><?php echo $orderRow['orderid'];?></label>                            <?php if ($orderRow['pickMerchantName'] !=''){
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
                        <td id = "2" style="width: 120px; border-right: 1px solid #16469E">
                            <label style="<?php  if ($orderRow['pickMerchantName'] != ''){echo "color:red;";} ?> font: 11px 'paperfly roman'"><?php echo $orderRow['merOrderRef'];?></label>
                            <label id="<?php echo "dropMerchantRef".$orderRow['orderid'];?>" style="<?php  if ($orderRow['pickMerchantName'] != ''){echo "color:red;";} ?> font: 11px 'paperfly roman'" hidden><?php echo $orderRow['merOrderRef'];?></label>
                            <label id="<?php echo "price1".$orderRow['orderid'];?>" hidden><?php echo num_to_format(round($orderRow['packagePrice']));?></label>
                            <label id="<?php echo "price2".$orderRow['orderid'];?>" hidden><?php echo $orderRow['packagePrice'];?></label>
                        </td>
                        <td id = "3" style="width: 150px">
                            <?php if (substr($orderRow['pickPointEmp'],0,5) !='') {?>
                                <select id="pickemp<?php echo $orderRow['orderid'];?>" name="pickPointEmp" style="width: 150px">
                                    <?php
                                        $empCode = trim($orderRow['pickPointEmp']);
                                        $pickEmpSQL = "Select empCode, empName from tbl_employee_info where empCode='$empCode' and isActive = 'Y'";
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
                         <td style="width: 15px; margin-left: 1px; border-right: 1px solid #16469E">
                             <?php if (substr($orderRow['pickPointEmp'],0,5) !='') {?>
                                <button id="assignPic<?php echo $orderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignPick" disabled></button>
                             <?php } else {?>
                                <button id="assignPic<?php echo $orderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignPick" onclick="<?php echo "return assignPicExec('".$orderRow['orderid']."')"?>"></button>
                             <?php }?>
                         </td>
                        <td style="width: 50px; border-right: 1px solid #16469E">
                            <?php if ($orderRow['pickPointEmp'] !='' and $orderRow['Pick'] == '') {?>
                                <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php if ($orderRow['orderType'] == 'smartPick'){ echo "return SmartPickUpdate('".$orderRow['orderid']."')"; } else { echo "return PickUpdate('".$orderRow['orderid']."')";}?>"></button>
                            <?php } else {?>
                                <?php if ($orderRow['Pick'] !='') {?>
                                    <?php if($orderRow['orderType'] == 'smartPick'){?>
                                        <label id="<?php echo "pickmsg".$orderRow['orderid'];?>" hidden><?php echo $orderRow['smartPickComment'];?></label>     
                                        <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return smartPickMsg('".$orderRow['orderid']."')"?>" ><?php echo substr($orderRow['PickTime'], 8,2)?></button>
                                    <?php } else {?>
                                        <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled ><?php echo substr($orderRow['PickTime'], 8,2)?></button>
                                    <?php }?>
                                <?php }else {?>
                                    <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($orderRow['PickTime'], 8,2)?></button>
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
                            <?php if($orderRow['DP1'] =='Y' and $orderRow['Shtl'] !='Y'){?>
                                <button id="Shtl<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" ></button>
                            <?php }else{?>
                                <?php if($orderRow['Shtl'] =='Y'){?>
                                    <button id="Shtl<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="Shtl<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if ($orderRow['destination'] == 'interDistrict'){?>
                                <?php if ($orderRow['Shtl'] == 'Y' and $orderRow['cp1'] != 'Y') {?>
                                    <button id="cp1<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return cp1Update('".$orderRow['orderid']."')"?>"></button>                                
                                <?php } else {?>
                                    <button id="cp1<?php echo $orderRow['orderid'];?>" style="background-color: <?php if($orderRow['cp1'] == 'Y'){echo 'green';}else{echo '#dbd9d9';} ?>; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>                                
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if ($orderRow['destination'] == 'interDistrict'){?>
                                <?php if ($orderRow['cp1'] == 'Y' and $orderRow['cp1Shuttle'] != 'Y') {?>
                                    <button id="cp1Shuttle<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return cp1ShuttleUpdate('".$orderRow['orderid']."')"?>"></button>                                
                                <?php } else {?>
                                    <button id="cp1Shuttle<?php echo $orderRow['orderid'];?>" style="background-color: <?php if($orderRow['cp1Shuttle'] == 'Y'){echo 'green';}else{echo '#dbd9d9';} ?>; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>                                
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if ($orderRow['destination'] == 'interDistrict'){?>
                                <?php if ($orderRow['cp1Shuttle'] == 'Y' and $orderRow['cp2'] != 'Y') {?>
                                    <button id="cp2<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return cp2Update('".$orderRow['orderid']."')"?>"></button>                                
                                <?php } else {?>
                                    <button id="cp2<?php echo $orderRow['orderid'];?>" style="background-color: <?php if($orderRow['cp2'] == 'Y'){echo 'green';}else{echo '#dbd9d9';} ?>; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>                                
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if ($orderRow['destination'] == 'interDistrict'){?>
                                <?php if ($orderRow['cp2'] == 'Y' and $orderRow['cp2Shuttle'] != 'Y') {?>
                                    <button id="cp2Shuttle<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return cp2ShuttleUpdate('".$orderRow['orderid']."')"?>"></button>                                
                                <?php } else {?>
                                    <button id="cp2Shuttle<?php echo $orderRow['orderid'];?>" style="background-color: <?php if($orderRow['cp2Shuttle'] == 'Y'){echo 'green';}else{echo '#dbd9d9';} ?>; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>                                
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
            
            <div style="width: 850px; float: left">
                <table style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <thead>
                    <tr style="font: 13px 'paperfly roman'">
                        <td style="width: 110px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Order ID</label>
                        </td>
                        <td style="width: 100px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Merchant Ref.</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>DP 2</label>
                        </td>
                        <td style="width: 150px; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Drop Exec</label>
                        </td>
                        <td style="width: 15px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF"></td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Pick</label>
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
                        <td style="width: 65px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #dbd9d9">
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
                    </tr>
                    </thead>
                    <tbody>
                <?php foreach ($dropOrderResult as $dropOrderRow){?>
                        <?php if ($dropOrderRow['districtId'] == 0){ 
                                $findMer = $dropOrderRow['merchantCode'];
                                $merDistrictSQL ="select districtid from tbl_merchant_info where merchantCode ='$findMer'";
                                $merDistrictResult = mysqli_query($conn, $merDistrictSQL);
                                $merDistrictRow = mysqli_fetch_array($merDistrictResult);
                                    if ($merDistrictRow['districtid'] != $dropOrderRow['customerDistrict']){
                                        $diffDist = 1;
                                    } else {
                                        $diffDist = 0;
                                    }
                                    } else {
                                    if ($dropOrderRow['districtId'] != $dropOrderRow['customerDistrict']){
                                        $diffDist = 1;
                                    } else {
                                        $diffDist = 0;
                                    }
                                }
                                ?>
                     <tr <?php
                             if ($dropOrderRow['Cash'] == 'Y' and $dropOrderRow['close'] == 'Y'){
                                 echo "class='cash'";
                             }
                             if ($dropOrderRow['Ret'] == 'Y' and $dropOrderRow['close'] == 'Y'){
                                 echo "class='return'";
                             }
                             if ($dropOrderRow['partial'] == 'Y' and $dropOrderRow['close'] == 'Y'){
                                 echo "class='partial'";
                             }
                             if ($dropOrderRow['accRem'] != ''){
                                 echo "class='accident'";
                             }
                             if ( $dropOrderRow['close'] != 'Y'){
                                 echo "class='ud'";
                             }
                         ?> style="background-color: <?php if (intval($dropOrderRow['CashAmt']) != round($dropOrderRow['packagePrice']) and $dropOrderRow['Cash'] == 'Y'){echo "yellow";} if ($dropOrderRow['orderType'] == 'smartPick'){echo "#d6c7c2";}?>; <?php if ($diffDist ==1){?>border-top: 2px dotted red; border-left: 1px solid red; border-right: 1px solid red; border-bottom: 2px dotted red; <?php }?>  font: 11px 'paperfly roman'">
                        <td id = "1" style="width: 110px; <?php if ($dropOrderRow['packagePrice'] >= $ordermarkRow['price']) {?> border: 3px solid <?php echo "#".$ordermarkRow['priceColor'];  } else {?> border-right: 1px solid #16469E<?php }?>">
                            <label id="<?php echo $dropOrderRow['orderid'];?>" style="<?php if ($dropOrderRow['deliveryOption'] == 'express'){ ?>background-color: <?php echo "#".$ordermarkRow['delOptionBack']?>; color: <?php echo "#".$ordermarkRow['delOptionFont'].";"; }?> <?php if ($dropOrderRow['productSizeWeight'] == 'large' or $dropOrderRow['productSizeWeight'] == 'special'){ ?>color: <?php echo "#".$ordermarkRow['large'];?>; font: 10px 'paperfly bold'<?php } else {?> font: 10px 'paperfly roman'<?php }?>" title="Click for more detail of this order ID" class="js-open-modal" data-modal-id="popup" onclick="<?php echo "return showOrderDetail('".$dropOrderRow['orderid']."')"?>"><?php echo $dropOrderRow['orderid'];?></label>                            <?php if ($dropOrderRow['pickMerchantName'] !=''){
                                $pickFrom = $dropOrderRow['pickMerchantName'];
                                $pickAddress = $dropOrderRow['pickMerchantAddress']." , ".$dropOrderRow['picThana'];
                                $pickPhone = $dropOrderRow['pickupMerchantPhone'];
                            } else {
                                $pickFrom = $dropOrderRow['merchantName'];
                                $pickAddress = $dropOrderRow['address']." , ".$dropOrderRow['thanaName'];
                                $pickPhone = $dropOrderRow['contactNumber'];                                
                            }
                            if($dropOrderRow['orderType'] == 'smartPick'){
                                $smpickFrom = $dropOrderRow['pickMerchantName'];
                                $smpickAddress = $dropOrderRow['pickMerchantAddress']." , ".$dropOrderRow['picThana'];
                                $smpickPhone = $dropOrderRow['pickupMerchantPhone'];
                                $pickFrom = $dropOrderRow['merchantName'];
                                $pickAddress = $dropOrderRow['address']." , ".$dropOrderRow['thanaName'];
                                $pickPhone = $dropOrderRow['contactNumber'];                                                                       
                            }                            
                            ?>
                            <label id="<?php echo "orderinf".$dropOrderRow['orderid'];?>" hidden><?php if ($dropOrderRow['orderType'] == 'smartPick') {echo "Order ID : ".$dropOrderRow['orderid']."\nOrder Time : ".date('h:i:s a', strtotime($dropOrderRow['creation_date']))."\n\n ---Pick-up Detail---\nPickup from : ".$smpickFrom."\nPickup address : ".$smpickAddress."\nphone : ".$smpickPhone."\n\n ---Package Detail---\nPackage Option : ".$dropOrderRow['productSizeWeight']."\nDelivery Option : ".$dropOrderRow['deliveryOption']."\nProduct Breif : ".$dropOrderRow['productBrief']."\nPackage Price : ".num_to_format(round($dropOrderRow['packagePrice']))."\n\n ---Merchant Details---\nName : ".$pickFrom."\nAddress : ".$pickAddress."\nPhone : ".$pickPhone;}else{ echo "<b>Order ID : </b>".$dropOrderRow['orderid']."<br><b>Order Time : </b>".date('h:i:s a', strtotime($dropOrderRow['creation_date']))."<br><br> <b>---Pick-up Detail---</b><br><b>Merchant : </b>".$dropOrderRow['merchantName']."<br><b>Pickup from : </b>".$pickFrom."<br><b>Pickup address : </b>".$pickAddress."<br><b>phone : </b>".$pickPhone."<br><br> <b>---Package Detail---</b><br><b>Package Option : </b>".$dropOrderRow['productSizeWeight']."<br><b>Delivery Option : </b>".$dropOrderRow['deliveryOption']."<br><b>Product Breif : </b>".$dropOrderRow['productBrief']."<br><b>Package Price : </b>".num_to_format(round($dropOrderRow['packagePrice']))."<br><br> <b>---Customer Details---</b><br><b>Name : </b>".$dropOrderRow['custname']."<br><b>Address : </b>".$dropOrderRow['custaddress']." , ".$dropOrderRow['CustomerTh']."<br><b>Phone : </b>".$dropOrderRow['custphone'];}?></label>
                        </td>
                        <td id = "2" style="width: 120px; border-right: 1px solid #16469E">
                            <label style="<?php  if ($dropOrderRow['pickMerchantName'] != ''){echo "color:red;";} ?> font: 11px 'paperfly roman'"><?php echo $dropOrderRow['merOrderRef'];?></label>
                            <label id="<?php echo "dropMerchantRef".$dropOrderRow['orderid'];?>" style="<?php  if ($dropOrderRow['pickMerchantName'] != ''){echo "color:red;";} ?> font: 11px 'paperfly roman'" hidden><?php echo $dropOrderRow['merOrderRef'];?></label>
                            <label id="<?php echo "price1".$dropOrderRow['orderid'];?>" hidden><?php echo num_to_format(round($dropOrderRow['packagePrice']));?></label>
                            <label id="<?php echo "price2".$dropOrderRow['orderid'];?>" hidden><?php echo $dropOrderRow['packagePrice'];?></label>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if ($dropOrderRow['destination'] == 'interDistrict'){?>
                                <?php if($dropOrderRow['cp2Shuttle'] =='Y' and $dropOrderRow['DP2'] !='Y'){?>
                                    <button id="DP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return DP2Update('".$dropOrderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <?php if($dropOrderRow['DP2'] =='Y'){?>
                                        <button id="DP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['DP2Time'], 8,2)?></button>
                                    <?php }else{?>
                                        <button id="DP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['DP2Time'], 8,2)?></button>
                                    <?php }?>
                                <?php }?>
                            <?php } else {?>
                                <?php if($dropOrderRow['Shtl'] =='Y' and $dropOrderRow['DP2'] !='Y'){?>
                                    <button id="DP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return DP2Update('".$dropOrderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <?php if($dropOrderRow['DP2'] =='Y'){?>
                                        <button id="DP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['DP2Time'], 8,2)?></button>
                                    <?php }else{?>
                                        <button id="DP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['DP2Time'], 8,2)?></button>
                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td id = "4" style="width: 150px">
                            <?php if (substr($dropOrderRow['dropPointEmp'],0,5) !='') {?>
                                <select id="dropemp<?php echo $dropOrderRow['orderid'];?>" name="dropPointEmp" style="width: 150px">
                                    <?php
                                        $dropCode = trim($dropOrderRow['dropPointEmp']);
                                        $dropEmpSQL = "Select empCode, empName from tbl_employee_info where empCode='$dropCode' and isActive = 'Y'";
                                        $dropEmpResult = mysqli_query($conn, $dropEmpSQL);
                                        $dropRow = mysqli_fetch_array($dropEmpResult);
                                    ?>
                                    <option value="<?php echo $dropRow['empCode'];?>"><?php echo $dropRow['empName'];?></option>
                                    <?php
                                        $dropCode =  $dropOrderRow['dropPointCode'];
                                        $droppointSQL = "Select tbl_employee_point.empCode, tbl_employee_info.empName, tbl_employee_point.pointCode 
                                        from tbl_employee_point, tbl_employee_info where tbl_employee_point.empCode = tbl_employee_info.empCode 
                                        and tbl_employee_point.pointCode = '$dropCode' and tbl_employee_info.isActive = 'Y'";
                                        $droppointResult = mysqli_query($conn, $droppointSQL);
                                        foreach ($droppointResult as $dropRow){
                                    ?>
                                    <option value="<?php echo $dropRow['empCode'];?>"><?php echo $dropRow['empName'];?></option>
                                    <?php }?>
                                </select>
                            <?php } else {?>
                                <select id="dropemp<?php echo $dropOrderRow['orderid'];?>" name="dropPointEmp" style="width: 150px">
                                    <option></option>
                                    <?php
                                        $dropCode =  $dropOrderRow['dropPointCode'];
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
                         <td style="width: 15px; border-right: 1px solid #16469E">
                                <?php if (substr($dropOrderRow['dropPointEmp'],0,5) !=''){?>
                                    <button id="assignDrop<?php echo $dropOrderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignDrop" onclick="<?php echo "return assignDropExec('".$dropOrderRow['orderid']."')"?>"></button>            
                                <?php } else {?>
                                    <button id="assignDrop<?php echo $dropOrderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignDrop" onclick="<?php echo "return assignDropExec('".$dropOrderRow['orderid']."')"?>"></button>
                                <?php }?>
                         </td>
                        <td style="width: 50px; border-right: 1px solid #16469E">
                            <?php if ($dropOrderRow['dropPointEmp'] !='' and $dropOrderRow['PickDrop'] != 'Y' and $dropOrderRow['DP2'] =='Y') {?>
                                <button id="PickDrop<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return PickDropUpdate('".$dropOrderRow['orderid']."')"?>"></button>
                            <?php } else {?>
                                <?php if ($dropOrderRow['PickDrop'] =='Y') {?>
                                    <button id="PickDrop<?php echo $dropOrderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else {?>
                                    <button id="PickDrop<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 50px">
                            <label id="<?php echo "orgPrice".$dropOrderRow['orderid'];?>" hidden><?php echo $dropOrderRow['packagePrice'];?></label>
                            <label id="<?php echo "cashType".$dropOrderRow['orderid'];?>" hidden><?php echo $dropOrderRow['cashType'];?></label>
                            <label id="<?php echo "cashAmt".$dropOrderRow['orderid'];?>" hidden><?php echo num_to_format(round($dropOrderRow['CashAmt']));?></label>
                            <label id="<?php echo "cashComment".$dropOrderRow['orderid'];?>" hidden><?php echo $dropOrderRow['cashComment'];?></label>
                            <label id="<?php echo "cashTime".$dropOrderRow['orderid'];?>" hidden><?php echo date("d-M-y H:i", strtotime($dropOrderRow['CashTime']));?></label>
                            <?php if($dropOrderRow['PickDrop'] =='Y' and $dropOrderRow['Cash'] !='Y' and $dropOrderRow['Ret'] !='Y' and $dropOrderRow['partial'] !='Y'){?>
                                <button id="Cash<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CashUpdate('".$dropOrderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($dropOrderRow['Cash'] =='Y'){?>
                                    <button id="Cash<?php echo $dropOrderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return cashmsg('".$dropOrderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <button id="Cash<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 50px">
                            <label id="<?php echo "retmsg1".$dropOrderRow['orderid'];?>" hidden><?php  echo $dropOrderRow['retReason'];?></label>
                            <label id="<?php echo "retmsg2".$dropOrderRow['orderid'];?>" hidden><?php echo $dropOrderRow['retRem'];?></label>
                            <label id="<?php echo "retmsg3".$dropOrderRow['orderid'];?>" hidden><?php echo date("d-M-y H:i", strtotime($dropOrderRow['RetTime']));?></label>
                            <?php if($dropOrderRow['PickDrop'] =='Y' and $dropOrderRow['Cash'] !='Y' and $dropOrderRow['Ret'] !='Y' and $dropOrderRow['partial'] !='Y'){?>
                                <button id="Ret<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return RetUpdate('".$dropOrderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($dropOrderRow['Ret'] =='Y'){?>
                                    <button id="Ret<?php echo $dropOrderRow['orderid'];?>" style="background-color: red; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return showRetmsg('".$dropOrderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <button id="Ret<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 60px">
                            <label id="<?php echo "deliveredQty".$dropOrderRow['orderid'];?>" hidden><?php echo $dropOrderRow['partialReceive'];?></label>
                            <label id="<?php echo "returnedQty".$dropOrderRow['orderid'];?>" hidden><?php echo $dropOrderRow['partialReturn'];?></label>
                            <label id="<?php echo "partialReason".$dropOrderRow['orderid'];?>" hidden><?php echo $dropOrderRow['partialReason'];?></label>
                            <label id="<?php echo "partialTime".$dropOrderRow['orderid'];?>" hidden><?php echo date("d-M-y H:i", strtotime($dropOrderRow['partialTime']));?></label>
                            <?php if($dropOrderRow['PickDrop'] =='Y' and $dropOrderRow['Cash'] !='Y' and $dropOrderRow['Ret'] !='Y' and $dropOrderRow['partial'] !='Y'){?>
                                <button id="partial<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return partialUpdate('".$dropOrderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($dropOrderRow['partial'] =='Y'){?>
                                    <button id="partial<?php echo $dropOrderRow['orderid'];?>" style="background-color: #ff6a00; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return partialmsg('".$dropOrderRow['orderid']."')"?>"></button>
                                <?php }else{?>
                                    <button id="partial<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 60px">
                            <input id="onHold<?php echo $dropOrderRow['orderid'];?>" style="display: none; width: 25px" type="text" name="onHoldSchedule" value="<?php echo $dropOrderRow['onHoldSchedule'];?>">
                            <?php if($dropOrderRow['PickDrop'] =='Y' and $dropOrderRow['Cash'] !='Y' and $dropOrderRow['Ret'] !='Y' and $dropOrderRow['partial'] !='Y' and $dropOrderRow['Rea'] !='Y'){?>
                                <button id="Rea<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return holdUpdate('".$dropOrderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($dropOrderRow['Rea'] =='Y'){
                                    if ($dropOrderRow['Cash'] !='Y' and $dropOrderRow['Ret'] !='Y' and $dropOrderRow['partial'] !='Y'){
                                ?>
                                        <button id="Rea<?php echo $dropOrderRow['orderid'];?>" style="background-color: yellow; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return holdUpdate('".$dropOrderRow['orderid']."')"?>"><?php echo substr($dropOrderRow['onHoldSchedule'], 8, 2);?></button>
                                    <?php } else {?>
                                        <button id="Rea<?php echo $dropOrderRow['orderid'];?>" style="background-color: yellow; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['onHoldSchedule'], 8, 2);?></button>
                                <?php }}else{?>
                                    <button id="Rea<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                            <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if(($dropOrderRow['Cash'] =='Y' and $dropOrderRow['DropDP2'] !='Y') or ($dropOrderRow['Ret'] =='Y' and $dropOrderRow['DropDP2'] !='Y') or ($dropOrderRow['partial'] =='Y' and $dropOrderRow['DropDP2'] !='Y')){?>
                                <button id="DropDP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000"></button>
                            <?php }else{?>
                                <?php if($dropOrderRow['DropDP2'] =='Y'){?>
                                    <button id="DropDP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['DropDP2Time'], 8,2)?></button>
                                <?php }else{?>
                                    <button id="DropDP2<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['DropDP2Time'], 8,2)?></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #16469E; width: 50px">
                            <?php if(($dropOrderRow['DropDP2'] =='Y' and $dropOrderRow['bank'] !='Y' and $dropOrderRow['Ret'] !='Y') or ($dropOrderRow['DropDP2'] =='Y' and $dropOrderRow['bank'] !='Y' and $dropOrderRow['partial'] =='Y')){?>
                                <button id="bank<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000"></button>
                            <?php }else{?>
                                <?php if($dropOrderRow['bank'] =='Y'){?>
                                    <button id="bank<?php echo $dropOrderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['bankTime'], 8,2)?></button>
                                <?php }else{?>
                                    <button id="bank<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['bankTime'], 8,2)?></button>
                                <?php }?>
                            <?php }?>
                        </td>
                         <td style="border-right: 1px solid #16469E; width: 50px">
                             <?php if (($dropOrderRow['Ret'] == 'Y' and $dropOrderRow['DropDP2'] == 'Y' and $dropOrderRow['retcp1'] != 'Y') or ($dropOrderRow['bank'] != 'Y' and $dropOrderRow['partial'] == 'Y' and $dropOrderRow['DropDP2'] == 'Y' and $dropOrderRow['retcp1'] != 'Y')){?>
                                <button id="retcp1<?php echo $dropOrderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000"></button>                                
                             <?php } else {?>
                                <button id="retcp1<?php echo $dropOrderRow['orderid'];?>" style="background-color: <?php if($dropOrderRow['retcp1'] == 'Y'){echo 'red';}else{echo '#dbd9d9';} ?>; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled><?php echo substr($dropOrderRow['retcp1Time'], 8,2)?></button>                                
                             <?php }?>
                         </td>
                         <td id="5" style="display: none"><?php echo $dropOrderRow['dropPointCode']?></td>
                         <td id="6" style="display: none"><?php if ($dropOrderRow['pickMerchantName'] !=''){ echo $dropOrderRow['pickMerchantName'];} else { echo $dropOrderRow['merchantName'];}?></td>
                    </tr>
                <?php }?>
                    </tbody>
                </table>
            </div>
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
        <!-- The Modal for Return reason input and message -->
        <div id="returnModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div id="return_modal-content"  class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">Return Order</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <br><b>
                    Return Reason</b><br> <select id="retReason" name="returnReason" style="margin-left: 10px; height: 28px; width: 98%">
                                    <option value="Contact number incorrect/not available">Contact number incorrect/not available</option>
                                    <option value="On Hold for more than twice">On Hold for more than twice</option>
                                    <option value="When reached, does not answer call/not available">When reached, does not answer call/not available</option>
                                    <option value="Cancelled order over phone">Cancelled order over phone</option>
                                    <option value="Cancelled order, wanted delivery address changed">Cancelled order, wanted delivery address changed</option>
                                    <option value="Cancelled order after seeing, does not like the product">Cancelled order after seeing, does not like the product</option>
                                    <option value="Cancelled order after seeing, product size/color problem">Cancelled order after seeing, product size/color problem</option>
                                    <option value="Cancelled order after seeing, product is faulty">Cancelled order after seeing, product is faulty</option>
                                    <option value="Cancelled order after seeing, pricing incorrect">Cancelled order after seeing, pricing incorrect</option>
                                    <option value="Cancelled order after seeing, wants installation">Cancelled order after seeing, wants installation</option>
                                    <option value="Others">Others</option>
                                </select>
                    <b>Remarks</b><br><input type="text" id="retRemarks" value="" style="height: 25px; width: 98%">
                    <p id="retMessage" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <button id="btnRetSave" class="btn btn-primary">Save</button>
                    <button id="btnRetCancel" class="btn btn-default">Cancel</button>
                </div>
            </div>
        </div>

        <!-- The Modal for Cash Input -->
        <div id="cashModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div id="cash_modal-content"  class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">Cash Collection</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <br>
                    <table border="0">
                        <tr>
                            <td>
                                <label id="ordID" style="text-align: left"></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="merchantRef" style="text-align: left"></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="radio" style="text-align: left; font: 14px 'paperfly roman'">
                                    <input id="op1" type="radio" name="codType" value="CoD" checked> CoD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input id="op2" type="radio" name="codType" value="SoD" disabled> SoD
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="price" style="text-align: left"></label>
                                <p style="text-align: left; color: #16469E"><b>Collection Amount: </b>
                                    <input id="cashCollection" name="cashCollection" value="" style="display: inline-block; height: 25px; width: 100px" onkeyup="return isNumberKey(this)">
                                </p>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: left; color: #16469E">Remarks: &nbsp;&nbsp;
                                    <input id="cashComment" name="cashComment" value="" style="height: 25px; width: 200px">
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p id="cashMessage" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <button id="btnCashSave" class="btn btn-primary">Save</button>
                    <button id="btnCashCancel" class="btn btn-default">Cancel</button>
                    <br><br>
                </div>
            </div>
        </div>
        <!-- The Modal for Partial Input -->
        <div id="partialModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div id="partial_modal-content"  class="modal-content modal-dialog" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">Partial Delivery</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <br>
                    <table border="0" style="width: 100%">
                        <tr>
                            <td>
                                <label id="ordIDpartial" style="text-align: left"></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="merchantRefPartial" style="text-align: left"></label>
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: left; color: #16469E"><b>Delivered Qty: </b>
                                    <input id="receivedQty" name="receivedQty" value="" style="display: inline-block; height: 25px; width: 100px" onkeyup="return isNumberKey(this)">
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: left; color: #16469E"><b>Returned Qty:  </b>
                                    &nbsp;<input id="returnedQty" name="returnedQty" value="" style="display: inline-block; height: 25px; width: 100px" onkeyup="return isNumberKey(this)">
                                </p>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: left; color: #16469E">Reason: &nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="partialReason" name="partialReason" value="" style="height: 25px; width: 200px">
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="radio" style="text-align: left; font: 14px 'paperfly roman'">
                                    <input id="op3" type="radio" name="partialType" value="CoD" checked> CoD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input id="op4" type="radio" name="partialType" value="SoD" disabled> SoD
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="pricePartial" style="text-align: left"></label>
                                <p style="text-align: left; color: #16469E"><b>Collection Amount: </b>
                                    <input id="partialCollection" name="partialCollection" value="" style="display: inline-block; height: 25px; width: 100px" onkeyup="return isNumberKey(this)">
                                </p>
                            </td>
                        </tr>
                    </table>
                    <p id="partialMessage" style="text-align: center; font: 13px 'paperfly roman'"></p>
                    <button id="btnPartialSave" class="btn btn-primary">Save</button>
                    <button id="btnPartialCancel" class="btn btn-default">Cancel</button>
                    <br><br>
                </div>
            </div>
        </div>
        <!-- The Modal for onHold input and message -->
        <div id="onHoldModal" class="modal" style="width: auto">
            <!-- Modal content -->
            <div id="onhold_modal-content"  class="modal-content" style="width: 25%">
                <div class="modal-header" style="height: 40px; background-color: #16469E">
                    <span class="close">&times;</span>
                    <h3 style="font: 25px 'paperfly roman'">On Hold Order</h3>
                </div>
                <div class="modal-body" style="text-align: center">
                    <input type="hidden" id="onHoldOrderID" value="">
                    <div class="row">
                        <div class="col-sm-6">
                            <b><p id="onHoldOrder" style="text-align: left"></p></b>
                        </div>
                    </div>
                    <div class="row" style="text-align: left">
                        <div class="col-sm-4">
                            <b>On Hold Schedule</b>
                        </div>
                        <div class="col-sm-6">
                            <form name="frmOnHold">
                                <input type="text" class="form-control" id="onHoldDate" value="" onfocus="displayCalendar(document.frmOnHold.onHoldDate,'dd-mm-yyyy',this)">
                            </form>
                        </div>
                    </div>
                    <div class="row" style="text-align: left">
                        <div class="col-sm-4">
                            <b>On Hold Reason</b>
                        </div>
                        <div class="col-sm-6">
                            <select id="onReason" class="form-control" style="height: 28px">
                            </select>
                        </div>
                    </div>                    

                    <!--<select id="onHoldDate" name="onHoldDate" style="margin-left: 10px; height: 28px; width: 98%">
                                </select>-->
                    <br>
                    <div class="row" id="btnDiv" hidden>
                        <div class="col-sm-12">
                            <p id="onHoldMessage" style="text-align: center; font: 13px 'paperfly roman'"></p>
                            <button id="btnOnHoldSave" class="btn btn-primary" style ="color: white; background-color: blue">Save</button>
                            <button id="btnOnHoldCancel" class="btn btn-default">Cancel</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <script type="text/javascript">
        function SmartPickUpdate(ord)
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
            messageModal.style.display = "block";
            span.onclick = function ()
            {
                messageModal.style.display = "none";
            }
            $('#orderid').val(ord);
        }

        $('#btnCancel').click(function ()
        {
            $('#messageModal').css('display', 'none');
        })

        $('#btnSave').click(function ()
        {
            if ($('#smartPick').val() == '')
            {
                $('#msg2').html('Please insert comments');
            } else
            {
                var smartPickComments = $('#smartPick').val();
                var val = $('#orderid').val();
                var flag = 'smartPickComment';
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: val,
                        smartPickComment: smartPickComments,
                        flagreq: flag
                    },
                    success: function (response)
                    {
                        if (response == 'success')
                        {
                            $("#msg2").css("color", "green");
                            $('#msg2').html('Orders Picked Successfully');
                            $("#pick" + val).css("background-color", "green");
                            $("#pick" + val).attr("disabled", true);
                            $("#altermsg").css("color", "green");
                            $("#altermsg").text(val + " Pick successful");
                            $("#dp1" + val).attr("disabled", false);
                            setTimeout($('#messageModal').css('display', 'none'), 2000);
                        } else
                        {
                            $('#msg2').html(response);
                        }
                    }
                });
            }
        })
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
        $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
            $('#merchantList').select2();
            $('#dropExecList').select2();
            $('#pointList').select2();
        });

    </script>
    <script src="js/pot1.js"></script>
    </body>
</html>
