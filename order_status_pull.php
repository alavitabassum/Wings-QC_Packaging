<?php
 

    include('config2.php');

    //cURL $_SERVER array list

    //Basic Authentication $_SERVER keys are [PHP_AUTH_USER] and [PHP_AUTH_PW] . examples are [PHP_AUTH_USER] => paperfly and [PHP_AUTH_PW] => paperfly123

            //Array
            //(
            //[REDIRECT_STATUS] => 200
            //    
            //[HTTP_USERNAME] => m10414
            //    
            //[HTTP_PASS] => ShopUp123
            //    
            //[HTTP_REQUESTFOR] => all
            //    
            //[HTTP_CACHE_CONTROL] => no-cache
            //    
            //[HTTP_POSTMAN_TOKEN] => a38e214d-4bab-434c-8565-b77bfed223bd
            //    
            //[HTTP_USER_AGENT] => PostmanRuntime/7.1.5
            //    
            //[HTTP_ACCEPT] => */*
            //    
            //[HTTP_HOST] => paperflybd.com
            //    
            //[HTTP_ACCEPT_ENCODING] => gzip, deflate
            //    
            //[CONTENT_LENGTH] => 0
            //    
            //[HTTP_CONNECTION] => keep-alive
            //    
            //[PATH] => /usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
            //    
            //[SERVER_SIGNATURE] =>
            //    <address>Apache/2.4.7 (Ubuntu) Server at paperflybd.com Port 80</address>

            //    
            //[SERVER_SOFTWARE] => Apache/2.4.7 (Ubuntu)
            //    
            //[SERVER_NAME] => paperflybd.com
            //    
            //[SERVER_ADDR] => 128.199.74.21
            //    
            //[SERVER_PORT] => 80
            //    
            //[REMOTE_ADDR] => 103.232.103.73
            //    
            //[DOCUMENT_ROOT] => /var/www/html
            //    
            //[REQUEST_SCHEME] => http
            //    
            //[CONTEXT_PREFIX] => 
            //    
            //[CONTEXT_DOCUMENT_ROOT] => /var/www/html
            //    
            //[SERVER_ADMIN] => webmaster@localhost
            //    
            //[SCRIPT_FILENAME] => /var/www/html/order_status_pull.php
            //    
            //[REMOTE_PORT] => 49977
            //    
            //[REDIRECT_URL] => /Request-Status
            //    
            //[GATEWAY_INTERFACE] => CGI/1.1
            //    
            //[SERVER_PROTOCOL] => HTTP/1.1
            //    
            //[REQUEST_METHOD] => POST
            //    
            //[QUERY_STRING] => 
            //    
            //[REQUEST_URI] => /Request-Status
            //    
            //[SCRIPT_NAME] => /order_status_pull.php
            //    
            //[PHP_SELF] => /order_status_pull.php
            //    
            //[PHP_AUTH_USER] => paperfly
            //    
            //[PHP_AUTH_PW] => paperfly123
            //    
            //[REQUEST_TIME_FLOAT] => 1530869554.285
            //    
            //[REQUEST_TIME] => 1530869554
            //)



    //cURL requese 
    
   if(isset($_SERVER['PHP_AUTH_USER'], $_SERVER['HTTP_PAPERFLYKEY'])){
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        $apiKey = $_SERVER['HTTP_PAPERFLYKEY'];

        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        $password = md5($password);

        $query = "select * from tbl_user_info where userPassword='$password' AND userName='$username' AND isActive = 'Y'";
        $result = mysqli_query($conn, $query);
         if (mysqli_num_rows($result) > 0)
         {
            //Need to check access level i.e. test , prod or none.

        $accessCheckSQL = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_api_client where userName = '$username'"));
        if($accessCheckSQL['userName'] !=''){
            $row = mysqli_fetch_array($result);
            $data = json_decode(file_get_contents("php://input"), TRUE);
            $i=0;
             foreach($data as $row){
            $i++;
            $action = $row['action'];
            $recipient_name = $row['recipient_name'];
            $recipient_email = $row['recipient_email'];
            $recipient_type = $row['recipient_type'];
            $recipient_mobile = $row['recipient_mobile'];
            $recipient_thana = $row['recipient_thana'];
            $recipient_district = $row['recipient_district'];
            $price = $row['price'];
            $weight = $row['weight'];
            $payment_method = $row['payment_method'];
            $order_id = $row['order_id'];
            $recipient_address = $row['recipient_address'];
            $pick_address = $row['pick_address'];
            $emi_detail = $row['emi_detail'];
            $freebee_detail = $row['freebee_detail'];
            $products_description = $row['products_description'];
            $comments = $row['comments'];


            if($accessCheckSQL['push'] == 'prod'){
                $Sql_Query = "insert into robishop (action,recipient_name,recipient_email,recipient_type,recipient_mobile,recipient_thana,recipient_district,price,weight,payment_method,order_id,recipient_address,pick_address,emi_detail,freebee_detail,products_description,comments) values ('$action','$recipient_name','$recipient_email',
                '$recipient_type','$recipient_mobile','$recipient_thana','$recipient_district','$price','$weight','$payment_method','$order_id','$recipient_address','$pick_address','$emi_detail','$freebee_detail','$products_description','$comments')";

               

            }
            if($accessCheckSQL['push'] == 'test'){
                $Sql_Query = "insert into robishop (action,recipient_name,recipient_email,recipient_type,recipient_mobile,recipient_thana,recipient_district,price,weight,payment_method,order_id,recipient_address,pick_address,emi_detail,freebee_detail,products_description,comments) values ('$action','$recipient_name','$recipient_email',
                '$recipient_type','$recipient_mobile','$recipient_thana','$recipient_district','$price','$weight','$payment_method','$order_id','$recipient_address','$pick_address','$emi_detail','$freebee_detail','$products_description','$comments')";

                /**/

            }
         }
            if(mysqli_query($conn,$Sql_Query)){

                    http_response_code(200);
                    $response['Success'] = "Total created order : " .$i;
                    echo json_encode($response);
                    echo '{ Status = 200, Message = "Success" }';
                  }
                else{
                    $response['error'] = mysqli_error($conn);
                    echo json_encode($response);
                  /* http_response_code(503);
                   echo json_encode($response);*/
                   echo '{ IsSuccess = false, Message = "Unsuccessfull" }';
                    

                }
        }
    } else {
        echo '[{"Error":"Invalid Username and Password"}]';
    } 
    }
    else{
       echo '{"Error" : "Please provide all information in the header"}';
    }
    //for form-data request
    if(isset($_POST['userName'])){
        $username = $_POST['userName'];
        $password = $_POST['pass'];
        $requestFor = $_POST['requestFor'];
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        $password = md5($password);
        $query = "select * from tbl_user_info where userPassword='$password' AND userName='$username' AND isActive = 'Y'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {

            //Need to check access level i.e. test , prod or none.

            $accessCheckSQL = mysqli_fetch_array(mysqli_query($conn, "select * from tbl_api_client where userName = '$username'"));
            if($accessCheckSQL['userName'] !=''){
                $row = mysqli_fetch_array($result);
                $merchantCode = $row['merchEmpCode'];

                if($accessCheckSQL['pull'] == 'prod'){
                    if(strtoupper($requestFor)=='ALL'){
                        $orderListSQL = "select orderid, merOrderRef, pick, pickTime, DP1, DP1Time, DP2, DP2Time, cust, custTime, cash, cashTime, ret, retTime as returnTime, partial, partialTime, Rea as onHold, onHoldSchedule from tbl_order_details where merchantCode = '$merchantCode' and close is null";
                        $orderListResult = mysqli_query($conn, $orderListSQL) or die ("Error searching orders information".mysqli_error($conn));

                        $ordersArray = array();
                        while($orderListRow = mysqli_fetch_assoc($orderListResult)){
                            $ordersArray[] = $orderListRow;
                        }
                        header("Content-type:application/json");
                        echo json_encode($ordersArray);     
   
                    } else {
                        $orderListSQL = "select orderid, merOrderRef, pick, pickTime, DP1, DP1Time, DP2, DP2Time, cust, custTime, cash, cashTime, ret, retTime as returnTime, partial, partialTime, Rea as onHold, onHoldSchedule from tbl_order_details where merchantCode = '$merchantCode' and (merOrderRef = '$requestFor' or orderid = '$requestFor' ) and close is null";
                        $orderListResult = mysqli_query($conn, $orderListSQL) or die ("Error searching orders information".mysqli_error($conn));
                        $ordersArray = array();
                        while($orderListRow = mysqli_fetch_assoc($orderListResult)){
                            $ordersArray[] = $orderListRow;
                        }
                        header("Content-type:application/json");
                        echo json_encode($ordersArray, JSON_UNESCAPED_UNICODE);                                                
                    }                     
                }
                
                if($accessCheckSQL['pull'] == 'test'){
                    if(strtoupper($requestFor)=='ALL'){
                        $orderListSQL = "select orderid, merOrderRef, pick, pickTime, DP1, DP1Time, DP2, DP2Time, cust, custTime, cash, cashTime, ret, retTime as returnTime, partial, partialTime, Rea as onHold, onHoldSchedule from tbl_order_test where merchantCode = '$merchantCode' and close is null";
                        $orderListResult = mysqli_query($conn, $orderListSQL) or die ("Error searching orders information".mysqli_error($conn));
                        $ordersArray = array();
                        while($orderListRow = mysqli_fetch_assoc($orderListResult)){
                            $ordersArray[] = $orderListRow;
                        }
                        header("Content-type:application/json");
                        echo json_encode($ordersArray);                
                    } else {
                        $orderListSQL = "select orderid, merOrderRef, pick, pickTime, DP1, DP1Time, DP2, DP2Time, cust, custTime, cash, cashTime, ret, retTime as returnTime, partial, partialTime, Rea as onHold, onHoldSchedule from tbl_order_test where merchantCode = '$merchantCode' and (merOrderRef = '$requestFor' or orderid = '$requestFor' ) and close is null";
                        $orderListResult = mysqli_query($conn, $orderListSQL) or die ("Error searching orders information".mysqli_error($conn));
                        $ordersArray = array();
                        while($orderListRow = mysqli_fetch_assoc($orderListResult)){
                            $ordersArray[] = $orderListRow;
                        }
                        header("Content-type:application/json");
                        echo json_encode($ordersArray);                                                
                    }                     
                }
                if($accessCheckSQL['pull'] == 'none'){
                    echo '[{"error":"No valid access. Please ask Paperfly for access"}]';
                } 
            }
        } else {
            echo '[{"error":"invalid username and password"}]';
        }
    mysqli_close($conn); // mysqli connection
    }

?>