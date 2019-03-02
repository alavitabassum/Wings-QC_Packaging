<?php
    include('session.php');
    include('header.php');
    include('config.php');
?>
        <div style="background-color: #dad8d8">
            <p style="font-weight: bold; color: #808080">Transaction=>Orders=>Edit/Delete Orders</p>
        </div>
        <div style="width: 100%">
            <form action="" method="post"  style="width: 100%; border-style: solid; border-color: #dad8d8; border-radius: 5px">
                <p style="background-color: #000; border-radius: 5px; width: 100%; height: 25px; font-weight: bold; color: #fff">Order Assignments</p>
                <?php
                    if ($user_check='admin' || $user_type='Administrator'){
                        $sqlundelivered = "select tbl_merchant_info.merchant_id, tbl_merchant_info.merchantName, 
                        count(tbl_order_details.merchant_id) as orders, tbl_pickup_point.pointName from tbl_order_details, tbl_merchant_info, 
                        tbl_pickup_point where tbl_order_details.merchant_id=tbl_merchant_info.merchant_id 
                        and tbl_merchant_info.pickPointCode = tbl_pickup_point.pointid 
                        and orderStatusId !='99' group by tbl_order_details.merchant_id";
                    } else {
                      $sqlundelivered = "select tbl_merchant_info.merchant_id, tbl_merchant_info.merchantName, 
                      count(tbl_order_track.merchant_id) as orders, tbl_pickup_point.pointName from tbl_order_track, tbl_merchant_info, tbl_pickup_point 
                      where tbl_order_track.merchant_id=tbl_merchant_info.merchant_id 
                      and tbl_merchant_info.pickPointCode = tbl_pickup_point.pointid and orderStatusId !='99' 
                      and assignedUser_id ='$user_id' group by tbl_order_track.merchant_id";  
                    }
                    echo "
                        <table class='table table-hover'>
                        <tr style='background-color:#dad8d8; li'>
                            <th>Merchant Name</th>
                            <th>Orders Count</th>
                            <th>Select Merchant</th>
                            <th>Pick up Point</th>
                            <th>Assign To</th>
                            <th>Submit</th>
                        </tr>
                    ";                    
                    $undeliveredresult = mysqli_query($conn, $sqlundelivered);
                    if (mysqli_num_rows($undeliveredresult) > 0) {
                        // output data of each row
                        while($row = mysqli_fetch_assoc($undeliveredresult)) {
                            echo "<tr>";
                                echo "<td>";
                                    echo $row['merchantName'];
                                echo "</td>";
                                echo "<td>";
                                    echo $row['orders'];
                                echo "</td>";
                                echo "<td>";
                                        echo "<input type='checkbox' name='chkbox' value='".$row['merchant_id']."'>";
                                echo "</td>";
                                echo "<td>";
                                        echo $row['pointName'];
                                echo "</td>";
                                echo "<td>";
                                    $merchantid=$row['merchant_id'];
                                    echo "<select class='form-control' name='empid".$merchantid."' style='height: 30px'>";
                                        $usersql ="Select tbl_user_info.user_id, tbl_user_info.userName from tbl_user_info, tbl_pickup_point, tbl_merchant_info
                                                    where tbl_user_info.user_id=tbl_pickup_point.user_id 
                                                    and tbl_pickup_point.pointId=tbl_merchant_info.pickPointCode 
                                                    and tbl_merchant_info.merchant_id='$merchantid'";
                                        $useresult = mysqli_query($conn, $usersql);
                                        foreach ($useresult as $userrow) {
                                            echo "<option value=".$userrow['user_id'].">".$userrow['userName']."</option>";
                                        }
                                    echo "</select>";
                                echo "</td>";
                                echo "<td><input type='submit' name='submit' value='Assign' class='btn-primary' style='width: 120px; height: 30px; border-radius: 5%'></td>";
                            echo "</tr>";
                        }
                    }
                mysqli_close($conn);
                echo "</table>";                       
                ?>
            </form>
        </div>
        <?php
            if (isset($_POST['submit'])){
                $chkboxval=trim($_POST['chkbox']);
                echo "Merchant Selected :". $chkboxval;
            }
        ?>
        <script type="text/javascript">
            $('input[type="checkbox"]').on('change', function() {
               $('input[type="checkbox"]').not(this).prop('checked', false);
            });
        </script>        
    </body>
</html>
