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
                Select User : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchuserid" style="width: 220px; height: 28px">
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
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Select Order Tracker Level</p>
                <?php
                    if (isset($_POST['search'])){
                        $userid = trim($_POST['searchuserid']);
                        $gpSQL = "Select * from tbl_order_tracker where user_id = $userid";
                        $gpResult = mysqli_query($conn, $gpSQL);
                        $row_count = mysqli_num_rows($gpResult);
                        $gpRow = mysqli_fetch_array($gpResult);
                        $userSQL = "select * from tbl_user_info where user_id= $userid";
                        $userResult = mysqli_query($conn, $userSQL);
                        $usrRow = mysqli_fetch_array($userResult);
                        if ($row_count > 0){
                            // Exising user
                ?>
                Select User : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchuserid" style="width: 220px; height: 28px">
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
                <u>Order Tracker Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                <hr>
                <table>
                    <tr>
                        <td style="width: 150px">Power User</td>
                        <td style="width: 130px">Level 4</td>
                        <td style="width: 170px">Level 3</td>
                        <td style="width: 150px">Level 2</td>
                        <td style="width: 150px">Level 1</td>
                        <td style="width: 150px">No Privilege</td>
                    </tr>
                    <tr>
                        <td><input id="op1" type="radio" name="level" value="power_user" <?php if($gpRow['level'] == 'power_user'){ echo 'checked';}?>></td>
                        <td><input id="op2" type="radio" name="level" value="level4" <?php if($gpRow['level'] == 'level4'){ echo 'checked';}?>></td>
                        <td><input id="op3" type="radio" name="level" value="level3" <?php if($gpRow['level'] == 'level3'){ echo 'checked';}?>></td>
                        <td><input id="op4" type="radio" name="level" value="level2" <?php if($gpRow['level'] == 'level2'){ echo 'checked';}?>></td>
                        <td><input id="op5" type="radio" name="level" value="level1" <?php if($gpRow['level'] == 'level1'){ echo 'checked';}?>></td>
                        <td><input id="op6" type="radio" name="level" value="noprivilege" <?php if($gpRow['level'] == 'noprivilege'){ echo 'checked';}?>></td>
                    </tr>
                </table>
                <?php
                            
                        } else {
                            //If New user 
                ?>
                <input type="hidden" name="userid" value="<?php echo $userid;?>">
                <input type="hidden" name="userExist" value="<?php echo $row_count;?>">
                <u>Order Tracker Permission Setting for :</u><u style="color: #0026ff"><strong><?php echo $usrRow['userName'];?></strong></u>
                <hr>
                <table>
                    <tr>
                        <td style="width: 150px">Power User</td>
                        <td style="width: 130px">Level 4</td>
                        <td style="width: 170px">Level 3</td>
                        <td style="width: 150px">Level 2</td>
                        <td style="width: 150px">Level 1</td>
                        <td style="width: 150px">No Privilege</td>
                    </tr>
                    <tr>
                        <td><input id="op1" type="radio" name="level" value="power_user"></td>
                        <td><input id="op2" type="radio" name="level" value="level4"></td>
                        <td><input id="op3" type="radio" name="level" value="level3"></td>
                        <td><input id="op4" type="radio" name="level" value="level2"></td>
                        <td><input id="op5" type="radio" name="level" value="level1"></td>
                        <td><input id="op6" type="radio" name="level" value="noprivilege"></td>
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
                    $level = trim($_POST['level']);
                    if ($userExist == 0) {
                        //Insert
                        $insertSQL = "INSERT INTO tbl_order_tracker (user_id, level, creation_date, created_by) 
                        values ('$userid', '$level', NOW(), '$user_check')";
                        if (!mysqli_query($conn,$insertSQL))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                echo "<div class='alert alert-success'>";
                                    echo "Order Tracker Permission Update successfully";
                                echo "</div>";
                            }
                    } else {
                        //update
                        $updateSQL = "UPDATE tbl_order_tracker set level = '$level', 
                        update_date = NOW() , updated_by = '$user_check' where user_id = $userid"; 
                        if (!mysqli_query($conn,$updateSQL))
                            {
                                $error ="Update Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                echo "<div class='alert alert-success'>";
                                    echo "Order Tracker Permission Update successfully";
                                echo "</div>";
                            }
                    }
                }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript" charset="utf-8">
            $(window).load(function(){<!-- w  ww.j a  v a 2 s.  c  o  m-->
                $('#searchid').select2();
            });
        </script>
    </body>
</html>
