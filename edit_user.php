<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_usersetting` WHERE user_id = $user_id_chk and edit_user = 'Y'"));
    if ($userPrivCheckRow['edit_user'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search User for Edit</p>
                <input id="editid" type="hidden" name="userName">
                <div>
                    <?php
                    if ((!isset($_POST['submit'])) and (!isset($_POST['edit'])) and (!isset($_POST['search']))){
                    $listUser = "select * from tbl_user_info where userName != 'admin' and isActive = 'Y'";
                    $listResult = mysqli_query($conn, $listUser);
                    ?>
                        <table class='table table-hover' id="userList">
                            <tr style='background-color:#dad8d8; li'>
                                <th>User Name</th>
                                <th>User Type</th> 
                                <th>User Code</th>
                                <th>Edit</th>
                            </tr>
                            <?php
                                foreach ($listResult as $userRow){
                                    ?>
                                        <tr>
                                            <td><label id="<?php echo $userRow['user_id'];?>"><?php echo $userRow['userName'];?></label></td>
                                            <td><?php echo $userRow['userType'];?></td>
                                            <td><?php echo $userRow['merchEmpCode'];?></td>
                                            <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$userRow['user_id']."')"?>"></td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </table>
                    <?php
                        }
                    ?>
                </div>
            </form>
        </div>
        <div style="margin-left: 15px; width: 98%">
            <?php
                //After search edit screen
                if (isset($_POST['edit'])) {
                    $userName = trim($_POST['userName']);
                    $editSQL = "Select * from tbl_user_info where userName = '$userName' and isActive = 'Y'";
                    $editResult = mysqli_query($conn, $editSQL);
                    $editRow = mysqli_fetch_array($editResult);
                ?>
            <div>
                <form id="newEmp" name="newEmp" action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                    <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Update Existing User</p>
                    <input type="hidden" name="user_id" value="<?php echo $editRow['user_id'];?>">
                    <table>
                        <tr>
                            <td>User Type&nbsp;</td>
                            <td>
                                <div class="radio">
                                    <?php
                                        if ($editRow['userType'] == 'Employee'){
                                    ?>
                                        <input id="op1" type="radio" name="userType" value="Merchant" onclick="return showHide()"> Merchant &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input id="op2" type="radio" name="userType" value="Employee" onclick="return showHide()" checked> Employee
                                    <?php
                                        } else {
                                    ?>
                                        <input id="op1" type="radio" name="userType" value="Merchant" onclick="return showHide()" checked> Merchant &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input id="op2" type="radio" name="userType" value="Employee" onclick="return showHide()"> Employee
                                    <?php
                                        }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <?php
                                        if ($editRow['userType'] == 'Employee'){
                                    ?>
                                        <label id="empid">Employee ID &nbsp;</label>
                                        <label id="merid" style="display: none; color: #0094ff">Merchant ID &nbsp;</label>
                                    <?php
                                        } else {
                                    ?>
                                        <label id="empid" style="display: none">Employee ID &nbsp;</label>
                                        <label id="merid" style="color: #0094ff">Merchant ID &nbsp;</label>
                                    <?php
                                        }
                                    ?>
                            </td>
                            <td>
                                    <?php
                                        if ($editRow['userType'] == 'Employee'){
                                    ?>
                                <select id="merSelect" name="merCode" style="display: none">
                                    <option></option>
                                    <?php
                                        $merSQL = "Select merchantCode, merchantName from tbl_merchant_info";
                                        $merResult = mysqli_query($conn, $merSQL);
                                        foreach ($merResult as $merRow){
                                            echo "<option value = '".$merRow['merchantCode']."'>".$merRow['merchantCode']." - ".$merRow['merchantName']."</option>";
                                        }
                                    ?>
                                </select>
                                <select id="empSelect" name="empCode" required>
                                    <option></option>
                                    <?php
                                        $empSQL = "Select empCode, empName from tbl_employee_info where isActive = 'Y'";
                                        $empResult = mysqli_query($conn, $empSQL);
                                        foreach ($empResult as $empRow){
                                            if ($empRow['empCode'] == $editRow['merchEmpCode']){
                                                echo "<option value = '".$empRow['empCode']."' selected>".$empRow['empCode']." - ".$empRow['empName']."</option>";
                                            } else {
                                                echo "<option value = '".$empRow['empCode']."'>".$empRow['empCode']." - ".$empRow['empName']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                                    <?php
                                        } else {
                                    ?>
                                <select id="merSelect" name="merCode" required>
                                    <option></option>
                                    <?php
                                        $merSQL = "Select merchantCode, merchantName from tbl_merchant_info";
                                        $merResult = mysqli_query($conn, $merSQL);
                                        foreach ($merResult as $merRow){
                                            if ($merRow['merchantCode'] == $editRow['merchEmpCode']){
                                                echo "<option value = '".$merRow['merchantCode']."' selected>".$merRow['merchantCode']." - ".$merRow['merchantName']."</option>"; 
                                            } else{
                                                echo "<option value = '".$merRow['merchantCode']."'>".$merRow['merchantCode']." - ".$merRow['merchantName']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                                <select id="empSelect" name="empCode" style="display: none">
                                    <option></option>
                                    <?php
                                        $empSQL = "Select empCode, empName from tbl_employee_info where isActive = 'Y'";
                                        $empResult = mysqli_query($conn, $empSQL);
                                        foreach ($empResult as $empRow){
                                            echo "<option value = '".$empRow['empCode']."'>".$empRow['empCode']." - ".$empRow['empName']."</option>";
                                        }
                                    ?>
                                </select>
                                    <?php
                                        }
                                    ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>User Name&nbsp;</label>
                            </td>
                            <td>
                                <input type="text" name="userName" style="height: 25px" value="<?php echo $editRow['userName'];?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Password&nbsp;</label>
                            </td>
                            <td>
                                <input id="pass1" type="password" name="userPassword" onkeypress="return pass2change()" style="height: 25px" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Confirm Password&nbsp;</label>
                            </td>
                            <td>
                                <input id="pass2" type="password" name="confirmPassword" onkeyup="return checkPass()" style="height: 25px" required>
                                <span id="confirmMessage" class="confirmMessage"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" name="submit" value="Save" class="btn-primary"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                            </td>
                        </tr>
                    </table>
                </form>
                </div>
            <?php
                }
                //Search result
                if (isset($_POST['search'])) {
                    $searchType = trim($_POST['searchType']);
                    $searchText = trim($_POST['searchText']);
                    $searchSQL = "select user_id, userName, userType, merchEmpCode from tbl_user_info where $searchType like '%$searchText%' and userName !='admin'";
                    $searchResult = mysqli_query($conn, $searchSQL);
                    $searchCount = mysqli_num_rows($searchResult);
                    ?><p style="font: 15px 'paperfly roman'"><?php
                    echo "<u>Search result for <strong>".$searchText." </strong></u> : ".$searchCount." records found";
                    ?></p><?php
                ?>
                    <form action="" method="post" style="color: #16469E; font: 15px 'paperfly roman'">
                    <input id="searchuserid" type="hidden" name="userName"> 
                    <table class='table table-hover'>
                        <tr style='background-color:#dad8d8; li'>
                            <th>User Name</th>
                            <th>User Type</th> 
                            <th>User Code</th>
                            <th>Edit</th>
                        </tr>
                        <?php
                            foreach ($searchResult as $searchRow){
                                ?>
                                    <tr>
                                        <td><label id="<?php echo $searchRow['user_id'];?>"><?php echo $searchRow['userName'];?></label></td>
                                        <td><?php echo $searchRow['userType'];?></td>
                                        <td><?php echo $searchRow['merchEmpCode'];?></td>
                                        <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$searchRow['user_id']."')"?>"></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </table>
                    </form>
                <?php
                    }
                if (isset($_POST['submit'])){
                    $userid = trim($_POST['user_id']);
                    $userName = trim($_POST['userName']);
                    $userName = mysqli_real_escape_string($conn, $userName);
                    $userPassword = trim($_POST['userPassword']);
                    $userPassword = mysqli_real_escape_string($conn, $userPassword);
                    $userType = trim($_POST['userType']);
                    if ($userType=="Employee"){
                        $empCode = trim($_POST['empCode']);
                    } else {
                        $empCode = trim($_POST['merCode']);;
                    }
                    $updatesql = "UPDATE tbl_user_info set userName = '$userName', userPassword = md5('$userPassword'), 
                    userType = '$userType', merchEmpCode = '$empCode', update_date = NOW() + INTERVAL 6 HOUR, updated_by = '$user_check' 
                    where user_id = $userid ";  
                    if (!mysqli_query($conn,$updatesql))
                        {
                            $error ="Update Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-success'>";
                                echo "User updated successfully";
                            echo "</div>";
                        }
     
                }
                mysqli_close($conn);
            ?>
        </div>
  
        <script type="text/javascript">
            $('#empSelect').select2();
            $('#userList').bdt({
                showSearchForm: 1,
                showEntriesPerPageField: 1

            });
            function pass2change() {
                document.getElementById('pass2').value = "";
                document.getElementById('confirmMessage').innerHTML = "";
                return true;
            }

            function checkPass() {
                //Store the password field objects into variables ...
                var pass1 = document.getElementById('pass1');
                var pass2 = document.getElementById('pass2');
                //Store the Confimation Message Object ...
                var message = document.getElementById('confirmMessage');
                //Set the colors we will be using ...
                var goodColor = "#66cc66";
                var badColor = "#ff6666";
                //Compare the values in the password field 
                //and the confirmation field
                if (pass1.value == pass2.value) {
                    //The passwords match. 
                    //Set the color to the good color and inform
                    //the user that they have entered the correct password 
                    pass2.style.backgroundColor = goodColor;
                    message.style.color = goodColor;
                    message.innerHTML = "Passwords Match!"
                } else {
                    //The passwords do not match.
                    //Set the color to the bad color and
                    //notify the user.
                    pass2.style.backgroundColor = badColor;
                    message.style.color = badColor;
                    message.innerHTML = "Passwords Do Not Match!"
                }
            }

            function ttVal(submitVal) {
                var inptext = document.getElementById(submitVal).innerHTML;
                document.getElementById("editid").value = inptext;
                document.getElementById("searchuserid").value = inptext;
            }

            function showHide() {
                if (document.getElementById('op1').checked == true) {
                    //Employee Attributes
                    document.getElementById('empid').style.display = "none";
                    document.getElementById('empSelect').style.display = "none";
                    document.getElementById("empSelect").required = false;
                    // Merchant Attibutes
                    document.getElementById('merid').style.display = "inline";
                    document.getElementById('merSelect').style.display = "inline";
                    document.getElementById("merSelect").required = true;
                    return true;
                } else {
                    //Employee Attributes
                    document.getElementById('empid').style.display = "inline";
                    document.getElementById('empSelect').style.display = "inline";
                    document.getElementById("empSelect").required = true;
                    // Merchant Attibutes
                    document.getElementById('merid').style.display = "none";
                    document.getElementById('merSelect').style.display = "none";
                    document.getElementById("merSelect").required = false;
                    return true;
                }
            }
        </script>
    </body>
</html>
