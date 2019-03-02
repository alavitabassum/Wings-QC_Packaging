<?php
    include('session.php');
    include('header.php');
    include('config.php');
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">New Rate Chart</p>
                <table style="font: 15px 'paperfly roman'">
                    <tr style="text-align: left">
                        <td>
                            <label>Rate Chart Name</label>
                        </td>
                        <td>
                            <select name="rateChartName" style="height: 28px; width: 98%">
                                <?php
                                $ratetypeSQL = "select * from tbl_ratechart_name";
                                $ratetypeResult = mysqli_query($conn, $ratetypeSQL);
                                foreach ($ratetypeResult as $ratetypeRow){
                                ?>
                                <option value="<?php echo $ratetypeRow['rateChartName']?>"><?php echo $ratetypeRow['rateChartName']?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td>
                            <label>&nbsp; &nbsp;Package Option</label>
                        </td>
                        <td>
                            <select name="productSizeWeight" style="margin-left: 10px; height: 28px; width: 98%">
                                <option value="standard">Standard</option>
                                <option value="large">Large</option>
                                <option value="special">Special</option>
                                <option value="specialplus">Special Plus</option>
                            </select>
                        </td>
                        <td>
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delivery Option</label>
                        </td>
                        <td>
                            <select name="deliveryOption" style="margin-left: 10px; height: 28px; width: 98%">
                                <option value="regular">Regular</option>
                                <option value="express">Express</option>
                            </select>
                        </td>
                        <td>
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destination</label>
                        </td>
                        <td>
                            <select name="destination" style="margin-left: 10px; height: 28px; width: 98%">
                                <option value="local">Local</option>
                                <option value="interDistrict">Inter-District</option>
                                <option value="international">International</option>
                            </select>
                        </td>
                        <td>
                            <label>&nbsp; &nbsp;&nbsp; &nbsp; Charge&nbsp; &nbsp; </label>
                        </td>
                        <td>
                            <input type="text" name="charge" style="width: 50px; height: 28px;">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Package Dimension and Weight</label></td>
                        <td><textarea style="width: 180px" class="form-control" rows="3" id="packageDim" name="packageDim"></textarea></td>
                        <td><label>&nbsp;&nbsp;Order Cut Off Time (hrs)</label></td>
                        <td>
                            <select name="orderCut" style="margin-left: 10px; height: 28px; width: 98%">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                            </select>
                        </td>
                        <td><label>&nbsp;&nbsp;&nbsp;&nbsp;Delivery Time Length (hrs)</label></td>
                        <td><input type="text" name="DelTimeLength" style="margin-left: 10px; height: 28px; width: 50px" onkeyup="return isNumberKey(this)"></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" name="submit" value="Create New" class="btn-primary"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                        </td>
                    </tr>
                </table>
            </form>
            <table id="rateChartList" class='table table-hover' style="font: 15px 'paperfly roman'">
                <tr style='background-color:#dad8d8; li'>
                    <th>Rate Chart Name</th>
                    <th>Package Option</th> 
                    <th>Delivery Option</th>
                    <th>Destination</th>
                    <th>Charge (Amount)</th>
                    <th>Package Dimension and Weight</th>
                    <th>Order Cut Off Time (hrs)</th>
                    <th>Delivery Time Length (hrs)</th>
                </tr>
                <?php
                    $ratetypeSQL = "select rateId, rateChartname, packageOption, deliveryOption, destination,  charge, packageDim, orderCut, DelTimeLength from tbl_rate_type order by rateId desc";
                    $ratetypeResult = mysqli_query($conn, $ratetypeSQL);
                    foreach ($ratetypeResult as $ratetypeRow){
                        ?>
                            <tr>
                                <td><?php echo $ratetypeRow['rateChartname'];?></label></td>
                                <td><?php echo $ratetypeRow['packageOption'];?></td>
                                <td><?php echo $ratetypeRow['deliveryOption'];?></td>
                                <td><?php echo $ratetypeRow['destination'];?></td>
                                <td style="text-align: center"><?php echo $ratetypeRow['charge'];?></td>
                                <td><?php echo $ratetypeRow['packageDim'];?></td>
                                <td style="text-align: center"><?php echo $ratetypeRow['orderCut'];?></td>
                                <td style="text-align: center"><?php echo $ratetypeRow['DelTimeLength'];?></td>
                            </tr>
                        <?php
                    }
                ?>
            </table>
            <?php
                if (isset($_POST['submit'])) {
                    $rateChartName = trim($_POST['rateChartName']);
                    $productSizeWeight = trim($_POST['productSizeWeight']);
                    $deliveryOption = trim($_POST['deliveryOption']);
                    $destination = trim($_POST['destination']);
                    $charge = trim($_POST['charge']);
                    $packageDim = $_POST['packageDim'];
                    $packageDim = mysqli_real_escape_string($conn, $packageDim);
                    $orderCut = trim($_POST['orderCut']);
                    $DelTimeLength = trim($_POST['DelTimeLength']);
                    $rateChartSQL = "select ratechartId from tbl_ratechart_name where rateChartName = '$rateChartName'";
                    $rateChartResult = mysqli_query($conn, $rateChartSQL);
                    $rateRow = mysqli_fetch_array($rateChartResult);
                    $ratechartId = $rateRow['ratechartId'];
                    $insertsql = "INSERT INTO  tbl_rate_type (ratechartId, rateChartName, packageOption, deliveryOption, destination, charge, packageDim, orderCut, DelTimeLength, created_date, created_by) VALUES ('$ratechartId', '$rateChartName', '$productSizeWeight', '$deliveryOption','$destination', '$charge', '$packageDim', '$orderCut', '$DelTimeLength', NOW() + INTERVAL 6 HOUR , '$user_check')";
                    if (!mysqli_query($conn,$insertsql))
                        {
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
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
            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }
        </script>
    </body>
</html>
