<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $districtsql = "select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info)";
    $districtresult = mysqli_query($conn,$districtsql);

    $rateChartSQL = "select * from tbl_ratechart_name";
    $rateChartResult = mysqli_query($conn, $rateChartSQL);

    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and merchant = 'Y'"));
    if ($userPrivCheckRow['merchant'] != 'Y'){
        exit();
    }

?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">New Merchant</p>
                <table style="width: 100%">
                    <tr>
                        <td colspan="3">
                            <label style="font-weight: bold"><u>Basic Information:</u></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Merchant Name</label>
                        </td>
                        <td>
                            <input type="text" name="merchantName" style="height: 25px" required>
                        </td>
                        <td>
                            <label>Business Type</label>
                        </td>
                        <td>
                            <select id="businessType" name="businessType" style="width: 92%; height: 25px">
                                <option value="M">Market Place</option>
                                <option value="S">Shop</option>
                            </select>
                        </td>
                        <td>
                            <label>Product(s) Nature</label>
                        </td>
                        <td>
                            <input type="text" name="productNature" style="height: 25px">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Address</label>
                        </td>
                        <td>
                            <input type="text" name="address" style="height: 25px">
                        </td>
                        <td>
                            <label>District</label>
                        </td>
                        <td>
                            <select name="districtid" style="width: 92%; height: 25px" onchange="fetch_select(this.value);" required>
                                <option></option>
                                <?php
                                    foreach ($districtresult as $districtrow){
                                        echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <label>Thana</label>
                        </td>
                        <td>
                            <select id="thana" name="thanaid" style="width: 92%; height: 25px" onchange="fetch_pointCode(this.value)" required>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Website</label>
                        </td>
                        <td>
                            <input type="text" name="website" style="height: 25px">
                        </td>
                        <td>
                            <label>Facebook Page</label>
                        </td>
                        <td>
                            <input type="text" name="facebook" style="height: 25px">
                        </td>
                        <td>
                            <label>Company Phone</label>
                        </td>
                        <td>
                            <input type="text" name="companyPhone" style="height: 25px">
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
                            <input type="text" name="contactName" style="height: 25px" required>
                        </td>
                        <td>
                            <label>Designation</label>
                        </td>
                        <td>
                            <input type="text" name="designation" style="height: 25px">
                        </td>
                        <td>
                            <label>Contact Number</label>
                        </td>
                        <td>
                            <input type="text" name="contactNumber" style="height: 25px" required>
                        </td>
                        <td>
                            <label>Email</label>
                        </td>
                        <td>
                            <input type="text" name="email" style="height: 25px">
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
                            <select id="rateChart" name="rateChart">
                                <?php foreach ($rateChartResult as $rateChartRow) {?>
                                <option value="<?php echo $rateChartRow['ratechartId'];?>"><?php echo $rateChartRow['rateChartName'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <label>CoD (%)</label>
                        </td>
                        <td>
                            <input type="text" name="cod" style="height: 25px" onkeyup="return isNumberKey(this)">
                        </td>
                        <td><label>Smart Rate Chart</label></td>
                        <td>
                            <select id="smartRateChart" name="smartRateChart">
                                <option></option>
                                <?php foreach ($rateChartResult as $smartRateChartRow) {?>
                                <option value="<?php echo $smartRateChartRow['ratechartId'];?>"><?php echo $smartRateChartRow['rateChartName'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><label>Return Charge</label></td>
                        <td>
                            <select id="returnCharge" name="returnCharge">
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
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
                            <input type="text" name="accountName" style="height: 25px">
                        </td>
                        <td>
                            <label>Account Number</label>
                        </td>
                        <td>
                            <input type="text" name="accountNumber" style="height: 25px">
                        </td>
                        <td>
                            <label>Bank Name</label>
                        </td>
                        <td>
                            <input type="text" name="bankName" style="height: 25px">
                        </td>
                        <td>
                            <label>Branch</label>
                        </td>
                        <td>
                            <input type="text" name="branch" style="height: 25px">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Routing Number</label>
                        </td>
                        <td>
                            <input type="text" name="routeNumber" style="height: 25px">
                        </td>
                        <td>
                            <label>Payment Mode</label>
                        </td>
                        <td colspan="2">
                            <select name="paymentMode" style="width: 70%; height: 25px" required>
                                <option value="cheque">Cheque</option>
                                <option value="beftn">BEFTN</option>
                                <option value="cash">Cash</option>
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
                            <select id="pointCode" name="pointCode" style="width: 92%; height: 25px" required></select>
                        </td>
                        <td>
                            <label>Relationship Manager</label>
                        </td>
                        <td>
                            <select id="relationId" name="empCode" style="width: 60%; height: 25px" required>
                                <option></option>
                                <?php
                                    $empsql = "Select tbl_employee_info.empCode, tbl_employee_info.empName, tbl_designation_info.name 
                                                from tbl_employee_info, tbl_designation_info where tbl_employee_info.desigid=tbl_designation_info.desigid and tbl_employee_info.desigid=7 and tbl_employee_info.isActive = 'Y'";
                                    $empresult = mysqli_query($conn, $empsql);
                                    foreach ($empresult as $emprow){
                                        echo "<option value='".$emprow['empCode']."'>".$emprow['empName']." - ".$emprow['name']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            <label>Monthly Invoice</label>
                        </td>
                        <td>
                            <select id="monthlyInvoice" name="monthlyInvoice" style="width: 92%; height: 25px" required>
                                <option value="N">No</option>
                                <option value="Y">Yes</option>
                            </select>
                        </td>
                        <td>
                            <label>Pick-up SMS</label>
                        </td>
                        <td>
                            <select id="pickupSMS" name="pickupSMS" style="width: 92%; height: 25px" required>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>VAT(%)</label>
                        </td>
                        <td>
                            <input type="text" id="vat" name="vat" style="height: 25px" onkeyup="return isNumberKey(this)">
                        </td>
                    </tr>
                </table>
                <div style="float: right">
                    <input type="submit" name="submit" value="save" class="btn-primary" onclick="selectDeselect('pointAssigned', true)" style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                </div>
                <br>
                <br>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    $districtid = trim($_POST['districtid']);
                    $maxmerid ="Select max(merchant_id) as merchantid from tbl_merchant_info";
                    $maxresult = mysqli_query($conn, $maxmerid);
                    foreach ($maxresult as $maxrow){
                        $merchantid = $maxrow['merchantid']+1;
                        $maxSeq = $maxrow['merchantid']+1;
                    }
                    $merchantprefix = "M-".$districtid;

                    switch (strlen($merchantid)){
                        case 1: $merchantid = $merchantprefix."-000".$merchantid;
                        $userID = 'm'.$districtid.'000'.$maxSeq;
                        break;
                        case 2: $merchantid = $merchantprefix."-00".$merchantid;
                        $userID = 'm'.$districtid.'00'.$maxSeq;
                        break;
                        case 3: $merchantid = $merchantprefix."-0".$merchantid;
                        $userID = 'm'.$districtid.'0'.$maxSeq;
                        break;
                        case 4: $merchantid = $merchantprefix."-".$merchantid;
                        $userID = 'm'.$districtid.$maxSeq;
                        break;
                        default:
                            echo "Null";
                    }
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
                    $smartRateChart = trim($_POST['smartRateChart']);
                    $accountName = trim($_POST['accountName']);
                    $accountName = mysqli_real_escape_string($conn, $accountName);
                    $accountNumber = trim($_POST['accountNumber']);
                    $accountNumber = mysqli_real_escape_string($conn, $accountNumber);
                    $bankName = trim($_POST['bankName']);
                    $bankName = mysqli_real_escape_string($conn, $bankName);
                    $branch = trim($_POST['branch']);
                    $branch = mysqli_real_escape_string($conn, $branch);
                    $routeNumber =trim($_POST['routeNumber']);
                    $routeNumber = mysqli_real_escape_string($conn, $routeNumber);
                    $pointCode = trim($_POST['pointCode']);
                    $empCode = trim($_POST['empCode']);
                    $paymentMode = trim($_POST['paymentMode']);
                    $statementDate = new DateTime(date('Y-m-d', strtotime('+6 hour')));
                    $statementDate->sub(new DateInterval('P1D'));
                    $statementDate = date_format($statementDate, 'Y-m-d');
                    $returnCharge = trim($_POST['returnCharge']);
                    $monthlyInvoice = trim($_POST['monthlyInvoice']);
                    $pickupSMS = trim($_POST['pickupSMS']);
                    $vat = trim($_POST['vat']);
                    $insertsql = "INSERT INTO tbl_merchant_info(merchantCode, merchantName, businessType, productNature, 
                    address, thanaid, districtid, website, facebook, companyPhone, contactName, designation, contactNumber, email, 
                    ratechartId, cod, smarRateChart, accountName, accountNumber, bankName, branch, routeNumber, pointCode, empCode, paymentMode, statementDate, returnCharge, monthlyInvoice, sendSms, vat, creation_date, created_by) 
                    VALUES ('$merchantid' ,'$merchantName' ,'$businessType' ,'$productNature' ,'$address' ,'$thanaid'  ,'$districtid', '$website',
                    '$facebook' , '$companyPhone', '$contactName', '$designation', '$contactNumber', '$email', '$rateChart', '$cod', '$smartRateChart', '$accountName', '$accountNumber', '$bankName', '$branch', '$routeNumber' ,
                    '$pointCode', '$empCode', '$paymentMode', '$statementDate', '$returnCharge', '$monthlyInvoice', '$pickupSMS', '$vat', NOW() + INTERVAL 6 HOUR , '$user_check')";
                    if (!mysqli_query($conn,$insertsql))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                if(mysqli_query($conn, "insert into tbl_user_info (userName, userPassword, userType, merchEmpCode, creation_date, created_by) values ('$userID', md5('$userID'), 'Merchant', '$merchantid', NOW() + INTERVAL 6 HOUR, '$user_check')")){
                                    $user_insert_id = mysqli_insert_id($conn);
                                    echo '<script>
                                            $.ajax(
                                            {
                                                type: "post",
                                                url: "sendMessage",
                                                data:
                                                {
                                                    rootData: "'.$userID.'",
                                                    phone: "'.$contactNumber.'",
                                                    flagreq: "smsMerchantUser"
                                                },
                                                success: function (response){
                                                }
                                            });
                                    </script>';
                                    mysqli_query($conn, "INSERT INTO tbl_permission_group (user_id, Order_Management, User_Setting, Report, creation_date, created_by) VALUES ('$user_insert_id', 'Y', 'Y', 'Y', NOW() + INTERVAL 6 HOUR, '$user_check')");
                                    mysqli_query($conn, "insert into tbl_menu_report (user_id, closedOrders, printInvoices, creation_date, created_by) values ('$user_insert_id', 'Y', 'Y', NOW() + INTERVAL 6 HOUR, '$user_check')");
                                    mysqli_query($conn, "insert into tbl_menu_ordermgt (user_id, new_order, creation_date, created_by) values ('$user_insert_id', 'Y', NOW() + INTERVAL 6 HOUR, '$user_check')");
                                    mysqli_query($conn, "insert into tbl_order_tracker (user_id, level, creation_date, created_by) values ('$user_insert_id', 'level1', NOW() + INTERVAL 6 HOUR, '$user_check')");
                                }
                                echo "<div class='alert alert-success'>";
    //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                    echo "Merchant ".$merchantid." created successfully ";
                                echo "</div>";
                        }
                }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript">
            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }

            function fetch_select(val)
            {
                $.ajax({
                    type: 'post',
                    url: 'thanafetch',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response)
                    {
                        document.getElementById("thana").innerHTML = response;
                        document.getElementById("thana2").innerHTML = response;
                    }
                });
            }

            function fetch_pointCode(val)
            {
                $.ajax({
                    type: 'post',
                    url: 'pointfetch',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response)
                    {
                        document.getElementById("pointCode").innerHTML = response;
                    }
                });
            }
            function addPoint()
            {
                var listbox;
                var x = document.getElementById("pointSel");
                for (var i = 0; i < x.options.length; i++)
                {
                    if (x.options[i].selected == true)
                    {
                        x.options[i].value + "-" + x.options[i].textContent
                        listbox += "<option value=" + x.options[i].value + ">" + x.options[i].textContent + "</option>";
                    }
                }
                document.getElementById('pointAssigned').innerHTML = document.getElementById('pointAssigned').innerHTML + listbox;
            }

            function removeItem(selectbox)
            {
                var i;
                for (i = selectbox.options.length - 1; i >= 0; i--)
                {
                    if (selectbox.options[i].selected)
                        selectbox.remove(i);
                }
            }
            function selectDeselect(listid, status)
            {
                var listb = document.getElementById(listid);
                var len = listb.options.length;
                for (var i = 0; i < len; i++)
                {
                    listb.options[i].selected = status;
                }
            }  
        </script>
    </body>
</html>
