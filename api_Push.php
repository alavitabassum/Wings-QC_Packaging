<?php
include('config.php');

$startDate = date('Y-m-d',strtotime('-1 day'));
$startDateTime = $startDate.' 07:00';

//$startDateTime = '2018-03-25 05:00';
//echo $startDateTime.'<br>';

$endDate = date('Y-m-d');
$endDateTime = $endDate.' 11:00';
//$endDateTime =  '2018-03-26 05:00';

//echo $endDateTime.'<br>';

$orderListResult = mysqli_query($conn, "select orderid, merOrderRef, orderDate, merchantCode from tbl_order_details where DATE_FORMAT(tbl_order_details.ShtlTime,'%Y-%m-%d %H:%i') between '$startDateTime' and '$endDateTime' and Shtl = 'Y' and merchantCode = 'M-1-0262' and orderid not in (SELECT orderid FROM `tbl_order_details` WHERE merOrderRef like 'Gift%')");
//$orderListResult = mysqli_query($conn, "select orderid, merOrderRef, orderDate, merchantCode from tbl_order_details where DATE_FORMAT(tbl_order_details.ShtlTime,'%Y-%m-%d %H:%i') between '2018-03-20 05:00' and '2018-03-21 05:00' and Shtl = 'Y' and merchantCode = 'M-1-0262' and orderid not in (SELECT orderid FROM `tbl_order_details` WHERE merOrderRef like 'Gift%')");

$lineCount = 0;
foreach($orderListResult as $orderListRow){
    $merchantIDs = str_replace(' ','',$orderListRow['merOrderRef']);
    $findComma = substr($merchantIDs, -1);
    if($findComma == ','){
        $strLength = strlen($merchantIDs);
        $strConsideration = $strLength - 1;
        $merchantID = substr($merchantIDs, 0, $strConsideration);
    } else {
        $merchantID = $merchantIDs;
    }
    //if($lineCount == 0){
        $data = '[{"OrderIds":['.$merchantID.'],"ThirdPartyOrderId":"'.$orderListRow['orderid'].'","ThirdPartyId":30}]';      
    //} else {
    //    $data .= ',{"OrderIds":['.$merchantID.'],"ThirdPartyOrderId":"'.$orderListRow['orderid'].'","ThirdPartyId":30}';
    //}
    //$lineCount++;

$curl = curl_init();

//$data = '['.$data.']';
//echo $data;

//$insertLog = mysqli_query($conn, "insert into tbl_api_push (orderCount, merchantCode, pushDate) values ('$lineCount', 'M-1-0262', '$endDate')");

//echo $data.'<br>';

//echo $lineCount; 

        curl_setopt_array($curl, array(

          CURLOPT_URL => "http://bridge.ajkerdeal.com/ThirdPartyOrderAction/UpdateBulkPODnumberStatus",

          CURLOPT_RETURNTRANSFER => true,

          CURLOPT_ENCODING => "",

          CURLOPT_MAXREDIRS => 10,

          CURLOPT_TIMEOUT => 90,

          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

          CURLOPT_CUSTOMREQUEST => "POST",

          CURLOPT_POSTFIELDS => $data ,

          CURLOPT_HTTPHEADER => array(

            "api_key: Ajkerdeal_~La?Rj73FcLm",

            "authorization: Basic UGFwZXJGbHk6SGpGZTVWNWY=",

            "cache-control: no-cache",

            "content-type: application/json",

            "postman-token: 3a2fe2f4-6dc8-26a0-107f-488588971493"

          ),

        ));

         

        $response = curl_exec($curl);

        $err = curl_error($curl);

         

        curl_close($curl);

 

if ($err) {
    $insertLog = mysqli_query($conn, "insert into tbl_api_push (orderCount, merchantCode, pushDate, status) values ('0', 'M-1-0262', '$endDate', '$merchantID.$err')");    
  echo "cURL Error #:" . $err;

} else {
    //$insertLog = mysqli_query($conn, "insert into tbl_api_push (orderCount, merchantCode, pushDate, status) values ('$lineCount', 'M-1-0262', '$endDate', '$response')");    
  echo $response;
  $lineCount++;

}
}
$insertLog = mysqli_query($conn, "insert into tbl_api_push (orderCount, merchantCode, pushDate, status) values ('$lineCount', 'M-1-0262', '$endDate', 'Successfull')");    

?>