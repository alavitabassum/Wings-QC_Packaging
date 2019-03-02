<?php
    include('config.php');
    if(isset($_POST['username'])){
        $username = $_POST['username'];
        $password = $_POST['pass'];
        $merchantCode = trim($_POST['merchantCode']);

        // Establishing Connection with Server by passing server_name, user_id and password as a parameter
        // To protect MySQL injection for Security purpose
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        $password = md5($password);

        //fetch table rows from mysql db
        $sql = "select userName, merchEmpCode from tbl_user_info where userPassword='$password' AND userName='$username'";
        $result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));
        

        if (mysqli_num_rows($result) > 0) {

            $userRow = mysqli_fetch_array($result);
            $userCode = $userRow['merchEmpCode'];
            
            $merchantPickSQL = "SELECT orderid, barcode, merchantCode, merOrderRef, packagePrice, custname, custphone as phone  FROM tbl_order_details  WHERE Pick is null and pickPointEmp = '$userCode' and merchantCode = '$merchantCode'"; 
            $merchantPickResult = mysqli_query($conn, $merchantPickSQL);
            if (mysqli_num_rows($merchantPickResult) > 0) {
                //create an array
                $merArray = array();
                while($row =mysqli_fetch_assoc($merchantPickResult))
                {
                    $merArray[] = $row;
                }
                echo json_encode($merArray);

                //close the db connection
                mysqli_close($conn);
            }
        }
    }   
?>