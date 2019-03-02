<?php
    include('session.php');
    include('header.php');
    include('config.php');
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Update Pick-up System</p>
                <div class="radio" style="font: 14px 'paperfly roman'">
                    <?php 
                        if (isset($_POST['submit'])) {
                            $pickType = trim($_POST['pickType']);
                            if ($pickType == 'central'){
                                $updatePickSys = "update tbl_pickup_system set central = 'Y', local = '', update_time = NOW(), update_by = '$user_check'";
                            } else {
                                $updatePickSys = "update tbl_pickup_system set local = 'Y', central = '', update_time = NOW(), update_by = '$user_check'";
                            }
                            if (!mysqli_query($conn,$updatePickSys))
                                {
                                    $error ="Update Error : " . mysqli_error($conn);
                                    echo "<div class='alert alert-danger'>";
                                        echo "<strong>Error!</strong>".$error; 
                                    echo "</div>";
                                } else {
                                    $pickStateSQL = "Select central, local from tbl_pickup_system";
                                    $pickStateResult = mysqli_query($conn,$pickStateSQL);
                                    $pickStateRow = mysqli_fetch_array($pickStateResult);
                                    //echo "<meta http-equiv='refresh' content='0'>";
                                    if ($pickStateRow['central'] == 'Y'){
                                         ?>
                                            Central&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="central" type="radio" name="pickType" value="central" checked>
                                            Local&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="local" type="radio" name="pickType" value="local">
                                        <?php } else {?>
                                            Central&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="central" type="radio" name="pickType" value="central">
                                            Local&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="local" type="radio" name="pickType" value="local" checked>
                                        <?php }
                                }
                            } else {
                                $pickStateSQL = "Select central, local from tbl_pickup_system";
                                $pickStateResult = mysqli_query($conn,$pickStateSQL);
                                $pickStateRow = mysqli_fetch_array($pickStateResult);
                                //echo "<meta http-equiv='refresh' content='0'>";
                                if ($pickStateRow['central'] == 'Y'){
                                        ?>
                                        Central&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="central" type="radio" name="pickType" value="central" checked>
                                        Local&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="local" type="radio" name="pickType" value="local">
                                    <?php } else {?>
                                        Central&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="central" type="radio" name="pickType" value="central">
                                        Local&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="local" type="radio" name="pickType" value="local" checked>
                                    <?php }                                
                            }
                        mysqli_close($conn);                    
                    ?>
                    <br>
                    <br>
                    <input class="btn btn-primary" type="submit" name="submit" value="Update">
                </div>
            </form>
        </div>
    </body>
</html>
