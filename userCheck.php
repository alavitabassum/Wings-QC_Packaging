<?php
    include('session.php');
    if(isset($_POST['userCheck'])){
        include('config.php');
        $userCode = $_POST['userCheck'];
        $flag = $_POST['flag'];
        if ($flag == 'shuttle' or $flag == "retcp1") {
            $findsql="SELECT empCode FROM tbl_employee_info WHERE desigid in (9,10) and empCode='$user_code'";
            //echo $findsql;
            $findresult = mysqli_query($conn, $findsql);
            if (mysqli_num_rows($findresult) > 0) {
                echo "shuttleTrue";
            }
            exit;
        } else {
            $findsql="SELECT empCode FROM tbl_employee_info WHERE desigid in (3,4,6,7,8,9) and empCode='$user_code'";
            $findresult = mysqli_query($conn, $findsql);
            if (mysqli_num_rows($findresult) > 0) {
                echo "empTrue";
            }
            exit;
        }
    }
?>

