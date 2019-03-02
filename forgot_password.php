<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
        <link rel="icon" type="image/jpg" href="image/favicon.jpg">
		<title>PaperFly Login</title>
		<meta name="generator" content="Bootply" />
        <link rel="stylesheet" href="styles.css">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<style>
body, html {
    height: 100%;
    background-repeat: no-repeat;
}

.card-container.card {
    max-width: 350px;
    padding: 40px 40px;
}

.btn {
    color: #fff;
    height: 36px;
    width: 100%;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    cursor: default;
}
.card {
    padding: 20px 25px 30px;
    margin: 0 auto 25px;
    margin-top: 50px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.3);
}

.form-signin #inputtext,
.form-signin #inputPassword {
    direction: ltr;
    height: 44px;
    font: 18px 'paperfly roman';
}

.form-signin input[type=password],
.form-signin input[type=text],
.form-signin button {
    width: 100%;
    display: block;
    margin-bottom: 10px;
    z-index: 1;
    position: relative;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

.form-signin .form-control:focus {
    border-color: rgb(104, 145, 162);
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
}

.btn.btn-signin {
    background-color: #00AEEF;
    padding: 0px;
    font-weight: 700;
    font-size: 14px;
    height: 36px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border: none;
    -o-transition: all 0.218s;
    -moz-transition: all 0.218s;
    -webkit-transition: all 0.218s;
    transition: all 0.218s;
}

.btn.btn-signin:hover,
.btn.btn-signin:active,
.btn.btn-signin:focus {
        background-color: #16469E;
}

.forgot-password {
    color: #00AEEF;
}

.forgot-password:hover,
.forgot-password:active,
.forgot-password:focus{
    color: #16469E;
}
</style>
	</head>
	<body>
        <div class="container" style="margin-top: 8%">
            <div class="card card-container">
                <img id="profile-img" class="profile-img-card" style="height: 100px; width: 80%; margin-left: 13%" src="image/Wings.jpg" />
                <p style="color: #16469E; text-align: center; font: 20px 'paperfly roman'">Forgot WINGS Password</p>
                <form action="" class="form-signin" method="post">
                    <input type="text" id="inputtext" class="form-control" name="username" placeholder="User Name" required autofocus>
                    <p style="color: #16469E; text-align: justify; font: 13px 'paperfly roman'">If PAPERFLY have your email address, new password will be sent to your email address. 
                        Otherwise your password change request will be notified to the concerned PAPERFLY official. 
                    </p>
                    <input class="btn btn-signin" style="font: 18px 'paperfly roman'" name="submit" type="submit" value="Are you sure?">
                </form>
                <div style="text-align: left; width: 90px;float: left; clear: both">
                    <span style="font: 13px 'paperfly roman'"><a class="forgot-password" href="main">Back to Home</a></span>
                </div>
                <div style=" text-align: right; width:110px;float: right">
                    <span style="font: 13px 'paperfly roman'" ><a class="forgot-password" href="login">Back to Login</a></span>
                </div>
            <?php
                if (isset($_POST['submit'])){
            ?>
                <div style="clear: both; font: 13px 'paperfly roman'">
                    <br>
            <?php
                    include('config.php');
                    $username = trim($_POST['username']);
                    $username = mysqli_real_escape_string($conn, $username);
                    $userTypeSQL = "select userType, merchEmpCode from tbl_user_info where userName='$username'";
                    $userTypeResult = mysqli_query($conn, $userTypeSQL);
                    $userTypeRow = mysqli_fetch_array($userTypeResult);
                    if ($userTypeRow['userType'] == 'Merchant') {
                        $merchantCode = $userTypeRow['merchEmpCode'];
                        $merchantSQL = "Select email from tbl_merchant_info where merchantCode = '$merchantCode'";
                        $merchantResult = mysqli_query($conn, $merchantSQL);
                        $merchantRow = mysqli_fetch_array($merchantResult);
                        if ($merchantRow['email'] !=''){
                            $requestDate=date("Y-m-d");
                            $userPassword=$username.date("dmy", strtotime($requestDate))."$";
                            $updatePassSQL = "update tbl_user_info set userPassword = md5('$userPassword') where userName='$username'";
                            if (!mysqli_query($conn,$updatePassSQL))
                                {
                                    $error="Request Error : " . mysqli_error($conn);
                                    echo $error;
                                } else {
                                    $to = trim($merchantRow['email']);
                                    $subject = "Password Chanage Request";
                                    $message = "Your new password is :".$userPassword;
                                    $from = "noreply@paperfly.com.bd";
                                    $headers = "From:" . $from;
                                    mail($to,$subject,$message,$headers);
                                    echo "New password sent to your email address ".$merchantRow['email'];
                                }
                        } else {
                            $to = "info@paperfly.com.bd";
                            $subject = "Password Chanage Request from".$merchantCode;
                            $message = $merchantCode." requested to change password. Please take necessary action";
                            $from = "noreply@paperfly.com.bd";
                            $headers = "From:" . $from;
                            mail($to,$subject,$message,$headers);
                            echo "Password change request sent to PAPERFLY. Please contact with concerned PAPERFLY Official.";
                        }
                    }
                    if ($userTypeRow['userType'] == 'Employee') {
                        $empCode = $userTypeRow['merchEmpCode'];
                        $empSQL = "Select email from tbl_employee_info where empCode = '$empCode'";
                        $empResult = mysqli_query($conn, $empSQL);
                        $empRow = mysqli_fetch_array($empResult);
                        if ($empRow['email'] !=''){
                            $requestDate=date("Y-m-d");
                            $userPassword=$username.date("dmy", strtotime($requestDate))."$";
                            $updatePassSQL = "update tbl_user_info set userPassword = md5('$userPassword') where userName='$username'";
                            if (!mysqli_query($conn,$updatePassSQL))
                                {
                                    $error="Request Error : " . mysqli_error($conn);
                                    echo $error;
                                } else {
                                    $to = trim($empRow['email']);
                                    $subject = "Password Chanage Request";
                                    $message = "Your new password is :".$userPassword;
                                    $from = "noreply@paperfly.com.bd";
                                    $headers = "From:" . $from;
                                    mail($to,$subject,$message,$headers);
                                    echo "New password sent to your email address ".$empRow['email'];
                                }
                        } else {
                            $to = "info@paperfly.com.bd";
                            $subject = "Password Chanage Request from".$empCode;
                            $message = $empCode." requested to change password. Please take necessary action";
                            $from = "noreply@paperfly.com.bd";
                            $headers = "From:" . $from;
                            mail($to,$subject,$message,$headers);
                            echo "Password change request sent to PAPERFLY. Please contact with concerned PAPERFLY Official.";
                        }
                    }
            ?>
                </div>
            <?php
                mysqli_close($conn);
                }
            ?>
            </div>
        </div>
    </body>
</html>