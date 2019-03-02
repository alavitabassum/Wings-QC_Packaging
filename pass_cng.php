<?php
    include('session.php');
    include('header.php');
    include('config.php');
?>

        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Change Password</p>
                <table>
                    <tr>
                        <td>
                            <label>Current Password&nbsp;</label>
                        </td>
                        <td>
                            <input id="pass3" type="password" name="cPassword" style="height: 25px" required>
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
                            <input type="submit" name="submit" value="Update" onclick=" return checkPass()" class="btn-primary" style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                        </td>
                    </tr>
                </table>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    $cPassword = trim($_POST['cPassword']);
                    $cPassword = mysqli_real_escape_string($conn, $cPassword);
                    $cpassword = md5($cPassword);
                    $userPassword = trim($_POST['userPassword']);
                    $userPassword = mysqli_real_escape_string($conn, $userPassword);
                    $verifySQL = "select * from tbl_user_info where userPassword='$cpassword' AND userName='$user_check'";
                    $result = mysqli_query($conn, $verifySQL);
                    if (mysqli_num_rows($result) > 0) {
                        $cngpasssql = "UPDATE tbl_user_info set userPassword = md5('$userPassword'), update_date = NOW(), updated_by = '$user_check' 
                        where user_id = $user_id_chk";
                        if (!mysqli_query($conn,$cngpasssql))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                echo "<div class='alert alert-success'>";
                                    echo "Password Changed successfully";
                                echo "</div>";
                            }
                        } else {

                              // echo "<script type='text/javascript'>";
                              //  echo "alert('Authentication failed! Please try again.')";
                              // echo "</script>";    
                              // echo "<meta http-equiv='refresh' content='0'>";                        
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Authentication failed! Please try again.</strong>"; 
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
                    return true;
                } else
                {
                    //The passwords do not match.
                    //Set the color to the bad color and
                    //notify the user.
                    pass2.style.backgroundColor = badColor;
                    message.style.color = badColor;
                    message.innerHTML = "Passwords Do Not Match!"
                    return false;
                }
            }
        </script>
    </body>
</html>
