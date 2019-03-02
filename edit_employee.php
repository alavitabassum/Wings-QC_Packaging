<?php
    include('session.php');
    include('header.php');
    include('config.php');
    $userPrivCheckRow = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_menu_dbmgt WHERE user_id = $user_id_chk and employee = 'Y'"));
    if ($userPrivCheckRow['employee'] != 'Y'){
        exit();
    }
?>
        <div style="margin-left: 15px; width: 98%; clear: both">
            <form action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Search Employee</p>
                Search by : &nbsp;&nbsp;&nbsp;&nbsp;<select id="searchid" name="searchType" style="font: 15px 'paperfly roman'">
                    <option value="empName">Employee Name</option>
                    <option value="empCode">Employee Code</option>
                    <option value="contactNumber">Contact Number</option>
                </select><br>
                Search Text :&nbsp; <input type="text" name="searchText" style="height: 30px" placeholder="Search criteria"><br>
                <input type="submit" name="search" value="Search" class="btn-info"style="width: 100px; height: 30px; border-radius: 5%">&nbsp;<br><br>
                <input id="editid" type="hidden" name="empCode">
                <div>
                    <?php
                    if ((!isset($_POST['submit'])) and (!isset($_POST['edit'])) and (!isset($_POST['search']))){
                    $listEmp = "select * from tbl_employee_info";
                    $empResult = mysqli_query($conn, $listEmp);
                    ?>
                        <table class='table table-hover'>
                            <tr style='background-color:#dad8d8; li'>
                                <th>Employee Code</th>
                                <th>Employee Name</th> 
                                <th>Contact Number</th>
                                <th>Edit</th>
                            </tr>
                            <?php
                                foreach ($empResult as $empRow){
                                    ?>
                                        <tr>
                                            <td><label id="<?php echo $empRow['empCode'];?>"><?php echo $empRow['empCode'];?></label></td>
                                            <td><?php echo $empRow['empName'];?></td>
                                            <td><?php echo $empRow['contactNumber'];?></td>
                                            <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$empRow['empCode']."')"?>"></td>
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
                    $empCode = trim($_POST['empCode']);
                    $editSQL = "Select * from tbl_employee_info where empCode = '$empCode'";
                    $editResult = mysqli_query($conn, $editSQL);
                    $emprow = mysqli_fetch_array($editResult);
                ?>
            <div>
                <form id="newEmp" name="newEmp" action="" method="post"  style="color: #16469E; font: 15px 'paperfly roman'">
                    <p style="background-color: #16469E; border-radius: 5px; width: 100%; height: 25px; color: #fff; font: 15px 'paperfly roman'">Update Existing Employee</p>
                    <input type="hidden" name="empCode" value="<?php echo $emprow['empCode'];?>">
                        <table style="width: 100%">
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold; color: #0026ff"><u>Employee Details for <?php echo $emprow['empCode'];?>:</u></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Employee Name</label>
                                </td>
                                <td>
                                    <input type="text" name="empName" style="height: 25px" value="<?php echo $emprow['empName'];?>" required>
                                </td>
                                <td>
                                    <label> Designation</label>
                                </td>
                                <td>
                                    <select id="designation" name="desigid" style="width: 98%; height: 25px" required>
                                        <option></option>
                                        <?php
                                            $desigsql = "select desigid, name from tbl_designation_info";
                                            $desigresult = mysqli_query($conn,$desigsql);
                                            foreach ($desigresult as $desigrow){
                                                if ($desigrow['desigid']== $emprow['desigid']) {
                                                    echo "<option value='".$desigrow['desigid']."' selected>".$desigrow['name']."</option>";
                                                } else {
                                                    echo "<option value='".$desigrow['desigid']."'>".$desigrow['name']."</option>";    
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <label> Contact Number</label>
                                </td>
                                <td>
                                    <input type="text" name="contactNumber" style="height: 25px" value="<?php echo $emprow['contactNumber']; ?>" required>
                                </td>
                                <td>
                                    <label> Email Address</label>
                                </td>
                                <td>
                                    <input type="email" name="email" placeholder="your@paperfly.com.bd" style="height: 25px" value="<?php echo $emprow['email']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label> Address</label>
                                </td>
                                <td colspan="3">
                                    <input type="text" name="address" style="width: 99%; height: 25px" value="<?php echo $emprow['address']; ?>" required>
                                </td>
                                <td>
                                    <label>District</label>
                                </td>
                                <td>
                                    <select name="districtId" style="width: 98%; height: 25px" onchange="fetch_select(this.value);" required>
                                        <option></option>
                                        <?php
                                            $districtsql = "select districtId, districtName from tbl_district_info";
                                            $districtresult = mysqli_query($conn,$districtsql);                                            
                                            foreach ($districtresult as $districtrow){
                                                if ($districtrow['districtId'] == $emprow['districtId']){
                                                    echo "<option value=".$districtrow['districtId']." selected>".$districtrow['districtName']."</option>";
                                                } else {
                                                    echo "<option value=".$districtrow['districtId'].">".$districtrow['districtName']."</option>";    
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>                                                                        
                                <td>
                                    <label>Thana</label>
                                </td>
                                <td>
                                    <?php
                                        $empthana = $emprow['thanaId'];
                                        $thanasql = "Select thanaId, thanaName from tbl_thana_info where thanaId='$empthana'";
                                        $thanaresult = mysqli_query($conn, $thanasql);
                                        $thanarow = mysqli_fetch_array($thanaresult);
                                    ?>
                                    <select id="thana" name="thanaId" style="width: 98%; height: 25px" required>
                                        <option value="<?php echo $thanarow['thanaId'];?>"><?php echo $thanarow['thanaName'];?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Date of Joining</label>
                                </td>
                                <td>
                                    <input type="text" name="doj" style="height: 25px" value="<?php echo date("d-m-Y", strtotime(trim($emprow['doj']))); ?>" onfocus="displayCalendar(document.newEmp.doj,'dd-mm-yyyy',this)" required>
                                </td>
                                <td>
                                    <label>HR Band</label>
                                </td>
                                <td>
                                    <input type="text" name="hrBand" style="height: 25px" value="<?php echo $emprow['hrBand']; ?>">
                                </td>
                                <td>
                                    <label>Basic Salary</label>
                                </td>
                                <td>
                                    <input type="text" name="basicSalary" style="height: 25px" value="<?php echo $emprow['basicSalary']; ?>" onkeyup="return isNumberKey(this)" required>
                                </td>
                                <td>
                                    <label>House Rent</label>
                                </td>
                                <td>
                                    <input type="text" name="houseRent" style="height: 25px" value="<?php echo $emprow['houseRent']; ?>" onkeyup="return isNumberKey(this)">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Transport Allowance</label>
                                </td>
                                <td>
                                    <input type="text" name="tAllowance" value="<?php echo $emprow['tAllowance'];?>" style="height: 25px" onkeyup="return isNumberKey(this)">
                                </td>
                                <td>
                                    <label>Quarterly Incentive(%)</label>
                                </td>
                                <td>
                                    <input type="text" name="qIncentive" value="<?php echo $emprow['qIncentive'];?>" style="height: 25px" onkeyup="return isNumberKey(this)">
                                </td>                        <td>
                                    <label>Festival Bonus(%)</label>
                                </td>
                                <td>
                                    <input type="text" name="fBonus" value="<?php echo $emprow['fBonus'];?>" style="height: 25px" onkeyup="return isNumberKey(this)">
                                </td>
                                <td>
                                    <label>Bank A/C</label>
                                </td>
                                <td>
                                    <input type="text" name="bankAccount" style="height: 25px" value="<?php echo $emprow['bankAccount']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>TIN Number</label>
                                </td>
                                <td>
                                    <input type="text" name="tinNumber" style="height: 25px" value="<?php echo $emprow['tinNumber']; ?>" >
                                </td>
                                <td>
                                    <label>Date of Birth</label>
                                </td>
                                <td>
                                    <input type="text" name="dob" style="height: 25px" value="<?php echo date("d-m-Y", strtotime(trim($emprow['dob']))); ?>" onfocus="displayCalendar(document.newEmp.dob,'dd-mm-yyyy',this)">
                                </td>
                                <td>
                                    <label>Marital Status</label>
                                </td>
                                <td>
                                    <select id="maritalStatus" name="maritalStatus" style="width: 98%; height: 25px">
                                        <?php
                                            if ($emprow['maritalStatus']=="M") {
                                        ?>
                                            <option value="S">Single</option>
                                            <option value="M" selected>Married</option>
                                            <option value="D">Divorced</option>
                                        <?php      
                                            }
                                            if ($emprow['maritalStatus']=="S") {
                                        ?>
                                            <option value="S" selected>Single</option>
                                            <option value="M">Married</option>
                                            <option value="D">Divorced</option>
                                        <?php      
                                            }

                                            if ($emprow['maritalStatus']=="D") {
                                        ?>
                                            <option value="S">Single</option>
                                            <option value="M">Married</option>
                                            <option value="D" selected>Divorced</option>
                                        <?php      
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <label>Blood Group</label>
                                </td>
                                <td>
                                    <input type="text" name="bloodGroup" style="height: 25px" value="<?php echo $emprow['bloodGroup'];?>" >
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Gender</label>
                                </td>
                                <td>
                                    <select id="gender" name="gender" style="width: 98%; height: 25px">
                                        <?php
                                            if ($emprow['gender']=="M"){
                                        ?>
                                            <option value="M" selected>Male</option>
                                            <option value="F">Female</option>
                                        <?php
                                            }
                                            if ($emprow['gender']=="F"){
                                        ?>
                                            <option value="M">Male</option>
                                            <option value="F" selected>Female</option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <label>Father's/Spouse Name</label>
                                </td>
                                <td>
                                    <input type="text" name="fatherName" style="height: 25px" value="<?php echo $emprow['fatherName'];?>">
                                </td>
                                <td>
                                    <label>NID</label>
                                </td>
                                <td>
                                    <input type="text" name="nid" style="height: 25px" value="<?php echo $emprow['nid'];?>">
                                </td>
                                <td>
                                    <label>Religion</label>
                                </td>
                                <td>
                                    <select id="religion" name="religion" style="width: 95%; height: 25px">
                                        <option value="I" <?php if ($emprow['religion'] == 'I') { echo "selected";}?>>Islam</option>
                                        <option value="C" <?php if ($emprow['religion'] == 'C') { echo "selected";}?>>Christianity</option>
                                        <option value="H" <?php if ($emprow['religion'] == 'H') { echo "selected";}?>>Hinduism</option>
                                        <option value="B" <?php if ($emprow['religion'] == 'B') { echo "selected";}?>>Buddhism</option>
                                        <option value="O" <?php if ($emprow['religion'] == 'O') { echo "selected";}?>>Others</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold"><u>Point Assignment:</u></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Point List</label>
                                </td>
                                <td>
                                    <select id="pointSel" name="pointSel" style="width: 98%; height: 100px" multiple>
                                    <?php
                                        $pointsql = "Select pointCode, pointName from tbl_point_info";
                                        $pointresult = mysqli_query($conn, $pointsql);
                                        foreach ($pointresult as $pointrow) {
                                            echo "<option value='".$pointrow['pointCode']."'>".$pointrow['pointName']."</option>";
                                        }
                                    ?>
                                    </select>
                                </td>
                                <td>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="pointAdd" value="+" style="width: 40px" class="btn btn-default" onclick="addPoint()"><br><br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="pointRem" value="-" style="width: 40px" class="btn btn-default" onclick="removeItem(pointAssigned)"><br>
                                </td>
                                <td>
                                    <select id="pointAssigned" name="pointAssigned[]" style="width: 98%; height: 100px" multiple>
                                    <?php
                                        $pointCoverageSQL = "Select tbl_employee_point.pointCode, tbl_point_info.pointName from tbl_employee_point, 
                                        tbl_point_info where tbl_employee_point.pointCode=tbl_point_info.pointCode 
                                        and tbl_employee_point.empCode='$empCode'";
                                        $pointCovResult = mysqli_query($conn, $pointCoverageSQL);
                                        foreach ($pointCovResult as $pointCovRow){
                                            echo "<option value='".$pointCovRow['pointCode']."'>".$pointCovRow['pointName']."</option>";
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label style="font-weight: bold"><u>Emergency Contact Details:</u></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Name</label>
                                </td>
                                <td>
                                    <input type="text" name="emergencyName" style="height: 25px" value="<?php echo $emprow['emergencyName'];?>">
                                </td>
                                <td>
                                    <label> Address</label>
                                </td>
                                <td>
                                    <input type="text" name="emergencyAddress" style="height: 25px" value="<?php echo $emprow['emergencyAddress'];?>">
                                </td>
                                <td>
                                    <label> Contact Number</label>
                                </td>
                                <td>
                                    <input type="text" name="emergencyNumber" style="height: 25px" value="<?php echo $emprow['emergencyNumber'];?>">
                                </td>
                                <td>
                                    <label> Relationship</label>
                                </td>
                                <td>
                                    <input type="text" name="relationship" style="height: 25px" value="<?php echo $emprow['relationship'];?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Status</label>
                                </td>
                                <td>
                                    <select id="isActive" name="isActive" class="form-control input-sm">
                                        <option value="Y" <?php if($emprow['isActive'] == 'Y'){ echo 'selected';}?>>Yes</option>
                                        <option value="N" <?php if($emprow['isActive'] == 'N'){ echo 'selected';}?>>No</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div style="float: right">
                            <input type="submit" name="submit" value="save" class="btn-primary" onclick="selectDeselect('pointAssigned', true)" style="width: 100px; height: 30px; border-radius: 5%">&nbsp;
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
                    $searchSQL = "select empCode, empName, contactNumber from tbl_employee_info where $searchType like '%$searchText%'";
                    $searchResult = mysqli_query($conn, $searchSQL);
                    $searchCount = mysqli_num_rows($searchResult);
                    ?><p style="font: 15px 'paperfly roman'"><?php
                    echo "<u>Search result for <strong>".$searchText." </strong></u> : ".$searchCount." records found";
                    ?></p><?php
                ?>
                    <form action="" method="post" style="color: #16469E; font: 15px 'paperfly roman'">
                    <input id="searchuserid" type="hidden" name="empCode"> 
                    <table class='table table-hover'>
                        <tr style='background-color:#dad8d8; li'>
                            <th>Employee Code</th>
                            <th>Employee Name</th> 
                            <th>Contact Number</th>
                            <th>Edit</th>
                        </tr>
                        <?php
                            foreach ($searchResult as $searchRow){
                                ?>
                                    <tr>
                                        <td><label id="<?php echo $searchRow['empCode'];?>"><?php echo $searchRow['empCode'];?></label></td>
                                        <td><?php echo $searchRow['empName'];?></td>
                                        <td><?php echo $searchRow['contactNumber'];?></td>
                                        <td><input type="submit" name="edit" class="btn btn-info" value="Edit" onclick="<?php echo "return ttVal('".$searchRow['empCode']."')"?>"></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </table>
                    </form>
                <?php
                    }
                    if (isset($_POST['submit'])) {
                        $empCode = trim($_POST['empCode']);
                        $empName = trim($_POST['empName']);
                        $empName = mysqli_real_escape_string($conn, $empName);
                        $empType = trim($_POST['empType']);
                        $desigid = trim($_POST['desigid']);
                        $contactNumber = trim($_POST['contactNumber']);
                        $contactNumber = mysqli_real_escape_string($conn, $contactNumber);
                        $email = trim($_POST['email']);
                        $email = mysqli_real_escape_string($conn, $email);
                        $address = trim($_POST['address']);
                        $address = mysqli_real_escape_string($conn, $address);
                        $thanaId = trim($_POST['thanaId']);
                        $districtId = trim($_POST['districtId']);
                        $doj = date("Y-m-d", strtotime(trim($_POST['doj'])));
                        $hrBand = trim($_POST['hrBand']);
                        $hrBand = mysqli_real_escape_string($conn, $hrBand);
                        $basicSalary = trim($_POST['basicSalary']);
                        $houseRent = trim($_POST['houseRent']);
                        $tAllowance = trim($_POST['tAllowance']);
                        $qIncentive = trim($_POST['qIncentive']);
                        $fBonus = trim($_POST['fBonus']);
                        $bankAccount = trim($_POST['bankAccount']);
                        $bankAccount = mysqli_real_escape_string($conn, $bankAccount);
                        $tinNumber = trim($_POST['tinNumber']);
                        $tinNumber = mysqli_real_escape_string($conn, $tinNumber);
                        $lineManager = trim($_POST['lineManager']);
                        $dob = date("Y-m-d", strtotime(trim($_POST['dob'])));
                        $maritalStatus = trim($_POST['maritalStatus']);
                        $bloodGroup = trim($_POST['bloodGroup']);
                        $bloodGroup = mysqli_real_escape_string($conn, $bloodGroup);
                        $gender = trim($_POST['gender']);
                        $fatherName = trim($_POST['fatherName']);
                        $nid = trim($_POST['nid']);
                        $religion = trim($_POST['religion']);
                        $emergencyName = trim($_POST['emergencyName']);
                        $emergencyName = mysqli_real_escape_string($conn, $emergencyName);
                        $emergencyAddress = trim($_POST['emergencyAddress']);
                        $emergencyAddress = mysqli_real_escape_string($conn, $emergencyAddress);
                        $emergencyNumber = trim($_POST['emergencyNumber']);
                        $emergencyNumber = mysqli_real_escape_string($conn, $emergencyNumber);
                        $relationship = trim($_POST['relationship']);
                        $relationship = mysqli_real_escape_string($conn, $relationship);
                        $emergencynid = trim($_POST['emergencynid']);
                        $emergencynid = mysqli_real_escape_string($conn, $emergencynid);
                        $isActive = trim($_POST['isActive']);

                        $empUpateSQL = "UPDATE tbl_employee_info SET empName='$empName',  
                        desigid='$desigid', contactNumber='$contactNumber', email='$email', address='$address', thanaId='$thanaId',
                        districtId='$districtId', doj='$doj', hrBand='$hrBand', basicSalary='$basicSalary', houseRent='$houseRent', 
                        tAllowance='$tAllowance', qIncentive='$qIncentive', fBonus='$fBonus', 
                        bankAccount='$bankAccount',tinNumber='$tinNumber', dob='$dob', 
                        maritalStatus='$maritalStatus', bloodGroup='$bloodGroup', gender='$gender', fatherName='$fatherName', nid='$nid', religion='$religion', 
                        emergencyName='$emergencyName', emergencyAddress='$emergencyAddress', emergencyNumber='$emergencyNumber' , relationship='$relationship', isActive = '$isActive',
                        update_date=NOW() + INTERVAL 6 HOUR, updated_by='$user_check' WHERE empCode = '$empCode'";
                        if (!mysqli_query($conn,$empUpateSQL))
                        {
                            $error ="Insert Error : " . mysqli_error($conn);
                            echo "<div class='alert alert-danger'>";
                                echo "<strong>Error!</strong>".$error; 
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-success'>";
                                echo "Employee updated successfully";
                            echo "</div>";
                            
                            if($isActive == 'N'){
                                $findEmployeeUserResult = mysqli_query($conn, "select merchEmpCode from tbl_user_info where merchEmpCode = '$empCode'");
                                if(mysqli_num_rows($findEmployeeUserResult) > 0){
                                    $updateUserLoginResult = mysqli_query($conn, "update tbl_user_info set isActive = 'N' where merchEmpCode = '$empCode'");
                                }
                            } else {
                                $findEmployeeUserResult = mysqli_query($conn, "select merchEmpCode from tbl_user_info where merchEmpCode = '$empCode'");
                                if(mysqli_num_rows($findEmployeeUserResult) > 0){
                                    $updateUserLoginResult = mysqli_query($conn, "update tbl_user_info set isActive = 'Y' where merchEmpCode = '$empCode'");
                                }                                
                            }                                
                        }
                        //Delete employee points
                        $deleteSQL = "delete from tbl_employee_point where empCode ='$empCode'";
                        $deleteResult = mysqli_query($conn, $deleteSQL);
                        //Insert employee points
                        $checked_point = $_POST['pointAssigned'];
                        $count = count($checked_point);
                        for ($c=0; $c < $count; $c++) {
                            //echo $checked_thana[$c];
                            //echo "<br>";
                            $ins_qry="INSERT INTO tbl_employee_point(empCode, pointCode, creation_date, created_by) 
                            VALUES('$empCode', '".$checked_point[$c]."',  NOW() + INTERVAL 6 HOUR, '$user_check')";
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

            function addPoint()
            {
                var listbox;
                var x = document.getElementById("pointSel");
                for (var i = 0; i < x.options.length; i++)
                {
                    if (x.options[i].selected == true)
                    {
                        x.options[i].value + "-" + x.options[i].textContent
                        listbox += "<option value=" + x.options[i].value + ">" + x.options[i].textContent + "</option>";
                    }
                }
                document.getElementById('pointAssigned').innerHTML = document.getElementById('pointAssigned').innerHTML + listbox;
            }

            function removeItem(selectbox)
            {
                var i;
                for (i = selectbox.options.length - 1; i >= 0; i--)
                {
                    if (selectbox.options[i].selected)
                        selectbox.remove(i);
                }
            }
            function selectDeselect(listid, status)
            {
                var listb = document.getElementById(listid);
                var len = listb.options.length;
                for (var i = 0; i < len; i++)
                {
                    listb.options[i].selected = status;
                }
            }  
        </script>
    </body>
</html>
