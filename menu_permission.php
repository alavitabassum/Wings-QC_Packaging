<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_usersetting` WHERE user_id = $user_id_chk and permission = 'Y'"));
    if ($userPrivCheckRow['permission'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <?php if (!isset($_POST['search'])){?>
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search User for Permission</p>
                Select User : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchuserid" style="width: 220px; height: 28px">
                <?php
                    $listUser = "select * from tbl_user_info where userName != 'admin'";
                    $listResult = mysqli_query($conn, $listUser);
                    foreach ($listResult as $userRow){
                        echo "<option value='".$userRow['user_id']."'>".$userRow['userName']."</option>";
                    }  
                ?>
                </select><br><br>
                Select Menu : &nbsp;&nbsp;&nbsp;<select id="menuid" name="menuPermission">
                    <option value="tbl_menu_ordermgt">Order Management</option>
                    <option value="tbl_menu_dbmgt">Database Management</option>
                    <option value="tbl_menu_capacityrel">Relocation</option>
                    <option value="tbl_menu_usersetting">User Settings</option>
                    <option value="tbl_menu_report">Report</option>
                </select><br>
                <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;<br><br>
            </form>
            <?php }?>
        </div>
        <div style="margin-left: 15px; width: 98%">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Select Menu Item</p>
                <?php
                    if (isset($_POST['search'])){
                        $userid = trim($_POST['searchuserid']);
                        $menuPermission = trim($_POST['menuPermission']);
                ?>
                Select User : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchuserid" style="width: 220px; height: 28px">
                <?php
                    $listUser = "select * from tbl_user_info where userName != 'admin'";
                    $listResult = mysqli_query($conn, $listUser);
                    foreach ($listResult as $userRow){
                        echo "<option value='".$userRow['user_id']."'"?><?php if ($userRow['user_id'] == $userid) {echo "selected";} echo ">".$userRow['userName']."</option>";
                    }  
                ?>
                </select><br><br>
                Select Menu : &nbsp;&nbsp;&nbsp;<select id="menuid" name="menuPermission">
                    <option value="tbl_menu_ordermgt" <?php if ($menuPermission =='tbl_menu_ordermgt'){echo "selected";}?>>Order Management</option>
                    <option value="tbl_menu_dbmgt" <?php if ($menuPermission =='tbl_menu_dbmgt'){echo "selected";}?>>Database Management</option>
                    <option value="tbl_menu_capacityrel" <?php if ($menuPermission =='tbl_menu_capacityrel'){echo "selected";}?>>Relocation</option>
                    <option value="tbl_menu_usersetting" <?php if ($menuPermission =='tbl_menu_usersetting'){echo "selected";}?>>User Settings</option>
                    <option value="tbl_menu_report" <?php if ($menuPermission =='tbl_menu_report'){echo "selected";}?>>Report</option>
                </select><br>
                <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;<br><br>
                <?php
                        if ($menuPermission == 'tbl_menu_ordermgt') {
                            $gpSQL = "Select * from tbl_menu_ordermgt where user_id = $userid";
                            $gpResult = mysqli_query($conn, $gpSQL);
                            $row_count = mysqli_num_rows($gpResult);
                            $gpRow = mysqli_fetch_array($gpResult);
                            $userSQL = "select * from tbl_user_info where user_id= $userid";
                            $userResult = mysqli_query($conn, $userSQL);
                            $usrRow = mysqli_fetch_array($userResult);
                            if ($row_count > 0){
                            // Exising user
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">New Order</td>
                                    <td style="width: 150px">Fulfillment Pick</td>
                                    <td style="width: 150px">3P Order Service</td>
                                    <td style="width: 150px">Pick Orders</td>
                                    <td style="width: 170px">Close Order</td>
                                    <td style="width: 170px">Assign Order</td>
                                    <td style="width: 150px">Edit Order</td>
                                    <td style="width: 150px">Export Orders to Excel</td>
                                    <td style="width: 150px">Collection at Bank</td>
                                    <td style="width: 150px">Scan Orders</td>
                                    <td style="width: 150px">Return Orders</td>
                                    <td style="width: 150px">DP2 Management</td>
                                    <td style="width: 150px">Barcode Warehouse</td>
                                    <td style="width: 150px">Measure Product</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="new_order" value="Y" <?php if($gpRow['new_order'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="smart_pick" value="Y" <?php if($gpRow['smart_pick'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="3p_order_service" value="Y" <?php if($gpRow['3p_order_service'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="pickOrders" value="Y" <?php if($gpRow['pickOrders'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="close_order" value="Y" <?php if($gpRow['close_order'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="assign_order" value="Y" <?php if($gpRow['assign_order'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="edit_order" value="Y" <?php if($gpRow['edit_order'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="exportToExcel" value="Y" <?php if($gpRow['exportToExcel'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="bankCollection" value="Y" <?php if($gpRow['bankCollection'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="scanOrders" value="Y" <?php if($gpRow['scanOrders'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="retOrder" value="Y" <?php if($gpRow['retOrder'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="DP2_mgt" value="Y" <?php if($gpRow['DP2_mgt'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="barcodeWarehouse" value="Y" <?php if($gpRow['barcodeWarehouse'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="measureOrders" value="Y" <?php if($gpRow['measureOrders'] == 'Y'){ echo 'checked';}?>></td>
                                </tr>
                            </table>
                            <?php
                            
                                    } else {
                                        //If New user 
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">New Order</td>
                                    <td style="width: 150px">Fulfillment Pick</td>
                                    <td style="width: 150px">3P Order Service</td>
                                    <td style="width: 150px">Pick Orders</td>
                                    <td style="width: 170px">Close Order</td>
                                    <td style="width: 170px">Assign Order</td>
                                    <td style="width: 150px">Edit Order</td>
                                    <td style="width: 150px">Export Orders to Excel</td>
                                    <td style="width: 150px">Collection at Bank</td>
                                    <td style="width: 150px">Scan Orders</td>
                                    <td style="width: 150px">Return Orders</td>
                                    <td style="width: 150px">DP2 Management</td>
                                    <td style="width: 150px">Barcode Warehouse</td>
                                    <td style="width: 150px">Measure Product</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="new_order" value="Y"></td>
                                    <td><input type="checkbox" name="smart_pick" value="Y"></td>
                                    <td><input type="checkbox" name="3p_order_service" value="Y"></td>
                                    <td><input type="checkbox" name="pickOrders" value="Y"></td>
                                    <td><input type="checkbox" name="close_order" value="Y"></td>
                                    <td><input type="checkbox" name="assign_order" value="Y"></td>
                                    <td><input type="checkbox" name="edit_order" value="Y"></td>
                                    <td><input type="checkbox" name="exportToExcel" value="Y"></td>
                                    <td><input type="checkbox" name="bankCollection" value="Y"></td>
                                    <td><input type="checkbox" name="scanOrders" value="Y"></td>
                                    <td><input type="checkbox" name="retOrder" value="Y"></td>
                                    <td><input type="checkbox" name="DP2_mgt" value="Y"></td>
                                    <td><input type="checkbox" name="barcodeWarehouse" value="Y"></td>
                                    <td><input type="checkbox" name="measureOrders" value="Y"></td>
                                </tr>
                            </table>
                            <?php
                        }                            
                        }
                        if ($menuPermission == 'tbl_menu_dbmgt') {
                            $gpSQL = "Select * from tbl_menu_dbmgt where user_id = $userid";
                            $gpResult = mysqli_query($conn, $gpSQL);
                            $row_count = mysqli_num_rows($gpResult);
                            $gpRow = mysqli_fetch_array($gpResult);
                            $userSQL = "select * from tbl_user_info where user_id= $userid";
                            $userResult = mysqli_query($conn, $userSQL);
                            $usrRow = mysqli_fetch_array($userResult);
                            if ($row_count > 0){
                            // Exising user
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">Merchant</td>
                                    <td style="width: 170px">Point</td>
                                    <td style="width: 150px">Employee</td>
                                    <td style="width: 150px">Designation</td>
                                    <td style="width: 150px">Facility Info</td>
                                    <td style="width: 150px">Rate Type</td>
                                    <td style="width: 150px">Rate Chart</td>
                                    <td style="width: 150px">Thana Assignment</td>
                                    <td style="width: 150px">ATM Locations</td>
                                    <td style="width: 150px">Order Marker</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="merchant" value="Y" <?php if($gpRow['merchant'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="point" value="Y" <?php if($gpRow['point'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="employee" value="Y" <?php if($gpRow['employee'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="designation" value="Y" <?php if($gpRow['designation'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="facilityInfo" value="Y" <?php if($gpRow['facilityInfo'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="RateType" value="Y" <?php if($gpRow['RateType'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="RateChart" value="Y" <?php if($gpRow['RateChart'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="thana" value="Y" <?php if($gpRow['thana'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="atmLocation" value="Y" <?php if($gpRow['atmLocation'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="orderMark" value="Y" <?php if($gpRow['orderMark'] == 'Y'){ echo 'checked';}?>></td>
                                </tr>
                            </table>
                            <?php
                            
                                    } else {
                                        //If New user 
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">Merchant</td>
                                    <td style="width: 170px">Point</td>
                                    <td style="width: 150px">Employee</td>
                                    <td style="width: 150px">Designation</td>
                                    <td style="width: 150px">Facility Info</td>
                                    <td style="width: 150px">Rate Type</td>
                                    <td style="width: 150px">Rate Chart</td>
                                    <td style="width: 150px">Thana Assignment</td>
                                    <td style="width: 150px">ATM Location</td>
                                    <td style="width: 150px">Order Marker</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="merchant" value="Y"></td>
                                    <td><input type="checkbox" name="point" value="Y"></td>
                                    <td><input type="checkbox" name="employee" value="Y"></td>
                                    <td><input type="checkbox" name="designation" value="Y"></td>
                                    <td><input type="checkbox" name="facilityInfo" value="Y"></td>
                                    <td><input type="checkbox" name="RateType" value="Y"></td>
                                    <td><input type="checkbox" name="RateChart" value="Y"></td>
                                    <td><input type="checkbox" name="thana" value="Y"></td>
                                    <td><input type="checkbox" name="atmLocation" value="Y"></td>
                                    <td><input type="checkbox" name="orderMark" value="Y"></td>
                                </tr>
                            </table>
                            <?php
                        }
                        }
                        if ($menuPermission == 'tbl_menu_capacityrel') {
                            $gpSQL = "Select * from tbl_menu_capacityrel where user_id = $userid";
                            $gpResult = mysqli_query($conn, $gpSQL);
                            $row_count = mysqli_num_rows($gpResult);
                            $gpRow = mysqli_fetch_array($gpResult);
                            $userSQL = "select * from tbl_user_info where user_id= $userid";
                            $userResult = mysqli_query($conn, $userSQL);
                            $usrRow = mysqli_fetch_array($userResult);
                            if ($row_count > 0){
                            // Exising user
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">Pick-up System</td>
                                    <td style="width: 170px">Delivery Capacity</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="pickupSystem" value="Y" <?php if($gpRow['pickupSystem'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="deliveryCapacity" value="Y" <?php if($gpRow['deliveryCapacity'] == 'Y'){ echo 'checked';}?>></td>
                                </tr>
                            </table>
                            <?php
                            
                                    } else {
                                        //If New user 
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">Pick-up System</td>
                                    <td style="width: 170px">Delivery Capacity</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="pickupSystem" value="Y"></td>
                                    <td><input type="checkbox" name="deliveryCapacity" value="Y"></td>
                                </tr>
                            </table>
                            <?php
                        }                            
                        }
                        if ($menuPermission == 'tbl_menu_usersetting') {
                            $gpSQL = "Select * from tbl_menu_usersetting where user_id = $userid";
                            $gpResult = mysqli_query($conn, $gpSQL);
                            $row_count = mysqli_num_rows($gpResult);
                            $gpRow = mysqli_fetch_array($gpResult);
                            $userSQL = "select * from tbl_user_info where user_id= $userid";
                            $userResult = mysqli_query($conn, $userSQL);
                            $usrRow = mysqli_fetch_array($userResult);
                            if ($row_count > 0){
                            // Exising user
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">Create New User</td>
                                    <td style="width: 170px">Edit User</td>
                                    <td style="width: 150px">Permission</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="new_user" value="Y" <?php if($gpRow['new_user'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="edit_user" value="Y" <?php if($gpRow['edit_user'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="permission" value="Y" <?php if($gpRow['permission'] == 'Y'){ echo 'checked';}?>></td>
                                </tr>
                            </table>
                            <?php
                            
                                    } else {
                                        //If New user 
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">Create New User</td>
                                    <td style="width: 170px">Edit User</td>
                                    <td style="width: 150px">Permission</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="new_user" value="Y"></td>
                                    <td><input type="checkbox" name="edit_user" value="Y"></td>
                                    <td><input type="checkbox" name="permission" value="Y"></td>
                                </tr>
                            </table>
                            <?php
                        }
                            
                        }
                        if ($menuPermission == 'tbl_menu_report') {
                            $gpSQL = "Select * from tbl_menu_report where user_id = $userid";
                            $gpResult = mysqli_query($conn, $gpSQL);
                            $row_count = mysqli_num_rows($gpResult);
                            $gpRow = mysqli_fetch_array($gpResult);
                            $userSQL = "select * from tbl_user_info where user_id= $userid";
                            $userResult = mysqli_query($conn, $userSQL);
                            $usrRow = mysqli_fetch_array($userResult);
                            if ($row_count > 0){
                            // Exising user
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">Hit Counter</td>
                                    <td style="width: 150px">All Orders</td>
                                    <td style="width: 150px">Create Invoice</td>
                                    <td style="width: 150px">Print Invoice</td>
                                    <td style="width: 150px">Bank Deposit</td>
                                    <td style="width: 150px">Revenue Summary</td>
                                    <td style="width: 150px">Shuttle Report</td>
                                    <td style="width: 150px">BEFTN</td>
                                    <td style="width: 150px">Cheque Print</td>
                                    <td style="width: 150px">Barcode Print</td>
                                    <td style="width: 150px">Dashboard</td>
                                    <td style="width: 150px">Point Performance</td>
                                    <td style="width: 150px">Daily Point & Officers Performance</td>
                                    <td style="width: 150px">Revenue Performance</td>
                                    <td style="width: 150px">Pending Cash Collection</td>
                                    <td style="width: 150px">SLA Missed Orders</td>
                                    <td style="width: 150px">Barcode Print (Single)</td>
                                    <td style="width: 150px">Return Report</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="hitCounter" value="Y" <?php if($gpRow['hitCounter'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="closedOrders" value="Y" <?php if($gpRow['closedOrders'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="invoice" value="Y" <?php if($gpRow['invoices'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="printInvoices" value="Y" <?php if($gpRow['printInvoices'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="bankDeposit" value="Y" <?php if($gpRow['bankDeposit'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="revSummary" value="Y" <?php if($gpRow['revSummary'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="shuttleReport" value="Y" <?php if($gpRow['shuttleReport'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="beftn" value="Y" <?php if($gpRow['beftn'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="chequePrint" value="Y" <?php if($gpRow['chequePrint'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="barcodePrint" value="Y" <?php if($gpRow['barcodePrint'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="dashboard" value="Y" <?php if($gpRow['dashboard'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="point_executive" value="Y" <?php if($gpRow['point_executive'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="delivery_performance" value="Y" <?php if($gpRow['delivery_performance'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="revenuePerformance" value="Y" <?php if($gpRow['revenuePerformance'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="cashPending" value="Y" <?php if($gpRow['cashPending'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="slaMissed" value="Y" <?php if($gpRow['slaMissed'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="barcodeOne" value="Y" <?php if($gpRow['barcodeOne'] == 'Y'){ echo 'checked';}?>></td>
                                    <td><input type="checkbox" name="returnReport" value="Y" <?php if($gpRow['returnReport'] == 'Y'){ echo 'checked';}?>></td>
                                </tr>
                            </table>
                            <?php
                            
                                    } else {
                                        //If New user 
                            ?>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                            <input type="hidden" name="menuUpdate" value="<?php echo $menuPermission;?>">
                            <u>Menu Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                            <hr>
                            <table>
                                <tr>
                                    <td style="width: 150px">Hit Counter</td>
                                    <td style="width: 150px">All Orders</td>
                                    <td style="width: 150px">Create Invoice</td>
                                    <td style="width: 150px">Print Invoices</td>
                                    <td style="width: 150px">Bank Deposit</td>
                                    <td style="width: 150px">Revenue Summary</td>
                                    <td style="width: 150px">Shuttle Report</td>
                                    <td style="width: 150px">BEFTN</td>
                                    <td style="width: 150px">Cheque Print</td>
                                    <td style="width: 150px">Barcode Print</td>
                                    <td style="width: 150px">Dashboard</td>
                                    <td style="width: 150px">Point Performance</td>
                                    <td style="width: 150px">Daily Point & Officers Performance</td>
                                    <td style="width: 150px">Revenue Performance</td>
                                    <td style="width: 150px">Pending Cash Collection</td>
                                    <td style="width: 150px">SLA Missed Orders</td>
                                    <td style="width: 150px">Barcode Print (Single)</td>
                                    <td style="width: 150px">Return Report</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="hitCounter" value="Y"></td>
                                    <td><input type="checkbox" name="closedOrders" value="Y"></td>
                                    <td><input type="checkbox" name="invoice" value="Y"></td>
                                    <td><input type="checkbox" name="printInvoices" value="Y"></td>
                                    <td><input type="checkbox" name="bankDeposit" value="Y"></td>
                                    <td><input type="checkbox" name="revSummary" value="Y"></td>
                                    <td><input type="checkbox" name="shuttleReport" value="Y"></td>
                                    <td><input type="checkbox" name="beftn" value="Y"></td>
                                    <td><input type="checkbox" name="chequePrint" value="Y"></td>
                                    <td><input type="checkbox" name="barcodePrint" value="Y"></td>
                                    <td><input type="checkbox" name="dashboard" value="Y"></td>
                                    <td><input type="checkbox" name="point_executive" value="Y"></td>
                                    <td><input type="checkbox" name="delivery_performance" value="Y"></td>
                                    <td><input type="checkbox" name="revenuePerformance" value="Y"></td>
                                    <td><input type="checkbox" name="cashPending" value="Y"></td>
                                    <td><input type="checkbox" name="slaMissed" value="Y"></td>
                                    <td><input type="checkbox" name="barcodeOne" value="Y"></td>
                                    <td><input type="checkbox" name="returnReport" value="Y"></td>
                                </tr>
                            </table>
                            <?php
                        }
                            
                        }

                    }
                ?>
                <br>
                <input type="submit" name="submit" class="btn btn-primary" value="Update Permission">
            </form>
            <?php
                if (isset($_POST['submit'])){
                    $userid = trim($_POST['userid']);
                    $userExist = trim($_POST['userExist']);
                    $menuUpdate = trim($_POST['menuUpdate']);
                    if ($menuUpdate == 'tbl_menu_ordermgt'){
                        $new_order = trim($_POST['new_order']);
                        $smart_pick=trim($_POST['smart_pick']);
                        $p_order_service =trim($_POST['3p_order_service']);
                        $pickOrders = trim($_POST['pickOrders']);
                        $close_order = trim($_POST['close_order']);
                        $assign_order = trim($_POST['assign_order']);
                        $edit_order = trim($_POST['edit_order']);
                        $exportToExcel = trim($_POST['exportToExcel']);
                        $bankCollection = trim($_POST['bankCollection']);
                        $scanOrders = trim($_POST['scanOrders']);
                        $retOrder = trim($_POST['retOrder']);
                        $DP2_mgt = trim($_POST['DP2_mgt']);
                        $barcodeWarehouse = trim($_POST['barcodeWarehouse']);
                        $measureOrders = trim($_POST['measureOrders']);
                        if ($userExist == 0) {
                            //Insert
                            $insertSQL = "INSERT INTO tbl_menu_ordermgt (user_id, new_order, smart_pick, 3p_order_service, pickOrders, close_order, assign_order, edit_order, exportToExcel, bankCollection, scanOrders, retOrder, DP2_mgt, barcodeWarehouse, measureOrders, creation_date, created_by) 
                            values ('$userid', '$new_order', '$smart_pick', '$p_order_service', '$pickOrders', '$close_order', '$assign_order', '$edit_order', '$exportToExcel', '$bankCollection', '$scanOrders', '$retOrder', '$DP2_mgt', '$barcodeWarehouse', '$measureOrders', NOW() + INTERVAL 6 HOUR, '$user_check')";
                            if (!mysqli_query($conn,$insertSQL))
                                {
                                    $error ="Insert Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        } else {
                            //update
                            $updateSQL = "UPDATE tbl_menu_ordermgt set new_order = '$new_order', smart_pick='$smart_pick', 3p_order_service='$p_order_service', pickOrders = '$pickOrders',
                            close_order = '$close_order', assign_order = '$assign_order', edit_order = '$edit_order', exportToExcel='$exportToExcel', bankCollection='$bankCollection', scanOrders = '$scanOrders', retOrder = '$retOrder', DP2_mgt = '$DP2_mgt', barcodeWarehouse = '$barcodeWarehouse', measureOrders = '$measureOrders',
                            update_date = NOW() + INTERVAL 6 HOUR , updated_by = '$user_check' where user_id = $userid"; 
                            if (!mysqli_query($conn,$updateSQL))
                                {
                                    $error ="Update Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        }                        
                    }
                    if ($menuUpdate == 'tbl_menu_capacityrel'){
                        $pickupSystem = trim($_POST['pickupSystem']);
                        $deliveryCapacity = trim($_POST['deliveryCapacity']);
                        if ($userExist == 0) {
                            //Insert
                            $insertSQL = "INSERT INTO tbl_menu_capacityrel (user_id, pickupSystem, deliveryCapacity, creation_date, created_by) 
                            values ('$userid', '$pickupSystem', '$deliveryCapacity', NOW() + INTERVAL 6 HOUR, '$user_check')";
                            if (!mysqli_query($conn,$insertSQL))
                                {
                                    $error ="Insert Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        } else {
                            //update
                            $updateSQL = "UPDATE tbl_menu_capacityrel set pickupSystem = '$pickupSystem', 
                            deliveryCapacity = '$deliveryCapacity',
                            update_date = NOW() + INTERVAL 6 HOUR , updated_by = '$user_check' where user_id = $userid"; 
                            if (!mysqli_query($conn,$updateSQL))
                                {
                                    $error ="Update Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        }                        
                    }
                    if ($menuUpdate == 'tbl_menu_dbmgt'){
                        $merchant = trim($_POST['merchant']);
                        $point = trim($_POST['point']);
                        $employee = trim($_POST['employee']);
                        $designation = trim($_POST['designation']);
                        $facilityInfo = trim($_POST['facilityInfo']);
                        $RateType = trim($_POST['RateType']);
                        $RateChart = trim($_POST['RateChart']);
                        $thana = trim($_POST['thana']);
                        $atmLocation = trim($_POST['atmLocation']);
                        $orderMark = trim($_POST['orderMark']);
                        if ($userExist == 0) {
                            //Insert
                            $insertSQL = "INSERT INTO tbl_menu_dbmgt (user_id, merchant, point, employee, designation, facilityInfo, RateType, RateChart, thana, atmLocation, orderMark, creation_date, created_by) 
                            values ('$userid', '$merchant', '$point', '$employee', '$designation', '$facilityInfo', '$RateType', '$RateChart', '$thana', '$atmLocation', '$orderMark', NOW() + INTERVAL 6 HOUR, '$user_check')";
                            if (!mysqli_query($conn,$insertSQL))
                                {
                                    $error ="Insert Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        } else {
                            //update
                            $updateSQL = "UPDATE tbl_menu_dbmgt set merchant = '$merchant', 
                            point = '$point', employee = '$employee', designation = '$designation', facilityInfo = '$facilityInfo', RateType='$RateType', RateChart='$RateChart', thana='$thana', orderMark = '$orderMark', atmLocation='$atmLocation',
                            update_date = NOW() + INTERVAL 6 HOUR , updated_by = '$user_check' where user_id = $userid"; 
                            if (!mysqli_query($conn,$updateSQL))
                                {
                                    $error ="Update Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        }                         
                    }
                    if ($menuUpdate == 'tbl_menu_usersetting'){
                        $new_user = trim($_POST['new_user']);
                        $edit_user = trim($_POST['edit_user']);
                        $permission = trim($_POST['permission']);
                        if ($userExist == 0) {
                            //Insert
                            $insertSQL = "INSERT INTO tbl_menu_usersetting (user_id, new_user, edit_user, permission, creation_date, created_by) 
                            values ('$userid', '$new_user', '$edit_user', '$permission', NOW() + INTERVAL 6 HOUR, '$user_check')";
                            if (!mysqli_query($conn,$insertSQL))
                                {
                                    $error ="Insert Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        } else {
                            //update
                            $updateSQL = "UPDATE tbl_menu_usersetting set new_user = '$new_user', 
                            edit_user = '$edit_user', permission = '$permission', 
                            update_date = NOW() + INTERVAL 6 HOUR , updated_by = '$user_check' where user_id = $userid"; 
                            if (!mysqli_query($conn,$updateSQL))
                                {
                                    $error ="Update Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        }                        
                    }
                    if ($menuUpdate == 'tbl_menu_report'){
                        $hitCounter = trim($_POST['hitCounter']);
                        $closedOrders = trim($_POST['closedOrders']);
                        $invoice = trim($_POST['invoice']);
                        $printInvoices = trim($_POST['printInvoices']);
                        $bankDeposit = trim($_POST['bankDeposit']);
                        $revSummary = trim($_POST['revSummary']);
                        $shuttleReport = trim($_POST['shuttleReport']);
                        $beftn = trim($_POST['beftn']);
                        $chequePrint = trim($_POST['chequePrint']);
                        $barcodePrint = trim($_POST['barcodePrint']);
                        $dashboard = trim($_POST['dashboard']);
                        $point_executive = trim($_POST['point_executive']);
                        $delivery_performance =trim($_POST['delivery_performance']);
                        $revenuePerformance = trim($_POST['revenuePerformance']);
                        $cashPending = trim($_POST['cashPending']);
                        $slaMissed = trim($_POST['slaMissed']);
                        $barcodeOne = trim($_POST['barcodeOne']);
                        $returnReport = trim($_POST['returnReport']);
                        if ($userExist == 0) {
                            //Insert
                            $insertSQL = "INSERT INTO tbl_menu_report (user_id, hitCounter, closedOrders, invoices, printInvoices, bankDeposit, revSummary, shuttleReport, beftn, chequePrint, barcodePrint, dashboard, point_executive, revenuePerformance, delivery_performance, cashPending, slaMissed, barcodeOne, returnReport, creation_date, created_by) 
                            values ('$userid', '$hitCounter', '$closedOrders', '$invoice', '$printInvoices','$bankDeposit', '$revSummary', '$shuttleReport', '$beftn', '$chequePrint', '$barcodePrint', '$dashboard', '$point_executive', '$revenuePerformance', '$delivery_performance', '$cashPending', '$slaMissed', '$barcodeOne', '$returnReport', NOW() + INTERVAL 6 HOUR, '$user_check')";
                            if (!mysqli_query($conn,$insertSQL))
                                {
                                    $error ="Insert Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        } else {
                            //update
                            $updateSQL = "UPDATE tbl_menu_report set hitCounter = '$hitCounter', closedOrders='$closedOrders', invoices='$invoice', printInvoices='$printInvoices', bankDeposit='$bankDeposit', revSummary='$revSummary', shuttleReport='$shuttleReport', beftn ='$beftn', chequePrint='$chequePrint', barcodePrint = '$barcodePrint', dashboard = '$dashboard', point_executive='$point_executive', revenuePerformance='$revenuePerformance', delivery_performance='$delivery_performance', cashPending = '$cashPending', slaMissed = '$slaMissed', barcodeOne = '$barcodeOne', returnReport = '$returnReport',
                            update_date = NOW() + INTERVAL 6 HOUR , updated_by = '$user_check' where user_id = $userid"; 
                            if (!mysqli_query($conn,$updateSQL))
                                {
                                    $error ="Update Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-success'>";
                                        echo "Permission Update successfully";
                                    echo "</div>";
                                }
                        }                        
                    }
                }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript" charset="utf-8">
            $(window).load(function(){<!-- www.j a  v a 2 s.  c  o  m-->
                $('#searchid').select2();
            });
        </script>
    </body>
</html>
