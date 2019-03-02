<?php
    if(isset($_POST['get_orderid'])){
        include('session.php');
        include('config.php');
        $rateId = $_POST['get_orderid'];
        $chargerate = $_POST['chargerate'];
        $chargerate = mysqli_real_escape_string($conn, $chargerate);
        $flag = $_POST['flagreq'];
        if ($flag == 'update'){
            $updateorders="update tbl_rate_type set charge='$chargerate', update_date =  NOW() + INTERVAL 6 HOUR , updated_by ='$user_check' where rateId='$rateId'";
            if (!mysqli_query($conn,$updateorders)){
                $error ="Update Error : " . mysqli_error($conn);
                echo $error;
            } else {
                echo "success";
            }
            mysqli_close($conn);
            exit;
        }
        if ($flag == 'delete'){
            $updateorders="delete from  tbl_rate_type where rateId='$rateId'";
            if (!mysqli_query($conn,$updateorders)){
                $error ="Delete Error : " . mysqli_error($conn);
                echo $error;
            } else {
                echo "success";
            }
            mysqli_close($conn);
            exit;
        }
    }
?>