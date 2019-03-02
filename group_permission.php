<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_usersetting` WHERE user_id = $user_id_chk and permission = 'Y'"));
    if ($userPrivCheckRow['permission'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <?php if (!isset($_POST['search'])){?>
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search User for Permission</p>
                Select User : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchuserid" style="width: 250px; height: 28px">
                <?php
                    $listUser = "select * from tbl_user_info where userName != 'admin'";
                    $listResult = mysqli_query($conn, $listUser);
                    foreach ($listResult as $userRow){
                        echo "<option value='".$userRow['user_id']."'>".$userRow['userName']."</option>";
                    }  
                ?>
                </select><br>

                <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;<br><br>
            </form>
            <?php }?>
        </div>
        <div style="margin-left: 15px; width: 98%">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Select Group</p>
                <?php
                    if (isset($_POST['search'])){
                        $userid = trim($_POST['searchuserid']);
                        $gpSQL = "Select * from tbl_permission_group where user_id = $userid";
                        $gpResult = mysqli_query($conn, $gpSQL);
                        $row_count = mysqli_num_rows($gpResult);
                        $gpRow = mysqli_fetch_array($gpResult);
                        $userSQL = "select * from tbl_user_info where user_id= $userid";
                        $userResult = mysqli_query($conn, $userSQL);
                        $usrRow = mysqli_fetch_array($userResult);
                        if ($row_count > 0){
                            // Exising user
                ?>
                Select User : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchuserid" style="width: 250px; height: 28px">
                <?php
                    $listUser = "select * from tbl_user_info where userName != 'admin'";
                    $listResult = mysqli_query($conn, $listUser);
                    foreach ($listResult as $userRow){
                        echo "<option value='".$userRow['user_id']."'"?><?php if ($userRow['user_id'] == $userid) {echo "selected";} echo ">".$userRow['userName']."</option>";
                    }  
                ?>
                </select><br>
                <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;<br><br>
                <input type="hidden" name="userid" value="<?php echo $userid;?>">
                <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                <u>Group Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                <hr>
                <table>
                    <tr>
                        <td style="width: 150px">Order Management</td>
                        <td style="width: 170px">Database Management</td>
                        <td style="width: 150px">Relocation</td>
                        <td style="width: 150px">User Setting</td>
                        <td style="width: 100px">Reports</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="Order_Management" value="Y" <?php if($gpRow['Order_Management'] == 'Y'){ echo 'checked';}?>></td>
                        <td><input type="checkbox" name="Database_Management" value="Y" <?php if($gpRow['Database_Management'] == 'Y'){ echo 'checked';}?>></td>
                        <td><input type="checkbox" name="Capacity_Relocation" value="Y" <?php if($gpRow['Capacity_Relocation'] == 'Y'){ echo 'checked';}?>></td>
                        <td><input type="checkbox" name="User_Setting" value="Y" <?php if($gpRow['User_Setting'] == 'Y'){ echo 'checked';}?>></td>
                        <td><input type="checkbox" name="Report" value="Y" <?php if($gpRow['Report'] == 'Y'){ echo 'checked';}?>></td>
                    </tr>
                </table>
                <?php
                            
                        } else {
                            //If New user 
                ?>
                <input type="hidden" name="userid" value="<?php echo $userid;?>">
                <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                <u>Group Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                <hr>
                <table>
                    <tr>
                        <td style="width: 150px">Order Management</td>
                        <td style="width: 170px">Database Management</td>
                        <td style="width: 150px">Relocation</td>
                        <td style="width: 150px">User Setting</td>
                        <td style="width: 100px">Reports</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="Order_Management" value="Y"></td>
                        <td><input type="checkbox" name="Database_Management" value="Y"></td>
                        <td><input type="checkbox" name="Capacity_Relocation" value="Y"></td>
                        <td><input type="checkbox" name="User_Setting" value="Y"></td>
                        <td><input type="checkbox" name="Report" value="Y"></td>
                    </tr>
                </table>
                <?php
                        }
                    }
                ?>
                <br>
                <input type="submit" name="submit" class="btn btn-primary" value="Update Permission">
            </form>
            <?php
                if (isset($_POST['submit'])){
                    $userid = trim($_POST['userid']);
                    $userExist = trim($_POST['userExist']);
                    $Order_Management = trim($_POST['Order_Management']);
                    $Database_Management = trim($_POST['Database_Management']);
                    $Capacity_Relocation = trim($_POST['Capacity_Relocation']);
                    $User_Setting = trim($_POST['User_Setting']);
                    $Report = trim($_POST['Report']);
                    if ($userExist == 0) {
                        //Insert
                        $insertSQL = "INSERT INTO tbl_permission_group (user_id, Order_Management, Database_Management, 
                        Capacity_Relocation, User_Setting, Report, creation_date, created_by) 
                        values ('$userid', '$Order_Management', '$Database_Management', '$Capacity_Relocation', 
                        '$User_Setting', '$Report', NOW() + INTERVAL 6 HOUR, '$user_check')";
                        if (!mysqli_query($conn,$insertSQL))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                echo "<div class='alert alert-success'>";
                                    echo "Group Permission Update successfully";
                                echo "</div>";
                            }
                    } else {
                        //update
                        $updateSQL = "UPDATE tbl_permission_group set Order_Management = '$Order_Management',  
                        Database_Management = '$Database_Management', Capacity_Relocation = '$Capacity_Relocation', 
                        User_Setting = '$User_Setting', Report = '$Report', 
                        update_date = NOW() + INTERVAL 6 HOUR , updated_by = '$user_check' where user_id = $userid"; 
                        if (!mysqli_query($conn,$updateSQL))
                            {
                                $error ="Update Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                echo "<div class='alert alert-success'>";
                                    echo "Group Permission Update successfully";
                                echo "</div>";
                            }
                    }
                }
                mysqli_close($conn);
            ?>
        </div>
        <script>
                $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
                    $('#searchid').select2();
                });
        </script>
    </body>
</html>
