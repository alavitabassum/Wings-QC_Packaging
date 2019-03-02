<?php
    include("config.php");
    include('session.php');
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://bridge.ajkerdeal.com/Collector/Parcel/GetCourierList/30",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "API_KEY: Ajkerdeal_~La?Rj73FcLm",
        "Authorization: Basic UGFwZXJGbHk6SGpGZTVWNWY=",
        "Cache-Control: no-cache",
        "Postman-Token: 196ee106-a3d6-41df-86da-d62884315e69"
      ),
    ));

    $response = curl_exec($curl);

    $err = curl_error($curl);
    curl_close($curl);

    $orderLine = json_decode($response, TRUE);

    $insertSQL = "insert into tbl_api_orders (merchantCode, pickPointCode, dropPointCode, customerName, customerAddress, customerNumber, productType, deliveryType, merOrderRef, barcode, status, dataValidity, destination, creationDate, createdBy) values ";

    $lineCount = 0;
    $merchantCode = 'M-1-0262';
    $merchantRow = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_merchant_info where merchantCode = 'M-1-0262'"));

    foreach($orderLine as $orderItem){
        $DelivaryDistrictId = $orderItem['DelivaryDistrictId'];
        $DelivaryThanaId = $orderItem['DelivaryThanaId'];
        $customerName = $orderItem['CustomerName'];
        $CustomerAddress = $orderItem['CustomerAddress'];
        $CustomerAddress = mysqli_real_escape_string($conn, $CustomerAddress);
        $DelivaryDistrict = $orderItem['DelivaryDistrict'];
        $DelivaryThana = $orderItem['DelivaryThana'];
        $DelivaryArea = $orderItem['DelivaryArea'];
        $CustomerNumber = $orderItem['CustomerNumber'];
        $CollectionAddress = $orderItem['CollectionAddress'];
        $CollectionNumber = $orderItem['CollectionNumber'];
        $ProductType = $orderItem['ProductType'];
        $BarCodeNo = $orderItem['BarCodeNo'];
        $OrderId = $orderItem['OrderId'];
        $OrderId = mysqli_real_escape_string($conn, $OrderId);
        $Pickuplocation = $orderItem['Pickuplocation'];
        $DeliveryType = $orderItem['DeliveryType'];

        if(ucfirst($Pickuplocation) == "Dhaka"){
            $pickPointCode = $merchantRow['pointCode'];    
        } else {
            $pickPointCode = '';
        }

        $dropPointRow = mysqli_fetch_array(mysqli_query($conn, "select pointCode from tbl_ajker_deal_map where districtID = '$DelivaryDistrictId' and thanaID = '$DelivaryThanaId'"));
        $dropPointCode = $dropPointRow['pointCode'];

        if(ucfirst($DelivaryDistrict) == ucfirst($Pickuplocation)){
            $destination = "local";    
        } else {
            $destination = "interDistrict";
        }
    
        $status = '';
        if($dropPointCode == '' || $Pickuplocation == '' || $BarCodeNo == '' || $CustomerNumber == '' || $DeliveryType == ''){
            $dataValidity = 'N';
            if($dropPointCode == ''){
                $status .= "Delivery district or thana not found";
            }
            if($Pickuplocation == ''){
                $status .= " || Pickup location missing";
            }
            if($BarCodeNo== ''){
                $status .= " || Barcode missing";
            }
            if($CustomerNumber == ''){
                $status .= " || Customer phone not found";
            }
            if($DeliveryType == ''){
                $status .= " || Delivery type not found";
            }
        } else {
            $dataValidity = 'Y';
        }

        if($lineCount == 0 ){
            $insertSQL .= " ('".$merchantCode."','".$pickPointCode."','".$dropPointCode."','".$customerName."','".$CustomerAddress."','".$CustomerNumber."','".$ProductType."','".$DeliveryType."','".$OrderId."','".$BarCodeNo."','".$status."','".$dataValidity."','".$destination."', NOW() + INTERVAL 6 HOUR, '".$user_check."')";    
        } else {
            $insertSQL .= " ,('".$merchantCode."','".$pickPointCode."','".$dropPointCode."','".$customerName."','".$CustomerAddress."','".$CustomerNumber."','".$ProductType."','".$DeliveryType."','".$OrderId."','".$BarCodeNo."','".$status."','".$dataValidity."','".$destination."', NOW() + INTERVAL 6 HOUR, '".$user_check."')";
        }
        $lineCount++;
    }
    mysqli_set_charset( $conn, 'utf8' );
    

    if(!mysqli_query($conn, $insertSQL)){
        //echo "Error: unable to process data ".mysqli_error($conn);
        $err = "Error: unable to process data ".mysqli_error($conn);
        $logInsertResult = mysqli_query($conn, "insert into tbl_api_log (merchantCode, logType, logDescription, creationDate, createdBy) values ('M-1-0262', 'pull', '$err', NOW() + INTERVAL 6 HOUR, 'admin')");    
    } else {
        //echo "Pulled ".$lineCount." orders successully";
        $success = "Pulled ".$lineCount." orders successully";
        $logInsertResult = mysqli_query($conn, "insert into tbl_api_log (merchantCode, logType, logDescription, creationDate, createdBy) values ('M-1-0262', 'pull', '$success', NOW() + INTERVAL 6 HOUR, 'admin')");
    }

?>
