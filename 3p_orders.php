<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info";
    $merchantresult = mysqli_query($conn,$merchantsql);
    $merchantrow = mysqli_fetch_array($merchantresult);
    $districtsql = "select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info)";
    $districtresult = mysqli_query($conn,$districtsql);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and 3p_order_service = 'Y'"));
    if ($userPrivCheckRow['3p_order_service'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Place a Order</p>
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Merchant&nbsp;&nbsp;&nbsp;<select id ="merchantCode" name="merchantCode" style="margin-left: 10px; width: 250px; height: 28px" required>
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

                            </select><br>&nbsp;
                    </div>
                <div>   
                    Merchant Order Ref&nbsp;<input type="text" name="merOrderRef" style="height: 28px" required>
                </div>
                <div class="radio" style="font: 14px 'paperfly roman'">
                    Pick-up from Ordering Merchant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="op1" type="radio" name="orderType" value="Merchant" onclick="return showHide()">&nbsp;&nbsp;&nbsp;
                    Pick-up from Other Merchant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="op2" type="radio" name="orderType" value="Other_Merchant" onclick="return showHide()" checked>
                </div>
                <table border="0" style="width: 100%">
                    <tr>
                        <td colspan="3">
                            <label style="font-weight: bold"><u>Pick-up Detail:</u></label>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td style="width: 170px">
                            <label>Pick-up Merchant Name</label>
                        </td>
                        <td style="width: 300px">
                            <input id="pickMerchantName" type="text" name="pickMerchantName" style="height: 28px; width: 98%" readonly>
                        </td>
                        <td style="width: 180px">
                            <label>Pick-up Merchant Address</label>
                        </td>
                        <td>
                            <input id="pickMerchantAddress" type="text" name="pickMerchantAddress" style="height: 28px; width: 98%" readonly>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>District/Sub-district</label>
                        </td>
                        <td>
                            <select id="districtId" name="districtId" style="margin-left: 10px; height: 28px; width: 98%" onchange="fetch_merchantThana(this.value);" disabled>
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
                        <td style="width: 300px">
                            <select id="thana" name="thanaId" style="margin-left: 10px; height: 28px; width: 98%" disabled>

                            </select>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Pick-up Merchant Phone</label>
                        </td>
                        <td>
                            <input id="pickupMerchantPhone" type="text" name="pickupMerchantPhone" style="height: 28px; width: 98%" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label style="font-weight: bold"><u>Package and Delivery Detail:</u></label>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Package Option</label>
                        </td>
                        <td>
                            <select name="productSizeWeight" style="margin-left: 10px; height: 28px; width: 98%">
                                <option value="standard">Standard</option>
                                <option value="large">Large</option>
                                <option value="special">Special</option>
                                <option value="specialplus">Special Plus</option>
                            </select>
                        </td>
                        <td>
                            <label>Delivery Option</label>
                        </td>
                        <td>
                            <select name="deliveryOption" style="margin-left: 10px; height: 28px; width: 98%">
                                <option value="regular">Regular</option>
                                <option value="express">Express</option>
                            </select>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Product(s) Brief</label>
                        </td>
                        <td>
                            <input type="text" name="productBrief" style="height: 28px; width: 98%">
                        </td>
                        <td>
                            <label>Package Price</label>
                        </td>
                        <td>
                            <input type="text" name="packagePrice" style="height: 28px; width: 98%" onkeyup="return isNumberKey(this)">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Commission</label>
                        </td>
                        <td>
                            <input type="text" name="Thp_commission" style="height: 28px; width: 98%" onkeyup="return isNumberKey(this)">
                        </td>

                    </tr>
                    <tr>
                        <td colspan="3">
                            <label style="font-weight: bold"><u>Customer Detail:</u></label>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Customer Name</label>
                        </td>
                        <td>
                            <input type="text" name="custname" style="height: 28px; width: 98%" required>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Customer Address</label>
                        </td>
                        <td>
                            <input type="text" name="custaddress" style="height: 28px; width: 98%" required>
                        </td>
                        <td>
                            <label>Customer Phone/Mobile</label>
                        </td>
                        <td>
                            <input type="text" name="custphone" style="height: 28px; width: 98%">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>District/Sub-district</label>
                        </td>
                        <td>
                            <select name="customerDistrict" style="margin-left: 10px; height: 28px; width: 98%" onchange="fetch_customerThana(this.value);">
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
                            <select id="customerThana" name="customerThana" style="margin-left: 10px; height: 28px; width: 98%" required>

                            </select>
                        </td>
                    </tr>
                </table>
                <div style="float: right">
                    <input type="submit" name="submit" value="save" class="btn-primary" onclick="return validation()" style="width: 100px; height: 28px; border-radius: 5%">&nbsp;
                </div>
                <br>
                <br>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    if ($user_check!=''){
                        //$orderType = trim($_POST['orderType']);
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
                        $Thp_commission = trim($_POST['Thp_commission']);
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
                            foreach ($result as $droprow){
                                $droppointcode = $droprow['pointCode'];
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
                                $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$pickuppointcode."-".$droppointcode;
                            //}
                        
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
                            pickMerchantName, pickMerchantAddress, thanaId, districtId, pickupMerchantPhone, productSizeWeight, deliveryOption, Thp_commission, ratechartId, destination, charge, cod,
                            productBrief, packagePrice, custname, custaddress, customerThana, customerDistrict, 
                            custphone, creation_date, created_by) VALUES ('$ordSeq', '$orderid', '$barcode', NOW() + INTERVAL 6 HOUR, 'thirdPick', '$pickuppointcode', '$droppointcode', '$merchantCode', '$merOrderRef',   
                            '$pickMerchantName', '$pickMerchantAddress', '$thanaId', '$districtId', '$pickupMerchantPhone', '$productSizeWeight', '$deliveryOption', '$Thp_commission' ,'$merRateChartId', '$destination', '$charge', '$mercod',
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
                            foreach ($result as $droprow){
                                $droppointcode = $droprow['pointCode'];
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
                                $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$pickuppointcode."-".$droppointcode;
                            //}
                            $merRateIdSQL = "select districtid, ratechartId, cod from tbl_merchant_info where merchantCode ='$merchantCode'";
                            $merRateIdResult = mysqli_query($conn, $merRateIdSQL);
                            $merRateIdRow = mysqli_fetch_array($merRateIdResult);
                            $merRateChartId = $merRateIdRow['ratechartId'];
                            $mercod = $merRateIdRow['cod'];
                            $merdistrictid = $merRateIdRow['districtid'];
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
                            pickMerchantName, pickMerchantAddress, thanaId, districtId, pickupMerchantPhone, productSizeWeight, deliveryOption, Thp_commission, ratechartId, destination, charge, cod,
                            productBrief, packagePrice, custname, custaddress, customerThana, customerDistrict, 
                            custphone, creation_date, created_by) VALUES ('$ordSeq', '$orderid', '$barcode', NOW() + INTERVAL 6 HOUR, 'thirdPick', '$pickuppointcode', '$droppointcode', '$merchantCode', '$merOrderRef',   
                            '$pickMerchantName', '$pickMerchantAddress', '$thanaId', '$districtId', '$pickupMerchantPhone', '$productSizeWeight', '$deliveryOption', '$Thp_commission','$merRateChartId', '$destination', '$charge', '$mercod',
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
            $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
               $('#merchantCode').select2();
            });
            showHide();
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
