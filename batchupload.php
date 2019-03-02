<?php
    include('session.php');
    include('header.php');
    include('config.php');
    if ($user_type=='Merchant'){
        $merchantsql = "select merchantCode, merchantName, isActive from tbl_merchant_info where merchantCode='$user_code'";
        $merchantresult = mysqli_query($conn,$merchantsql);
        $merchantrow = mysqli_fetch_array($merchantresult);
        if($merchantrow['isActive'] == 'N'){
            echo '<div class="container">';
                echo 'This account is currently inactive. <br>For assistance please call 01998706063 or email to: info@paperfly.com.bd or leave a message to our Facebook page.';
            echo '</div>';
            exit();
        }
    } else {
        $merchantsql = "select merchantCode, merchantName from tbl_merchant_info";
        $merchantresult = mysqli_query($conn,$merchantsql);
    }
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_ordermgt` WHERE user_id = $user_id_chk and new_order = 'Y'"));
    if ($userPrivCheckRow['new_order'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post" enctype="multipart/form-data" style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Batch Order Process</p>
                <input type="hidden" name="path">
                <input style="margin-left: 2px; font: 15px 'paperfly roman'" type="file" name="fileToUpload" id="fileToUpload" required>
                <br>
                <div style="float: left">
                    <input type="submit" name="submit" value="Upload Orders" class="btn-primary" style="width: 120px; height: 30px; border-radius: 5%">&nbsp;
                    <br>
                    <br>
                </div>
                <br>
                <br>
            </form>
        </div>
        <?php   
            if (isset($_POST['submit'])){
                $files = @$_FILES["fileToUpload"];
                if($files["name"] != '')
                {
                    //***For Hosting Server
                    //$fullpath = $_REQUEST["path"].$files["name"];
                    $fullpath = $_SERVER[DOCUMENT_ROOT]."/orderfile/".$files["name"];
                    if(move_uploaded_file($files['tmp_name'],$fullpath)) {
                    //***End for Hosting Server

                    //**for local machine site
                    /*
                    $target = "C:\wamp\www\orderfile\\"; 
                    $target = $target .$_FILES['fileToUpload']['name'];
                    $pic=($_FILES['fileToUpload']['tmp_name']);
                    $filename="C:\wamp\www\orderfile\\".basename($_FILES["fileToUpload"]["name"]);

                    */
                    $filename="http://paperflybd.com/orderfile/".basename($_FILES["fileToUpload"]["name"]);
                    if ($filename!='' && substr($filename,-3)=='csv'){
                        /*
                        if(move_uploaded_file($pic, $target))

                        //*** End of Local Machine site
                        {*/
                            $timeSQL="select NOW() + INTERVAL 6 HOUR as currenttime";
                            $timeResult = mysqli_query($conn, $timeSQL);
                            $timeRow = mysqli_fetch_array($timeResult);
                            $orderDate = date("Y-m-d", strtotime($timeRow['currenttime']));
                            //$orderDate = date("Y-m-d");
                            echo "<div style='width: 60%; height: 200px'>";
                            echo "<p style='background-color: #135c91; border-radius: 5px; width: 100%; height: 25px; font-weight: bold; color: #fff'>Process Status</p>";
		                        $row = 0;
                                $error_row = 0;
                                $error_insert = 0;
                                $success_insert = 0;
                                $insert = 1;
			                        if (($handle = fopen($filename, "r")) !== FALSE) {
				                        while (($data = fgetcsv($handle, 8000, ",")) !== FALSE) {
					                        $num = count($data);
					                        $row++;
                                            $insert = 1;
                                            if ($user_type == 'Merchant'){ //if user type is merchant
                                                $mrchCode = substr(trim($data[0]), -8);
                                                //$merCheckSQL = "Select merchantCode from tbl_merchant_info where merchantCode='$mrchCode'";
                                                //$merResult = mysqli_query($conn, $merCheckSQL);
                                                //if (mysqli_num_rows($merResult) > 0){
                                                if ($mrchCode == $user_code){
                                                    if ($data[2] !=''){  // if Pick up merchant name exists
                                                        if ($data[4] != '' and $data[5] != ''){ // Thana and district id are valid or not
                                                            //$thanasql = "Select * from tbl_thana_info where thanaid='$data[4]' and districtid='$data[5]'";
                                                            $thana = strtolower($data[4]);
                                                            $district = strtolower($data[5]);
                                                            $thanasql = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thana' and LCASE(tbl_district_info.districtName) = '$district')";
                                                            $thanaresult = mysqli_query($conn, $thanasql);
                                                            if (mysqli_num_rows($thanaresult) <= 0){
                                                                echo "<p style='font-size: 80%'>Invalid Merchant Thana or District -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                                $error_row++;
                                                                $insert = 0;                                                            
                                                            } else {
                                                                $thanrow = mysqli_fetch_array($thanaresult);
                                                                $thanaId = $thanrow['thanaId'];
                                                                $districtId = $thanrow['districtId'];
                                                            } 
                                                        } else {
                                                            echo "<p style='font-size: 80%'>Invalid Merchant Thana or District -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                            $error_row++;
                                                            $insert = 0;                                                                                                                                                                                            
                                                        }
                                                    } else {
                                                        //$merRow = mysqli_fetch_array($merResult);
                                                        $thanaId = '';
                                                        $districtId = '';
                                                    }
                                                    //checking whether customer data is misssing
                                                    if ($data[11] == '' or $data[12] == '' or $data[13] == '' or $data[14] == '' or $data[15] ==''){
                                                        echo "<p style='font-size: 80%'>Invalid Customer Data -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                        $error_row++;
                                                        $insert = 0;                                                                                                                                
                                                    } else {
                                                        // if customer thana is valid or not
                                                        $thana = strtolower($data[13]);
                                                        $district = strtolower($data[14]);
                                                        $thanasql = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thana' and LCASE(tbl_district_info.districtName) = '$district')";
                                                        $thanaresult = mysqli_query($conn, $thanasql);
                                                        if (mysqli_num_rows($thanaresult) <= 0){
                                                            echo "<p style='font-size: 80%'>Invalid Customer Thana or District -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                            $error_row++;
                                                            $insert = 0;
                                                        } else {
                                                           $thanrow = mysqli_fetch_array($thanaresult);
                                                           $customerThana = $thanrow['thanaId'];
                                                           $customerDistrict = $thanrow['districtId']; 
                                                        }
                                                    }
                                                    if (strtolower($data[7]) == 'standard' or strtolower($data[7]) == 'large' or strtolower($data[7]) == 'special' or strtolower($data[7]) == 'specialplus'){
                                                        $productSizeWeight = strtolower($data[7]);
                                                    } else {
                                                        echo "<p style='font-size: 80%'>Invalid Package Option -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                        $error_row++;
                                                        $insert = 0;                                                        
                                                    }
                                                    if (strtolower($data[8]) == 'regular' or strtolower($data[8]) =='express'){
                                                        $deliveryOption = strtolower($data[8]);
                                                    }else {
                                                        echo "<p style='font-size: 80%'>Invalid Delivery Option -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                        $error_row++;
                                                        $insert = 0;                                                                                                                
                                                    }
                                                    if (is_numeric($data[10])) {
                                                        $packagePrice = trim($data[10]);
                                                    } else {
                                                        echo "<p style='font-size: 80%'>Invalid Package Price -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                        $error_row++;
                                                        $insert = 0;                                                                                                                                                                        
                                                    }
                                                    if ($insert == 1) {
                                                        // Order ID generation
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
                                                        // if pick up merchant is unregistered merchant then registered pickup merchant pick up point will be considered
                                                        if ($data[2] != ""){
                                                            $orderType ='Other_Merchant';
                                                            $thanap = strtolower($data[4]);
                                                            $districtp = strtolower($data[5]);
                                                            $thanasqlp = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thanap' and LCASE(tbl_district_info.districtName) = '$districtp')";
                                                            $thanaresultp = mysqli_query($conn, $thanasqlp);
                                                            $thanarowp = mysqli_fetch_array($thanaresultp);
                                                            $pointThana = $thanarowp['thanaId'];
                                                            $pickPointDistrict = $thanarowp['districtId'];
                                                            $pickuppoint = "Select pointCode from tbl_point_coverage where thanaId = '$pointThana'";
                                                            $result = mysqli_query($conn, $pickuppoint);
                                                            foreach ($result as $pointrow){
                                                                $merchantPointCode = $pointrow['pointCode'];
                                                            }
                                                            $pickupSystemSQL = "Select pointCode, pickPointCode from tbl_regular_point where pointCode = '$merchantPointCode'";
                                                            $pickupSystemResult = mysqli_query($conn, $pickupSystemSQL);
                                                            $pickupSystemRow = mysqli_fetch_array($pickupSystemResult);
                                                            $pickuppointcode = $pickupSystemRow['pickPointCode'];
                                                            $thana = strtolower($data[13]);
                                                            $district = strtolower($data[14]);
                                                            $thanasql = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thana' and LCASE(tbl_district_info.districtName) = '$district')";
                                                            $thanaresult = mysqli_query($conn, $thanasql);
                                                            $thanarow = mysqli_fetch_array($thanaresult);
                                                            $customerDistrict = $thanarow['districtId'];
                                                            $dropThana = $thanrow['thanaId'];
                                                            $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
                                                            $result = mysqli_query($conn, $droppoint);
                                                            foreach ($result as $droprow){
                                                                $droppointcode = $droprow['pointCode'];
                                                            }
                                                            //if (strtolower($data[7]) == 'large' or strtolower($data[7]) == 'special' or strtolower($data[7]) == 'specialplus'){
                                                            //    $districtPoint = "select pointCode, dropPointCode from tbl_central_point where districtId = '$pointDistrict'";
                                                            //    $districtPointResult = mysqli_query($conn, $districtPoint);
                                                            //    foreach ($districtPointResult as $districtPointRow){
                                                            //        $centralPoint = $districtPointRow['pointCode'];
                                                            //        $centralDropPoint = $districtPointRow['dropPointCode'];
                                                            //        if ($pointDistrict != $customerDistrict){
                                                            //            $customerdistrictPoint = "select pointCode, dropPointCode from tbl_central_point where districtId = '$customerDistrict'";
                                                            //            $customerdistrictPointResult = mysqli_query($conn, $customerdistrictPoint);                                        
                                                            //            foreach ($customerdistrictPointResult as $droprow){
                                                            //                $centralDropPoint = $droprow['dropPointCode'];
                                                            //                if ($centralDropPoint == 'customer'){
                                                            //                    $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
                                                            //                    $result = mysqli_query($conn, $droppoint);
                                                            //                    foreach ($result as $droprow){
                                                            //                        $centralDropPoint = $droprow['pointCode'];
                                                            //                    }                                        
                                                            //                }
                                                            //            }                                        
                                                            //        } else {
                                                            //            if ($centralDropPoint == 'customer'){
                                                            //                $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
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
                                                                $barcode = date("dmy", strtotime($orderDate)).$orderid."0";
                                                                $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$pickuppointcode."-".$droppointcode;
                                                            //}
                                                        } else {
                                                            $orderType ='Merchant';
                                                            $mrCode = trim($data[0]);
                                                            $pickuppoint = "Select pointCode from tbl_merchant_info where merchantCode = '$mrCode'";
                                                            $result = mysqli_query($conn, $pickuppoint);
                                                            foreach ($result as $pointrow){
                                                                $merchantPointCode = $pointrow['pointCode'];
                                                            }
                                                            $pickupSystemSQL = "Select pointCode, pickPointCode from tbl_regular_point where pointCode = '$merchantPointCode'";
                                                            $pickupSystemResult = mysqli_query($conn, $pickupSystemSQL);
                                                            $pickupSystemRow = mysqli_fetch_array($pickupSystemResult);
                                                            $pickuppointcode = $pickupSystemRow['pickPointCode'];
                                                            // identify drop point
                                                            $thana = strtolower($data[13]);
                                                            $district = strtolower($data[14]);
                                                            $thanasql = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thana' and LCASE(tbl_district_info.districtName) = '$district')";
                                                            $thanaresult = mysqli_query($conn, $thanasql);
                                                            $thanarow = mysqli_fetch_array($thanaresult);
                                                            $customerDistrict = $thanarow['districtId'];
                                                            $dropThana = $thanrow['thanaId'];
                                                            $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
                                                            $result = mysqli_query($conn, $droppoint);
                                                            foreach ($result as $droprow){
                                                                $droppointcode = $droprow['pointCode'];
                                                            }
                                                            //if (strtolower($data[7]) == 'large' or strtolower($data[7]) == 'special' or strtolower($data[7]) == 'specialplus'){
                                                            //    $merchantdistrictpoint = "Select districtid from tbl_merchant_info where merchantCode = '$mrCode'";
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
                                                            //                    $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
                                                            //                    $result = mysqli_query($conn, $droppoint);
                                                            //                    foreach ($result as $droprow){
                                                            //                        $centralDropPoint = $droprow['pointCode'];
                                                            //                    }                                        
                                                            //                }
                                                            //            }                                        
                                                            //        } else {
                                                            //            if ($centralDropPoint == 'customer'){
                                                            //                $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
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
                                                                $barcode = date("dmy", strtotime($orderDate)).$orderid."0";
                                                                $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$pickuppointcode."-".$droppointcode;
                                                            //}                                                            
                                                        }
                                                        //End of order ID generation
                                                        $merchantCode = substr(trim($data[0]), -8);
                                                        $merOrderRef = trim($data[1]);
                                                        $merOrderRef = mysqli_real_escape_string($conn, $merOrderRef);
                                                        $timeSQL="select NOW() + INTERVAL 6 HOUR as currenttime";
                                                        $timeResult = mysqli_query($conn, $timeSQL);
                                                        $timeRow = mysqli_fetch_array($timeResult);
                                                        $orderDate = date("Y-m-d", strtotime($timeRow['currenttime']));
                                                        //$orderDate = date("Y-m-d");
                                                        $pickMerchantName = trim($data[2]);
                                                        $pickMerchantName = mysqli_real_escape_string($conn, $pickMerchantName);
                                                        $pickMerchantAddress = trim($data[3]);
                                                        $pickMerchantAddress = mysqli_real_escape_string($conn, $pickMerchantAddress);
                                                        $pickupMerchantPhone = trim($data[6]);
                                                        $pickupMerchantPhone = mysqli_real_escape_string($conn, $pickupMerchantPhone);
                                                        $productBrief = trim($data[9]);
                                                        $productBrief = mysqli_real_escape_string($conn, $productBrief);
                                                        $custname = trim($data[11]);
                                                        $custname = mysqli_real_escape_string($conn, $custname);
                                                        $custaddress = trim($data[12]);
                                                        $custaddress = mysqli_real_escape_string($conn, $custaddress);
                                                        $custphone = trim($data[15]);
                                                        $custphone = mysqli_real_escape_string($conn, $custphone);
                                                        $merRateIdSQL = "select districtid, ratechartId, cod from tbl_merchant_info where merchantCode ='$merchantCode'";
                                                        $merRateIdResult = mysqli_query($conn, $merRateIdSQL);
                                                        $merRateIdRow = mysqli_fetch_array($merRateIdResult);
                                                        $merRateChartId = $merRateIdRow['ratechartId'];
                                                        $mercod = $merRateIdRow['cod'];
                                                        $merdistrictid = $merRateIdRow['districtid'];
                                                        if ($data[2] != ""){
                                                            if ($pickPointDistrict != $customerDistrict){
                                                                $destination = 'interDistrict';
                                                            } else {
                                                                $destination = 'local';
                                                            }                                                            
                                                        } else {
                                                            if ($merdistrictid != $customerDistrict){
                                                                $destination = 'interDistrict';
                                                            } else {
                                                                $destination = 'local';
                                                            }                                                            
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
                                                        
                                                        /*
                                                        $sql ="INSERT INTO tbl_temp_order(ordSeq, orderid, orderDate, orderType, pickPointCode, dropPointCode, merchantCode, merOrderRef,  
                                                        pickMerchantName, pickMerchantAddress, thanaId, districtId, pickupMerchantPhone, productSizeWeight, deliveryOption,
                                                        productBrief, packagePrice, custname, custaddress, customerThana, customerDistrict, 
                                                        custphone, creation_date, created_by) VALUES ('$ordSeq', '$orderid', NOW() + INTERVAL 6 HOUR, '$orderType', '$pickuppointcode', '$droppointcode', '$merchantCode', '$merOrderRef',   
                                                        '$pickMerchantName', '$pickMerchantAddress', '$thanaId', '$districtId', '$pickupMerchantPhone', '$productSizeWeight', '$deliveryOption',
                                                        '$productBrief', '$packagePrice', '$custname', '$custaddress', '$customerThana', '$customerDistrict',
                                                        '$custphone', NOW() + INTERVAL 6 HOUR , '$user_check')";
                                                        */
                                                        if (!mysqli_query($conn,$sql))
                                                            {
                                                                $error ="Insert Error : " . mysqli_error($conn);
                                                                echo "<strong>Error!</strong>".$error;
                                                                $error_insert++; 
                                                            } else {
                                                                echo "Order ID ".$orderid." created successfully<br>";
                                                                $success_insert++;
                                                            }
                                                    }                                                     
                                                } else {
                                                    echo "<p style='font-size: 80%'>Invalid Data -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                    $error_row++;
                                                }
                                            } else {
                                                $mrchCode = substr(trim($data[0]), -8);
                                                $merCheckSQL = "Select merchantCode, thanaid, districtid from tbl_merchant_info where merchantCode='$mrchCode'";
                                                $merResult = mysqli_query($conn, $merCheckSQL);
                                                if (mysqli_num_rows($merResult) > 0){
                                                    if ($data[2] !=''){  // if Pick up merchant name exists
                                                        if ($data[4] != '' and $data[5] != ''){ // Thana and district id are valid or not
                                                            //$thanasql = "Select * from tbl_thana_info where thanaid='$data[4]' and districtid='$data[5]'";
                                                            $thana = strtolower($data[4]);
                                                            $district = strtolower($data[5]);
                                                            $thanasql = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thana' and LCASE(tbl_district_info.districtName) = '$district')";
                                                            $thanaresult = mysqli_query($conn, $thanasql);
                                                            if (mysqli_num_rows($thanaresult) <= 0){
                                                                echo "<p style='font-size: 80%'>Invalid Merchant Thana or District -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                                $error_row++;
                                                                $insert = 0;                                                            
                                                            } else {
                                                                $thanrow = mysqli_fetch_array($thanaresult);
                                                                $thanaId = $thanrow['thanaId'];
                                                                $districtId = $thanrow['districtId'];
                                                            } 
                                                        } else {
                                                            echo "<p style='font-size: 80%'>Invalid Merchant Thana or District -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                            $error_row++;
                                                            $insert = 0;                                                                                                                                                                                            
                                                        }
                                                    } else {
                                                        //$merRow = mysqli_fetch_array($merResult);
                                                        $thanaId = '';
                                                        $districtId = '';
                                                    }
                                                    //checking whether customer data is misssing
                                                    if ($data[11] == '' or $data[12] == '' or $data[13] == '' or $data[14] == '' or $data[15] ==''){
                                                        echo "<p style='font-size: 80%'>Invalid Customer Data -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                        $error_row++;
                                                        $insert = 0;                                                                                                                                
                                                    } else {
                                                        // if customer thana is valid or not
                                                        $thana = strtolower($data[13]);
                                                        $district = strtolower($data[14]);
                                                        $thanasql = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thana' and LCASE(tbl_district_info.districtName) = '$district')";
                                                        $thanaresult = mysqli_query($conn, $thanasql);
                                                        if (mysqli_num_rows($thanaresult) <= 0){
                                                            echo "<p style='font-size: 80%'>Invalid Customer Thana or District -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                            $error_row++;
                                                            $insert = 0;
                                                        } else {
                                                           $thanrow = mysqli_fetch_array($thanaresult);
                                                           $customerThana = $thanrow['thanaId'];
                                                           $customerDistrict = $thanrow['districtId']; 
                                                        }
                                                    }
                                                    if (strtolower($data[7]) == 'standard' or strtolower($data[7]) == 'large' or strtolower($data[7]) == 'special' or strtolower($data[7]) == 'specialplus'){
                                                        $productSizeWeight = strtolower($data[7]);
                                                    } else {
                                                        echo "<p style='font-size: 80%'>Invalid Package Option -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                        $error_row++;
                                                        $insert = 0;                                                        
                                                    }
                                                    if (strtolower($data[8]) == 'regular' or strtolower($data[8]) =='express'){
                                                        $deliveryOption = strtolower($data[8]);
                                                    }else {
                                                        echo "<p style='font-size: 80%'>Invalid Delivery Option -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                        $error_row++;
                                                        $insert = 0;                                                                                                                
                                                    }
                                                    if (is_numeric($data[10])) {
                                                        $packagePrice = trim($data[10]);
                                                    } else {
                                                        echo "<p style='font-size: 80%'>Invalid Package Price -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                        $error_row++;
                                                        $insert = 0;                                                                                                                                                                        
                                                    }
                                                    if ($insert == 1) {
                                                        // Order ID generation
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
                                                        // if pick up merchant is unregistered merchant then registered pickup merchant pick up point will be considered
                                                        if ($data[2] != ""){
                                                            $orderType ='Other_Merchant';
                                                            $thanap = strtolower($data[4]);
                                                            $districtp = strtolower($data[5]);
                                                            $thanasqlp = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thanap' and LCASE(tbl_district_info.districtName) = '$districtp')";
                                                            $thanaresultp = mysqli_query($conn, $thanasqlp);
                                                            $thanarowp = mysqli_fetch_array($thanaresultp);
                                                            $pointThana = $thanarowp['thanaId'];
                                                            $pointDistrict = $thanarowp['districtId'];
                                                            $pickuppoint = "Select pointCode from tbl_point_coverage where thanaId = '$pointThana'";
                                                            $result = mysqli_query($conn, $pickuppoint);
                                                            foreach ($result as $pointrow){
                                                                $merchantPointCode = $pointrow['pointCode'];
                                                            }
                                                            $pickupSystemSQL = "Select pointCode, pickPointCode from tbl_regular_point where pointCode = '$merchantPointCode'";
                                                            $pickupSystemResult = mysqli_query($conn, $pickupSystemSQL);
                                                            $pickupSystemRow = mysqli_fetch_array($pickupSystemResult);
                                                            $pickuppointcode = $pickupSystemRow['pickPointCode'];
                                                            $thana = strtolower($data[13]);
                                                            $district = strtolower($data[14]);
                                                            $thanasql = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thana' and LCASE(tbl_district_info.districtName) = '$district')";
                                                            $thanaresult = mysqli_query($conn, $thanasql);
                                                            $thanarow = mysqli_fetch_array($thanaresult);
                                                            $customerDistrict = $thanarow['districtId'];
                                                            $dropThana = $thanrow['thanaId'];
                                                            $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
                                                            $result = mysqli_query($conn, $droppoint);
                                                            foreach ($result as $droprow){
                                                                $droppointcode = $droprow['pointCode'];
                                                            }
                                                            //if (strtolower($data[7]) == 'large' or strtolower($data[7]) == 'special' or strtolower($data[7]) == 'specialplus'){
                                                            //    $districtPoint = "select pointCode, dropPointCode from tbl_central_point where districtId = '$pointDistrict'";
                                                            //    $districtPointResult = mysqli_query($conn, $districtPoint);
                                                            //    foreach ($districtPointResult as $districtPointRow){
                                                            //        $centralPoint = $districtPointRow['pointCode'];
                                                            //        $centralDropPoint = $districtPointRow['dropPointCode'];
                                                            //        if ($pointDistrict != $customerDistrict){
                                                            //            $customerdistrictPoint = "select pointCode, dropPointCode from tbl_central_point where districtId = '$customerDistrict'";
                                                            //            $customerdistrictPointResult = mysqli_query($conn, $customerdistrictPoint);                                        
                                                            //            foreach ($customerdistrictPointResult as $droprow){
                                                            //                $centralDropPoint = $droprow['dropPointCode'];
                                                            //                if ($centralDropPoint == 'customer'){
                                                            //                    $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
                                                            //                    $result = mysqli_query($conn, $droppoint);
                                                            //                    foreach ($result as $droprow){
                                                            //                        $centralDropPoint = $droprow['pointCode'];
                                                            //                    }                                        
                                                            //                }
                                                            //            }                                        
                                                            //        } else {
                                                            //            if ($centralDropPoint == 'customer'){
                                                            //                $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
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
                                                                $barcode = date("dmy", strtotime($orderDate)).$orderid."0";
                                                                $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$pickuppointcode."-".$droppointcode;
                                                            //}
                                                        } else {
                                                            $orderType ='Merchant';
                                                            $mrCode = trim($data[0]);
                                                            $pickuppoint = "Select pointCode from tbl_merchant_info where merchantCode = '$mrCode'";
                                                            $result = mysqli_query($conn, $pickuppoint);
                                                            foreach ($result as $pointrow){
                                                                $merchantPointCode = $pointrow['pointCode'];
                                                            }
                                                            $pickupSystemSQL = "Select pointCode, pickPointCode from tbl_regular_point where pointCode = '$merchantPointCode'";
                                                            $pickupSystemResult = mysqli_query($conn, $pickupSystemSQL);
                                                            $pickupSystemRow = mysqli_fetch_array($pickupSystemResult);
                                                            $pickuppointcode = $pickupSystemRow['pickPointCode'];
                                                            // identify drop point
                                                            $thana = strtolower($data[13]);
                                                            $district = strtolower($data[14]);
                                                            $thanasql = "SELECT thanaId, thanaName, tbl_district_info.districtId FROM tbl_thana_info, tbl_district_info where tbl_thana_info.districtId = tbl_district_info.districtId and tbl_thana_info.isActive = 'Y' and (LCASE(tbl_thana_info.thanaName) = '$thana' and LCASE(tbl_district_info.districtName) = '$district')";
                                                            $thanaresult = mysqli_query($conn, $thanasql);
                                                            $thanarow = mysqli_fetch_array($thanaresult);
                                                            $customerDistrict = $thanarow['districtId'];
                                                            $dropThana = $thanrow['thanaId'];
                                                            $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
                                                            $result = mysqli_query($conn, $droppoint);
                                                            foreach ($result as $droprow){
                                                                $droppointcode = $droprow['pointCode'];
                                                            }
                                                            //if (strtolower($data[7]) == 'large' or strtolower($data[7]) == 'special' or strtolower($data[7]) == 'specialplus'){
                                                            //    $merchantdistrictpoint = "Select districtid from tbl_merchant_info where merchantCode = '$mrCode'";
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
                                                            //                    $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
                                                            //                    $result = mysqli_query($conn, $droppoint);
                                                            //                    foreach ($result as $droprow){
                                                            //                        $centralDropPoint = $droprow['pointCode'];
                                                            //                    }                                        
                                                            //                }
                                                            //            }                                        
                                                            //        } else {
                                                            //            if ($centralDropPoint == 'customer'){
                                                            //                $droppoint = "Select pointCode from tbl_point_coverage where thanaId = '$dropThana'";
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
                                                                $barcode = date("dmy", strtotime($orderDate)).$orderid."0";
                                                                $orderid = date("dmy", strtotime($orderDate))."-".$orderid."-".$pickuppointcode."-".$droppointcode;
                                                            //}                                                            
                                                        }
                                                        //End of order ID generation
                                                        $merchantCode = substr(trim($data[0]), -8);
                                                        $merOrderRef = trim($data[1]);
                                                        $merOrderRef = mysqli_real_escape_string($conn, $merOrderRef);
                                                        $timeSQL="select NOW() + INTERVAL 6 HOUR as currenttime";
                                                        $timeResult = mysqli_query($conn, $timeSQL);
                                                        $timeRow = mysqli_fetch_array($timeResult);
                                                        $orderDate = date("Y-m-d", strtotime($timeRow['currenttime']));
                                                        //$orderDate = date("Y-m-d");
                                                        $pickMerchantName = trim($data[2]);
                                                        $pickMerchantName = mysqli_real_escape_string($conn, $pickMerchantName);
                                                        $pickMerchantAddress = trim($data[3]);
                                                        $pickMerchantAddress = mysqli_real_escape_string($conn, $pickMerchantAddress);
                                                        $pickupMerchantPhone = trim($data[6]);
                                                        $pickupMerchantPhone = mysqli_real_escape_string($conn, $pickupMerchantPhone);
                                                        $productBrief = trim($data[9]);
                                                        $productBrief = mysqli_real_escape_string($conn, $productBrief);
                                                        $custname = trim($data[11]);
                                                        $custname = mysqli_real_escape_string($conn, $custname);
                                                        $custaddress = trim($data[12]);
                                                        $custaddress = mysqli_real_escape_string($conn, $custaddress);
                                                        $custphone = trim($data[15]);
                                                        $custphone = mysqli_real_escape_string($conn, $custphone);
                                                        $merRateIdSQL = "select districtid, ratechartId, cod from tbl_merchant_info where merchantCode ='$merchantCode'";
                                                        $merRateIdResult = mysqli_query($conn, $merRateIdSQL);
                                                        $merRateIdRow = mysqli_fetch_array($merRateIdResult);
                                                        $merRateChartId = $merRateIdRow['ratechartId'];
                                                        $mercod = $merRateIdRow['cod'];
                                                        $merdistrictid = $merRateIdRow['districtid'];
                                                        if ($data[2] != ""){
                                                            if ($pointDistrict != $customerDistrict){
                                                                $destination = 'interDistrict';
                                                            } else {
                                                                $destination = 'local';
                                                            }                                                            
                                                        } else {
                                                            if ($merdistrictid != $customerDistrict){
                                                                $destination = 'interDistrict';
                                                            } else {
                                                                $destination = 'local';
                                                            }                                                            
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
                                                        
                                                        /*
                                                        $sql ="INSERT INTO tbl_temp_order(ordSeq, orderid, orderDate, orderType, pickPointCode, dropPointCode, merchantCode, merOrderRef,  
                                                        pickMerchantName, pickMerchantAddress, thanaId, districtId, pickupMerchantPhone, productSizeWeight, deliveryOption,
                                                        productBrief, packagePrice, custname, custaddress, customerThana, customerDistrict, 
                                                        custphone, creation_date, created_by) VALUES ('$ordSeq', '$orderid', NOW() + INTERVAL 6 HOUR, '$orderType', '$pickuppointcode', '$droppointcode', '$merchantCode', '$merOrderRef',   
                                                        '$pickMerchantName', '$pickMerchantAddress', '$thanaId', '$districtId', '$pickupMerchantPhone', '$productSizeWeight', '$deliveryOption',
                                                        '$productBrief', '$packagePrice', '$custname', '$custaddress', '$customerThana', '$customerDistrict',
                                                        '$custphone', NOW() + INTERVAL 6 HOUR , '$user_check')";
                                                        */
                                                        if (!mysqli_query($conn,$sql))
                                                            {
                                                                $error ="Insert Error : " . mysqli_error($conn);
                                                                echo "<strong>Error!</strong>".$error;
                                                                $error_insert++; 
                                                            } else {
                                                                echo "Order ID ".$orderid." created successfully<br>";
                                                                $success_insert++;
                                                            }
                                                    }                                                     
                                                } else {
                                                    echo "<p style='font-size: 80%'>Invalid Data -- Line No.".$row." -:- ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",".$data[10].",".$data[11].",".$data[12].",".$data[13].",".$data[14].",".$data[15]. "</p>\n";
                                                    echo $sql;
                                                    $error_row++;
                                                }
                                            }
				                        }
				                        fclose($handle);
                                        if ($error_row >0){
                                            echo "<hr>";
                                            echo "<p style='font-weight: bold; color: #f00'>Error in file. Unable to upload orders</p>";
                                            echo "<hr>";
                                            echo "<p style='font-weight: bold; color: #135c91'>Total no of Orders : ".$row."</p>";
                                            echo "<p style='font-weight: bold; color: #f00'>Total Error Orders count : ".$error_row."</p>";
                                        }
                                        if ($success_insert > 0){
                                            echo "<hr>";
                                            echo "<p style='font-weight: bold; color: #135c91'>Successfully processed Orders : ".$success_insert."</p>";
                                            echo "<p style='font-weight: bold; color: #f00'>Total Error Orders count : ".$error_insert."</p>";
                                            //echo "<iframe id='reportframe' src='orderprocesspdf.php?ordermaxid=".$ordermaxid."&recmaxid=".($ordermaxid+$success_insert)."&merchantname=".$merchantname."&orderDate=".$orderDate."' style='width: 100%; height: 100%' hidden></iframe>";
                                            //echo "<button class='btn-info' onclick='return showreport()'>Show Processed Orders</button>";                               
                                        }
			                        }		

                            echo "</div>";
                        }
                	} else {
		                echo "This is not a valid CSV file :- ".substr($filename,-3);
	                }
                }
                //submit ends
                mysqli_close($conn);
            } else {
        ?>
        <div style="margin-left: 15px; width: 98%">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">File Example</p>
            <p style="color: #4800ff; font: 15px 'paperfly bold'">CSV file should contain following column information <span style="color: #f00; font: 15px 'paperfly roman'">but column name should not be in the CSV file</span></p>
            <p style="font: 10px 'paperfly roman'">**Please fill up the <span style="background-color: yellow">yellow color marked columns</span> when ordered products need to be picked up from other merchant store/warehouse, not from selling merchant's own web store.</p>
            <img src="image/csvColumnEx.jpg" alt="CSV Column Example">
            <br>
            <br>
            <p style="color: #ff6a00; font: 20px 'paperfly bold'">CSV File Example:</p>
            <p style="font: 10px 'paperfly roman'">**Merchant code M-1-0001 is xyz.com <span style="background-color: yellow">ordered item needs to pick from Yellow Mart's store/warehouse</span></p>
            <p style="font: 10px 'paperfly roman'">**Merchant code M-1-0002 is abc.com <span style="background-color: blue; color: white">yellow marked columns will remain blank as ordered item will be picked from own store/warehouse of abc.com</span></p>
            <pre>
                M-1-0001,ref1001,<span style="background-color: yellow">Yellow Mart,Shampur,Shampur,dhaka,01711679845,</span>standard,regular,"2'3" Mobile Phone",15500,Mr. X,Mohammadpur,Mohammadpur,dhaka,01811679845
                M-1-0002,ref1002,<span style="background-color: blue; color: white">,,,,,</span>standard,regular,Blue Sari/Cloth,3589,Mr. Y,Kalabagan,Mirpur,Dhaka,01934876950
                M-1-0003,ref1003,<span style="background-color: yellow">ETC Store,Sutrapur,Sutrapur,DHaka,01699786543,</span>Large,Express,Handicraft,1500,Mr. Z,Mirpur,Mirpur,dhaka,01799786543                
            </pre>
        </div>
        <?php 
            }
        ?>
        <script type="text/javascript">
            $('#datetimepicker').datetimepicker({
            format: 'dd-MM-yyyy',
            language: 'en'
            });
            function showreport()
            {
                document.getElementById('reportframe').hidden = false;
            } 
        </script>
    </body>
</html>
