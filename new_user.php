<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `tbl_menu_usersetting` WHERE user_id = $user_id_chk and new_user = 'Y'"));
    if ($userPrivCheckRow['new_user'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form id="newEmp" name="newEmp" action="" method="post"  style="color: #16469E">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Create New User</p>
                <table style="font: 15px 'paperfly roman'">
                    <tr>
                        <td><span style="font: 14px 'paperfly roman'">User Type&nbsp;</span></td>
                        <td>
                            <div class="radio" style="font: 14px 'paperfly roman'">
                                <input id="op1" type="radio" name="userType" value="Merchant" onclick="return showHide()"> Merchant &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="op2" type="radio" name="userType" value="Employee" onclick="return showHide()" checked> Employee
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="empid">Employee ID &nbsp;</label>
                            <label id="merid" style="display: none; color: #0094ff">Merchant ID &nbsp;</label>
                        </td>
                        <td>
                            <select id="merSelect" name="merCode" style="display: none" required>
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
                                        echo "<option value = '".$empRow['empCode']."'>".$empRow['empCode']." - ".$empRow['empName']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>User Name&nbsp;</label>
                        </td>
                        <td>
                            <input type="text" name="userName" style="height: 25px" required>
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
            <?php
                if (isset($_POST['submit'])) {
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
                    $insertsql = "INSERT INTO  tbl_user_info (userName, userPassword, 	userType, merchEmpCode, creation_date, created_by) 
                    VALUES ('$userName', md5('$userPassword'), '$userType', '$empCode', NOW() + INTERVAL 6 HOUR , '$user_check')";
                    if (!mysqli_query($conn,$insertsql))
                        {
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-success'>";
                                echo "User created successfully";
                            echo "</div>";
                        }
                    }
                mysqli_close($conn);                    
            ?>
        </div>
        <script type="text/javascript">
            function pass2change()
            {
                document.getElementById('pass2').value = "";
                document.getElementById('confirmMessage').innerHTML = "";
                return true;
            }

            function checkPass()
            {
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
                if (pass1.value == pass2.value)
                {
                    //The passwords match. 
                    //Set the color to the good color and inform
                    //the user that they have entered the correct password 
                    pass2.style.backgroundColor = goodColor;
                    message.style.color = goodColor;
                    message.innerHTML = "Passwords Match!"
                } else
                {
                    //The passwords do not match.
                    //Set the color to the bad color and
                    //notify the user.
                    pass2.style.backgroundColor = badColor;
                    message.style.color = badColor;
                    message.innerHTML = "Passwords Do Not Match!"
                }
            }
              function showHide()
              {
                  if (document.getElementById('op1').checked == true)
                  {
                      //Employee Attributes
                      document.getElementById('empid').style.display = "none";
                      document.getElementById('empSelect').style.display = "none";
                      document.getElementById("empSelect").required = false;
                      // Merchant Attibutes
                      document.getElementById('merid').style.display = "inline";
                      document.getElementById('merSelect').style.display = "inline";
                      document.getElementById("merSelect").required = true;
                      return true;
                  } else
                  {
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
