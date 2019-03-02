<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and RateChart = 'Y'"));
    if ($userPrivCheckRow['RateChart'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search Rate Chart</p>
                Select Rate Chart : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchRateChart" style="font: 15px 'paperfly roman'">
                    <?php
                        $rateChartSQL = "select ratechartId, rateChartName from tbl_ratechart_name";
                        $rateChartResult = mysqli_query($conn, $rateChartSQL);
                        foreach($rateChartResult as $rateChartRow){
                            if ($rateChartRow['ratechartId'] == trim($_POST['searchRateChart'])){ 
                                ?>
                                <option value="<?php echo $rateChartRow['ratechartId'];?>" selected><?php echo $rateChartRow['rateChartName'];?></option>
                                <?php
                            } else {
                                ?>
                                <option value="<?php echo $rateChartRow['ratechartId'];?>"><?php echo $rateChartRow['rateChartName'];?></option>
                                <?php
                            }
                        }
                    ?>
                </select><br>
                <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;<br><br>
            </form>
        </div>
        <div style="margin-left: 15px; width: 98%">
            <?php
                //Search result
                if (isset($_POST['search'])) {
                    $searchText = trim($_POST['searchRateChart']);
                    if ($searchText !=''){
                        $searchSQL = "select * from tbl_rate_type where ratechartId = '$searchText'";
                        $searchResult = mysqli_query($conn, $searchSQL);
                        $searchCount = mysqli_num_rows($searchResult);
                    }
                    ?>
                    <p id="updateAlert" style="color: #ffd800; font: 15px 'paperfly roman'"></p>
                    <p id="searchText" style="font: 15px 'paperfly roman'"><?php
                    echo "<u>Search result for <strong>".$searchText." </strong></u> : ".$searchCount." records found";
                    ?></p>
                    <table class='table table-hover' style="font: 13px 'paperfly roman'">
                        <tr style='background-color:#dad8d8; li'>
                            <th>Package Option</th>
                            <th>Delivery Option</th> 
                            <th>Destination</th>
                            <th>Charge</th>
                            <th>Package Dimension</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                        <?php
                            foreach ($searchResult as $searchRow){
                                ?>
                                    <tr id="tr<?php echo $searchRow['rateId'];?>" >
                                        <td><label style="font: 11px 'paperfly roman'"><?php echo $searchRow['packageOption'];?></label></td>
                                        <td><label style="font: 11px 'paperfly roman'"><?php echo $searchRow['deliveryOption'];?></label></td>
                                        <td><label style="font: 11px 'paperfly roman'"><?php echo $searchRow['destination'];?></label></td>
                                        <td><input style="width: 100px; height: 25px; font: 11px 'paperfly roman'" id="<?php echo $searchRow['rateId'];?>" type="text" name="charge" value ="<?php echo $searchRow['charge'];?>" onkeyup="return isNumberKey(this)" required></td>
                                        <td><label style="font: 11px 'paperfly roman'"><?php echo $searchRow['packageDim'];?></label></td>
                                        <td><button class="btn btn-primary" value="Update" onclick="<?php echo "return Update('".$searchRow['rateId']."')"?>">Update</button></td>
                                        <td><button class="btn btn-danger" value="Delete" onclick="<?php echo "return Delete('".$searchRow['rateId']."')"?>">Delete</button></td>
                                    </tr>
                        <?php } ?>
                    </table>
            <?php }?>
        </div>
        <script>
            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }
            function Update(ord)
            {
                var charge = document.getElementById(ord).value;
                var flag = 'update';
                if (charge == '')
                {
                    alert("Blank input not allowed");
                } else
                {
                    $.ajax({
                        type: 'post',
                        url: 'toupdatechart',
                        data: {
                            get_orderid: ord,
                            chargerate: charge,
                            flagreq: flag
                        },
                        success: function (response)
                        {
                            if (response == 'success')
                            {
                                $('#updateAlert').html('Rate Chart Updated Successfully');
                            } else
                            {
                                alert("Unable to update!!!");
                            }
                        }
                    });
                }
            }

            function Delete(ord)
            {
                var flag = 'delete';
                var r = confirm("Are you sure!!!!");
                if (r == true)
                {
                    $.ajax({
                        type: 'post',
                        url: 'toupdatechart',
                        data: {
                            get_orderid: ord,
                            flagreq: flag
                        },
                        success: function (response)
                        {
                            if (response == 'success')
                            {
                                $("#tr" + ord).css("display", "none");
                                alert("Deleted successully");
                            } else
                            {
                                alert("Unable to update!!!");
                            }
                        }
                    });
                }
            }
        </script>
    </body>
</html>
