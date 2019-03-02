<?php
include('config.php');
if (isset($_POST['orderRequest'])){
    $appUser = trim($_POST['appUser']);
    $json = trim($_POST['orderRequest']);
    $obj = json_decode($json, true);
}
if($appUser == 'runway'){
    $success = 0;
    $barcodeValue ='';
    foreach ($obj['orders'] as $item){
        $user =$item['user'];
        $barcode = $item['barcode'];
        $requestfor = $item['requestfor'];
        $cashAmt = $item['cashamt'];
        $cashType =$item['cashtype'];
        $cashComment = $item['cashcomment'];
        $cashComment = mysqli_real_escape_string($conn, $cashComment);
        $retReason =$item['retReason'];
        $retReason = mysqli_real_escape_string($conn, $retReason);
        $retRem = $item['retRem'];
        $retRem = mysqli_real_escape_string($conn, $retRem);
        $partialReceive = $item['partialReceive'];
        $partialReturn = $item['partialReturn'];
        $partialReason = $item['partialReason'];
        $partialReason = mysqli_real_escape_string($conn, $partialReason);
        $onHoldDate = date("Y-m-d", strtotime($_POST['onHoldDate']));
        $onHoldReason = $item['onHoldReason'];

        //Update for pick request
        if($requestfor == 'pick'){
            $searchPickSQL = "select orderid, pickPointEmp, pick from tbl_order_details where barcode = SUBSTRING('$barcode',1,11)";
            $searchPickResult = mysqli_query($conn, $searchPickSQL);
            if (mysqli_num_rows($searchPickResult) > 0) {
                $searchPickRow = mysqli_fetch_array($searchPickResult);
                $orderid = $searchPickRow['orderid'];
                $pick = $searchPickRow['pick'];
                $employeeCode = $searchPickRow['pickPointEmp']; 
                if($pick !='Y'){
                    $searchUserNameSQL = "select userName from tbl_user_info where merchEmpCode = '$employeeCode' and userName = '$user'";
                    $searchUserNameResult = mysqli_query($conn, $searchUserNameSQL);
                    $searchUserNameRow = mysqli_fetch_array($searchUserNameResult);
                    $userNameResult = $searchUserNameRow['userName'];
                    if($user == $userNameResult){
                        $updateOrdersSQL = "update tbl_order_details set pick = 'Y', pickTime = NOW() + INTERVAL 6 HOUR, pickBy = '$userNameResult' where orderid = '$orderid'";
                        if(!mysqli_query($conn, $updateOrdersSQL)){
                        } else{
                            if($success == 1){
                                $barcodeValue=$barcodeValue.',{"barcode":"'.$barcode.'"}';
                            } else {
                                $barcodeValue = '{"barcode":"'.$barcode.'"}';
                            }
                            $success = 1;                            
                        }
                    }
                }
            }
        }

        //Update for Shuttle request
        if($requestfor == 'shuttle'){
            $searchUserNameSQL = "select userName, shuttle from tbl_user_info where userName = '$user' and shuttle='Y'";
            $searchUserNameResult = mysqli_query($conn, $searchUserNameSQL);
            if (mysqli_num_rows($searchUserNameResult) > 0){
                $searchShtlSQL = "select orderid, pickPointEmp, Shtl from tbl_order_details where barcode = SUBSTRING('$barcode',1,11)";
                $searchShtlResult = mysqli_query($conn, $searchShtlSQL);
                if (mysqli_num_rows($searchShtlResult) > 0) {
                    $searchShtlRow = mysqli_fetch_array($searchShtlResult);
                    $orderid = $searchShtlRow['orderid'];
                    $Shtl = $searchShtlRow['Shtl'];
                    if($Shtl !='Y'){
                        $updateOrdersSQL = "update tbl_order_details set Shtl = 'Y', ShtlTime = NOW() + INTERVAL 6 HOUR, ShtlBy = '$user' where orderid = '$orderid'";
                        if(!mysqli_query($conn, $updateOrdersSQL)){
                        } else{
                            if($success == 1){
                                $barcodeValue=$barcodeValue.',{"barcode":"'.$barcode.'"}';
                            } else {
                                $barcodeValue = '{"barcode":"'.$barcode.'"}';
                            }
                            $success = 1;                            
                        }
                    }
                }
            }
        }

        //Update for droppick request
        if($requestfor == 'droppick'){
            $searchPickSQL = "select orderid, dropPointEmp, PickDrop from tbl_order_details where barcode = SUBSTRING('$barcode',1,11)";
            $searchPickResult = mysqli_query($conn, $searchPickSQL);
            if (mysqli_num_rows($searchPickResult) > 0) {
                $searchPickRow = mysqli_fetch_array($searchPickResult);
                $orderid = $searchPickRow['orderid'];
                $PickDrop = $searchPickRow['PickDrop'];
                $employeeCode = $searchPickRow['dropPointEmp']; 
                if($PickDrop !='Y'){
                    $searchUserNameSQL = "select userName from tbl_user_info where merchEmpCode = '$employeeCode' and userName = '$user'";
                    $searchUserNameResult = mysqli_query($conn, $searchUserNameSQL);
                    $searchUserNameRow = mysqli_fetch_array($searchUserNameResult);
                    $userNameResult = $searchUserNameRow['userName'];
                    if($user == $userNameResult){
                        $updateOrdersSQL = "update tbl_order_details set PickDrop = 'Y', PickDropTime = NOW() + INTERVAL 6 HOUR, PickDropBy = '$userNameResult' where orderid = '$orderid'";
                        if(!mysqli_query($conn, $updateOrdersSQL)){
                        } else{
                            if($success == 1){
                                $barcodeValue=$barcodeValue.',{"barcode":"'.$barcode.'"}';
                            } else {
                                $barcodeValue = '{"barcode":"'.$barcode.'"}';
                            }
                            $success = 1;                            
                        }
                    }
                }
            }
        }

        //Update for cash request
        if($requestfor == 'cash'){
            $searchPickSQL = "select orderid, dropPointEmp, Cash from tbl_order_details where barcode = SUBSTRING('$barcode',1,11)";
            $searchPickResult = mysqli_query($conn, $searchPickSQL);
            if (mysqli_num_rows($searchPickResult) > 0) {
                $searchPickRow = mysqli_fetch_array($searchPickResult);
                $orderid = $searchPickRow['orderid'];
                $Cash = $searchPickRow['Cash'];
                $employeeCode = $searchPickRow['dropPointEmp']; 
                if($Cash !='Y'){
                    $searchUserNameSQL = "select userName from tbl_user_info where merchEmpCode = '$employeeCode' and userName = '$user'";
                    $searchUserNameResult = mysqli_query($conn, $searchUserNameSQL);
                    $searchUserNameRow = mysqli_fetch_array($searchUserNameResult);
                    $userNameResult = $searchUserNameRow['userName'];
                    if($user == $userNameResult){
                        $updateOrdersSQL = "update tbl_order_details set Cust = 'Y', CustTime = NOW() + INTERVAL 6 HOUR, CustBy = '$userNameResult', Cash = 'Y', CashTime = NOW() + INTERVAL 6 HOUR, CashBy = '$userNameResult', CashAmt = $cashAmt, cashComment = '$cashComment', cashType = '$cashType' where orderid = '$orderid'";
                        if(!mysqli_query($conn, $updateOrdersSQL)){
                        } else {
                            if($success == 1){
                                $barcodeValue=$barcodeValue.',{"barcode":"'.$barcode.'"}';
                            } else {
                                $barcodeValue = '{"barcode":"'.$barcode.'"}';
                            }
                            $success = 1;                            
                        }
                    }
                }
            }
        }


        //Update for return request
        if($requestfor == 'return'){
            $searchPickSQL = "select orderid, dropPointEmp, Ret from tbl_order_details where barcode = SUBSTRING('$barcode',1,11)";
            $searchPickResult = mysqli_query($conn, $searchPickSQL);
            if (mysqli_num_rows($searchPickResult) > 0) {
                $searchPickRow = mysqli_fetch_array($searchPickResult);
                $orderid = $searchPickRow['orderid'];
                $Ret = $searchPickRow['Ret'];
                $employeeCode = $searchPickRow['dropPointEmp']; 
                if($Ret !='Y'){
                    $searchUserNameSQL = "select userName from tbl_user_info where merchEmpCode = '$employeeCode' and userName = '$user'";
                    $searchUserNameResult = mysqli_query($conn, $searchUserNameSQL);
                    $searchUserNameRow = mysqli_fetch_array($searchUserNameResult);
                    $userNameResult = $searchUserNameRow['userName'];
                    if($user == $userNameResult){
                        $updateOrdersSQL = "update tbl_order_details set Cust = 'Y', CustTime = NOW() + INTERVAL 6 HOUR, CustBy = '$userNameResult', Ret = 'Y', RetTime = NOW() + INTERVAL 6 HOUR, RetBy = '$userNameResult', retReason = $retReason, retRem = '$retComment' where orderid = '$orderid'";
                        if(!mysqli_query($conn, $updateOrdersSQL)){
                        } else {
                            if($success == 1){
                                $barcodeValue=$barcodeValue.',{"barcode":"'.$barcode.'"}';
                            } else {
                                $barcodeValue = '{"barcode":"'.$barcode.'"}';
                            }
                            $success = 1;                            
                        }
                    }
                }
            }
        }


        //Update for partial request
        if($requestfor == 'partial'){
            $searchPickSQL = "select orderid, dropPointEmp, partial from tbl_order_details where barcode = SUBSTRING('$barcode',1,11)";
            $searchPickResult = mysqli_query($conn, $searchPickSQL);
            if (mysqli_num_rows($searchPickResult) > 0) {
                $searchPickRow = mysqli_fetch_array($searchPickResult);
                $orderid = $searchPickRow['orderid'];
                $partial = $searchPickRow['partial'];
                $employeeCode = $searchPickRow['dropPointEmp']; 
                if($partial !='Y'){
                    $searchUserNameSQL = "select userName from tbl_user_info where merchEmpCode = '$employeeCode' and userName = '$user'";
                    $searchUserNameResult = mysqli_query($conn, $searchUserNameSQL);
                    $searchUserNameRow = mysqli_fetch_array($searchUserNameResult);
                    $userNameResult = $searchUserNameRow['userName'];
                    if($user == $userNameResult){
                        $updateOrdersSQL = "update tbl_order_details set Cust = 'Y', CustTime = NOW() + INTERVAL 6 HOUR, CustBy = '$userNameResult', partial = 'Y', partialTime = NOW() + INTERVAL 6 HOUR, partialBy = '$userNameResult', partialReceive = $partialReceive, partialReturn = '$partialReturn', cashType = '$cashType', partialReason = '$partialReason' where orderid = '$orderid'";
                        if(!mysqli_query($conn, $updateOrdersSQL)){
                        } else {
                            if($success == 1){
                                $barcodeValue=$barcodeValue.',{"barcode":"'.$barcode.'"}';
                            } else {
                                $barcodeValue = '{"barcode":"'.$barcode.'"}';
                            }
                            $success = 1;                            
                        }
                    }
                }
            }
        }
        if($requestfor == 'onHold'){
            $searchPickSQL = "select orderid, dropPointEmp, Rea from tbl_order_details where barcode = SUBSTRING('$barcode',1,11)";
            $searchPickResult = mysqli_query($conn, $searchPickSQL);
            if (mysqli_num_rows($searchPickResult) > 0) {
                $searchPickRow = mysqli_fetch_array($searchPickResult);
                $orderid = $searchPickRow['orderid'];
                $onHold = $searchPickRow['Rea'];
                $employeeCode = $searchPickRow['dropPointEmp']; 
                if($onHold !='Y'){
                    $searchUserNameSQL = "select userName from tbl_user_info where merchEmpCode = '$employeeCode' and userName = '$user'";
                    $searchUserNameResult = mysqli_query($conn, $searchUserNameSQL);
                    $searchUserNameRow = mysqli_fetch_array($searchUserNameResult);
                    $userNameResult = $searchUserNameRow['userName'];
                    if($user == $userNameResult){
                        $updateOrdersSQL = "update tbl_order_details set Cust = 'Y', CustTime = NOW() + INTERVAL 6 HOUR, CustBy = '$userNameResult', Rea = 'Y', ReaTime = NOW() + INTERVAL 6 HOUR, ReaBy = '$userNameResult', onHoldSchedule = '$onHoldDate', onHoldReason = '$onHoldReason' where orderid = '$orderid'";
                        if(!mysqli_query($conn, $updateOrdersSQL)){
                        } else {
                            if($success == 1){
                                $barcodeValue=$barcodeValue.',{"barcode":"'.$barcode.'"}';
                            } else {
                                $barcodeValue = '{"barcode":"'.$barcode.'"}';
                            }
                            $success = 1;                            
                        }
                    }
                }
            }
        }


    }
    echo '['.$barcodeValue.']';        
}
?>