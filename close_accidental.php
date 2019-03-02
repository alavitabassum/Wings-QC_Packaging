        <?php
            include('header.php');
            if (!isset($_POST['searchOrder'])){
                $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                        FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) order by orderid, v_merchant_info.merchantName";
                $orderResult = mysqli_query($conn, $orderSQL);
            } else {
                $searchText = trim($_POST['searchText']);
                if ($searchText !=''){
                    $orderSQL = "SELECT tbl_order_details.*, v_merchant_info.merchantName, v_merchant_info.address, v_merchant_info.contactNumber, v_merchant_info.thanaName, v_merchant_info.districtName, tbl_thana_info.thanaName AS PickThana, v_thana_info.thanaName AS CustomerTh, v_thana_info.districtName AS CustomerDist
                                    FROM ((tbl_order_details INNER JOIN v_merchant_info ON tbl_order_details.merchantCode = v_merchant_info.merchantCode) LEFT JOIN tbl_thana_info ON tbl_order_details.thanaId = tbl_thana_info.thanaId) LEFT JOIN v_thana_info ON tbl_order_details.customerThana = v_thana_info.thanaId where (close != 'Y' or close is null) and (orderid LIKE '%$searchText%' or merOrderRef LIKE '%$searchText%') order by orderid, v_merchant_info.merchantName";                    
                    $orderResult = mysqli_query($conn, $orderSQL);
                } else {
                    echo "<script>";
                    echo "alert('No such order found!!!')";
                    echo "</script>";
                }
            }                   
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
                        <label style="background-color: red; color: white; font: 20px 'paperfly roman'">Accidental Close</label>
                    </td>
                    <td style="padding-left: 1px; width: 190px">
                        <input id="searchInput"  style="height: 27px; margin: auto; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="text" name="searchText" placeholder="Search Order ID">
                    </td>
                    <td>
                        <button style="height: 25px; border-style: solid; border-width: 1px; margin: 2px; border-color: #0094ff" type="submit" class="btn btn-default" name="searchOrder">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </td>
                    <td><label style="margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Drop Point&nbsp;&nbsp;<img id="img5" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 5)"/></label></td>
                    <td><label style="margin: auto; color: #16469E; font: 13px 'paperfly roman'">&nbsp;&nbsp; Pick-up Merchant&nbsp;&nbsp;<img id="img6" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 6)"/></label></td>
                </tr>
            </table>
            </form>
            <input id="col2sort" type="hidden" name="col2sort" value="1">
            <div style="width: 1200px">
                <table style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <thead>
                    <tr style="font: 13px 'paperfly bold'">
                        <td style="width: 100px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Order ID&nbsp;&nbsp;<img id="img1" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 1)"/></label>
                        </td>
                        <td style="width: 120px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Merchant Ref.&nbsp;<img id="img2" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 2)"/></label>
                        </td>
                        <td style="width: 153px; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Pick-up Exec&nbsp;&nbsp;&nbsp;&nbsp;<img id="img3" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 3)"/></label>
                        </td>
                        <td style="width: 15px;border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF"></td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Pick</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>DP 1</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Shtl</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>DP 2</label>
                        </td>
                        <td style="width: 152px; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Drop Exec&nbsp;&nbsp;&nbsp;&nbsp;<img id="img4" src="image/updown1.png" onclick="sortTable($('#orderTracker'), 4)"/></label>
                        </td>
                        <td style="width: 15px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF"></td>
                        <td style="width: 50px; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Pick</label>
                        </td>
                        <td style="width: 50px; border-right: 1px solid #00AEEF; border-bottom: 1px solid #16469E; background-color: #00AEEF">
                            <label>Cust</label>
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
                            <label>Close</label>
                        </td>
                    </tr>
                    </thead>
                </table>
            </div>
            <div style="overflow-y: auto; height: 400px; margin: 1px; border-bottom: 1px solid #16469E; width: 1208px">
                <table id="orderTracker" style="text-align: center; border-top: 1px solid #16469E; border-left: 1px solid #16469E; border-right: 1px solid #16469E; border-bottom: 1px solid #16469E">
                    <tbody>
                <?php foreach ($orderResult as $orderRow){?>
                     <tr style="font: 11px 'paperfly roman'">
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
                        <td id = "2" style="width: 120px; border-right: 1px solid #16469E">
                            <label style="font: 11px 'paperfly roman'"><?php echo $orderRow['merOrderRef'];?></label>
                        </td>
                        <td id = "3" style="width: 150px">
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
                                    and tbl_employee_point.pointCode = '$pointCode'";
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
                                <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return PickUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php } else {?>
                                <?php if ($orderRow['Pick'] !='') {?>
                                    <button id="pick<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
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
                            <?php if($orderRow['DP1'] =='Y' and $orderRow['Shtl'] !='Y'){?>
                                <button id="Shtl<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return ShtlUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['Shtl'] =='Y'){?>
                                    <button id="Shtl<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="Shtl<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
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
                        <td id = "4" style="width: 150px">
                            <?php if (substr($orderRow['dropPointEmp'],0,5) !='') {?>
                                <select id="dropemp<?php echo $orderRow['orderid'];?>" name="dropPointEmp" style="width: 150px">
                                    <?php
                                        $dropCode = trim($orderRow['dropPointEmp']);
                                        $dropEmpSQL = "Select empCode, empName from tbl_employee_info where empCode='$dropCode'";
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
                                        and tbl_employee_point.pointCode = '$dropCode'";
                                        $droppointResult = mysqli_query($conn, $droppointSQL);
                                        foreach ($droppointResult as $dropRow){
                                    ?>
                                    <option value="<?php echo $dropRow['empCode'];?>"><?php echo $dropRow['empName'];?></option>
                                    <?php }?>
                                </select>
                            <?php }?>
                        </td>
                         <td style="width: 15px; border-right: 1px solid #16469E">
                                <?php if (substr($orderRow['dropPointEmp'],0,5) !=''){?>
                                    <button id="assignDrop<?php echo $orderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignDrop" disabled></button>            
                                <?php } else {?>
                                    <button id="assignDrop<?php echo $orderRow['orderid'];?>" style="margin-bottom: 9px; width: 15px; height: 31px" name="assignDrop" onclick="<?php echo "return assignDropExec('".$orderRow['orderid']."')"?>"></button>
                                <?php }?>
                         </td>
                        <td style="width: 50px; border-right: 1px solid #16469E">
                            <?php if ($orderRow['dropPointEmp'] !='' and $orderRow['PickDrop'] != 'Y' and $orderRow['DP2'] =='Y') {?>
                                <button id="PickDrop<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return PickDropUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php } else {?>
                                <?php if ($orderRow['PickDrop'] =='Y') {?>
                                    <button id="PickDrop<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else {?>
                                    <button id="PickDrop<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; width: 50px">
                            <?php if($orderRow['PickDrop'] =='Y' and $orderRow['Cust'] !='Y'){?>
                                <button id="Cust<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CustUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['Cust'] =='Y'){?>
                                    <button id="Cust<?php echo $orderRow['orderid'];?>" style="background-color: green; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="Cust<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }?>
                            <?php }?>
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
                            <?php if($orderRow['Cust'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y'){?>
                                <button id="Ret<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return RetUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['Ret'] =='Y'){?>
                                    <button id="Ret<?php echo $orderRow['orderid'];?>" style="background-color: red; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
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
                                    <button id="partial<?php echo $orderRow['orderid'];?>" style="background-color: orange; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
                                    <button id="partial<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                            <?php }?>
                            <?php }?>
                        </td>
                        <td style="border-right: 1px solid #00AEEF; background-color: #dbd9d9; width: 60px">
                            <?php if($orderRow['Cust'] =='Y' and $orderRow['Cash'] !='Y' and $orderRow['Ret'] !='Y' and $orderRow['partial'] !='Y' and $orderRow['Rea'] !='Y'){?>
                                <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return holdUpdate('".$orderRow['orderid']."')"?>"></button>
                            <?php }else{?>
                                <?php if($orderRow['Rea'] =='Y'){?>
                                    <button id="Rea<?php echo $orderRow['orderid'];?>" style="background-color: yellow; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" disabled></button>
                                <?php }else{?>
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
                        <td style="width: 50px">
                            <button id="close<?php echo $orderRow['orderid'];?>" style="background-color: #dbd9d9; height: 26px; width: 26px; border-radius: 50px; border: 1px solid #000" onclick="<?php echo "return CloseUpdate('".$orderRow['orderid']."')"?>"></button>
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
                        var accComment = prompt("Please enter accident detail", "Accident detail required");

                        if (accComment != null)
                        {
                            if (confirm("Are you sure!") == true) {
                                var pickempid = ($("#pickemp" + ord).val());
                                var orderid = ord;
                                var updateFlag = 'accclose';
                                $.ajax({
                                    type: "POST",
                                    url: "trackerupdate",
                                    data:
                                        {
                                            data: pickempid,
                                            order: orderid,
                                            accRem: accComment,
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
                            }                          
                        } else
                        {
                            alert("Accient detail required");
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
                    break;
                case 2:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    break;
                case 3:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    break;
                case 4:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    break;
                case 5:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img6").attr("src", "image/updown1.png");
                    break;
                case 6:
                    $("#img1").attr("src", "image/updown1.png");
                    $("#img2").attr("src", "image/updown1.png");
                    $("#img3").attr("src", "image/updown1.png");
                    $("#img4").attr("src", "image/updown1.png");
                    $("#img5").attr("src", "image/updown1.png");
                    break;
            }
        }
    </script>