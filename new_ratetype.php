<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and RateType = 'Y'"));
    if ($userPrivCheckRow['RateType'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">New Rate Chart Name</p>
                <table>
                    <tr style="text-align: right">
                        <td>
                            <label>Rate Chart Name&nbsp;&nbsp;&nbsp;</label>
                        </td>
                        <td>
                            <input type="text" name="rateChartName" value="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" name="submit" value="Create New" class="btn-primary"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                        </td>
                    </tr>
                </table>
            </form>
            <table id="rateChartName" class='table table-hover'>
                <tr style='background-color:#dad8d8; li'>
                    <th>Rate Chart ID</th>
                    <th>Rate Chart Name</th>
                    <th>Remove</th> 
                </tr>
                <?php
                    $ratetypeSQL = "select * from tbl_ratechart_name";
                    $ratetypeResult = mysqli_query($conn, $ratetypeSQL);
                    foreach ($ratetypeResult as $ratetypeRow){
                        ?>
                            <tr>
                                <td><?php echo $ratetypeRow['ratechartId'];?></label></td>
                                <td><?php echo $ratetypeRow['rateChartName'];?></td>
                                <td><input type="button" name="remove" class="btn btn-danger" value="Remove" onclick="<?php echo "return ttVal('".$ratetypeRow['ratechartId']."')"?>"></td>
                            </tr>
                        <?php
                    }
                ?>
            </table>
            <?php
                if (isset($_POST['submit'])) {
                    $rateChartName = trim($_POST['rateChartName']);
                    $insertsql = "INSERT INTO  tbl_ratechart_name (rateChartName, created_date, created_by) VALUES ('$rateChartName', NOW() + INTERVAL 6 HOUR , '$user_check')";
                    if (!mysqli_query($conn,$insertsql))
                        {
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            $rateChartID = mysqli_insert_id($conn);
                            $createRateChartSQL = "insert into tbl_rate_type (ratechartId, rateChartName, packageOption, deliveryOption, destination, charge, packageDim, orderCut, DelTimeLength, created_date, created_by) select $rateChartID, '$rateChartName', packageOption, deliveryOption, destination, charge, packageDim, orderCut, DelTimeLength, NOW() + INTERVAL 6 HOUR, '$user_check' from tbl_rate_type where ratechartId = 1";
                            $createRateChartResult = mysqli_query($conn, $createRateChartSQL);
                            echo "<meta http-equiv='refresh' content='0'>";
                            //echo "<div class='alert alert-success'>";
                            //    echo "Designation created successfully";
                            //echo "</div>";
                        }
                    }
                mysqli_close($conn);                    
            ?>
        </div>
    <script>
        function ttVal(removeVal)
        {
            var r = confirm("Are you sure!!!!");
            if (r == true)
            {
                $.ajax({
                    type: 'post',
                    url: 'remratetype',
                    data: {
                        rate_type_id: removeVal
                    },
                    success: function (response)
                    {
                        if (response == 'success')
                        {
                            window.open("RateTypeNew", "_self");
                        } else
                        {
                            alert("Unable to remove!!!");
                        }
                    }
                });
            }
        }
    </script>
    </body>
</html>
