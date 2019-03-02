<?php
    include('session.php');
    include('header.php');
    include('config.php');
    if (isset($_POST['submit'])) {
        $empCode = trim($_POST['empCode']);
        $empName = trim($_POST['empName']);
        $empName = mysqli_real_escape_string($conn, $empName);
        $desigid = trim($_POST['desigid']);
        $contactNumber = trim($_POST['contactNumber']);
        $contactNumber = mysqli_real_escape_string($conn, $contactNumber);
        $email = trim($_POST['email']);
        $email = mysqli_real_escape_string($conn, $email);
        $address = trim($_POST['address']);
        $address = mysqli_real_escape_string($conn, $address);
        $thanaId = trim($_POST['thanaId']);
        $districtId = trim($_POST['districtId']);
        $doj = date("Y-m-d", strtotime(trim($_POST['doj'])));
        $hrBand = trim($_POST['hrBand']);
        $hrBand = mysqli_real_escape_string($conn, $hrBand);
        $basicSalary = trim($_POST['basicSalary']);
        $houseRent = trim($_POST['houseRent']);
        $bankAccount = trim($_POST['bankAccount']);
        $bankAccount = mysqli_real_escape_string($conn, $bankAccount);
        $tinNumber = trim($_POST['tinNumber']);
        $tinNumber = mysqli_real_escape_string($conn, $tinNumber);
        $lineManager = trim($_POST['lineManager']);
        $dob = date("Y-m-d", strtotime(trim($_POST['dob'])));
        $maritalStatus = trim($_POST['maritalStatus']);
        $bloodGroup = trim($_POST['bloodGroup']);
        $bloodGroup = mysqli_real_escape_string($conn, $bloodGroup);
        $gender = trim($_POST['gender']);
        $fatherName = trim($_POST['fatherName']);
        $nid = trim($_POST['nid']);
        $emergencyName = trim($_POST['emergencyName']);
        $emergencyName = mysqli_real_escape_string($conn, $emergencyName);
        $emergencyAddress = trim($_POST['emergencyAddress']);
        $emergencyAddress = mysqli_real_escape_string($conn, $emergencyAddress);
        $emergencyNumber = trim($_POST['emergencyNumber']);
        $emergencyNumber = mysqli_real_escape_string($conn, $emergencyNumber);
        $relationship = trim($_POST['relationship']);
        $relationship = mysqli_real_escape_string($conn, $relationship);
        $emergencynid = trim($_POST['emergencynid']);
        $emergencynid = mysqli_real_escape_string($conn, $emergencynid);
        $empUpateSQL = "UPDATE tbl_employee_info SET empName='$empName',
        desigid='$desigid', contactNumber='$contactNumber', email='$email', address='$address', thanaId='$thanaId',
        districtId='$districtId', doj='$doj', hrBand='$hrBand', basicSalary='$basicSalary', houseRent='$houseRent',
        bankAccount='$bankAccount',tinNumber='$tinNumber', lineManager='$lineManager',dob='$dob', 
        maritalStatus='$maritalStatus', bloodGroup='$bloodGroup', gender='$gender', fatherName='$fatherName', nid='$nid', 
        emergencyName='$emergencyName', emergencyAddress='$emergencyAddress', emergencyNumber='$emergencyNumber' , relationship='$relationship',
        emergencynid='$emergencynid' , update_date=NOW(), updated_by='$user_check' WHERE empCode = '$empCode'";
        if (!mysqli_query($conn,$empUpateSQL))
        {
            $error ="Insert Error : " . mysqli_error($conn);
            echo "<div class='alert alert-danger'>";
                echo "<strong>Error!</strong>".$error; 
            echo "</div>";
        } else {
            echo "<div class='alert alert-success'>";
                echo "Employee updated successfully";
            echo "</div>";                                
        }
        //Delete employee points
        $deleteSQL = "delete from tbl_employee_point where empCode ='$empCode'";
        $deleteResult = mysqli_query($conn, $deleteSQL);
        //Insert employee points
        $checked_point = $_POST['pointAssigned'];
        $count = count($checked_point);
        for ($c=0; $c < $count; $c++) {
            //echo $checked_thana[$c];
            //echo "<br>";
            $ins_qry="INSERT INTO tbl_employee_point(empCode, pointCode, creation_date, created_by) 
            VALUES('$empCode', '".$checked_point[$c]."',  NOW(), '$user_check')";
            mysqli_query($conn, $ins_qry);
        }           
    }
    mysqli_close($conn);
?>
    </body>
</html>