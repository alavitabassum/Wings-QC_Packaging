<?php
    include('config.php');
    $hitSQL = "UPDATE tbl_hit_counter SET contact_us=contact_us+1 WHERE id = 1";
    $hitResult = mysqli_query($conn, $hitSQL);
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="icon" type="image/jpg" href="image/favicon.jpg">
        <title>Paperfly | About Us</title>
        <link rel="stylesheet" href="styles.css">
        <style>
            div.img {
                margin: auto;
                width: 700px;
            }

            div.img img {
                width: 250px;
                height: auto;
                margin-left: 225px;
                margin-top: 30px;
            }
            div.container {
                margin: auto;
                height: 450px;
                width: 600px;
            }
            div.section1 {
                margin: auto;
                height: 30px;
                width: 540px;
                float: left;
            } 
            div.section2 {
                height: 40px;
                width: 540px;
                float: left;
            }
            div.section3 {
                height: 110px;
                width: 700px;
                float: left;
            }  
            div.section4 {
                height: 80px;
                width: 700px;
                float: left;
            } 
            div.section5 {
                height: 20px;
                width: 700px;
                float: left;
            }
                                                                    
        </style>
    </head>
    <body>
        <div class="img">
            <a href="http://paperfly.com.bd/main">
                <img src="../image/paperfly.png" alt="Paperfly Logo"/>
            </a>
        </div>
        <br>
        <div class="container">
            <div class="section1">
                <p style="text-align: justify; color: #16469E; font: 13px 'paperfly roman'">                
                    Contact info
                </p>
            </div>

            <div class="section2">
                <p style="text-align: justify; color: #16469E; font: 16px 'paperfly italic'">                
                    PAPERFLY Private Limited
                </p>
            </div>
            <div class="section3">
                <p style="text-align: justify; color: #00AEEF; font: 15px 'paperfly roman'">
                    House 04, Road 15, <br>Block D, Banani,  <br>Dhaka 1213,  <br>Bangladesh
                </p>
            </div>
            <div class="section4">
                <p style="text-align: justify; color: #16469E; font: 15px 'paperfly roman'">                
                    Call: +880 2 9851140, +880 1678 401000 <br>
                    Email: info@paperfly.com.bd
                </p>
            </div>
            <div class="section5" style="text-align: justify; font: 15px 'paperfly roman'">
                <br>
                <a style="color: #00AEEF" href="http://paperfly.com.bd/main">www.paperfly.com.bd</a>
            </div>
        </div>
    </body>
</html>
