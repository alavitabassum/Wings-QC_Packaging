<?php
    include('config.php');
    $hitSQL = "UPDATE tbl_hit_counter SET home_page=home_page+1 WHERE id = 1";
    $hitResult = mysqli_query($conn, $hitSQL);
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="icon" type="image/jpg" href="image/favicon.jpg">
        <link rel="stylesheet" href="styles.css">
        <link href="css/bootstrap-combined.min.css" rel="stylesheet">
        <title>Paperfly | Home</title>
    </head>
    <body>
        <img style="position: absolute; z-index: -1; top: 0; left: 0; width:100%" src="http://paperfly.com.bd/image/Landing.jpg" alt="Paperfly Home"/>
        <div style="z-index: 1; margin-top: 33%; margin-left:2%; width: 100%; height: auto; font: 120% 'paperfly italic'">
            <a href="login" style="color: #fff">Log in</a><br><br>
            <a href="http://paperfly.com.bd/apply" style="color: #fff">Merchant Application</a><br><br>
            <a href="about" style="color: #fff">About PAPERFLY</a><br><br>
            <a href="contact" style="color: #fff">Contact Us</a>
        </div>
        <div style="z-index: 2; width: 280px; height: 10%; float: right; font: 10px 'paperfly italic'">
            Copyright © 2016 All rights reserved PAPERFLY Private Ltd.
        </div>
    </body>
</html>
