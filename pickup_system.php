<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_capacityrel WHERE user_id = $user_id_chk and pickupSystem = 'Y'"));
    if ($userPrivCheckRow['pickupSystem'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Update Pick-up System</p>
            <div style="float: left;  width: 50%; font: 14px 'paperfly roman'">
                <p style="width: 100%; height: 25px; color: #16469E; font: 20px 'paperfly roman'"><u>Large/Special/Special Plus Item Pick-up</u></p>
                <form name="frmLarge" action="" method="post">
                    <table>
                        <tr>
                            <td>
                                <label style="font: 15px 'paperfly roman'">District</label>
                            </td>
                            <td>
                                <label style="font: 15px 'paperfly roman'">Central Pick Point</label>
                            </td>
                            <td>
                                <label style="font: 15px 'paperfly roman'">Central Drop Point</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php  
                                    $sqlDistrict = "select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info)";
                                    $districtResult = mysqli_query($conn, $sqlDistrict);
                                ?>
                                <select id="district" name="largeDistrict" style="color: #16469E; font: 15px 'paperfly roman'">
                                    <?php
                                        foreach ($districtResult as $distritRow){ 
                                    ?>
                                    <option value="<?php echo $distritRow['districtId'];?>"><?php echo $distritRow['districtName'];?></option>
                                    <?php }?>
                                </select>
                            </td>
                            <td>
                                <?php
                                    $sqlLargePoint = "select pointCode, pointName from tbl_point_info";
                                    $largePointResult = mysqli_query($conn, $sqlLargePoint);
                                ?>
                                <select id="largePoint" name="largePoint" style="color: #16469E; font: 15px 'paperfly roman'">
                                    <?php
                                        foreach ($largePointResult as $largePointRow){
                                    ?>
                                    <option value="<?php echo $largePointRow['pointCode'];?>"><?php echo $largePointRow['pointCode']." - ".$largePointRow['pointName'];?></option>
                                    <?php }?>
                                </select>
                            </td>
                            <td>
                                <select id="largePoint" name="largeDropPoint" style="color: #16469E; font: 15px 'paperfly roman'">
                                    <option value="customer">Customer</option>
                                    <?php
                                        foreach ($largePointResult as $largePointRow){
                                    ?>
                                    <option value="<?php echo $largePointRow['pointCode'];?>"><?php echo $largePointRow['pointCode']." - ".$largePointRow['pointName'];?></option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" name="largeSubmit" value="Update" class="btn-primary">
                </form>
            </div>
            <div style="width: 50%; float: right; font: 14px 'paperfly roman'">
                <p style="width: 100%; height: 25px; color: #16469E; font: 20px 'paperfly roman'"><u>Regular Item Pick-up</u></p>
                <form name="frmRegular" action="" method="post">
                    <table>
                        <tr>
                            <td>
                                <label style="font: 15px 'paperfly roman'">Point List</label>
                            </td>
                            <td>
                                <label style="font: 15px 'paperfly roman'">Pick-up Point</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <select id="pointList" name="pointList" style="color: #16469E; font: 15px 'paperfly roman'">
                                    <?php
                                        foreach ($largePointResult as $largePointRow){
                                    ?>
                                    <option value="<?php echo $largePointRow['pointCode'];?>"><?php echo $largePointRow['pointCode']." - ".$largePointRow['pointName'];?></option>
                                    <?php }?>
                                </select>
                            </td>
                            <td>
                                <select id="pickUpPoint" name="pickUpPoint" style="color: #16469E; font: 15px 'paperfly roman'">
                                    <?php
                                        foreach ($largePointResult as $largePointRow){
                                    ?>
                                    <option value="<?php echo $largePointRow['pointCode'];?>"><?php echo $largePointRow['pointCode']." - ".$largePointRow['pointName'];?></option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" name="regularSubmit" value="Update" class="btn-primary">
                </form>
            </div>
            <div style="float: left; width: 48%">
                <?php
                $listCentralPoint = "SELECT tbl_district_info.districtName, tbl_central_point.pointCode, tbl_point_info.pointName, tbl_central_point.dropPointCode FROM tbl_central_point, tbl_district_info, tbl_point_info where tbl_central_point.districtId = tbl_district_info.districtId and tbl_central_point.pointCode = tbl_point_info.pointCode";
                $centralPointResult = mysqli_query($conn, $listCentralPoint);
                ?>
                <table class='table table-hover'>
                    <tr style='background-color:#dad8d8; li'>
                        <th>District Name</th>
                        <th>Central Point</th>
                        <th>Central Drop Point</th> 
                    </tr>
                    <?php
                        foreach ($centralPointResult as $centralPointRow){
                            ?>
                                <tr>
                                    <td><?php echo $centralPointRow['districtName'];?></td>
                                    <td><?php echo $centralPointRow['pointCode'];?></td>
                                    <td><?php echo $centralPointRow['dropPointCode'];?></td>
                                </tr>
                            <?php
                        }
                    ?>
                </table>
            </div>
            <div style="float: right; width: 50%">
                <?php
                $listRegularPoint = "SELECT v_regular_point.pointCode, v_regular_point.pointName, v_regular_point.pickPointCode, tbl_point_info.pointName as pickPointName FROM v_regular_point, tbl_point_info where v_regular_point.pickPointCode = tbl_point_info.pointCode";
                $regularPointResult = mysqli_query($conn, $listRegularPoint);
                ?>
                <table class='table table-hover'>
                    <tr style='background-color:#dad8d8; li'>
                        <th>Point List</th>
                        <th>Pick-up Point</th> 
                    </tr>
                    <?php
                        foreach ($regularPointResult as $regularPointRow){
                            ?>
                                <tr>
                                    <td><?php echo $regularPointRow['pointCode']." - ".$regularPointRow['pointName'];?></td>
                                    <td><?php echo $regularPointRow['pickPointCode']." - ".$regularPointRow['pickPointName'];?></td>
                                </tr>
                            <?php
                        }
                    ?>
                </table>
            </div>
            <?php 
                if (isset($_POST['largeSubmit'])){
                    $largeDistrict = trim($_POST['largeDistrict']);
                    $largePoint = trim($_POST['largePoint']);
                    $largeDropPoint = trim($_POST['largeDropPoint']);
                    //$largePointResult = trim($_POST['largePoint']);
                    $searchDistrictSQL = "select * from tbl_central_point where districtId = '$largeDistrict'";
                    $searchDistrictResult = mysqli_query($conn, $searchDistrictSQL);
                    $searchDistrictRow = mysqli_fetch_array($searchDistrictResult);
                    if (mysqli_num_rows($searchDistrictResult) < 1){
                        $insertDistrictSQL = "Insert into tbl_central_point (districtId, pointCode, dropPointCode, creation_date, created_by) values ('$largeDistrict', '$largePoint', '$largeDropPoint', NOW() + INTERVAL 6 HOUR, '$user_check')";
                        $insertDistrictResult = mysqli_query($conn, $insertDistrictSQL);
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else {
                        $centralId = $searchDistrictRow['centralId'];
                        $updateDistrictSQL = "update tbl_central_point set pointCode = '$largePoint', update_date = NOW() + INTERVAL 6 HOUR, updated_by = '$user_check' where centralId = '$centralId'";
                        $updateDistrictResult = mysqli_query($conn, $updateDistrictSQL);
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                }
                if (isset($_POST['regularSubmit'])){
                    $pointList = trim($_POST['pointList']);
                    $pickUpPoint = trim($_POST['pickUpPoint']);
                    $searchPointSQL = "select * from tbl_regular_point where pointCode = '$pointList'";
                    $searchPointResult = mysqli_query($conn, $searchPointSQL);
                    $searchPointRow = mysqli_fetch_array($searchPointResult);
                    if (mysqli_num_rows($searchPointResult) < 1){
                        $insertPointSQL = "Insert into tbl_regular_point (pointCode, pickPointCode, creation_date, created_by) values ('$pointList', '$pickUpPoint', NOW() + INTERVAL 6 HOUR, '$user_check')";
                        $insertPointResult = mysqli_query($conn, $insertPointSQL);
                        echo "<meta http-equiv='refresh' content='0'>";
                    } else {
                        $pickId = $searchPointRow['pickID'];
                        $updatePointSQL = "update tbl_regular_point set pickPointCode = '$pickUpPoint', update_date = NOW() + INTERVAL 6 HOUR, updated_by = '$user_check' where pickID = '$pickId'";
                        $updatePointResult = mysqli_query($conn, $updatePointSQL);
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                }
            ?>
        </div>
    </body>
</html>
