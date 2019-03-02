<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and point = 'Y'"));
    if ($userPrivCheckRow['point'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search Point</p>
                Search by : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchType" style="font: 15px 'paperfly roman'">
                    <option value="pointCode">Point Code</option>
                    <option value="pointName">Point Name</option>
                </select><br>
                Search Text :&nbsp; <input type="text" name="searchText" style="height: 30px" placeholder="Search criteria"><br>
                <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;<br><br>
                <input id="editid" type="hidden" name="pointCode">
                <div>
                    <?php
                    if ((!isset($_POST['submit'])) and (!isset($_POST['edit'])) and (!isset($_POST['search']))){
                    $listPoint = "select * from tbl_point_info";
                    $pointResult = mysqli_query($conn, $listPoint);
                    ?>
                        <table class='table table-hover'>
                            <tr style='background-color:#dad8d8; li'>
                                <th>Point Code</th>
                                <th>Point Name</th> 
                                <th>Edit</th>
                            </tr>
                            <?php
                                foreach ($pointResult as $pointRow){
                                    ?>
                                        <tr>
                                            <td><label id="<?php echo $pointRow['pointCode'];?>"><?php echo $pointRow['pointCode'];?></label></td>
                                            <td><?php echo $pointRow['pointName'];?></td>
                                            <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$pointRow['pointCode']."')"?>"></td>
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
        <div style="margin-left: 15px; width: 98%">
            <?php
                //After search edit screen
                if (isset($_POST['edit'])) {
                    $pointCode = trim($_POST['pointCode']);
                    $editSQL = "Select * from tbl_point_info where pointCode = '$pointCode'";
                    $editResult = mysqli_query($conn, $editSQL);
                    $pointeditrow = mysqli_fetch_array($editResult);
                ?>
            <div>
                <form id="formPoint" name="formPoint" action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                    <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Update Existing Point</p>
                        <table style="width: 100%">
                            <tr style="text-align: right">
                                <td style="width: 160px">
                                    <label>Point Code</label>
                                </td>
                                <td>
                                    <input type="text" name="pointCode" value="<?php echo $pointeditrow['pointCode'];?>" style="height: 25px; width: 98%" readonly>
                                </td>
                                <td style="width: 150px">
                                    <label>Point Name</label>
                                </td>
                                <td>
                                    <input type="text" name="pointName" value="<?php echo $pointeditrow['pointName'];?>" style="height: 25px; width: 98%" required>
                                </td>
                            </tr>
                            <tr style="text-align: right">
                                <td>
                                    <label>Point Address</label>
                                </td>
                                <td colspan="6">
                                    <input type="text" name="pointAddress" value="<?php  echo $pointeditrow['pointAddress'];?>" style="height: 25px; width: 99%" >
                                </td>
                            </tr>
                            <tr>
                                <td>
                            
                                </td>
                                <td colspan="2">
                                    &nbsp;&nbsp;District<select class="form-control" name="districtId" style="margin-left: 1%; height: 30px; width: 77%" onchange="fetch_select(this.value);">
                                        <option>Select District</option>
                                        <?php
                                            $districtId = $pointeditrow['districtId'];
                                            $districtSQL = "Select districtId, districtName from tbl_district_info";
                                            $districtresult = mysqli_query($conn, $districtSQL);
                                            foreach ($districtresult as $districtrow){
                                                if ($districtrow['districtId'] == $districtId){
                                                    echo "<option value=".$districtrow['districtId']." selected>".$districtrow['districtName']."</option>";
                                                } else {
                                                    echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <?php
                                        $pointthana = $pointeditrow['thanaId'];
                                        $thanasql = "Select thanaId, thanaName from tbl_thana_info where thanaId='$pointthana'";
                                        $thanaresult = mysqli_query($conn, $thanasql);
                                        $thanarow = mysqli_fetch_array($thanaresult);
                                    ?>
                                    &nbsp;&nbsp;Thana<select id="thana" class="form-control" name="thanaId" style="margin-left: 2%; height: 30px; width: 97%">
                                        <option value="<?php echo $thanarow['thanaId'];?>"><?php echo $thanarow['thanaName'];?></option>
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
                                        <?php
                                            $pointCov = $pointeditrow['pointCode'];
                                            $pointCoverSQL = "SELECT tbl_point_coverage.thanaId, tbl_thana_info.thanaName 
                                            FROM tbl_point_coverage, tbl_thana_info WHERE tbl_point_coverage.thanaId = tbl_thana_info.thanaId 
                                            and tbl_point_coverage.pointCode = '$pointCov'";
                                            $poinCoverResult = mysqli_query($conn, $pointCoverSQL);
                                            foreach ($poinCoverResult as $pointCoverRow){
                                                echo "<option value=".$pointCoverRow['thanaId'].">".$pointCoverRow['thanaName']."</option>";
                                            }
                                        ?>
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
                                    <input type="text" name="landlordName" value="<?php echo $pointeditrow['landlordName'];?>" style="height: 25px; width: 98%">
                                </td>
                                <td>
                                    <label>Landlord Contact</label>
                                </td>
                                <td>
                                    <input type="text" name="landlordContact" value="<?php echo $pointeditrow['landlordContact'];?>" style="height: 25px; width: 98%">
                                </td>
                            </tr>
                            <tr style="text-align: right">
                                <td>
                                    <label>Monthly Rent Adjusted</label>
                                </td>
                                <td>
                                    <input type="text" name="advanceMonth" value="<?php echo $pointeditrow['advanceMonth'];?>" style="height: 25px; width: 98%" onkeyup="return isNumberKey(this)">
                                </td>
                                <td>
                                    <label>Advance Paid (TK)</label>
                                </td>
                                <td>
                                    <input type="text" name="advancePaid" value="<?php echo $pointeditrow['advancePaid'];?>" style="height: 25px; width: 98%" onkeyup="return isNumberKey(this)">
                                </td>
                            </tr>
                            <tr style="text-align: right">
                                <td>
                                    <label>Monthly Rent</label>
                                </td>
                                <td>
                                    <input type="text" name="monthlyRent" value="<?php echo $pointeditrow['monthlyRent'];?>" style="height: 25px; width: 98%" onkeyup="return isNumberKey(this)">
                                </td>
                                <td>
                                    <label>Monthly Sevice Charge</label>
                                </td>
                                <td>
                                    <input type="text" name="monthlyCharge" value="<?php echo $pointeditrow['monthlyCharge'];?>" style="height: 25px; width: 98%" onkeyup="return isNumberKey(this)">
                                </td>
                            </tr>
                            <tr style="text-align: right">
                                <td>
                                    <label>Contract Date</label>
                                </td>
                                <td>
                                    <input type="text" name="contractDate" value="<?php echo date("d-m-Y", strtotime(trim($pointeditrow['contractDate'])));?>" style="height: 25px; width: 98%" onclick="displayCalendar(document.formPoint.contractDate,'dd-mm-yyyy',this)">
                                </td>
                                <td>
                                    <label>Valid Till</label>
                                </td>
                                <td>
                                    <input type="text" name="validTill" value="<?php echo date("d-m-Y", strtotime(trim($pointeditrow['validTill'])));?>" style="height: 25px; width: 98%" onclick="displayCalendar(document.formPoint.validTill,'dd-mm-yyyy',this)">
                                </td>
                            </tr>
                        </table>
                        <div style="float: right">
                            <input type="submit" name="submit" value="save" class="btn-primary" onclick="selectDeselect('coveredthana', true)" style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
                        </div>
                        <br>
                        <br>
                    </form>
                </div>
            <?php
                }
                //Search result
                if (isset($_POST['search'])) {
                    $searchType = trim($_POST['searchType']);
                    $searchText = trim($_POST['searchText']);
                    $searchSQL = "select pointCode, pointName from tbl_point_info where $searchType like '%$searchText%'";
                    $searchResult = mysqli_query($conn, $searchSQL);
                    $searchCount = mysqli_num_rows($searchResult);
                    ?><p style="font: 15px 'paperfly roman'"><?php
                    echo "<u>Search result for <strong>".$searchText." </strong></u> : ".$searchCount." records found";
                    ?></p><?php
                ?>
                    <form action="" method="post" style="color: #16469E; font: 15px 'paperfly roman'">
                    <input id="searchuserid" type="hidden" name="pointCode"> 
                    <table class='table table-hover'>
                        <tr style='background-color:#dad8d8; li'>
                            <th>Point Code</th>
                            <th>Point Name</th> 
                            <th>Edit</th>
                        </tr>
                        <?php
                            foreach ($searchResult as $searchRow){
                                ?>
                                    <tr>
                                        <td><label id="<?php echo $searchRow['pointCode'];?>"><?php echo $searchRow['pointCode'];?></label></td>
                                        <td><?php echo $searchRow['pointName'];?></td>
                                        <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$searchRow['pointCode']."')"?>"></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </table>
                    </form>
                <?php
                    }
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
                        $empUpateSQL = "UPDATE tbl_point_info SET pointName='$pointName', pointAddress='$pointAddress', 
                        thanaId='$thanaId', districtId='$districtId', landlordName='$landlordName', landlordContact='$landlordContact', 
                        advanceMonth='$advanceMonth', advancePaid='$advancePaid', monthlyRent='$monthlyRent', monthlyCharge='$monthlyCharge', contractDate='$contractDate',validTill='$validTill', 
                        update_date=NOW() + INTERVAL 6 HOUR, updated_by='$user_check' WHERE pointCode = '$pointCode'";
                        if (!mysqli_query($conn,$empUpateSQL))
                        {
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-success'>";
                                echo "Point updated successfully";
                            echo "</div>";                                
                        }
                        //Delete employee points
                        $deleteSQL = "delete from tbl_point_coverage where pointCode ='$pointCode'";
                        $deleteResult = mysqli_query($conn, $deleteSQL);
                        //Insert employee points
                        $checked_point = $_POST['thanas'];
                        $count = count($checked_point);
                        for ($c=0; $c < $count; $c++) {
                            //echo $checked_thana[$c];
                            //echo "<br>";
                            $ins_qry="INSERT INTO tbl_point_coverage(pointCode, thanaId, districtId, creation_date, created_by) 
                            VALUES('$pointCode', '".$checked_point[$c]."', '$districtId', NOW() + INTERVAL 6 HOUR, '$user_check')";
                            mysqli_query($conn, $ins_qry);
                        }           
                    }
                mysqli_close($conn);
            ?>
        </div>
        <script type="text/javascript">

            function ttVal(submitVal)
            {
                var inptext = document.getElementById(submitVal).innerHTML;
                document.getElementById("editid").value = inptext;
                document.getElementById("searchuserid").value = inptext;
            }
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
