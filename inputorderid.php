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
                <p style="background-color: #000; border-radius: 5px; width: 100%; height: 25px; font-weight: bold; color: #fff">Select a Order to Edit/Delete</p>
                Undelivered Order ID:&nbsp;&nbsp;<input type="text" name="inputorder" style="height: 30px; width: 30%" required>
                <br>
                &nbsp;&nbsp;<input type="submit" name="submit" value="Search" class="btn-info" style="margin-left: 9%; width: 120px; height: 30px; border-radius: 5%">
            </form>
            <?php
                if(isset($_POST['submit']))
                {
                    $orderid = trim($_POST['inputorder']);
                    echo "<form action='orderupdate' method='post'>";
                    echo "<input type='hidden' name='orderId' value='".$orderid."'>";
                    echo "<p style='font-weight: bold; color: #135c91'>Search result for Order ID :<span style='font-weight: bold; color: orange'> ".$orderid."</span></p>";
                    echo "
                        <table class='table table-hover'>
                        <tr style='background-color:#dad8d8; li'>
                            <th>Merchant ID</th>
                            <th>Order Date</th>
                            <th>Product Details</th>
                            <th>Size</th>
                            <th>Weight</th>
                            <th>Price</th>
                            <th>Charge</th>
                            <th>Customer Name</th>
                            <th>Customer Address</th>
                            <th>Thana</th>
                            <th>District</th>
                            <th>Phone</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    ";
                        $searchsql ="select * from tbl_order_details where orderid='$orderid' and orderStatusId !='99'";
                        $result = mysqli_query($conn, $searchsql);
                        if (mysqli_num_rows($result) > 0) {
                            // output data of each row
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                    echo "<td>";
                                        echo "<input type='text' name='merchantid' style='width:60px; height: 25px' value='".$row['merchant_id']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                         echo "<div id='datetimepicker' class='input-append date'>";
                                             echo "<input id='datetimepicker' type='text' name='orderDate' class='input-append date' style='width:80px; height:25px' value='".date("d-M-Y", strtotime($row['orderDate']))."'>";
                                             echo "<span class='add-on' style='height:25px'><img src='image/cal.gif'></span>";
                                         echo "</div>";
                                    echo "</td>";
                                    echo "<td>";
                                         echo "<input type='text' name='productdetails' style='height: 25px' value='".$row['productDetails']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='size' style='width:60px; height: 25px' value='".$row['size']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='weight' style='width:60px; height: 25px' value='".$row['weight']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='productPrice' style='width:80px; height: 25px' value='".$row['productPrice']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='deliveryCharge' style=' width:60px;height: 25px' value='".$row['deliveryCharge']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='customerName' style='width:120px; height: 25px' value='".$row['customerName']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='customerAddress' style='height: 25px' value='".$row['customerAddress']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='thanaId' style='width:40px; height: 25px' value='".$row['thanaId']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='districtId' style=' width:40px; height: 25px' value='".$row['districtId']."'>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<input type='text' name='phone' style='width:100px; height: 25px' value='".$row['phone']."'>";
                                    echo "</td>";
                                    echo "<td><input type='submit' name='updatesubmit' value='Save' class='btn btn-primary'></td>";
                                    echo "<td><input type='submit' name='deletesubmit' value='Delete' class='btn btn-danger'></td>";
                                echo "</tr>";
                            }
                        }
                    }
                mysqli_close($conn);
                echo "</table>";
                echo "</form>";
            ?>
        </div>
        <script type="text/javascript">
            $('#datetimepicker').datetimepicker({
            format: 'dd-MM-yyyy',
            language: 'en'
            });

            function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31  && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }
        </script>        
    </body>
</html>
