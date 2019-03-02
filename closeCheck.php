<?php
    include('session.php');
    if(isset($_POST['userCheck'])){
        include('config.php');
        $userCode = $_POST['userCheck'];
        $findsql="SELECT empCode FROM tbl_employee_info WHERE desigid in (3,4) and empCode='$user_code'";
        $findresult = mysqli_query($conn, $findsql);
        if (mysqli_num_rows($findresult) > 0) {
            echo "empTrue";
        }
        exit;
    }
?>

