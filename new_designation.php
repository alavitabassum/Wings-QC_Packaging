<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and designation = 'Y'"));
    if ($userPrivCheckRow['designation'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">New Designation</p>
                <table>
                    <tr>
                        <td>
                            <label>Name of Designation&nbsp;</label>
                        </td>
                        <td>
                            <input type="text" name="name" style="height: 25px" required>
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
                    $name = trim($_POST['name']);
                    $insertsql = "INSERT INTO  tbl_designation_info (name, creation_date, created_by) VALUES ('$name', NOW() + INTERVAL 6 HOUR , '$user_check')";
                    if (!mysqli_query($conn,$insertsql))
                        {
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-success'>";
                                echo "Designation created successfully";
                            echo "</div>";
                        }
                    }
                mysqli_close($conn);                    
            ?>
        </div>
    </body>
</html>
