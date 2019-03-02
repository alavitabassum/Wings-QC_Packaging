<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and merchant = 'Y'"));
    if ($userPrivCheckRow['merchant'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search Merchant</p>
                <input id="editid" type="hidden" name="merchantCode">
                <div>
                    <?php
                    if ((!isset($_POST['submit'])) and (!isset($_POST['edit'])) and (!isset($_POST['search']))){
                    $listMerchant = "select * from tbl_merchant_info";
                    $merchantResult = mysqli_query($conn, $listMerchant);
                    ?>
                        <table class='table table-hover' id="merchantList">
                            <tr style='background-color:#dad8d8; li'>
                                <th>Merchant Code</th>
                                <th>Merchant Name</th> 
                                <th>Active/Inactive Status</th>
                                <th style="text-align: right">Edit&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            </tr>
                            <?php
                                foreach ($merchantResult as $merchantRow){
                                    ?>
                                        <tr>
                                            <td><label id="<?php echo $merchantRow['merchantCode'];?>"><?php echo $merchantRow['merchantCode'];?></label></td>
                                            <td><?php echo $merchantRow['merchantName'];?></td>
                                            <?php if($merchantRow['isActive'] == 'Y'){?>
                                                <td><button type="button" id="btnAI<?php echo $merchantRow['merchantCode'];?>" class="btn btn-warning" onclick="activeInactive('<?php echo $merchantRow['merchantCode'];?>')">Active</button></td>
                                            <?php } else {?>
                                                <td><button type="button" id="btnAI<?php echo $merchantRow['merchantCode'];?>" class="btn btn-default" onclick="activeInactive('<?php echo $merchantRow['merchantCode'];?>')">Inactive</button></td>
                                            <?php }?>
                                            <td style="text-align: right"><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$merchantRow['merchantCode']."')"?>"></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </table>
                    <?php
                        }
                    ?>
                </div>
            </form>
        </div>
        <div style="margin-left: 15px; width: 98%">
            <?php
                //After search edit screen
                if (isset($_POST['edit'])) {
                    $merchantCode = trim($_POST['merchantCode']);
                    $editSQL = "Select * from tbl_merchant_info where merchantCode = '$merchantCode'";
                    $editResult = mysqli_query($conn, $editSQL);
                    $merchanteditrow = mysqli_fetch_array($editResult);
                ?>
                <div>
                    <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                        <button id="merchantSearch" class="btn btn-info" onclick="return showMerchantList()">Show Search List</button>
                        <br><br>
                        <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Edit Merchant</p>
                        <input type="hidden" name="merchantCode" value="<?php echo $merchanteditrow['merchantCode'];?>">
                        <table style="width: 100%" border="0">
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold"><u>Basic Information update for <?php echo $merchanteditrow['merchantCode'];?></u></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Merchant Name</label>
                                </td>
                                <td>
                                    <input type="text" name="merchantName" value="<?php echo $merchanteditrow['merchantName'];?>" style="height: 25px" required>
                                </td>
                                <td>
                                    <label>Business Type</label>
                                </td>
                                <td>
                                    <select id="businessType" name="businessType" style="width: 92%; height: 25px">
                                        <?php if ($merchanteditrow['businessType'] == 'M'){?>
                                            <option value="M" selected>Market Place</option>
                                            <option value="S">Shop</option>
                                        <?php }else{?>
                                            <option value="M">Market Place</option>
                                            <option value="S" selected>Shop</option>
                                        <?php }?>
                                    </select>
                                </td>
                                <td>
                                    <label>Product(s) Nature</label>
                                </td>
                                <td>
                                    <input type="text" name="productNature" value="<?php echo $merchanteditrow['productNature'];?>" style="height: 25px">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Address</label>
                                </td>
                                <td>
                                    <input type="text" name="address" value="<?php echo $merchanteditrow['address'];?>" style="height: 25px">
                                </td>
                                <td>
                                    <label>District</label>
                                </td>
                                <td>
                                    <select name="districtid" style="width: 92%; height: 25px" onchange="fetch_select(this.value);" required>
                                        <option></option>
                                        <?php
                                            $districtsql = "select districtId, districtName from tbl_district_info";
                                            $districtresult = mysqli_query($conn,$districtsql);                                            
                                            foreach ($districtresult as $districtrow){
                                                if ($districtrow['districtId'] == $merchanteditrow['districtid']){
                                                    echo "<option value=".$districtrow['districtId']." selected>".$districtrow['districtName']."</option>";
                                                } else {
                                                    echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";    
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <label>Thana</label>
                                </td>
                                <td>
                                    <?php
                                        $merchantthana = $merchanteditrow['thanaid'];
                                        $thanasql = "Select thanaId, thanaName from tbl_thana_info where thanaId='$merchantthana'";
                                        $thanaresult = mysqli_query($conn, $thanasql);
                                        $thanarow = mysqli_fetch_array($thanaresult);
                                    ?>
                                    <select id="thana" name="thanaid" style="width: 92%; height: 25px" onchange="fetch_pointCode(this.value)" required>
                                        <option value="<?php echo $thanarow['thanaId'];?>"><?php echo $thanarow['thanaName'];?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Website</label>
                                </td>
                                <td>
                                    <input type="text" name="website" value="<?php echo $merchanteditrow['website'];?>" style="height: 25px">
                                </td>
                                <td>
                                    <label>Facebook Page</label>
                                </td>
                                <td>
                                    <input type="text" name="facebook" value="<?php echo $merchanteditrow['facebook'];?>" style="height: 25px">
                                </td>
                                <td>
                                    <label>Company Phone</label>
                                </td>
                                <td>
                                    <input type="text" name="companyPhone" value="<?php echo $merchanteditrow['companyPhone'];?>" style="height: 25px">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold"><u>Contact Person Details:</u></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Name</label>
                                </td>
                                <td>
                                    <input type="text" name="contactName" value="<?php echo $merchanteditrow['contactName'];?>" style="height: 25px" required>
                                </td>
                                <td>
                                    <label>Designation</label>
                                </td>
                                <td>
                                    <input type="text" name="designation" value="<?php echo $merchanteditrow['designation'];?>" style="height: 25px">
                                </td>
                                <td>
                                    <label>Contact Number</label>
                                </td>
                                <td>
                                    <input type="text" name="contactNumber" value="<?php echo $merchanteditrow['contactNumber'];?>" style="height: 25px" required>
                                </td>
                                <td>
                                    <label>Email</label>
                                </td>
                                <td>
                                    <input type="text" name="email" value="<?php echo $merchanteditrow['email'];?>" style="height: 25px">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold"><u>Delivery Charge Information:</u></label>
                                </td>                        
                            </tr>
                            <tr>
                                <td><label>Rate Chart</label></td>
                                <td>
                                    <?php 
                                        $merRate = $merchanteditrow['ratechartId'];
                                        $rateChartSQL = "select * from tbl_ratechart_name where ratechartId = '$merRate'";
                                        $rateChartResult = mysqli_query($conn, $rateChartSQL);
                                        $rateChartRow = mysqli_fetch_array($rateChartResult);
                                        $rateEditSQL = "select * from tbl_ratechart_name";
                                        $rateEditResult = mysqli_query($conn, $rateEditSQL);
                                    ?>
                                    <select id="rateChart" name="rateChart">
                                        <?php foreach ($rateEditResult as $rateEditRow) { if ($rateEditRow['ratechartId'] == $rateChartRow['ratechartId']) {?>
                                            <option value="<?php echo $rateChartRow['ratechartId'];?>" selected><?php echo $rateChartRow['rateChartName'];?></option>
                                        <?php } else {?>
                                            <option value="<?php echo $rateEditRow['ratechartId'];?>"><?php echo $rateEditRow['rateChartName'];?></option>
                                        <?php } } ?>
                                    </select>
                                </td>
                                <td>
                                    <label>CoD (%)</label>
                                </td>
                                <td>
                                    <input type="text" name="cod" value="<?php echo $merchanteditrow['cod'];?>" style="height: 25px" onkeyup="return isNumberKey(this)">
                                </td>
                                <td><label>Smart Rate Chart</label></td>
                                <td>
                                    <?php 
                                        $merRate = $merchanteditrow['smarRateChart'];
                                        $rateChartSQL = "select * from tbl_ratechart_name where ratechartId = '$merRate'";
                                        $rateChartResult = mysqli_query($conn, $rateChartSQL);
                                        $rateChartRow = mysqli_fetch_array($rateChartResult);
                                        $rateEditSQL = "select * from tbl_ratechart_name";
                                        $rateEditResult = mysqli_query($conn, $rateEditSQL);
                                    ?>
                                    <select id="smartRateChart" name="smartRateChart">
                                        <?php foreach ($rateEditResult as $rateEditRow) { if ($rateEditRow['ratechartId'] == $rateChartRow['ratechartId']) {?>
                                            <option value="<?php echo $rateChartRow['ratechartId'];?>" selected><?php echo $rateChartRow['rateChartName'];?></option>
                                        <?php } else {?>
                                            <option value="<?php echo $rateEditRow['ratechartId'];?>"><?php echo $rateEditRow['rateChartName'];?></option>
                                        <?php } } ?>
                                    </select>
                                </td>
                                <td><label>Return Charge</label></td>
                                <td>
                                    <select id="returnCharge" name="returnCharge">
                                        <option value="Y" <?php if($merchanteditrow['returnCharge'] == 'Y'){echo 'selected';}?>>Yes</option>
                                        <option value="N" <?php if($merchanteditrow['returnCharge'] == 'N'){echo 'selected';}?>>No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold"><u>Bank Information:</u></label>
                                </td> 
                            </tr>
                            <tr>
                                <td>
                                    <label>Account Name</label>
                                </td>
                                <td>
                                    <input type="text" name="accountName" value="<?php echo $merchanteditrow['accountName'];?>" style="height: 25px">
                                </td>
                                <td>
                                    <label>Account Number</label>
                                </td>
                                <td>
                                    <input type="text" name="accountNumber" value="<?php echo $merchanteditrow['accountNumber'];?>" style="height: 25px">
                                </td>
                                <td>
                                    <label>Bank Name</label>
                                </td>
                                <td>
                                    <input type="text" name="bankName" value="<?php echo $merchanteditrow['bankName'];?>" style="height: 25px">
                                </td>
                                <td>
                                    <label>Branch</label>
                                </td>
                                <td>
                                    <input type="text" name="branch" value="<?php echo $merchanteditrow['branch'];?>" style="height: 25px">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Route Number</label>
                                </td>
                                <td>
                                    <input type="text" name="routeNumber" value="<?php echo $merchanteditrow['routeNumber'];?>" style="height: 25px">
                                </td>
                                <td>
                                    <label>Payment Mode</label>
                                </td>
                                <td>
                                    <select name="paymentMode" style="width: 92%; height: 25px" required>
                                        <option value="cheque" <?php if($merchanteditrow['paymentMode'] == 'cheque'){ echo 'selected';}?>>Cheque</option>
                                        <option value="beftn" <?php if($merchanteditrow['paymentMode'] == 'beftn'){ echo 'selected';}?>>BEFTN</option>
                                        <option value="cash" <?php if($merchanteditrow['paymentMode'] == 'cash'){ echo 'selected';}?>>Cash</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold"><u>Other Details:</u></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Point Code</label>
                                </td>
                                <td>
                                    <select id="pointCode" name="pointCode" style="width: 92%; height: 25px" required>
                                        <?php
                                            $merPoint = $merchanteditrow['pointCode'];
                                            $pointSQL = "SELECT tbl_merchant_info.pointCode, tbl_point_info.pointName 
                                            FROM tbl_merchant_info, tbl_point_info WHERE tbl_merchant_info.pointCode = tbl_point_info.pointCode 
                                            and tbl_merchant_info.pointCode = '$merPoint'";
                                            $pointResult = mysqli_query($conn, $pointSQL);
                                            $pointRow = mysqli_fetch_array($pointResult);
                                        ?>
                                        <option value="<?php echo $pointRow['pointCode']?>"><?php echo $pointRow['pointName']?></option>
                                    </select>
                                </td>
                                <td>
                                    <label>Relationship Manager</label>
                                </td>
                                <td>
                                    <select id="relationId" name="empCode" style="width: 60%; height: 25px" required>
                                        <option></option>
                                        <?php
                                            $merEmp = $merchanteditrow['empCode'];
                                            $empsql = "Select tbl_employee_info.empCode, tbl_employee_info.empName, tbl_designation_info.name 
                                                        from tbl_employee_info, tbl_designation_info where tbl_employee_info.desigid=tbl_designation_info.desigid and tbl_employee_info.desigid=7 and tbl_employee_info.isActive = 'Y'";
                                            $empresult = mysqli_query($conn, $empsql);
                                            foreach ($empresult as $emprow){
                                                if ($emprow['empCode'] == $merEmp){
                                                    echo "<option value='".$emprow['empCode']."' selected>".$emprow['empName']." - ".$emprow['name']."</option>";
                                                } else {
                                                    echo "<option value='".$emprow['empCode']."'>".$emprow['empName']." - ".$emprow['name']."</option>";
                                                }
                                                
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <label>Monthly Invoice</label>
                                </td>
                                <td>
                                    <select id="monthlyInvoice" name="monthlyInvoice" style="width: 92%; height: 25px" required>
                                        <option value="N" <?php if($merchanteditrow['monthlyInvoice'] == 'N'){echo 'selected';}?>>No</option>
                                        <option value="Y" <?php if($merchanteditrow['monthlyInvoice'] == 'Y'){echo 'selected';}?>>Yes</option>
                                    </select>
                                </td>
                                <td>
                                    <label>Pick-up SMS</label>
                                </td>
                                <td>
                                    <select id="pickupSMS" name="pickupSMS" style="width: 92%; height: 25px" required>
                                        <option value="Y" <?php if($merchanteditrow['sendSms'] == 'Y'){echo 'selected';}?>>Yes</option>
                                        <option value="N" <?php if($merchanteditrow['sendSms'] == 'N'){echo 'selected';}?>>No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>VAT(%)</label>
                                </td>
                                <td>
                                    <input type="text" id="vat" name="vat" style="height: 25px" onkeyup="return isNumberKey(this)" value="<?php echo $merchanteditrow['vat'];?>">
                                </td>
                            </tr>
                        </table>
                        <div style="float: right">
                            <input type="submit" name="submit" value="save" class="btn-primary" onclick="selectDeselect('pointAssigned', true)" style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                        </div>
                        <br>
                        <br>
                    </form>
                </div>
            <?php
                }
                //Search result
                if (isset($_POST['search'])) {
                    $searchType = trim($_POST['searchType']);
                    $searchText = trim($_POST['searchText']);
                    $searchSQL = "select merchantCode, merchantName from tbl_merchant_info where $searchType like '%$searchText%'";
                    $searchResult = mysqli_query($conn, $searchSQL);
                    $searchCount = mysqli_num_rows($searchResult);
                    ?><p style="font: 15px 'paperfly roman'"><?php
                    echo "<u>Search result for <strong>".$searchText." </strong></u> : ".$searchCount." records found";
                    ?></p><?php
                ?>
                    <form action="" method="post" style="color: #16469E; font: 15px 'paperfly roman'">
                    <input id="searchuserid" type="hidden" name="merchantCode"> 
                    <table class='table table-hover'>
                        <tr style='background-color:#dad8d8; li'>
                            <th>Merchant Code</th>
                            <th>Merchant Name</th> 
                            <th>Edit</th>
                        </tr>
                        <?php
                            foreach ($searchResult as $searchRow){
                                ?>
                                    <tr>
                                        <td><label id="<?php echo $searchRow['merchantCode'];?>"><?php echo $searchRow['merchantCode'];?></label></td>
                                        <td><?php echo $searchRow['merchantName'];?></td>
                                        <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$searchRow['merchantCode']."')"?>"></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </table>
                    </form>
                <?php
                    }
                    if (isset($_POST['submit'])) {
                    $districtid = trim($_POST['districtid']);
                    $merchantCode = trim($_POST['merchantCode']); 
                    $maxmerid =substr($_POST['merchantCode'],-4);
                    $merchantprefix = "M-".$districtid;
                    $merchantid = $merchantprefix."-".$maxmerid;
                    $merchantName = trim($_POST['merchantName']);
                    $merchantName = mysqli_real_escape_string($conn, $merchantName);
                    $businessType = trim($_POST['businessType']);
                    $productNature = trim($_POST['productNature']);
                    $productNature = mysqli_real_escape_string($conn, $productNature);
                    $address = trim($_POST['address']);
                    $address = mysqli_real_escape_string($conn, $address);
                    $thanaid = trim($_POST['thanaid']);
                    $website = trim($_POST['website']);
                    $website = mysqli_real_escape_string($conn, $website);
                    $facebook = trim($_POST['facebook']);
                    $facebook = mysqli_real_escape_string($conn, $facebook);
                    $companyPhone = trim($_POST['companyPhone']);
                    $companyPhone = mysqli_real_escape_string($conn, $companyPhone);
                    $contactName = trim($_POST['contactName']);
                    $contactName = mysqli_real_escape_string($conn, $contactName);
                    $designation = trim($_POST['designation']);
                    $designation = mysqli_real_escape_string($conn, $designation);
                    $contactNumber = trim($_POST['contactNumber']);
                    $contactNumber = mysqli_real_escape_string($conn, $contactNumber);
                    $email = trim($_POST['email']);
                    $email = mysqli_real_escape_string($conn, $email);
                    $rateChart = trim($_POST['rateChart']);
                    $cod = trim($_POST['cod']);
                    $cod = mysqli_real_escape_string($conn, $cod);
                    $accountName = trim($_POST['accountName']);
                    $accountName = mysqli_real_escape_string($conn, $accountName);
                    $accountNumber = trim($_POST['accountNumber']);
                    $accountNumber = mysqli_real_escape_string($conn, $accountNumber);
                    $bankName = trim($_POST['bankName']);
                    $bankName = mysqli_real_escape_string($conn, $bankName);
                    $branch = trim($_POST['branch']);
                    $branch = mysqli_real_escape_string($conn, $branch);
                    $routeNumber = trim($_POST['routeNumber']);
                    $routeNumber = mysqli_real_escape_string($conn, $routeNumber);
                    $pointCode = trim($_POST['pointCode']);
                    $empCode = trim($_POST['empCode']);
                    $paymentMode =trim($_POST['paymentMode']);
                    $smartRateChart = trim($_POST['smartRateChart']);
                    $returnCharge = trim($_POST['returnCharge']);
                    $monthlyInvoice = trim($_POST['monthlyInvoice']);
                    $pickupSMS = trim($_POST['pickupSMS']);
                    $vat = trim($_POST['vat']);
                    $updateSQL ="update tbl_merchant_info set merchantCode='$merchantid' , merchantName='$merchantName' , businessType='$businessType' , 
                    productNature='$productNature' , address='$address' , thanaid='$thanaid'  , districtid='$districtid', website='$website', 
                    facebook='$facebook' , companyPhone='$companyPhone', contactName='$contactName', designation='$designation', contactNumber='$contactNumber' , 
                    email='$email', ratechartId='$rateChart', cod='$cod', accountName='$accountName', 
                    accountNumber='$accountNumber', bankName='$bankName', branch='$branch', routeNumber='$routeNumber' , pointCode='$pointCode', empCode='$empCode', 
                    paymentMode='$paymentMode', smarRateChart = '$smartRateChart', returnCharge = '$returnCharge', monthlyInvoice = '$monthlyInvoice', sendSms = '$pickupSMS', vat = '$vat', update_date = NOW() + INTERVAL 6 HOUR, update_by='$user_check' where merchantCode = '$merchantCode'";
                    if (!mysqli_query($conn,$updateSQL))
                            {
                                $error ="Update Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                //$updateInvoiceStatusSQL = "update tbl_invoice set chequeStatus = 'Y', beftn = 'Y' where merchantCode = '$merchantid'";
                                //if (!mysqli_query($conn, $updateInvoiceStatusSQL)){
                                //    $error ="Update Error : " . mysqli_error($conn);
                                //    echo "<div class='alert alert-danger'>";
                                //        echo "<strong>Error!</strong>".$error; 
                                //    echo "</div>";                                    
                                //} else {
                                    echo "<div class='alert alert-success'>";
        //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                        ?>
                                        <button id="merchantSearch" class="btn btn-info" onclick="return showMerchantList()">Show Search List</button>
                                        <br><br>
                                        <?php   
                                        echo "Merchant updated successfully ";
                                    echo "</div>";                                    
                                //}
                        }           
                    }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#merchantList').bdt({
                    showSearchForm: 1,
                    showEntriesPerPageField: 1
                });

            });
            function showMerchantList() {
                window.location.href = 'editMerchant';
            }
            function activeInactive(inp) {
                $.ajax({
                    type: 'post',
                    url: 'toupdateorders',
                    data: {
                        get_orderid: inp,
                        flagreq: 'merchantStatus'
                    },
                    success: function (response) {
                        $('#btnAI' + inp).html(response);
                        if(response == 'Inactive'){
                            $('#btnAI' + inp).removeClass("btn btn-warning").addClass("btn btn-default");    
                        } else {
                            $('#btnAI' + inp).removeClass("btn btn-default").addClass("btn btn-warning");
                        }
                        
                    }
                })
            }
            function ttVal(submitVal) {
                var inptext = document.getElementById(submitVal).innerHTML;
                document.getElementById("editid").value = inptext;
                document.getElementById("searchuserid").value = inptext;
            }
            function isNumberKey(inpt) {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }

            function fetch_select(val) {
                $.ajax({
                    type: 'post',
                    url: 'thanafetch',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response) {
                        document.getElementById("thana").innerHTML = response;
                        document.getElementById("thana2").innerHTML = response;
                    }
                });
            }

            function fetch_pointCode(val) {
                $.ajax({
                    type: 'post',
                    url: 'pointfetch',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response) {
                        document.getElementById("pointCode").innerHTML = response;
                    }
                });
            }
            function addPoint() {
                var listbox;
                var x = document.getElementById("pointSel");
                for (var i = 0; i < x.options.length; i++) {
                    if (x.options[i].selected == true) {
                        x.options[i].value + "-" + x.options[i].textContent
                        listbox += "<option value=" + x.options[i].value + ">" + x.options[i].textContent + "</option>";
                    }
                }
                document.getElementById('pointAssigned').innerHTML = document.getElementById('pointAssigned').innerHTML + listbox;
            }

            function removeItem(selectbox) {
                var i;
                for (i = selectbox.options.length - 1; i >= 0; i--) {
                    if (selectbox.options[i].selected)
                        selectbox.remove(i);
                }
            }
            function selectDeselect(listid, status) {
                var listb = document.getElementById(listid);
                var len = listb.options.length;
                for (var i = 0; i < len; i++) {
                    listb.options[i].selected = status;
                }
            }  
        </script>
    </body>
</html>
