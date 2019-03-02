<?php
    include('session.php');  
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
        //$sql = "select userName as usrname, userType as type, tbl_employee_info.empName, userRole from tbl_user_info left join tbl_employee_info on tbl_user_info.merchEmpCode = tbl_employee_info.empCode where userPassword='$password' AND userName='$username'";
        $sql = "select userName as usrname, userType as type, tbl_employee_info.empName, userRole from tbl_user_info left join tbl_employee_info on tbl_user_info.merchEmpCode = tbl_employee_info.empCode where userName='$username'";
        $result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
        if(mysqli_num_rows($result) > 0){
            //create an array
            $unAssignedResult = mysqli_query($conn, "SELECT tbl_order_details.merchantCode, tbl_merchant_info.address, pickMerchantName, pickMerchantAddress, tbl_merchant_info.merchantName, tbl_merchant_info.contactNumber, count(1) as cnt FROM `tbl_order_details` left join tbl_merchant_info on tbl_order_details.merchantCode = tbl_merchant_info.merchantCode WHERE pickPointEmp is null and close is null group by tbl_order_details.merchantCode, pickMerchantName order by tbl_order_details.merchantCode");
            $merArray = array();
            while($row =mysqli_fetch_assoc($unAssignedResult))
            {
                $merArray[] = $row;
            }
            echo json_encode(array("unAssignedlist"=>$merArray));

            //close the db connection
            mysqli_close($conn);            
        } else {
            echo 'failure';
        }
    } else {
        echo 'failure';
    }
?>