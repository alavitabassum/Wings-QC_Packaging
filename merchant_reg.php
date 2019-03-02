<?php
    include('config.php');
    $districtsql ="select districtId, districtName from tbl_district_info where districtId in (select distinct districtId from tbl_thana_info)";
    $districtresult = mysqli_query($conn, $districtsql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>New Customer Registration</title>
        <script src="js/jquery-2.2.0.min.js"></script>
    </head>
    <body>
        <div style="border-style: solid; border-color: #69afe2; border-radius: 10px; margin-left: 25%; width: 50%; margin-top: 5%">
            <p style="border-radius: 10px; width: 100%; height: 25px; font-weight: bold; font-size: 200%">User Registration Form</p>
            <hr>
            <form action="regconfirmation" method="post">
                <table style="width: 100%" border="0">
                    <tr>
                        <td>
                            <br>
                            <label style="font-weight: bold"><u>Company Information:</u></label>
                        </td>                                            
                    </tr>
                    <tr>
                        <td>
                            <label>Company Name</label>
                        </td>
                        <td>
                            <input type="text" name="compname" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Business Type</label>
                        </td>
                        <td>
                            <input type="text" name="businesstype">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Address</label>
                        </td>
                        <td colspan="3">
                            <input type="text" name="address" style="width: 99%" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Thana</label>
                        </td>
                        <td>
                            <select id="thana" name="thanaid" required>

                            </select>
                        </td>
                        <td>
                            <label>District</label>
                        </td>
                        <td>
                            <select name="districtid" onchange="fetch_select(this.value);">
                            <option>Select District</option>
                                <?php
                                    foreach ($districtresult as $districtrow){
                                        echo "<option value=".$districtrow['districtid'].">".$districtrow['districtName']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Company Phone</label>
                        </td>
                        <td>
                            <input type="text" name="compphone">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Website Address</label>
                        </td>
                        <td>
                            <input type="text" name="webaddress">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Facebook Address</label>
                        </td>
                        <td>
                            <input type="text" name="faceaddress">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <br>
                            <label style="font-weight: bold"><u>Contact Person Details:</u></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Contact Person</label>
                        </td>
                        <td>
                            <input type="text" name="contactname" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Designation</label>
                        </td>
                        <td>
                            <input type="text" name="designation">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Phone</label>
                        </td>
                        <td>
                            <input type="text" name="phone" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Email</label>
                        </td>
                        <td>
                            <input type="email" name="email">
                        </td>
                    </tr>
                </table>
                <br>
                <input type="submit" name="submit" value="Submit" style="border-radius: 10px; height: 25px; width: 100px; margin-left: 45%">
                <br>
            </form>
        </div>
        <script type="text/javascript">
            function fetch_select(val)
            {
               $.ajax({
                 type: 'post',
                 url: 'fetch_thana.php',
                 data: {
                   get_thanaid:val
                 },
                 success: function (response) {
                   document.getElementById("thana").innerHTML=response; 
                 }
               });
            }
        </script>
    </body>
</html>
