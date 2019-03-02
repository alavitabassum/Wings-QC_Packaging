<?php
require_once('config.php');

	  $merchantName = trim($_POST['merchantName']);
                    $merchantName = mysqli_real_escape_string($conn, $merchantName);
                    $businessType = trim($_POST['businessType']);
                    $productNature = trim($_POST['productNature']);
                    $productNature = mysqli_real_escape_string($conn, $productNature);
                    $address = trim($_POST['address']);
                    $address = mysqli_real_escape_string($conn, $address);
                    $districtid = trim($_POST['districtid']);
                    $thanaid = trim($_POST['thanaid']);
                    $website = trim($_POST['website']);
                    $website = mysqli_real_escape_string($conn, $website);
                    $facebook = trim($_POST['facebook']);
                    $facebook = mysqli_real_escape_string($conn, $facebook);
                    $companyPhone = trim($_POST['companyPhone']);
                    $companyPhone = mysqli_real_escape_string($conn, $companyPhone);
                    $contactName = trim($_POST['contactName']);
                    $contactName = mysqli_real_escape_string($conn, $contactName);
                    $designation = trim($_POST['designation']);
                    $designation = mysqli_real_escape_string($conn, $designation);
                    $contactNumber = trim($_POST['contactNumber']);
                    $contactNumber = mysqli_real_escape_string($conn, $contactNumber);
                    $email = trim($_POST['email']);
                    $email = mysqli_real_escape_string($conn, $email);
                    $accountName = trim($_POST['accountName']);
                    $accountName = mysqli_real_escape_string($conn, $accountName);
                    $accountNumber = trim($_POST['accountNumber']);
                    $accountNumber = mysqli_real_escape_string($conn, $accountNumber);
                    $bankName = trim($_POST['bankName']);
                    $bankName = mysqli_real_escape_string($conn, $bankName);
                    $branch = trim($_POST['branch']);
                    $branch = mysqli_real_escape_string($conn, $branch);
                    $routeNumber =trim($_POST['routeNumber']);
                    $routeNumber = mysqli_real_escape_string($conn, $routeNumber);
                    $paymentMode = trim($_POST['paymentMode']);

    //    $district = trim($_POST['district']);
    //    $thanaid = trim($_POST['thanaid']);

    $insertsql = "INSERT INTO temporary_merchant_reg
                (merchantName, shopMarket, productNature, 
                    address, thanaid, district, website, facebook, companyPhone, contactPerson, designation, contactNumber, email, accountName, accountNumber, bankName, branch, routeNumber,paymentMode, creation_date)
                values ('$merchantName' ,'$businessType' ,'$productNature' ,'$address' ,'$thanaid'  ,'$districtid', '$website',
                    '$facebook' , '$companyPhone', '$contactName', '$designation', '$contactNumber', '$email', '$accountName', '$accountNumber', '$bankName', '$branch', '$routeNumber' ,'$paymentMode', NOW() + INTERVAL 6 HOUR)";

    mysqli_query($conn,$insertsql);

    mysqli_close($conn);

 ?>