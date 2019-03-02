<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $districtsql = "select districtId, districtName from tbl_district_info";
    $districtresult = mysqli_query($conn,$districtsql);
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and point = 'Y'"));
    if ($userPrivCheckRow['point'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post" name="formPoint" style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Create New Point</p>
                <table style="width: 100%">
                    <tr style="text-align: right">
                        <td style="width: 160px">
                            <label>Point Code</label>
                        </td>
                        <td>
                            <input type="text" name="pointCode" style="height: 25px; width: 98%" required>
                        </td>
                        <td style="width: 150px">
                            <label>Point Name</label>
                        </td>
                        <td>
                            <input type="text" name="pointName" style="height: 25px; width: 98%" required>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Point Address</label>
                        </td>
                        <td colspan="6">
                            <input type="text" name="pointAddress" style="height: 25px; width: 99%" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                        </td>
                        <td colspan="2">
                            &nbsp;&nbsp;District<select class="form-control" name="districtId" style="margin-left: 1%; height: 30px; width: 77%" onchange="fetch_select(this.value);">
                                <option>Select District</option>
                                <?php
                                    foreach ($districtresult as $districtrow){
                                        echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td>
                            &nbsp;&nbsp;Thana<select id="thana" class="form-control" name="thanaId" style="margin-left: 2%; height: 30px; width: 97%">

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">
                            Point Coverage (Thana)
                        </td>

                        <td>&nbsp;&nbsp;Thana
                            <select id="thana2" class="form-control" name="thanaId2" style="margin-left: 2%; height: 100px; width: 98%" multiple>

                            </select>
                            <input type="button" name="thanaAdd" value="Add Thana" style="margin-left: 2%" class="btn btn-info" onclick="addThana()">
                        </td>

                        <td colspan="2">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            Selected Thana
                            <select id="coveredthana" class="form-control" name="thanas[]" style="margin-left: 24%; height: 100px; width: 76%" multiple>

                            </select>
                            <input type="button" name="thanaRem" value="Remove Thana" style="margin-left: 24%" class="btn btn-info" onclick="removeItem(coveredthana)">
                            
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label style="font-weight: bold"><u>Point Office Details:</u></label>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Point Landlord Name</label>
                        </td>
                        <td>
                            <input type="text" name="landlordName" style="height: 25px; width: 98%">
                        </td>
                        <td>
                            <label>Landlord Contact</label>
                        </td>
                        <td>
                            <input type="text" name="landlordContact" style="height: 25px; width: 98%">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Monthly Rent Adjusted</label>
                        </td>
                        <td>
                            <input type="text" name="advanceMonth" style="height: 25px; width: 98%" onkeyup="return isNumberKey(this)">
                        </td>
                        <td>
                            <label>Advance Paid (TK)</label>
                        </td>
                        <td>
                            <input type="text" name="advancePaid" style="height: 25px; width: 98%" onkeyup="return isNumberKey(this)">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Monthly Rent</label>
                        </td>
                        <td>
                            <input type="text" name="monthlyRent" style="height: 25px; width: 98%" onkeyup="return isNumberKey(this)">
                        </td>
                        <td>
                            <label>Monthly Sevice Charge</label>
                        </td>
                        <td>
                            <input type="text" name="monthlyCharge" style="height: 25px; width: 98%" onkeyup="return isNumberKey(this)">
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td>
                            <label>Contract Date</label>
                        </td>
                        <td>
                            <input type="text" name="contractDate" style="height: 25px; width: 98%" onclick="displayCalendar(document.formPoint.contractDate,'dd-mm-yyyy',this)">
                        </td>
                        <td>
                            <label>Valid Till</label>
                        </td>
                        <td>
                            <input type="text" name="validTill" style="height: 25px; width: 98%" onclick="displayCalendar(document.formPoint.validTill,'dd-mm-yyyy',this)">
                        </td>
                    </tr>
                </table>
                <div style="float: right">
                    <input type="submit" name="submit" value="save" class="btn-primary" onclick="selectDeselect('coveredthana', true)" style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                </div>
                <br>
                <br>
            </form>
            <?php
                if (isset($_POST['submit'])) {
                    $pointCode = trim($_POST['pointCode']);
                    $pointCode = mysqli_real_escape_string($conn, $pointCode);
                    $pointName = trim($_POST['pointName']);
                    $pointName = mysqli_real_escape_string($conn, $pointName);
                    $pointAddress = trim($_POST['pointAddress']);
                    $pointAddress = mysqli_real_escape_string($conn, $pointAddress);
                    $thanaId = trim($_POST['thanaId']);
                    $districtId = trim($_POST['districtId']);
                    $landlordName = trim($_POST['landlordName']);
                    $landlordName = mysqli_real_escape_string($conn, $landlordName);
                    $landlordContact = trim($_POST['landlordContact']);
                    $landlordContact = mysqli_real_escape_string($conn, $landlordContact);
                    $advanceMonth = trim($_POST['advanceMonth']);
                    $advancePaid = trim($_POST['advancePaid']);
                    $monthlyRent = trim($_POST['monthlyRent']);
                    $monthlyCharge = trim($_POST['monthlyCharge']);
                    $contractDate = date("Y-m-d", strtotime(trim($_POST['contractDate'])));
                    $validTill = date("Y-m-d", strtotime(trim($_POST['validTill'])));
                    $insertsql = "INSERT INTO  tbl_point_info (pointCode ,pointName ,pointAddress ,thanaId, districtId, 
                    landlordName ,landlordContact ,advanceMonth ,advancePaid, monthlyRent, monthlyCharge, contractDate, validTill, creation_date , created_by ) 
                    VALUES ('$pointCode' ,'$pointName' ,'$pointAddress' ,'$thanaId' ,'$districtId' ,'$landlordName'  ,'$landlordContact', 
                    '$advanceMonth' , '$advancePaid', '$monthlyRent', '$monthlyCharge', '$contractDate', '$validTill', NOW() + INTERVAL 6 HOUR , '$user_check')";
                    if (!mysqli_query($conn,$insertsql))
                            {
                                $error ="Insert Error : " . mysqli_error($conn);
                                echo "<div class='alert alert-danger'>";
                                    echo "<strong>Error!</strong>".$error; 
                                echo "</div>";
                            } else {
                                $checked_thana = $_POST['thanas'];
                                $count = count($checked_thana);
                                for ($c=0; $c < $count; $c++) {
                                    //echo $checked_thana[$c];
                                    //echo "<br>";
                                    $ins_qry="INSERT INTO tbl_point_coverage(pointCode, thanaId, districtId, creation_date, created_by) 
                                    VALUES('$pointCode', '".$checked_thana[$c]."', '$districtId', NOW() + INTERVAL 6 HOUR, '$user_check')";
                                    mysqli_query($conn, $ins_qry);
                                }
                                  echo "<div class='alert alert-success'>";
        //                                echo "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
                                        echo "Point created successfully with ".$count." Thana coverage area";
                                  echo "</div>";
                        }
                }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript">
            function isNumberKey(inpt)
            {
                var regex = /[^0-9.]/g;
                inpt.value = inpt.value.replace(regex, "");
            }

            function fetch_select(val)
            {
                $.ajax({
                    type: 'post',
                    url: 'thanafetch',
                    data: {
                        get_thanaid: val
                    },
                    success: function (response)
                    {
                        document.getElementById("thana").innerHTML = response;
                        document.getElementById("thana2").innerHTML = response;
                    }
                });
            }

            function addThana()
            {
                var listbox;
                var x = document.getElementById("thana2");
                for (var i = 0; i < x.options.length; i++)
                {
                    if (x.options[i].selected == true) {
                        x.options[i].value+"-"+x.options[i].textContent
                            listbox +="<option value="+x.options[i].value+">"+x.options[i].textContent+"</option>";
                         }
                }
                document.getElementById('coveredthana').innerHTML = document.getElementById('coveredthana').innerHTML + listbox;
            }

            function removeItem(selectbox)
            {
                var i;
                for(i=selectbox.options.length-1;i>=0;i--)
                {
                    if(selectbox.options[i].selected)
                    selectbox.remove(i);
                }
            }
            function selectDeselect(listid, status) {  
                var listb = document.getElementById(listid);  
                var len = listb.options.length;  
                for (var i = 0; i < len; i++) {  
                    listb.options[i].selected = status;  
                }  
            }  
        </script>
    </body>
</html>
