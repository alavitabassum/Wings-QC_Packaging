<?php
    if(isset($_POST['rootData'])){
        include('session.php');
        include('config.php');
        $rootData = trim($_POST['rootData']);
        $rootData = mysqli_real_escape_string($conn, $rootData);
        $flag = $_POST['flagreq'];
        $flag = mysqli_real_escape_string($conn, $flag);

        if($flag == 'sendPickupAccept'){
            $getMobileNoSQL = "SELECT orderid, SUBSTRING(merOrderRef,1,15) as merOrderRef, SUBSTRING(tbl_merchant_info.merchantName,1,33) as merchantName, tbl_merchant_info.sendSms, custname, custphone, substring(REPLACE(custphone,' ',''), -10) as phone, destination FROM tbl_order_details left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode where barcode = SUBSTRING('$rootData',1,11)";
            $getMobileNoResult = mysqli_query($conn, $getMobileNoSQL);
            $getMobileNoRow = mysqli_fetch_array($getMobileNoResult);

            $smsSendingStatus = $getMobileNoRow['sendSms'];

            if($smsSendingStatus == 'Y'){
            //echo $getMobileNoSQL;

//            $url='http://api.rankstelecom.com/api/v3/sendsms/json';
// 
//            $ch=curl_init($url);

//            $mobile = '880'.$getMobileNoRow['phone'];

//            if($getMobileNoRow['destination'] == 'local'){
//            $message = 'Your order '.$getMobileNoRow['merOrderRef'].' from '.$getMobileNoRow['merchantName'].' is ready for delivery. Our team will contact you. Thank You!
//Paperfly';

//            } else {
//            $message = 'Your order '.$getMobileNoRow['merOrderRef'].' from '.$getMobileNoRow['merchantName'].' is ready for delivery. Our team will contact you soon. Thank You!
//Paperfly';
//                
//            }


//            $data=array(
//            'authentication'=>array('username'=>'Paperfly','password'=>'TbikjHrN'),
//            'messages'=>array(array('sender'=>'7777','text'=>$message,'recipients'=>array(array('gsm'=>$mobile))
//            ))
//            );
//            $jsondataencode=json_encode($data);
//            CURL_SETOPT($ch,CURLOPT_POST,1);
//            CURL_SETOPT($ch,CURLOPT_POSTFIELDS,$jsondataencode);
//            CURL_SETOPT($ch,CURLOPT_HTTPHEADER,array('content-type:application/json'));
//            $result=CURL_EXEC($ch);
//            curl_close($ch);                
            } else {
                echo "Error : SMS not send as per merchant request";
            }
        }

        if($flag == 'Ret'){
            $getMobileNoSQL = "SELECT substring(REPLACE(contactNumber,' ',''), -10) as phone, v_order_details.merOrderRef FROM `tbl_merchant_info` left join (SELECT merchantCode, merOrderRef from tbl_order_details WHERE orderid = '$rootData') as v_order_details on tbl_merchant_info.merchantCode = v_order_details.merchantCode WHERE tbl_merchant_info.merchantCode = (SELECT merchantCode from tbl_order_details where orderid = '$rootData')";
            $getMobileNoResult = mysqli_query($conn, $getMobileNoSQL);
            $getMobileNoRow = mysqli_fetch_array($getMobileNoResult);

            //echo $getMobileNoSQL;

            $url='http://api.rankstelecom.com/api/v3/sendsms/json';
 
            $ch=curl_init($url);

            $mobile = '880'.$getMobileNoRow['phone'];

            //echo $mobile;

            $message = 'Order #'.$getMobileNoRow['merOrderRef'].' has been returned. Please find the details in Paperfly Wings.
Paperfly';

            $data=array(
            'authentication'=>array('username'=>'Paperfly','password'=>'TbikjHrN'),
            'messages'=>array(array('sender'=>'7777','text'=>$message,'recipients'=>array(array('gsm'=>$mobile))
            ))
            );
            $jsondataencode=json_encode($data);
            CURL_SETOPT($ch,CURLOPT_POST,1);
            CURL_SETOPT($ch,CURLOPT_POSTFIELDS,$jsondataencode);
            CURL_SETOPT($ch,CURLOPT_HTTPHEADER,array('content-type:application/json'));
            $result=CURL_EXEC($ch);
            curl_close($ch);            
        }

        if($flag == 'invoiceMessage'){
            $getMobileNoSQL = "SELECT invNum, substring(REPLACE(contactNumber,' ',''), -10) as phone FROM `tbl_invoice` left join tbl_merchant_info on tbl_invoice.merchantCode=tbl_merchant_info.merchantCode WHERE tbl_invoice.merchantCode = '$rootData' order by tbl_invoice.invSeq desc limit 1";
            $getMobileNoResult = mysqli_query($conn, $getMobileNoSQL);
            $getMobileNoRow = mysqli_fetch_array($getMobileNoResult);

            if(mysqli_num_rows($getMobileNoResult) > 0){
                $url='http://api.rankstelecom.com/api/v3/sendsms/json';
 
                $ch=curl_init($url);

                $mobile = '880'.$getMobileNoRow['phone'];

                $invNum = $getMobileNoRow['invNum'];

                $message = date('d-M-Y', strtotime('+6 hours')).'. Your invoice has been generated. Invoice #'.$invNum.' .Please download it from paperfly.com.bd .
    Paperfly';

                $data=array(
                'authentication'=>array('username'=>'Paperfly','password'=>'TbikjHrN'),
                'messages'=>array(array('sender'=>'7777','text'=>$message,'recipients'=>array(array('gsm'=>$mobile))
                ))
                );
                $jsondataencode=json_encode($data);
                CURL_SETOPT($ch,CURLOPT_POST,1);
                CURL_SETOPT($ch,CURLOPT_POSTFIELDS,$jsondataencode);
                CURL_SETOPT($ch,CURLOPT_HTTPHEADER,array('content-type:application/json'));
                $result=CURL_EXEC($ch);
                curl_close($ch);                
            } else {
                echo "No SMS send";
            }
        }
        if($flag == 'onHold'){
            $getMobileNoSQL = "SELECT tbl_merchant_info.merchantName, v_order_details.dropPointCode, substring(REPLACE(contactNumber,' ',''), -10) as phone, substring(REPLACE(v_order_details.custphone,' ',''), -10) as custphone, v_order_details.merOrderRef, v_order_details.onHoldSchedule, v_order_details.onHoldReason FROM `tbl_merchant_info` left join (SELECT merchantCode, dropPointCode, merOrderRef, custphone, DATE_FORMAT(onHoldSchedule, '%d-%M-%Y') as onHoldSchedule, onHoldReason from tbl_order_details WHERE orderid = '$rootData') as v_order_details on tbl_merchant_info.merchantCode = v_order_details.merchantCode WHERE tbl_merchant_info.merchantCode = (SELECT merchantCode from tbl_order_details where orderid = '$rootData')";
            $getMobileNoResult = mysqli_query($conn, $getMobileNoSQL);
            $getMobileNoRow = mysqli_fetch_array($getMobileNoResult);

            $onHoldReason = $getMobileNoRow['onHoldReason'];

            $dropPoint = $getMobileNoRow['dropPointCode'];

            //Send SMS to merchant;

            $url='http://api.rankstelecom.com/api/v3/sendsms/json';
 
            $ch=curl_init($url);

            $mobile = '880'.$getMobileNoRow['phone'];

            //echo $mobile;

            $message = 'Order #'.$getMobileNoRow['merOrderRef'].' has been rescheduled to '.$getMobileNoRow['onHoldSchedule'].' because of '.$getMobileNoRow['onHoldReason'].'.
Paperfly';

            $data=array(
            'authentication'=>array('username'=>'Paperfly','password'=>'TbikjHrN'),
            'messages'=>array(array('sender'=>'7777','text'=>$message,'recipients'=>array(array('gsm'=>$mobile))
            ))
            );
            $jsondataencode=json_encode($data);
            CURL_SETOPT($ch,CURLOPT_POST,1);
            CURL_SETOPT($ch,CURLOPT_POSTFIELDS,$jsondataencode);
            CURL_SETOPT($ch,CURLOPT_HTTPHEADER,array('content-type:application/json'));
            $result=CURL_EXEC($ch);
            curl_close($ch);
            
            if($onHoldReason == 'Customer unreachable'){

                $pointManagerSQL = mysqli_fetch_array(mysqli_query($conn, "select substring(REPLACE(contactNumber,' ',''), -10) as phone from tbl_employee_info where desigid = 6 and empCode in (select empCode from tbl_employee_point where pointCode = '$dropPoint') and isActive = 'Y'"));

                $ch=curl_init($url);

                $mobile = '880'.$getMobileNoRow['custphone'];

                //echo $mobile;

                $message = 'We are unable to contact you to deliver your Order ID:'.$getMobileNoRow['merOrderRef'].' from '.$getMobileNoRow['merchantName'].'. Pls call @ 0'.$pointManagerSQL['phone'].' for further assistance.
Paperfly';

                $data=array(
                'authentication'=>array('username'=>'Paperfly','password'=>'TbikjHrN'),
                'messages'=>array(array('sender'=>'7777','text'=>$message,'recipients'=>array(array('gsm'=>$mobile))
                ))
                );
                $jsondataencode=json_encode($data);
                CURL_SETOPT($ch,CURLOPT_POST,1);
                CURL_SETOPT($ch,CURLOPT_POSTFIELDS,$jsondataencode);
                CURL_SETOPT($ch,CURLOPT_HTTPHEADER,array('content-type:application/json'));
                $result=CURL_EXEC($ch);
                curl_close($ch);                
            }            
        }

        if($flag == 'smsStatus'){
            $status = $_POST['status'];
            $phone = $_POST['phone'];
            $insertSMSSQL = "insert into tbl_sms_log (orderid, status, phone, creationDate, createdBy) values ('$rootData', '$status', '$phone', NOW() + INTERVAL 6 HOUR, '$user_check')";
            if(!mysqli_query($conn, $insertSMSSQL)){
                echo "Error : Unable insert record".mysqli_error($conn);
            } else{
                echo "success";
            }
        }
        if($flag == 'smsMerchantUser'){
           $phone = $_POST['phone'];

            $url='http://api.rankstelecom.com/api/v3/sendsms/json';
 
            $ch=curl_init($url);

            $mobile = '880'.substr($phone, -10);

            $url = 'http://paperflybd.com/placeorders';

            //echo $mobile;

            $message = 'Your registration in Paperfly is successful. User ID '.$rootData.' Password '.$rootData.'.To Plcae order log into Paperfly WINGS '.$url ;
            $data=array(
            'authentication'=>array('username'=>'Paperfly','password'=>'TbikjHrN'),
            'messages'=>array(array('sender'=>'7777','text'=>$message,'recipients'=>array(array('gsm'=>$mobile))
            ))
            );
            $jsondataencode=json_encode($data);
            CURL_SETOPT($ch,CURLOPT_POST,1);
            CURL_SETOPT($ch,CURLOPT_POSTFIELDS,$jsondataencode);
            CURL_SETOPT($ch,CURLOPT_HTTPHEADER,array('content-type:application/json'));
            $result=CURL_EXEC($ch);
            curl_close($ch); 
        }




    }
?>
