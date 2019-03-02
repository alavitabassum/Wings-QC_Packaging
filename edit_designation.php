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
                <input id="editid" type="hidden" name="desigid">
                <div>
                    <?php
                    if ((!isset($_POST['submit'])) and (!isset($_POST['edit']))){
                    $listDesig = "select * from tbl_designation_info";
                    $desigResult = mysqli_query($conn, $listDesig);
                    ?>
                        <table class='table table-hover'>
                            <tr style='background-color:#dad8d8; li'>
                                <th>Designation ID</th>
                                <th>Designation Name</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            <?php
                                foreach ($desigResult as $desigRow){
                                    ?>
                                        <tr>
                                            <td><label id="<?php echo $desigRow['desigid'];?>"><?php echo $desigRow['desigid'];?></label></td>
                                            <td><?php echo $desigRow['Name'];?></td>
                                            <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$desigRow['desigid']."')"?>"></td>
                                            <td><input type="submit" name="delete" class="btn btn-danger" value="Delete" onclick="<?php echo "return ttVal('".$desigRow['desigid']."')"?>"></td>
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
            <?php if (isset($_POST['delete'])) {
                $desigid = trim($_POST['desigid']);
                $deleteSQL = "delete from tbl_designation_info where desigid = '$desigid'";
                $deleteResult = mysqli_query($conn, $deleteSQL);
                echo "<meta http-equiv='refresh' content='0'>";
            }?>
        <div style="margin-left: 15px; width: 98%">
            <?php
                //After search edit screen
                if (isset($_POST['edit'])) {
                    $desigid = trim($_POST['desigid']);
                    $editSQL = "Select * from tbl_designation_info where desigid = '$desigid'";
                    $editResult = mysqli_query($conn, $editSQL);
                    $desigRow = mysqli_fetch_array($editResult);
                ?>
            <div>
                <form id="newEmp" name="newEmp" action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                    <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Update Designation</p>
                    <input type="hidden" name="desigid" value="<?php echo $desigRow['desigid'];?>">
                        <table>
                            <tr>
                                <td>
                                    <label>Name of Designation&nbsp;</label>
                                </td>
                                <td>
                                    <input type="text" name="name" style="height: 25px" value="<?php echo $desigRow['Name'];?>" required>
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
                    if (isset($_POST['submit'])) {
                        $desigid = trim($_POST['desigid']);
                        $name = trim($_POST['name']);
                        $name = mysqli_real_escape_string($conn, $name);
                        $desigUpateSQL = "UPDATE tbl_designation_info SET Name='$name', update_date=NOW() + INTERVAL 6 HOUR, updated_by='$user_check' WHERE desigid = '$desigid'";
                        if (!mysqli_query($conn,$desigUpateSQL))
                        {
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-success'>";
                                echo "Designation updated successfully";
                            echo "</div>";
                        }
                    }
            ?>
        </div>
        <script type="text/javascript">

            function ttVal(submitVal)
            {
                var inptext = document.getElementById(submitVal).innerHTML;
                document.getElementById("editid").value = inptext;
            }
        </script>
    </body>
</html>
