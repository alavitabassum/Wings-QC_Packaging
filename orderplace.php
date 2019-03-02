<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info";
    $merchantresult = mysqli_query($conn,$merchantsql);
    $merchantrow = mysqli_fetch_array($merchantresult);
    $districtsql = "select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info where isActive = 'Y')";
    $districtresult = mysqli_query($conn,$districtsql);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and new_order = 'Y'"));
    if ($userPrivCheckRow['new_order'] != 'Y'){
        exit();
    }
    if ($user_type == 'Merchant'){
        $merchantRow = mysqli_fetch_array(mysqli_query($conn, "select merchantCode, merchantName, isActive from tbl_merchant_info where merchantCode = '$user_code'")) ;
        if($merchantRow['isActive'] == 'N'){
            echo '<div class="container">';
                echo 'This account is currently inactive. <br>For assistance please call 01998706063 or email to: info@paperfly.com.bd or leave a message to our Facebook page.';
            echo '</div>';
            exit();
        }
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Place a Order</p>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="merchantList" style="text-align: right">Merchant</label>
                        </div>
                        <div class="col-sm-4">
                            <select id="merchantCode" name="merchantCode" style="width: 100%" required>
                                <?php if ($user_type == 'Merchant'){
                                    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info where merchantCode = '$user_code'";
                                    $merchantresult = mysqli_query($conn,$merchantsql);
                                    $merchantrow = mysqli_fetch_array($merchantresult);
                                    echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";     
                                } else {?>
                                    <option></option>
                                    <?php
                                        foreach ($merchantresult as $merchantrow){
                                            echo "<option value=".$merchantrow['merchantCode'].">".$merchantrow['merchantName']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="col-sm-2">
                            <label for="merOrderRef" style="text-align: right">Merchant Order Ref.</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="merOrderRef" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3" style="text-align: right">
                            Pick-up from Ordering Merchant&nbsp;<input id="op1" type="radio" name="orderType" style="margin-top: -2px" value="Merchant" onclick="return showHide()" checked>
                        </div>
                        <div class="col-sm-3" style="text-align: right">
                            Pick-up from Other Merchant&nbsp;<input id="op2" type="radio" style="margin-top: -2px" name="orderType" value="Other_Merchant" onclick="return showHide()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="pickDetail" style="font-weight: bold"><u>Pick-up Detail:</u></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label style="text-align: right">Pick-up Merchant Name</label>
                        </div>
                        <div class="col-sm-4">
                            <input id="pickMerchantName" type="text" name="pickMerchantName" class="form-control" readonly>
                        </div>
                        <div class="col-sm-2">
                            <label style="text-align: right">Pick-up Merchant Address</label>
                        </div>
                        <div class="col-sm-4">
                            <input id="pickMerchantAddress" type="text" name="pickMerchantAddress" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label style="text-align: right">District/Sub-district</label>
                        </div>
                        <div class="col-sm-4">
                            <select id="districtId" name="districtId" style="width: 100%" onchange="fetch_merchantThana(this.value);" disabled>
                                <option></option>
                                <?php
                                    foreach ($districtresult as $districtrow){
                                        echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label style="text-align: right">Thana</label>
                        </div>
                        <div class="col-sm-4">
                            <select id="thana" name="thanaId" style="width: 100%" disabled>
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="col-sm-2">
                            <label style="text-align: right">Pick-up Merchant Phone</label>
                        </div>
                        <div class="col-sm-4">
                            <input id="pickupMerchantPhone" type="text" name="pickupMerchantPhone" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label style="font-weight: bold"><u>Package and Delivery Detail:</u></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label style="text-align: right">Package Option</label>
                        </div>
                        <div class="col-sm-4">
                            <select name="productSizeWeight" style="width: 100%">
                                <option value="standard">Standard</option>
                                <option value="large">Large</option>
                                <option value="special">Special</option>
                                <option value="specialplus">Special Plus</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label style="text-align: right">Delivery Option</label>
                        </div>
                        <div class="col-sm-4">
                            <select name="deliveryOption" style="width: 100%">
                                <option value="regular">Regular</option>
                                <option value="express">Express</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="col-sm-2">
                            <label style="text-align: right">Product(s) Brief</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="productBrief" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <label style="text-align: right">Package Price (maximum 7 digits)</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="packagePrice" name="packagePrice" class="form-control" onkeyup="return isNumberKey(this)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label style="font-weight: bold"><u>Customer Detail:</u></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label style="text-align: right">Customer Name</label>     
                        </div>     
                        <div class="col-sm-4">
                            <input type="text" name="custname" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label style="text-align: right">Customer Address</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="custaddress" class="form-control" required>
                        </div>
                        <div class="col-sm-2">
                             <label style="text-align: right">Customer Phone/Mobile</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="custphone" class="form-control" onkeyup="return isNumberKey(this)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6" style="padding-left: 3px; padding-right: 2px">
                            <div class="col-sm-4">
                                <label style="text-align: right">District/Sub-district</label>
                            </div>     
                            <div class="col-sm-8">
                                <select name="customerDistrict" style="width: 100%" onchange="fetch_customerThana(this.value);">
                                    <option></option>
                                    <?php
                                        foreach ($districtresult as $districtrow){
                                            echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" style="padding-left: 3px; padding-right: 2px">
                            <p style="color: red; text-align: right; font: 12px 'paperfly roman'; padding-right: 15px">Please select customer thana carefully. This helps us ensure on time delivery</p>
                            <div class="col-sm-4">
                                <label style="text-align: right">Thana</label>
                            </div>
                            <div class="col-sm-8">
                                <select id="customerThana" name="customerThana" style="width: 100%" required>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" style="text-align: right; margin-top: 10px">
                            <input type="submit" name="submit" value="Save" class="btn btn-primary" onclick="return validation()">
                        </div>
                    </div>
                </div>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    if ($user_check!=''){
                        $orderType = trim($_POST['orderType']);
                        $merchantCode = trim($_POST['merchantCode']);
                        $merOrderRef = trim($_POST['merOrderRef']);
                        $merOrderRef = mysqli_real_escape_string($conn, $merOrderRef);
                        $timeSQL="select NOW() + INTERVAL 6 HOUR as currenttime";
                        $timeResult = mysqli_query($conn, $timeSQL);
                        $timeRow = mysqli_fetch_array($timeResult);
                        $orderDate = date("Y-m-d", strtotime($timeRow['currenttime']));
                        //$orderDate = date("Y-m-d");
                        $pickMerchantName = trim($_POST['pickMerchantName']);
                        $pickMerchantName = mysqli_real_escape_string($conn, $pickMerchantName);
                        $pickMerchantAddress = trim($_POST['pickMerchantAddress']);
                        $pickMerchantAddress = mysqli_real_escape_string($conn, $pickMerchantAddress);
                        $thanaId = trim($_POST['thanaId']);
                        $districtId = trim($_POST['districtId']);
                        $pickupMerchantPhone = trim($_POST['pickupMerchantPhone']);
                        $pickupMerchantPhone = mysqli_real_escape_string($conn, $pickupMerchantPhone);
                        $productSizeWeight = trim($_POST['productSizeWeight']);
                        $productBrief = trim($_POST['productBrief']);
                        $productBrief = mysqli_real_escape_string($conn, $productBrief);
                        $packagePrice = trim($_POST['packagePrice']);
                        $deliveryOption = trim($_POST['deliveryOption']);
                        $custname = trim($_POST['custname']);
                        $custname = mysqli_real_escape_string($conn, $custname);
                        $custaddress = trim($_POST['custaddress']);
                        $custaddress = mysqli_real_escape_string($conn, $custaddress);
                        $customerThana = trim($_POST['customerThana']);
                        $customerDistrict = trim($_POST['customerDistrict']);
                        $custphone = trim($_POST['custphone']);
                        $custphone = mysqli_real_escape_string($conn, $custphone);
                        //selecting max auto id
                        $maxemordid ="Select max(ordSeq) as ordSeq from tbl_order_details where orderDate='$orderDate'";
                        $maxresult = mysqli_query($conn, $maxemordid);
                        foreach ($maxresult as $maxrow){
                            $orderid = $maxrow['ordSeq']+1;
                            $ordSeq = $maxrow['ordSeq']+1; 
                        }
                        switch (strlen($orderid)){
                            case 1: $orderid = "000".$orderid;
                            break;
                            case 2: $orderid = "00".$orderid;
                            break;
                            case 3: $orderid = "0".$orderid;
                            break;
                            default:
                                echo "Null";
                        }
                        $barcode = date("dmy", strtotime($orderDate)).$orderid."0";
                        // if pick up merchant is unregistered merchant then registered pickup merchant pick up point will be considered
                        if ($pickMerchantName != ""){
                            $pickuppoint = "Select pointCode from tbl_point_coverage where thanaId = '$thanaId'";
                            $result = mysqli_query($conn, $pickuppoint);
                            foreach ($result as $pointrow){
                                $merchantPointCode = $pointrow['pointCode'];
                            }
                            $pickupSystemSQL = "Select pointCode, pickPointCode from tbl_regular_point where pointCode = '$merchantPointCode'";
                            $pickupSystemResult = mysqli_query($conn, $pickupSystemSQL);
                            $pickupSystemRow = mysqli_fetch_array($pickupSystemResult);
                            $pickuppointcode = $pickupSystemRow['pickPointCode'];

                            $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$customerThana'";
                            $result = mysqli_query($conn, $droppoint);
                            if (mysqli_num_rows($result) > 0) {
                                foreach ($result as $droprow){
                                    $droppointcode = $droprow['pointCode'];
                                }

                                $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$pickuppointcode."-".$droppointcode;
                        
                                if ($pickupSystemRow['local'] == 'Y'){
                                    if ($pickuppoint == ""){
                                    echo "<div class='alert alert-danger'>";
                                        exit ("Pick up point not found!!! Unable to create order!");
                                    echo "</div>";
                                    }
                                }
                                $merRateIdSQL = "select ratechartId, cod from tbl_merchant_info where merchantCode ='$merchantCode'";
                                $merRateIdResult = mysqli_query($conn, $merRateIdSQL);
                                $merRateIdRow = mysqli_fetch_array($merRateIdResult);
                                $merRateChartId = $merRateIdRow['ratechartId'];
                                $mercod = $merRateIdRow['cod'];
                                //$testSQL = "insert into tbl_test (merchantCode, merchantDistrict, customerDistrict, merchantType) values ('$merchantCode', '$districtId', '$customerDistrict', 'P')";
                                //$testResult = mysqli_query($conn, $testSQL);
                                if ($districtId != $customerDistrict){
                                    $destination = 'interDistrict';
                                } else {
                                    $destination = 'local';
                                }
                                $orderChargeSQL = "SELECT * FROM tbl_rate_type where ratechartId = '$merRateChartId' and packageOption = '$productSizeWeight' and deliveryOption = '$deliveryOption' and destination = '$destination'";
                                $orderChargeResult = mysqli_query($conn, $orderChargeSQL);
                                $orderChargeRow = mysqli_fetch_array($orderChargeResult);
                                $charge = $orderChargeRow['charge'];

                                $sql ="INSERT INTO tbl_order_details(ordSeq, orderid, barcode, orderDate, orderType, pickPointCode, dropPointCode, merchantCode, merOrderRef,  
                                pickMerchantName, pickMerchantAddress, thanaId, districtId, pickupMerchantPhone, productSizeWeight, deliveryOption, ratechartId, destination, charge, cod,
                                productBrief, packagePrice, custname, custaddress, customerThana, customerDistrict, 
                                custphone, creation_date, created_by) VALUES ('$ordSeq', '$orderid', '$barcode', NOW() + INTERVAL 6 HOUR, '$orderType', '$pickuppointcode', '$droppointcode', '$merchantCode', '$merOrderRef',   
                                '$pickMerchantName', '$pickMerchantAddress', '$thanaId', '$districtId', '$pickupMerchantPhone', '$productSizeWeight', '$deliveryOption', '$merRateChartId', '$destination', '$charge', '$mercod',
                                '$productBrief', '$packagePrice', '$custname', '$custaddress', '$customerThana', '$customerDistrict',
                                '$custphone', NOW() + INTERVAL 6 HOUR , '$user_check')";
                                if (!mysqli_query($conn,$sql))
                                    {
                                        $error ="Insert Error : " . mysqli_error($conn);
                                        echo "<div class='alert alert-danger'>";
                                            echo "<strong>Error!</strong>".$error; 
                                        echo "</div>";
                                    } else {
                                        echo "<div class='alert alert-success'>";
            //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                            echo "Order ID ".$orderid." created successfully";
                                        echo "</div>";
                                    }

                                } else {
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong> Customer Thana Not Selected"; 
                                    echo "</div>";
                                    exit;
                                }
                            //if ($productSizeWeight == 'large' or $productSizeWeight == 'special' or $productSizeWeight == 'specialplus'){
                            //    $districtPoint = "select pointCode, dropPointCode from tbl_central_point where districtId = '$districtId'";
                            //    $districtPointResult = mysqli_query($conn, $districtPoint);
                            //    foreach ($districtPointResult as $districtPointRow){
                            //        $centralPoint = $districtPointRow['pointCode'];
                            //        $centralDropPoint = $districtPointRow['dropPointCode'];
                            //        if ($districtId != $customerDistrict){
                            //            $customerdistrictPoint = "select pointCode, dropPointCode from tbl_central_point where districtId = '$customerDistrict'";
                            //            $customerdistrictPointResult = mysqli_query($conn, $customerdistrictPoint);                                        
                            //            foreach ($customerdistrictPointResult as $droprow){
                            //                $centralDropPoint = $droprow['dropPointCode'];
                            //                if ($centralDropPoint == 'customer'){
                            //                    $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$customerThana'";
                            //                    $result = mysqli_query($conn, $droppoint);
                            //                    foreach ($result as $droprow){
                            //                        $centralDropPoint = $droprow['pointCode'];
                            //                    }                                        
                            //                }
                            //            }                                        
                            //        } else {
                            //            if ($centralDropPoint == 'customer'){
                            //                $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$customerThana'";
                            //                $result = mysqli_query($conn, $droppoint);
                            //                foreach ($result as $droprow){
                            //                    $centralDropPoint = $droprow['pointCode'];
                            //                }                                        
                            //            }                                        
                            //        }
                            //    }
                            //    $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$centralPoint."-".$centralDropPoint;
                            //    $pickuppointcode = $centralPoint;
                            //    $droppointcode = $centralDropPoint;
                            //} else {

                        } else {
                            //indentify pickup point for pick up merchant ID
                            $pickuppoint = "Select pointCode from tbl_merchant_info where merchantCode = '$merchantCode'";
                            $result = mysqli_query($conn, $pickuppoint);
                            foreach ($result as $pointrow){
                                $merchantPointCode = $pointrow['pointCode'];
                            }
                            $pickupSystemSQL = "Select pointCode, pickPointCode from tbl_regular_point where pointCode = '$merchantPointCode'";
                            $pickupSystemResult = mysqli_query($conn, $pickupSystemSQL);
                            $pickupSystemRow = mysqli_fetch_array($pickupSystemResult);
                            $pickuppointcode = $pickupSystemRow['pickPointCode'];
                            // identify drop point
                            $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$customerThana'";
                            $result = mysqli_query($conn, $droppoint);
                            if (mysqli_num_rows($result) > 0) {
                                foreach ($result as $droprow){
                                    $droppointcode = $droprow['pointCode'];
                                }

                                $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$pickuppointcode."-".$droppointcode;

                                $merRateIdSQL = "select districtid, ratechartId, cod from tbl_merchant_info where merchantCode ='$merchantCode'";
                                $merRateIdResult = mysqli_query($conn, $merRateIdSQL);
                                $merRateIdRow = mysqli_fetch_array($merRateIdResult);
                                $merRateChartId = $merRateIdRow['ratechartId'];
                                $mercod = $merRateIdRow['cod'];
                                $merdistrictid = $merRateIdRow['districtid'];
                                //$testSQL = "insert into tbl_test (merchantCode, merchantDistrict, customerDistrict, merchantType) values ('$merchantCode', '$merdistrictid', '$customerDistrict', 'M')";
                                //$testResult = mysqli_query($conn, $testSQL);
                                if ($merdistrictid != $customerDistrict){
                                    $destination = 'interDistrict';
                                } else {
                                    $destination = 'local';
                                }
                                $orderChargeSQL = "SELECT * FROM tbl_rate_type where ratechartId = '$merRateChartId' and packageOption = '$productSizeWeight' and deliveryOption = '$deliveryOption' and destination = '$destination'";
                                $orderChargeResult = mysqli_query($conn, $orderChargeSQL);
                                $orderChargeRow = mysqli_fetch_array($orderChargeResult);
                                $charge = $orderChargeRow['charge'];

                                $sql ="INSERT INTO tbl_order_details(ordSeq, orderid, barcode, orderDate, orderType, pickPointCode, dropPointCode, merchantCode, merOrderRef,  
                                pickMerchantName, pickMerchantAddress, thanaId, districtId, pickupMerchantPhone, productSizeWeight, deliveryOption, ratechartId, destination, charge, cod,
                                productBrief, packagePrice, custname, custaddress, customerThana, customerDistrict, 
                                custphone, creation_date, created_by) VALUES ('$ordSeq', '$orderid', '$barcode', NOW() + INTERVAL 6 HOUR, '$orderType', '$pickuppointcode', '$droppointcode', '$merchantCode', '$merOrderRef',   
                                '$pickMerchantName', '$pickMerchantAddress', '$thanaId', '$districtId', '$pickupMerchantPhone', '$productSizeWeight', '$deliveryOption', '$merRateChartId', '$destination', '$charge', '$mercod',
                                '$productBrief', '$packagePrice', '$custname', '$custaddress', '$customerThana', '$customerDistrict',
                                '$custphone', NOW() + INTERVAL 6 HOUR , '$user_check')";
                                if (!mysqli_query($conn,$sql))
                                    {
                                        $error ="Insert Error : " . mysqli_error($conn);
                                        echo "<div class='alert alert-danger'>";
                                            echo "<strong>Error!</strong>".$error; 
                                        echo "</div>";
                                    } else {
                                        echo "<div class='alert alert-success'>";
            //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                            echo "Order ID ".$orderid." created successfully";
                                        echo "</div>";
                                    }

                                } else {
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong> Customer Thana Not Selected"; 
                                    echo "</div>";
                            }
                            //if ($productSizeWeight == 'large' or $productSizeWeight == 'special' or $productSizeWeight == 'specialplus'){
                            //    $merchantdistrictpoint = "Select districtid from tbl_merchant_info where merchantCode = '$merchantCode'";
                            //    $result = mysqli_query($conn, $merchantdistrictpoint);
                            //    foreach ($result as $merchantDistrictrow){
                            //        $merchantDistrictId = $merchantDistrictrow['districtid'];
                            //    }
                            //    $districtPoint = "select pointCode, dropPointCode from tbl_central_point where districtId = '$merchantDistrictId'";
                            //    $districtPointResult = mysqli_query($conn, $districtPoint);
                            //    foreach ($districtPointResult as $districtPointRow){
                            //        $centralPoint = $districtPointRow['pointCode'];
                            //        $centralDropPoint = $districtPointRow['dropPointCode'];
                            //        if ($merchantDistrictId != $customerDistrict){
                            //            $customerdistrictPoint = "select pointCode, dropPointCode from tbl_central_point where districtId = '$customerDistrict'";
                            //            $customerdistrictPointResult = mysqli_query($conn, $customerdistrictPoint);                                        
                            //            foreach ($customerdistrictPointResult as $droprow){
                            //                $centralDropPoint = $droprow['dropPointCode'];
                            //                if ($centralDropPoint == 'customer'){
                            //                    $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$customerThana'";
                            //                    $result = mysqli_query($conn, $droppoint);
                            //                    foreach ($result as $droprow){
                            //                        $centralDropPoint = $droprow['pointCode'];
                            //                    }                                        
                            //                }
                            //            }                                        
                            //        } else {
                            //            if ($centralDropPoint == 'customer'){
                            //                $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$customerThana'";
                            //                $result = mysqli_query($conn, $droppoint);
                            //                foreach ($result as $droprow){
                            //                    $centralDropPoint = $droprow['pointCode'];
                            //                }                                        
                            //            }                                        
                            //        }
                            //    }
                            //
                            //    $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$centralPoint."-".$centralDropPoint;
                            //    $pickuppointcode = $centralPoint;
                            //    $droppointcode = $centralDropPoint;
                            //} else {
                        }
                    } else {
                        echo "<div class='alert alert-danger'>";
                            echo "<strong>Error!</strong> : Unable to insert order"; 
                        echo "</div>";
                    }
                }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript">
            $(window).load(function ()
            {
                $('select').select2();
            });
            function showHide()
            {
                if (document.getElementById("op2").checked == true)
                {
                    document.getElementById("pickMerchantName").readOnly = false;
                    document.getElementById("pickMerchantAddress").readOnly = false;
                    document.getElementById("pickupMerchantPhone").readOnly = false;
                    document.getElementById("districtId").disabled = false;
                    document.getElementById("thana").disabled = false;
                } else
                {
                    document.getElementById("pickMerchantName").readOnly = true;
                    document.getElementById("pickMerchantName").value = '';
                    document.getElementById("pickMerchantAddress").readOnly = true;
                    document.getElementById("pickMerchantAddress").value = '';
                    document.getElementById("pickupMerchantPhone").readOnly = true;
                    document.getElementById("pickupMerchantPhone").value = '';
                    document.getElementById("districtId").disabled = true;
                    document.getElementById("districtId").value = '';
                    document.getElementById("thana").disabled = true;
                    document.getElementById("thana").value = '';
                }
            }
            $('#packagePrice').change(function ()
            {
                var packagePrice = $('#packagePrice').val();
                if (packagePrice.length > 7)
                {
                    $('#packagePrice').val(packagePrice.substring(0,7));
                }
            })
            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }
            function validation()
            {
                if (document.getElementById("pickMerchantName").value != "")
                {
                    if (document.getElementById("thana").value == "")
                    {
                        alert("Thana not selected for unregisterd merchant");
                        return false;
                    }
                }
            }

            function fetch_merchantThana(val)
            {
                $.ajax({
                    type: 'post',
                    url: 'fetch_thana.php',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response)
                    {
                        document.getElementById("thana").innerHTML = response;
                    }
                });
            }

            function fetch_customerThana(val)
            {
                $.ajax({
                    type: 'post',
                    url: 'fetch_thana.php',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response)
                    {
                        document.getElementById("customerThana").innerHTML = response;
                    }
                });
            }
        </script>
    </body>
</html>
