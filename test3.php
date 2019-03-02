
<?php
    include('config.php');
    $orderSQL = "SELECT ordId, orderid, orderType, merchantCode, pickMerchantName, districtId, customerDistrict, productSizeWeight, deliveryOption, ratechartId, destination, charge FROM `tbl_order_details` WHERE merchantCode='M-1-0152' and orderDate ='2017-05-09'";
    $orerResult = mysqli_query($conn, $orderSQL);
    foreach($orerResult as $orderRow){
        $orderid = $orderRow['orderid'];
        $merchantCode = $orderRow['merchantCode'];
        $merchantInfoSQL = "select merchantCode, districtid, ratechartId, cod from tbl_merchant_info where merchantCode = '$merchantCode'";
        $merchantInfoResult = mysqli_query($conn, $merchantInfoSQL);
        $merchantInfoRow = mysqli_fetch_array($merchantInfoResult);
        $ratechartId = $merchantInfoRow['ratechartId'];
        $packageOption = $orderRow['productSizeWeight'];
        $deliveryOption = $orderRow['deliveryOption'];
        if($orderRow['districtId'] == 0){
            $merchantDistrictId = $merchantInfoRow['districtid'];
            if($merchantDistrictId == $orderRow['customerDistrict']){
                $destination = 'local';
            } else {
                $destination =  'interDistrict';
            }
            $rateChartSQL = "select charge from tbl_rate_type where ratechartId=$ratechartId and packageOption='$packageOption' and deliveryOption='$deliveryOption' and destination='$destination'";
            $rateChartResult = mysqli_query($conn, $rateChartSQL);
            $rateChartRow = mysqli_fetch_array($rateChartResult);
            $charge = $rateChartRow['charge'];
        }
        //echo $orderRow['ratechartId'].'||'.$orderRow['destination'].'||'.$orderRow['charge'].'||'.$ratechartId.'||'.$destination.'||'.$charge.'<br>';
        $updateOrderSQL = "update tbl_order_details set ratechartId = $ratechartId , destination = '$destination', charge = $charge where orderid = '$orderid' and  merchantCode = 'M-1-0152' and orderDate = '2017-05-09';";
        echo $updateOrderSQL.'<br>';
    }
    
?>
