<?php
session_start(); // Starting Session
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Please enter user name and password properly";
    }
    else
    {   
        // Define $username and $password
        $username=$_POST['username'];
        $password=$_POST['password'];
        // Establishing Connection with Server by passing server_name, user_id and password as a parameter
        include('config.php');
        // To protect MySQL injection for Security purpose
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        $password = md5($password);
        // SQL query to fetch information of registerd users and finds user match.
        $query = "select * from tbl_user_info where userPassword='$password' AND userName='$username' AND isActive = 'Y'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $_SESSION['login_user'] = $row['userName'];
                $_SESSION['user_type'] = $row['userType'];
                $_SESSION['userId'] = $row['user_id'];
                $_SESSION['userCode'] = $row['merchEmpCode'];
            }
            // Point relocation effective date 
            $curDateSQL = "select NOW()+INTERVAL 6 HOUR as currdate";
            $currResult = mysqli_query($conn, $curDateSQL);
            $curRow = mysqli_fetch_array($currResult);
            $curDate = date("Y-m-d", strtotime($curRow['currdate']));
            $emppoint = "SELECT distinct empCode,from_date, through_date FROM tbl_emppoint_tmp";
            $result = mysqli_query($conn, $emppoint);
            foreach ($result as $row){
                $empCode = $row['empCode'];
                if ($row['through_date'] < $curDate){
                    $deletepoint = "delete from tbl_employee_point where empCode ='$empCode'";
                    mysqli_query($conn, $deletepoint);
                    $inspoint ="insert into tbl_employee_point select * from tbl_emppoint_orig where empCode ='$empCode'";
                    mysqli_query($conn,$inspoint);
                    $deletetmppoint = "delete from tbl_emppoint_tmp where empCode ='$empCode'";
                    mysqli_query($conn,$deletetmppoint);
                    $deleteorigpoint = "delete from tbl_emppoint_orig where empCode ='$empCode'";
                    mysqli_query($conn,$deleteorigpoint);
                    //echo "Delete & Restore original point<br>";
                } else {
                    if ($row['from_date'] <=$curDate){
                        $deleteempSQL = "delete from tbl_employee_point where empCode='$empCode'";
                        mysqli_query($conn, $deleteempSQL);
                        $updatePoint = "insert into tbl_employee_point select emppointid, empCode, pointCode, NOW()+INTERVAL 6 HOUR as creation_date, 'admin' as created_by, NOW()+INTERVAL 6 HOUR as update_date, 'admin' as updated_by from tbl_emppoint_tmp where empCode='$empCode'";
                        if (!mysqli_query($conn, $updatePoint)){
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "Error:".$error;
                        }
                        //echo "Update existing points<br>"; 
                    } 
                }
            }
            $loggedInUser = $_SESSION['login_user'];
            $loggedInDate = $curRow['currdate'];
            $loginHistSQL = "Insert into tbl_login_history (userName, loginDate) values ('$loggedInUser','$loggedInDate')"; 
            $loginHistResult = mysqli_query($conn, $loginHistSQL);
            //End of point relocation effective date        
            if ( $_SESSION['user_type'] == 'Merchant'){
                header("location: Merchant-Dashboard");
            }else{
                header("location: Home-Page");
            }
           
        }
            else {
            header("location: Merchant-Dashboard");
        
        }
    mysqli_close($conn); // mysqli connection
    }
}
?>