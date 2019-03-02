<?php
    include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Registration Request Confirmation</title>
    </head>
    <body>
        <div style="border-style: solid; border-color: #69afe2; border-radius: 10px; margin-left: 25%; width: 50%; margin-top: 5%">
            <p style="border-radius: 10px; width: 100%; height: 25px; font-weight: bold; font-size: 200%">User Registration Form</p>
            <hr>
            <?php
                if (isset($_POST['submit'])){
                    $compname = trim($_POST['compname']);
                    $compname = mysqli_real_escape_string($conn, $compname);
                    $businesstype = trim($_POST['businesstype']);
                    $businesstype = mysqli_real_escape_string($conn, $businesstype);
                    $address = trim($_POST['address']);
                    $address = mysqli_real_escape_string($conn, $address);
                    $thanaid = trim($_POST['thanaid']);
                    $districtid = trim($_POST['districtid']);
                    $compphone = trim($_POST['compphone']);
                    $webaddress = trim($_POST['webaddress']);
                    $webaddress = mysqli_real_escape_string($conn, $webaddress);
                    $faceaddress = trim($_POST['faceaddress']);
                    $faceaddress = mysqli_real_escape_string($conn, $faceaddress);
                    $contactname = trim($_POST['contactname']);
                    $contactname = mysqli_real_escape_string($conn, $contactname);
                    $designation = trim($_POST['designation']);
                    $designation = mysqli_real_escape_string($conn, $designation);
                    $phone = trim($_POST['phone']);
                    $email = trim($_POST['email']);
                    $email = mysqli_real_escape_string($conn, $email);
                    $insertsql = "Insert into tbl_merchant_temp (companyName, businessType, address, thanaId, 
                    districtId, compPhone, comWebsit, facebook, contactPerson, designation, contactMobile, 
                    contactEmail, reviewStatus, creation_date) values ('$compname', '$businesstype', '$address', '$thanaid', 
                    '$districtid', '$compphone', '$webaddress', '$faceaddress', '$contactname', '$designation', '$phone', '$email', '1', NOW())";
                    if (!mysqli_query($conn,$insertsql)){
                        $error ="Insert Error : " . mysqli_error($conn);
                        echo "<strong>Error!</strong>".$error;
                    } else {
                        echo "<p>Thank you for your interest. One of our customer relationship manager will contact you soon.</p>";
                    }
                }
            ?>
            <a  href="main">Back to Home Page</a>
        </div>
    </body>
</html>
