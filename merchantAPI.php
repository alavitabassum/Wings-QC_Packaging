<?php
    include('config.php');
    if(isset($_POST['username'])){
        $username = $_POST['username'];
        $password = $_POST['pass'];

        // Establishing Connection with Server by passing server_name, user_id and password as a parameter
        // To protect MySQL injection for Security purpose
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        $password = md5($password);

        //fetch table rows from mysql db
        $sql = "select userName, merchEmpCode from tbl_user_info where userName='$username'";
        //$sql = "select userName, merchEmpCode from tbl_user_info where userPassword='$password' AND userName='$username'";
        $result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
        

        if (mysqli_num_rows($result) > 0) {

            $userRow = mysqli_fetch_array($result);
            $userCode = $userRow['merchEmpCode'];
            
            //$merchantPickSQL = "SELECT tbl_order_details.merchantCode, tbl_merchant_info.merchantName, tbl_merchant_info.address, tbl_merchant_info.companyPhone, count(1) as totorder, SUM(IF(productSizeWeight = 'large' OR productSizeWeight = 'special' OR productSizeWeight = 'specialplus',1,0)) as large, SUM(IF(deliveryOption = 'express',1,0)) as express  FROM `tbl_order_details` left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode WHERE Pick is null and pickPointEmp = '$userCode' group by merchantCode";
            $merchantPickSQL = "SELECT merchantCode, merchantName, contactName, contactNumber  FROM tbl_merchant_info WHERE isActive = 'Y'"; 
            $merchantPickResult = mysqli_query($conn, $merchantPickSQL);
            if (mysqli_num_rows($merchantPickResult) > 0) {
                //create an array
                $merArray = array();
                while($row =mysqli_fetch_assoc($merchantPickResult))
                {
                    $merArray[] = $row;
                }
                echo json_encode(array("merchantlist"=>$merArray));

                //close the db connection
                mysqli_close($conn);
            }
        }
    }   
?>