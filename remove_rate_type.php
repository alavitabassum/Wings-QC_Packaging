<?php
    if(isset($_POST['rate_type_id'])){
        include('config.php');
            $rateId = $_POST['rate_type_id'];
            $removeRateTypeSQL="delete from tbl_ratechart_name where ratechartId='$rateId'";
            if (!mysqli_query($conn,$removeRateTypeSQL)){
                $error ="Remove Error : " . mysqli_error($conn);
                echo $error;
            } else {
                echo "success";
            }
            mysqli_close($conn);
    }
?>