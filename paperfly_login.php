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
                <img id="profile-img" class="profile-img-card" style="height: 100%; width: 100%" src="image/Wings.jpg" />
                <p style="color: #16469E; text-align: center; font: 20px 'paperfly roman'">Login to WINGS </p>
                <form action="userlogin" class="form-signin" method="post">
                    <input type="text" id="inputtext" class="form-control" name="username" placeholder="User Name" required autofocus>
                    <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
                    <input class="btn btn-signin" style="font: 18px 'paperfly roman'" name="submit" type="submit" value="Sign In">
                </form>
                <div style="text-align: left; width: 90px;float: left; clear: both">
                    <span style="font: 13px 'paperfly roman'"><a class="forgot-password" href="main">Back to Home</a></span>
                </div>
                <div style=" text-align: right; width:110px;float: right">
                    <span style="font: 13px 'paperfly roman'" ><a class="forgot-password" href="http://paperflybd.com/Recover-Password">Forgot password?</a></span>
                </div>

            </div>
                <div style="z-index: 2; width: 268px; height: 10%; margin: auto;  font: 10px 'paperfly italic'">
                    Copyright © 2016-2017 All rights reserved PAPERFLY Private Ltd.
                </div>
        </div>
    </body>
</html>