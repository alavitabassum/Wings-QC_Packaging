<?php
    include('session.php');
    include('header.php');
    include('config.php');
?>
        <div style="background-color: #dad8d8">
            <p style="font-weight: bold; color: #808080">Customer Registration => Applied Customer</p>
        </div>
        <div style="width: 100%">
            <?php
                $sqlunregistered = "select * from tbl_merchant_temp where reviewStatus = '1'";
                echo "
                    <table class='table table-hover'>
                    <tr style='background-color:#dad8d8; li'>
                        <th>Company Name</th>
                        <th>Address</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Review</th>
                        <th>Delete</th>
                    </tr>
                ";                    
                $unregisteredresult = mysqli_query($conn, $sqlunregistered);
                if (mysqli_num_rows($unregisteredresult) > 0) {
                    // output data of each row
                    while($row = mysqli_fetch_assoc($unregisteredresult)) {
                        echo "<tr>";
                            echo "<td>";
                                echo $row['companyName'];
                            echo "</td>";
                            echo "<td>";
                                echo $row['address'];
                            echo "</td>";
                            echo "<td>";
                                echo $row['contactPerson'];
                            echo "</td>";
                            echo "<td>";
                                    echo $row['contactMobile'];
                            echo "</td>";
                            echo "<td><input type='submit' name='submit' value='Review' class='btn-info' style='width: 120px; height: 30px; border-radius: 5%'></td>";
                            echo "<td><input type='submit' name='submit' value='Delete' class='btn-danger' style='width: 120px; height: 30px; border-radius: 5%'></td>";
                        echo "</tr>";
                    }
                }
            mysqli_close($conn);
            echo "</table>";                       
            ?>
        </div>        
    </body>
</html>
