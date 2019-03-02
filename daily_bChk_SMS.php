<?php

 	$authorization = base64_encode('Paperfly:TbikjHrN');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json',"Authorization: Basic $authorization"));
    //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_URL, 'http://api.rankstelecom.com/account/1/balance');
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result  = curl_exec($ch);

    $obj = json_decode($result, TRUE);
    $smsBalance = round($obj['balance'],2);

    $message = "Remaining SMS balance TK. ".$smsBalance;
    $url='http://api.rankstelecom.com/api/v3/sendsms/json';
    $phone = '1711505045';
    // create a new cURL resource
    $ch=curl_init($url);

    $mobile1 = '8801711505045';
    $mobile2 = '8801976672194';

    $data=array(
    'authentication'=>array('username'=>'Paperfly','password'=>'TbikjHrN'),
    'messages'=>array(array('sender'=>'7777','text'=>$message,'recipients'=>array(array('gsm'=>$mobile1), array('gsm'=>$mobile2))
    ))
    );
    $jsondataencode=json_encode($data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    CURL_SETOPT($ch,CURLOPT_POST,1);
    CURL_SETOPT($ch,CURLOPT_POSTFIELDS,$jsondataencode);
    CURL_SETOPT($ch,CURLOPT_HTTPHEADER,array('content-type:application/json'));
    $result=CURL_EXEC($ch);
    curl_close($ch);	
?>