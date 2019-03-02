<?php
include('config.php');

$statusDate = date('Y-m-d', strtotime('-1 day'));

$orderListResult = mysqli_query($conn, "select merOrderRef from tbl_order_details where DATE_FORMAT(CashTime, '%Y-%m-%d') = '$statusDate' and merchantCode = 'M-1-0262' and merOrderRef not like 'Gift%'");

foreach($orderListResult as $orderListRow){
    $data = '{"OrderIds":['.$orderListRow['merOrderRef'].'],"ThirdPartyId":30}';      


    $curl = curl_init();

    curl_setopt_array($curl, array(

        CURLOPT_URL => "http://bridge.ajkerdeal.com/ThirdPartyOrderAction/UpdateStatusByCourier",

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

    $orderIds = $orderListRow['merOrderRef'];
    $pushDate = date('Y-m-d');

    $response = mysqli_real_escape_string($conn, $response);

    echo "Resonse #:".$orderIds. $response."<br>";

    //if ($err) {
    //    $insertLog = mysqli_query($conn, "insert into tbl_api_push (orderCount, merchantCode, pushDate, status) values ('0', '$orderIds', '$pushDate', '$err')");    
    //    echo "cURL Error #:".$orderIds. $err;

    //} else {
        $insertLog = mysqli_query($conn, "insert into tbl_api_push (orderCount, merchantCode, pushDate, status) values ('1', '$orderIds', '$pushDate', '$response')");    
        //echo $response;

    //}

}

?>
