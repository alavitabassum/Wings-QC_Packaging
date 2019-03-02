<?php
    include('session.php');
    include('header.php');
    include('config.php');
    if ($user_type=='Merchant'){
        $merchantsql = "select merchant_id, user_id, merchantName from tbl_merchant_info where user_id='$user_id'";
        $merchantresult = mysqli_query($conn,$merchantsql);
        $merchantrow = mysqli_fetch_array($merchantresult);
    } else {
        $merchantsql = "select merchant_id, user_id, merchantName from tbl_merchant_info";
        $merchantresult = mysqli_query($conn,$merchantsql);
    }
?>
        <div style="background-color: #dad8d8">
            <p style="font-weight: bold; color: #808080">Transaction=>Orders=>Batch Order Process</p>
        </div>
        <div style="width: 60%">
            <form action="" method="post" enctype="multipart/form-data" style="width: 100%; border-style: solid; border-color: #dad8d8; border-radius: 5px">
                <p style="background-color: #000; border-radius: 5px; width: 100%; height: 25px; font-weight: bold; color: #fff">Batch Order Process</p>
                <table style="width: 100%">
                    <?php   
                        if ($user_type=='Merchant'){
                    ?>
                        <tr>
                            <td>
                                <label>Merchant</label>
                            </td>
                            <td colspan="4">
                                <input type="text" name="merchantid" style="height: 30px; width: 30%" value="<?php  echo $merchantrow['merchant_id']; ?>" readonly>&nbsp;&nbsp; <?php echo $merchantrow['merchantName']; ?>
                            </td>
                        </tr>
                    <?php
                        } else {
                    ?>
                        <tr>
                            <td>
                                <label>Merchant</label>
                            </td>
                            <td colspan="4">
                                <select class="form-control" name="merchantid" style="height: 30px; width: 50%">
                                    <?php
                                        foreach ($merchantresult as $merchantrow){
                                            echo "<option value=".$merchantrow['merchant_id'].">".$merchantrow['merchantName']."</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
                    <tr >
                        <td>
                            <label>Order Date</label>
                        </td>
                        <td colspan="4">
                            <div id='datetimepicker' class='input-append date' >    
                                <input name='orderDate' type='text' id='datetimepicker' class='input-append date' style='width:30%; height:30px' value="<?php echo date("d-m-Y");?>" required>
                                <span class='add-on' style='height:30px'><img src='image/cal.gif'></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Select Order File to upload</label>
                        </td>
                        <td>
                            <input type="hidden" name="path">
                            <input type="file" name="fileToUpload" id="fileToUpload" required>
                        </td>
                    </tr>
                </table>
                <div style="float: right">
                    <input type="submit" name="submit" value="Upload Orders" class="btn-primary" style="width: 120px; height: 30px; border-radius: 5%">&nbsp;
                    <br>
                    <br>
                </div>
                <br>
                <br>
            </form>
        </div>
        <?php   
            if (isset($_POST['submit'])){
                $files = @$_FILES["fileToUpload"];
                if($files["name"] != '')
                {
                    //***For Hosting Server
                        //$fullpath = $_REQUEST["path"].$files["name"];
                        //if(move_uploaded_file($files['tmp_name'],$fullpath))
                    //***End for Hosting Server

                    //**for local machine site
                    $target = "C:\wamp\www\orderfile\\"; 
                    $target = $target .$_FILES['fileToUpload']['name'];
                    $pic=($_FILES['fileToUpload']['tmp_name']);

                    if(move_uploaded_file($pic, $target))
                    //*** End of Local Machine site
                    {
                
                        $merchantid = trim($_POST['merchantid']);
                        $orderDate = date("Y-m-d", strtotime(trim($_POST['orderDate'])));
	                    $filename="C:\wamp\www\orderfile\\".basename($_FILES["fileToUpload"]["name"]);
                
                        echo "<div style='width: 60%; height: 200px'>";
                        echo "<p style='background-color: #135c91; border-radius: 5px; width: 100%; height: 25px; font-weight: bold; color: #fff'>Process Status</p>";
	                    if ($filename!='' && substr($filename,-3)=='csv'){
                            //existing max order id
                            $ordermaxid = "select max(orderId) as orderId from tbl_order_details";
                            $ordermaxresult = mysqli_query($conn, $ordermaxid);
                            $ordermaxrow =  mysqli_fetch_array($ordermaxresult);
                            $ordermaxid = $ordermaxrow['orderId']+1;
                            //
                            //Merchant Name for PDF report
                            $merchantname = "select merchantName  from tbl_merchant_info where merchant_id='$merchantid'";
                            $merchantresult = mysqli_query($conn, $merchantname);
                            $merchantrow =  mysqli_fetch_array($merchantresult);
                            $merchantname = $merchantrow['merchantName'];
                            //
		                    $row = 0;
                            $error_row = 0;
                            $error_insert = 0;
                            $success_insert = 0;
			                    if (($handle = fopen($filename, "r")) !== FALSE) {
				                    while (($data = fgetcsv($handle, 8000, ",")) !== FALSE) {
					                    $num = count($data);
					                    $row++;
                                        $thanasql = "Select * from tbl_thana_info where thanaid='$data[7]' and districtid='$data[8]'";
                                        $thanaresult = mysqli_query($conn, $thanasql);
                                        $thanarow = mysqli_fetch_array($thanaresult);
                                        if ($thanarow['thanaId'] <=0  || $thanarow['thanaId'] != $data[7]){
                                            echo "<p style='font-weight: bold; color: #f00'>Wrong Thana or District Info: Unable to upload</p>";
                                            echo "Line No.".$row." : ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",". "<br />\n";
                                            $error_row++;
                                        } else {
                                            $productdetials = mysqli_real_escape_string($conn, $data[0]);
                                            $size = mysqli_real_escape_string($conn, $data[1]);
                                            $custname = mysqli_real_escape_string($conn, $data[5]);
                                            $custaddress = mysqli_real_escape_string($conn, $data[6]);
                                            $custphone = mysqli_real_escape_string($conn, $data[9]);
                                            $ordersql ="INSERT INTO  tbl_order_details (merchant_id ,orderDate ,productDetails ,size ,weight ,productPrice  ,
                                            deliveryCharge, customerName, customerAddress, 
                                            thanaId, districtId, phone, orderStatusId, creation_date , created_by ) 
                                            VALUES ('$merchantid' ,'$orderDate' ,'$productdetials' ,'$size' ,'$data[2]' ,'$data[3]'  ,'$data[4]', 
                                            '$custname' , '$custaddress', '$data[7]', '$data[8]', '$custphone', '1', NOW() , '$user_check')";
                                            if (!mysqli_query($conn, $ordersql)){
                                                $error_insert++;
                                                echo "Line No.".$row." : ".$data[0].",".$data[1].",".$data[2].",".$data[3].",".$data[4].",".$data[5].",".$data[6].",".$data[7].",".$data[8]."," .$data[9].",". "<br />\n";
                                            } else {
                                                $success_insert++;
                                            }
                                        }
				                    }
				                    fclose($handle);
                                    if ($error_row >0){
                                        echo "<hr>";
                                        echo "<p style='font-weight: bold; color: #f00'>Error in file. Unable to upload orders</p>";
                                        echo "<hr>";
                                        echo "<p style='font-weight: bold; color: #135c91'>Total no of Orders : ".$row."</p>";
                                        echo "<p style='font-weight: bold; color: #f00'>Total Error Orders count : ".$error_row."</p>";
                                    }
                                    if ($success_insert > 0){
                                        echo "<hr>";
                                        echo "<p style='font-weight: bold; color: #135c91'>Successfully processed Orders : ".$success_insert."</p>";
                                        echo "<p style='font-weight: bold; color: #f00'>Total Error Orders count : ".$error_insert."</p>";
                                        echo "<iframe id='reportframe' src='orderprocesspdf.php?ordermaxid=".$ordermaxid."&recmaxid=".($ordermaxid+$success_insert)."&merchantname=".$merchantname."&orderDate=".$orderDate."' style='width: 100%; height: 100%' hidden></iframe>";
                                        echo "<button class='btn-info' onclick='return showreport()'>Show Processed Orders</button>";                               
                                    }
			                    }		
	                    } else {
		                    echo "This is not a valid CSV file :- ".substr($filename,-3);
	                    }
                        echo "</div>";
                    }
                }
                //submit ends
                mysqli_close($conn);
            } else {
        ?>
        <div style="width: 60%">
            <p style="background-color: #135c91; border-radius: 5px; width: 100%; height: 25px; font-weight: bold; color: #fff">File Example</p>
            <p style="color: #4800ff; font-weight: bold">CSV file should contain following column information <span style="color: #f00">but column name should not be in the CSV file</span></p>
            <img src="image/csvColumnEx.jpg" alt="CSV Column Example">
            <br>
            <br>
            <p style="font-size: 200%; color: #ff6a00; font-weight: bold">CSV File Example:</p>
            <pre>
                Giant caterpillar,10sq. Ft.,5ton,234244524,2312314,Abdul Monem,"Hatirpool, Dhaka",1,1,1911345344
                Washing Machine,2sq. Ft.,20kg,4534534,47664,Salman F Rahman,"Dhanmondi, Dhaka",1,1,1911345344
                Walton Freeze,32cft,50kg,3056000,10500,Razibul Islam,"Shankar, Dhanmodi, Dhaka",1,1,1711456723
            </pre>
        </div>
        <?php 
            }
        ?>
        <script type="text/javascript">
            $('#datetimepicker').datetimepicker({
            format: 'dd-MM-yyyy',
            language: 'en'
            });
            function showreport()
            {
                document.getElementById('reportframe').hidden = false;
            } 
        </script>
    </body>
</html>
