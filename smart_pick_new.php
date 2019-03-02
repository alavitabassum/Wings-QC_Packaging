<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $merchantsql = "select merchantCode, merchantName from tbl_merchant_info";
    $merchantresult = mysqli_query($conn,$merchantsql);
    $merchantrow = mysqli_fetch_array($merchantresult);
    $districtsql = "select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info)";
    $districtresult = mysqli_query($conn,$districtsql);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and smart_pick = 'Y'"));
    if ($userPrivCheckRow['smart_pick'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Place a Order</p>
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Merchant<select name="merchantCode" style="margin-left: 10px; height: 28px" required>
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
                <div>   
                    Merchant Order Ref&nbsp;<input type="text" name="merOrderRef" style="height: 28px" required>
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
                            <input id="pickMerchantName" type="text" name="pickMerchantName" style="height: 28px; width: 98%">
                        </td>
                        <td style="width: 180px">
                            <label>Pick-up Merchant Address</label>
                        </td>
                        <td>
                            <input id="pickMerchantAddress" type="text" name="pickMerchantAddress" style="height: 28px; width: 98%">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>District/Sub-district</label>
                        </td>
                        <td>
                            <select id="districtId" name="districtId" style="margin-left: 10px; height: 28px; width: 98%" onchange="fetch_merchantThana(this.value);">
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
                            <select id="thana" name="thanaId" style="margin-left: 10px; height: 28px; width: 98%">

                            </select>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Pick-up Merchant Phone</label>
                        </td>
                        <td>
                            <input id="pickupMerchantPhone" type="text" name="pickupMerchantPhone" style="height: 28px; width: 98%">
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
                        $barcode = date("dmY", strtotime($orderDate)).$orderid.'0';
                        // if pick up merchant is unregistered merchant then registered pickup merchant pick up point will be considered
                        $pickuppoint = "Select pointCode from tbl_point_coverage where thanaId = '$thanaId'";
                        $result = mysqli_query($conn, $pickuppoint);
                        foreach ($result as $pointrow){
                            $merchantPointCode = $pointrow['pointCode'];
                        }
                        $pickupSystemSQL = "Select pointCode, pickPointCode from tbl_regular_point where pointCode = '$merchantPointCode'";
                        $pickupSystemResult = mysqli_query($conn, $pickupSystemSQL);
                        $pickupSystemRow = mysqli_fetch_array($pickupSystemResult);
                        $pickuppointcode = $pickupSystemRow['pickPointCode'];

                        $merRateIdSQL = "select smarRatechart, thanaid, districtid, cod from tbl_merchant_info where merchantCode ='$merchantCode'";
                        $merRateIdResult = mysqli_query($conn, $merRateIdSQL);
                        $merRateIdRow = mysqli_fetch_array($merRateIdResult);
                        $merRateChartId = $merRateIdRow['smarRatechart'];
                        $merchantDistrict = $merRateIdRow['districtid'];
                        $merchantThana = $merRateIdRow['thanaid'];
                        $mercod = $merRateIdRow['cod'];

                        if ($districtId != $merchantDistrict){
                            $destination = 'interDistrict';
                        } else {
                            $destination = 'local';
                        }

                        $orderChargeSQL = "SELECT * FROM tbl_rate_type where ratechartId = '$merRateChartId' and packageOption = '$productSizeWeight' and deliveryOption = '$deliveryOption' and destination = '$destination'";
                        $orderChargeResult = mysqli_query($conn, $orderChargeSQL);
                        $orderChargeRow = mysqli_fetch_array($orderChargeResult);
                        $charge = $orderChargeRow['charge'];
                        

                        $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$merchantThana'";
                        $result = mysqli_query($conn, $droppoint);
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


                        $sql ="INSERT INTO tbl_order_details(ordSeq, orderid, barcode, orderDate, orderType, pickPointCode, dropPointCode, merchantCode, merOrderRef,  
                        pickMerchantName, pickMerchantAddress, thanaId, districtId, pickupMerchantPhone, productSizeWeight, deliveryOption, ratechartId, destination, charge, cod,
                        productBrief, packagePrice, creation_date, created_by) VALUES ('$ordSeq', '$orderid', '$barcode', NOW() + INTERVAL 6 HOUR, 'smartPick', '$pickuppointcode', '$droppointcode', '$merchantCode', '$merOrderRef',   
                        '$pickMerchantName', '$pickMerchantAddress', '$thanaId', '$districtId', '$pickupMerchantPhone', '$productSizeWeight', '$deliveryOption', '$merRateChartId', '$destination', '$charge', '$mercod',
                        '$productBrief', '$packagePrice', NOW() + INTERVAL 6 HOUR , '$user_check')";
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
                            echo "<strong>Error!</strong> : Unable to insert order"; 
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
